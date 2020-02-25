<?php

namespace App\Http\Controllers\Pickup;

use DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Lib\Fastship\Fastship;
use App\Lib\Fastship\FS_Shipment;
use App\Lib\Fastship\FS_Pickup;
use App\Lib\Fastship\FS_Customer;
use Mail;
use Session;
use CodeItNow\BarcodeBundle\Utils\BarcodeGenerator;
use PDF;


class PickupController extends Controller
{

    public function __construct()
    {
        if($_SERVER['REMOTE_ADDR'] == "localhost"){
            include(app_path() . '\Lib\inc.functions.php');
        }else{
            include(app_path() . '/Lib/inc.functions.php');
        }
        
    }

    public function prepareCreatePickup()
    {
    	//check customer login
        if (session('customer.id') != null){
			$customerId = session('customer.id');
		}else{
			return redirect('/')->with('msg','คุณยังไม่ได้เข้าระบบ กรุณาเข้าสู่ระบบเพื่อใช้งาน');
		}

        Fastship::getToken($customerId);
    
        //prepare request data
        $searchDetails = array(
            "Status" => 'Pending',
        );
        $response = FS_Shipment::search($searchDetails);

        //get total rate
        $totalRate = 0;
        $totalWeight = 0;
        $sources = array();
        $agents = array();
        if($response === false){
            $status = "noShipment";
            $shipmentInCart = 0;
        	$shipment_data = array();
        }else{
        	$shipment_data = array();
        	if(sizeof($response) > 0 && is_array($response)){
	            foreach ($response as $shipmentId) {
	                $shipment = FS_Shipment::get($shipmentId);
	                $shipment_data[] = $shipment;
	                $totalRate += $shipment['ShipmentDetail']['ShippingRate'];
	                $totalWeight += $shipment['ShipmentDetail']['Weight'];
	                if (!in_array($shipment['Source'],$sources)){
	                    $sources[] = $shipment['Source'];
	                }
	                if (!in_array($shipment['ShipmentDetail']['ShippingAgent'],$agents)){
	                    $agents[] = $shipment['ShipmentDetail']['ShippingAgent'];
	                }
	                
	            }
	            $shipmentInCart = sizeof($response);
        	}else{
        	    $shipmentInCart = 0;
        	}
            $status = "";

        }
        Session()->put('pending.shipment', $shipmentInCart);

        //get sender
        
        $customer = FS_Customer::get($customerId);
        
        $customer_data = array();
        $customer_data['firstname'] = $customer['Firstname'];
        $customer_data['lastname'] = $customer['Lastname'];
        $customer_data['phonenumber'] = $customer['PhoneNumber'];
        $customer_data['email'] = $customer['Email'];
        $customer_data['company'] = $customer['Company'];
        $customer_data['taxid'] = $customer['TaxId'];
        $customer_data['address1'] = $customer['AddressLine1'];
        $customer_data['address2'] = $customer['AddressLine2'];
        $customer_data['city'] = $customer['City'];
        $customer_data['state'] = $customer['State'];
        $customer_data['postcode'] = $customer['Postcode'];
        $customer_data['country'] = $customer['Country'];
        $customer_data['latitude'] = $customer['Latitude'];
        $customer_data['longitude'] = $customer['Longitude'];
        $customer_data['invoice'] = $customer['IsInvoice'];
        //check first
        $firstTime = array();
        if($status != "noShipment"){
            $searchPickup = array(
                "Status" => "Sent",
            );
            $searchPickupResult = FS_Pickup::search($searchPickup);
            if(is_array($searchPickupResult)){
                $firstTime = array_merge($firstTime,$searchPickupResult);
            }
            
	        $searchPickup = array(
	        	"Status" => "Paid",
	        );
	        $searchPickupResult = FS_Pickup::search($searchPickup);
	        if(is_array($searchPickupResult)){
	        	$firstTime = array_merge($firstTime,$searchPickupResult);
	        }
	
	        $searchPickup = array(
	        	"Status" => "New",
	        );
	        $searchPickupResult = FS_Pickup::search($searchPickup);

	        if(is_array($searchPickupResult)){
	        	$firstTime = array_merge($firstTime,$searchPickupResult);
	        }
	        
	        $searchPickup = array(
	        	"Status" => "Received",
	        );
	        $searchPickupResult = FS_Pickup::search($searchPickup);
	        if(is_array($searchPickupResult)){
	        	$firstTime = array_merge($firstTime,$searchPickupResult);
	        }
	        
	        $searchPickup = array(
	        	"Status" => "Pickup",
	        );
	        $searchPickupResult = FS_Pickup::search($searchPickup);
	        if(is_array($searchPickupResult)){
	        	$firstTime = array_merge($firstTime,$searchPickupResult);
	        }
        }
        
        //calculate promotion discount
        $args = array(
            "refercode" => $customer['ReferCode'],
            "isFirstTime" => (sizeof($firstTime)==0),
            "totalRate" => $totalRate,
            "customerId" => $customerId,
        );
        
        if($customer['ReferCode'] != "FGF18116"){ 
            $discount = $this->calculateDiscount($args);
        }else{
            $discount = 0;
        }
        
        //check credit card
        $creditCardsObj = DB::table('omise_customer')
        ->select("ID","NUMBER","OMISE_LASTDIGITS","OMISE_BANK","OMISE_CARDNAME")
        ->where("CUST_ID",$customerId)
        ->where("IS_ACTIVE",1)
        ->get();
        $row_credit = $creditCardsObj->count();
        
        //check pickup rate
        if(sizeof($shipment_data)>0){
            $params = array(
                "Weight" => $totalWeight,
                "Width" => 0,
                "Height" => 0,
                "Length" => 0,
                "TotalShippingRate" => $totalRate,
                "Piece" => 1,
                "Postcode" => $customer['Postcode'],
            );
            $rates = FS_Pickup::get_pickup_rates($params);
            if(!isset($rates) || !$rates){
                $rates = array();
            }
        }else{
            $rates = array();
        }
        $data = array(
        	'status' => $status,
            'sources' => $sources,
            'agents' => $agents,
        	'shipment_data' => $shipment_data,
        	'customer_data' => $customer_data,
            'credit' => ($row_credit > 0),
        	'creditCards' => $creditCardsObj,
        	'discount' => $discount,
            'rates' => $rates,
        );
        return view('create_pickup',$data);
    }
    

    public function createPickup(Request $request)
    {

    	//check customer login
    	if (session('customer.id') != null){
    		$customerId = session('customer.id');
    	}else{
    		return redirect('/')->with('msg','คุณยังไม่ได้เข้าระบบ กรุณาเข้าสู่ระบบเพื่อใช้งาน');
    	}
    	
        $type = $request->input('type');
        if($request->has("address_select")){
            $address_select = $request->input('address_select');
        }else{
            $address_select = "old";
        }
        //alert($request->all());
        $PaymentMethod = $request->input('payment_method');

        if ($PaymentMethod == 'Credit_Card_New') {
            $pickupId = '298268';
            return redirect('new_creditcard/'.$pickupId);
        }
        
        //get api token
        Fastship::getToken($customerId);
        
        $customerObj = FS_Customer::get($customerId);
        if($address_select == "new"){
            $data['firstname'] = $request->input('firstname');
            $data['lastname'] = $request->input('lastname');
            $data['phonenumber'] = $request->input('telephone');
            $data['email'] = $request->input('email');
            $data['company'] = "";
            $data['taxid'] = "";
            $data['address1'] = $request->input('address1');
            $data['address2'] = $request->input('address2');
            $data['city'] = $request->input('city');
            $data['state'] = $request->input('state');
            $data['postcode'] = $request->input('postcode');
            $data['country'] = 'THA';
            $data['latitude'] = "";
            $data['longitude'] = "";
            $data['PickupDate'] = "";
            $data['PickupTime'] = "";
            
        }else{
            
            $data['firstname']  = $customerObj['Firstname'];
            $data['lastname']   = $customerObj['Lastname'];
            $data['phonenumber'] = $customerObj['PhoneNumber'];
            $data['email'] = $customerObj['Email'];
            $data['company'] = $customerObj['Company'];
            $data['taxid'] = $customerObj['TaxId'];
            $data['address1'] = $customerObj['AddressLine1'];
            $data['address2'] = $customerObj['AddressLine2'];
            $data['city'] = $customerObj['City'];
            $data['state'] = $customerObj['State'];
            $data['postcode'] = $customerObj['Postcode'];
            $data['country'] = $customerObj['Country'];
            $data['latitude'] = $customerObj['Latitude'];
            $data['longitude'] = $customerObj['Longitude'];
            $data['PickupDate'] = "";
            $data['PickupTime'] = "";
        }

        $shipmentIds = $request->input('shipment_id');
        
        if($request->input('agent') == 'Drop_AtThaiPost'){
            if( empty($data['firstname']) || empty($data['phonenumber']) || empty($data['address1']) ||
                empty($data['city']) || empty($data['state']) || empty($data['postcode'])){
                    return redirect('create_pickup')->with('msg','กรุณากรอกที่อยู่ให้ครบถ้วน');
            }
        }
        
        if($type == 'pickup'){
            $data['agent'] = 'Pickup_AtHome';
            
            if( !empty($request->input('pickupdate')) ){
            	$data['PickupDate'] = $request->input('pickupdate');
            	if($request->input('pickuptime') == "all" || $request->input('pickuptime') == ""){
            		$data['PickupTime'] = "00:00";
            	}else{
            		$data['PickupTime'] = $request->input('pickuptime');
            	}
            }else{
            	return redirect('create_pickup')->with('msg','กรุณาเลือกวันที่ให้เข้าไปรับพัสดุ');
            }
            
            if($address_select == 'new'){
                if( empty($request->input('firstname')) || empty($request->input('telephone')) || empty($request->input('address1')) || 
                    empty($request->input('city')) || empty($request->input('state')) || empty($request->input('postcode'))){
                        return redirect('create_pickup')->with('msg','กรุณากรอกที่อยู่ให้ครบถ้วน');
                }
            }
            
            $schedule = $data['PickupDate'] . " " . $data['PickupTime']  .":00";
        }else{
            $data['agent'] = $request->input('agent');
            $schedule = "";
        }
        

        if( !empty($request->input('width')) ){
            $data['Width'] = $request->input('width');
        }else{
            $data['Width'] = 0;
        }

        if( !empty($request->input('height')) ){
            $data['Height'] = $request->input('height');
        }else{
            $data['Height'] = 0;
        }

        if( !empty($request->input('length')) ){
            $data['Length'] = $request->input('length');
        }else{
            $data['Length'] = 0;
        }
        
        //get shipments
        foreach ($shipmentIds as $key => $shipid) {
            $shipment_data[$key] = FS_Shipment::get($shipid);
            $arr[$key]['Weight'] = $shipment_data[$key]['ShipmentDetail']['Weight'];
            $arr[$key]['ShippingRate'] = $shipment_data[$key]['ShipmentDetail']['ShippingRate'];
            $arr[$key]['ShippingAgent'] = $shipment_data[$key]['ShipmentDetail']['ShippingAgent'];
        }
        
        //alert($shipment_data);
        //alert($arr);
        $Weight =0;
        $ShippingRate =0;
        $agents = array();
        foreach ($arr as $key => $value) {
            //alert($value);
            //$Weight = $value[$key]['Weight'];
            //$Weight = $value[$key]['Weight'];
            $Weight+= $value['Weight'];
            $ShippingRate+= $value['ShippingRate'];
            $agents[] = $value['ShippingAgent'];
        }
        //alert($Weight);
        //alert($ShippingRate);
        
        //$data['PaymentMethod'] = "Bank_Transfer";
        $data['PaymentMethod'] = $request->input('payment_method');
        
        $firstTime = array();
        $searchPickup = array(
            "NoStatuses" => "Cancelled", //4
        );
        $searchPickupResult = FS_Pickup::search($searchPickup);
        if(is_array($searchPickupResult)){
            $firstTime = array_merge($firstTime,$searchPickupResult);
        }

        $args = array(
            "refercode" => $customerObj['ReferCode'],
            "isFirstTime" => (sizeof($firstTime)==0),
            "totalRate" => $ShippingRate,
            "customerId" => $customerId,
            "agents" => $agents,
        );
        $data['discount'] = $this->calculateDiscount($args);
        /*
        echo abs($request->input('discount'));
        if(  !empty($request->input('discount'))  && $request->input('discount') < 0){
            if($referDiscount >= abs($request->input('discount'))){
                $data['discount'] = $referDiscount;
            }else{
                $data['discount'] = abs($request->input('discount'));
            }
        }else{
            $data['discount'] = 0;
        }
        echo  $data['discount']; exit();*/
        
        $data['coupon_code'] = strtoupper($request->input('coupon_code'));

        //prepare request data
        $createDetails = array(
            'ShipmentDetail' => array(
                'ShipmentIds' => $shipmentIds,
                'Weight' => $Weight,
                'Width' => $data['Width'],
                'Height' => $data['Height'],
                'Length' => $data['Length'],
                'TotalShippingRate' => $ShippingRate,
            ),
            'PickupAddress' => array(
                'Firstname' => $data['firstname'],
                'Lastname' => $data['lastname'],
                'PhoneNumber' => $data['phonenumber'],
                'Email' => $data['email'],
                'Company' => $data['company'],
                'AddressLine1' => $data['address1'],
                'AddressLine2' => $data['address2'],
                'City' => $data['city'],
                'State' => $data['state'],
                'Postcode' => $data['postcode'],
            	'Latitude' => $data['latitude'],
            	'Longitude' => $data['longitude'],
            ),  
            'PaymentMethod' => $data['PaymentMethod'],
            'PickupType' => $data['agent'],
            'Coupon' => $data['coupon_code'],
            'Discount' => $data['discount'],
            'ScheduleDatetime' => $schedule,
            'Remark' => "",
        );
        //alert('<h2>ยอดชำระรายการ xxx รวม '.$createDetails['ShipmentDetail']['TotalShippingRate'].' บาท</h2>');
        //alert($createDetails);
        //die();
        //create pickup
        $response = FS_Pickup::create($createDetails);
        //$response = false;
        if($response === false){
            return redirect('create_pickup')->with('msg','สร้างใบรับพัสดุไม่สำเร็จ');
        }else{

        	$request->session()->put('pending.shipment', 0);
        	 
            $pickupId = $response;

            //pickup and shipments data
            $shipment_data = array();
            $pickupObj = FS_Pickup::get($pickupId);
            foreach ($pickupObj['ShipmentDetail']['ShipmentIds'] as $shipmentId) {
                $shipment_data[] = FS_Shipment::get($shipmentId);
            }

            // ### send email ###
            $toName = $customerObj['Firstname'] . ' ' . $customerObj['Lastname'];
            $eMail = $customerObj['Email'];
            
            $data = array(
            	'pickupId' => $pickupId,
            	'email' => $eMail,
                'pickupData' => $pickupObj,
            	'shipmentData' => $shipment_data,
            );

            Mail::send('email/new_order',$data,function($message) use ($data){
            	$message->to($data['email']);
            	$message->bcc(['thachie@tuff.co.th','oak@tuff.co.th']);
            	$message->from('cs@fastship.co', 'FastShip');
            	$message->subject('FastShip - ใบรับพัสดุหมายเลข '. $data['pickupId'] ." ถูกสร้างแล้ว");
            });
            // ####

            if ($PaymentMethod == 'QR') {
                return redirect('pickup_detail_payment/'.$pickupId)->with('msg','ระบบได้ทำสร้างใบรับพัสดุ เรียบร้อยแล้ว กรุณาตรวจสอบรายการและชำระเงิน')->with('msg-type','success');
            }else{
                return redirect('pickup_detail/'.$pickupId)->with('msg','ระบบได้ทำสร้างใบรับพัสดุ เรียบร้อยแล้ว')->with('msg-type','success');
            }
        }
    }

    //Feature Prepaid System
    public function preparePickupDetailPayment($pickupId=null)
    {
        //check customer login
        if (session('customer.id') != null){
            $customerId = session('customer.id');
        }else{
            return redirect('/')->with('msg','คุณยังไม่ได้เข้าระบบ กรุณาเข้าสู่ระบบเพื่อใช้งาน');
        }
        
        if(!empty($pickupId)){
            
            //get api token
            Fastship::getToken($customerId);
            //get pickup by pickup_id
            $response = FS_Pickup::get($pickupId);
            if ($response['Status'] != "Unpaid" && $response['PaymentMethod'] != "QR") {
                return redirect('pickup_detail/'.$pickupId);
            }else{
                $isSeperateLabel = ($response['PickupType'] == "Drop_AtThaiPost" || $response['PickupType'] == "Pickup_AtKerry");
                
                if($isSeperateLabel){
                    $labels = FS_Pickup::getLabels($pickupId);
                }else{
                    $labels = array();
                }
                if($response === false){
                    $pickupData = null;
                    $status = 'nopickupData';
                }else{
                    $status = '';
                    $pickupData = $response;
                    $shipmentIds = $response['ShipmentDetail']['ShipmentIds'];
                    //alert($pickupData);
                    
                    foreach ($shipmentIds as $key => $shipid) {
                        //$shipment_data[$key] = FS_Shipment::get($shipid);
                        $pickupData['ShipmentDetail']['ShipmentIds'][$key] = FS_Shipment::get($shipid);
                        //$arr[$key]['Weight'] = $shipment_data[$key]['ShipmentDetail']['Weight'];
                        //$arr[$key]['ShippingRate'] = $shipment_data[$key]['ShipmentDetail']['ShippingRate'];
                    }
                    //alert($shipment_data);
                }
                //QR
                //Fastship::getToken($customerId);
                //$pickup = FS_Pickup::get($pickupId);
                //alert($pickup);
                /*$shipmentIds = $pickup['ShipmentDetail']['ShipmentIds'];
                foreach ($shipmentIds as $key => $shipid) {
                    $pickup['ShipmentDetail']['ShipmentIds'][$key] = FS_Shipment::get($shipid);
                }*/
                //alert($pickupData['PaymentMethod']);
                //alert($pickupData);
                //prepare to Kbank
                $amount = $pickupData['Amount'];
                $description = "Pickup # " . $pickupData['ID'] . " - Pickup by " . $pickupData['PickupType'];
                $jsonCreateOrderId = '{
                    "amount": '.$amount.',
                    "currency": "THB",
                    "description": "'.$description.'",
                    "source_type": "qr",
                    "reference_order": "'.$pickupId.'"
                }';
                $method = "POST";
                $url = "https://kpaymentgateway-services.kasikornbank.com/qr/v2/order";
                $jsonData = $jsonCreateOrderId;

                $responseQr = callAPI_Kbank($method, $url, $jsonData);
                $res = json_decode($responseQr, true);
                $order_id = $res['id'];

                $data = array(
                    'pickupID' => $pickupId, 
                    'pickup_data' => $pickupData, 
                    'status' => $status, 
                    'labels' => $labels,
                    //"pickup" => $pickup,
                    "kbankOrderId" => $order_id,
                );
                return view('pickup_detail_payment',$data);
                /*if ($pickupData['PaymentMethod'] == 'Bank_Transfer' || $pickupData['PaymentMethod'] == 'QR') {
                    //QR
                    Fastship::getToken($customerId);
                    $pickup = FS_Pickup::get($pickupId);
                    $shipmentIds = $pickup['ShipmentDetail']['ShipmentIds'];
                    foreach ($shipmentIds as $key => $shipid) {
                        $pickup['ShipmentDetail']['ShipmentIds'][$key] = FS_Shipment::get($shipid);
                    }
                    //alert($pickupData['PaymentMethod']);
                    //alert($pickupData);
                    //prepare to Kbank
                    $amount = $pickup['Amount'];
                    $description = "Pickup # " . $pickup['ID'] . " - Pickup by " . $pickup['PickupType'];
                    $jsonCreateOrderId = '{
                        "amount": '.$amount.',
                        "currency": "THB",
                        "description": "'.$description.'",
                        "source_type": "qr",
                        "reference_order": "'.$pickupId.'"
                    }';
                    $method = "POST";
                    $url = "https://kpaymentgateway-services.kasikornbank.com/qr/v2/order";
                    $jsonData = $jsonCreateOrderId;

                    $response = callAPI_Kbank($method, $url, $jsonData);
                    $res = json_decode($response, true);
                    $order_id = $res['id'];

                    $data = array(
                        'pickupID' => $pickupId, 
                        'pickup_data' => $pickupData, 
                        'status' => $status, 
                        'labels' => $labels,
                        "pickup" => $pickup,
                        "kbankOrderId" => $order_id,
                    );
                    return view('pickup_detail_payment',$data);
                }else{
                    $data = array(
                        'pickupID' => $pickupId, 
                        'pickup_data' => $pickupData, 
                        'status' => $status, 
                        'labels' => $labels,
                    );
                    return redirect('pickup_detail/'.$pickupId)->with('msg','ระบบได้ทำสร้างใบรับพัสดุ เรียบร้อยแล้ว')->with('msg-type','success');
                }*/
            }
        }else{
            return 'Pickup id is null.';
        }
    }

    //public function preparePickupDetail($data)
    public function preparePickupDetail($pickupId=null)
    {
        
    	//check customer login
    	if (session('customer.id') != null){
    		$customerId = session('customer.id');
    	}else{
    		return redirect('/')->with('msg','คุณยังไม่ได้เข้าระบบ กรุณาเข้าสู่ระบบเพื่อใช้งาน');
    	}
    	
        //265412
        if(!empty($pickupId)){
            //get api token
            Fastship::getToken($customerId);
            //get pickup by pickup_id
            $response = FS_Pickup::get($pickupId);
            
            $isSeperateLabel = ($response['PickupType'] == "Drop_AtThaiPost" || $response['PickupType'] == "Pickup_AtKerry");
            
            if($isSeperateLabel){
                $labels = FS_Pickup::getLabels($pickupId);
            }else{
                $labels = array();
            }
            if($response === false){
                $pickupData = null;
                $status = 'nopickupData';
            }else{
                $status = '';
                $pickupData = $response;
                $shipmentIds = $response['ShipmentDetail']['ShipmentIds'];
                //alert($pickupData);
                
                foreach ($shipmentIds as $key => $shipid) {
                    //$shipment_data[$key] = FS_Shipment::get($shipid);
                    $pickupData['ShipmentDetail']['ShipmentIds'][$key] = FS_Shipment::get($shipid);
                    //$arr[$key]['Weight'] = $shipment_data[$key]['ShipmentDetail']['Weight'];
                    //$arr[$key]['ShippingRate'] = $shipment_data[$key]['ShipmentDetail']['ShippingRate'];
                }
                //alert($shipment_data);
            }
            //alert($pickupData);
            $data = array(
                'pickupID' => $pickupId, 
                'pickup_data' => $pickupData, 
                'status' => $status, 
                'labels' => $labels,
            );
            return view('pickup_detail',$data);
        }else{
            return 'Pickup id is null.';
        }
    }
    
    //public function preparePickupDetail($data)
    public function preparePickupDetailPrint($pickupId=null)
    {
    
    	//check customer login
    	if (session('customer.id') != null){
    		$customerId = session('customer.id');
    	}else{
    		return redirect('/')->with('msg','คุณยังไม่ได้เข้าระบบ กรุณาเข้าสู่ระบบเพื่อใช้งาน');
    	}
    	 
    	$additionalBarcodeImage = "";
    	
    	//265412
    	if(!empty($pickupId)){
    		//get api token
    		Fastship::getToken($customerId);
    		//get pickup by pickup_id
    		$response = FS_Pickup::get($pickupId);
    		
    		if($response === false){
    			$pickupData = null;
    			$status = 'nopickupData';
    		}else{
    			$status = '';
    			$pickupData = $response;
    			$shipmentIds = $response['ShipmentDetail']['ShipmentIds'];
    			//alert($pickupData);
    
    			foreach ($shipmentIds as $key => $shipid) {
    				//$shipment_data[$key] = FS_Shipment::get($shipid);
    				$pickupData['ShipmentDetail']['ShipmentIds'][$key] = FS_Shipment::get($shipid);
    				//$arr[$key]['Weight'] = $shipment_data[$key]['ShipmentDetail']['Weight'];
    				//$arr[$key]['ShippingRate'] = $shipment_data[$key]['ShipmentDetail']['ShippingRate'];
    				
    				$barcode = new BarcodeGenerator();
    				$barcode->setText($shipid);
    				$barcode->setType(BarcodeGenerator::Code39);
    				$barcode->setScale(2);
    				$barcode->setThickness(40);
    				$barcode->setFontSize(10);
    				$code = $barcode->generate();
    				$barcodeImage = '<img src="data:image/png;base64,'.$code.'" />';
    				$pickupData['ShipmentDetail']['ShipmentIds'][$key]['barcode'] = $barcodeImage;
    			}
    			//alert($shipment_data);
    			
    			if($pickupData['PickupType'] == "Drop_AtSkybox"){
    				$barcode = new BarcodeGenerator();
    				$barcode->setText("F".$pickupId);
    				$barcode->setType(BarcodeGenerator::Code39);
    				$barcode->setScale(2);
    				$barcode->setThickness(40);
    				$barcode->setFontSize(10);
    				$code = $barcode->generate();
    				$additionalBarcodeImage = '<img src="data:image/png;base64,'.$code.'" />';
    				$pickupData['ShipmentDetail']['ShipmentIds'][$key]['barcode'] = $barcodeImage;
    			}
    			
    			if(in_array($pickupData['PickupType'],array("Pickup_AtHome","Pickup_ByKerry","Pickup_ByFlash","Pickup_BySpeedy"))){
    			    $barcode = new BarcodeGenerator();
    			    $barcode->setText("FAST000".$pickupId);
    			    $barcode->setType(BarcodeGenerator::Code39);
    			    $barcode->setScale(1);
    			    $barcode->setThickness(40);
    			    $barcode->setFontSize(10);
    			    $code = $barcode->generate();
    			    $additionalBarcodeImage = '<img src="data:image/png;base64,'.$code.'" />';
    			    $pickupData['ShipmentDetail']['ShipmentIds'][$key]['barcode'] = $barcodeImage;
    			}
    			
    		}
    		
    		
    		
    		$barcode = new BarcodeGenerator();
    		$barcode->setText($pickupId);
    		$barcode->setType(BarcodeGenerator::Code39);
    		$barcode->setScale(2);
    		$barcode->setThickness(40);
    		$barcode->setFontSize(12);
    		$code = $barcode->generate();
    		$barcodeImage = '<img src="data:image/png;base64,'.$code.'" />';
    
            //declare type
            $dTypes = DB::table('product_type')->where("IS_ACTIVE",1)->orderBy("TYPE_SORT")->orderBy("TYPE_NAME")->get();
            $declareTypes = array();
            if(sizeof($dTypes)>0){
                foreach($dTypes as $dType){
                    $declareTypes[$dType->TYPE_CODE] = $dType->TYPE_NAME;
                }
            }

    		$data = array(
    			'pickupID' => $pickupId,
    			'pickup_data' => $pickupData,
    			'status' => $status,
    			'barcode' => $barcodeImage,
                'additionalBarcodeImage' => $additionalBarcodeImage,
                'declareTypes' => $declareTypes,
    		);
    		return view('pickup_detail_print',$data);
    	}else{
    		return 'Pickup id is null.';
    	}
    }
    
    public function getThaiPostLabel($barcode)
    {
        //check customer login
        if (session('customer.id') != null){
            $customerId = session('customer.id');
        }else{
            return redirect('/')->with('msg','คุณยังไม่ได้เข้าระบบ กรุณาเข้าสู่ระบบเพื่อใช้งาน');
        }
        
        Fastship::getToken($customerId);
        
        //call api
        $pdfPath = FS_Pickup::getLabel($barcode);

        $html = "";
//         header('Content-Type: application/pdf');

         $data = array(
             'label' => $pdfPath,
         );
        
         return redirect('http://api.fastship.co/thaipost/label/'.$barcode);
         
        //return view('thaipost_label',$data);
    }
    
    public function getCoupon(Request $request)
    {
        //check customer login
        if (session('customer.id') != null){
            $customerId = session('customer.id');
        }else{
            return redirect('/')->with('msg','คุณยังไม่ได้เข้าระบบ กรุณาเข้าสู่ระบบเพื่อใช้งาน');
        }

        Fastship::getToken($customerId);
        
        print_r(json_decode($request->get("agents")));
        
        //call api
        $params = array(
            "Code" => strtoupper($request->get("code")),
            "Payment" => $request->get("payment"),
            "Sources" => explode(";",$request->get("sources")),
            "Agents" => explode(";",$request->get("agents")),
            "Total" => $request->get("total"),
        );
        //print_r($params); exit();
        $discount = FS_Pickup::getCoupon($params);
       
        echo $discount;
        
        exit();
        
    }
    
    public function preparePickupInvoicePrint($pickupId=null)
    {
    
    	//check customer login
    	if (session('customer.id') != null){
    		$customerId = session('customer.id');
    	}else{
    		return redirect('/')->with('msg','คุณยังไม่ได้เข้าระบบ กรุณาเข้าสู่ระบบเพื่อใช้งาน');
    	}
    
    	$additionalBarcodeImage = "";
    	 
    	//265412
    	if(!empty($pickupId)){
    		
    	    //get api token
    	    Fastship::getToken($customerId);

    		$customerObj = FS_Customer::get($customerId);
    		
    		//get pickup by pickup_id
    		$response = FS_Pickup::get($pickupId);
    		if($response === false){
    			$pickupData = null;
    			$status = 'nopickupData';
    		}else{
    			$status = '';
    			$pickupData = $response;
    			$shipmentIds = $response['ShipmentDetail']['ShipmentIds'];
    			//alert($pickupData);
    
    			foreach ($shipmentIds as $key => $shipid) {
    				//$shipment_data[$key] = FS_Shipment::get($shipid);
    				$pickupData['ShipmentDetail']['ShipmentIds'][$key] = FS_Shipment::get($shipid);
    				//$arr[$key]['Weight'] = $shipment_data[$key]['ShipmentDetail']['Weight'];
    				//$arr[$key]['ShippingRate'] = $shipment_data[$key]['ShipmentDetail']['ShippingRate'];
    
    				$barcode = new BarcodeGenerator();
    				$barcode->setText($shipid);
    				$barcode->setType(BarcodeGenerator::Code39);
    				$barcode->setScale(1);
    				$barcode->setThickness(40);
    				$barcode->setFontSize(10);
    				$code = $barcode->generate();
    				$barcodeImage = '<img src="data:image/png;base64,'.$code.'" />';
    				$pickupData['ShipmentDetail']['ShipmentIds'][$key]['barcode'] = $barcodeImage;
    			}
    			//alert($shipment_data);
    			 
    			if($pickupData['PickupType'] == "Drop_AtSkybox"){
    				$barcode = new BarcodeGenerator();
    				$barcode->setText("F".$pickupId);
    				$barcode->setType(BarcodeGenerator::Code39);
    				$barcode->setScale(1);
    				$barcode->setThickness(40);
    				$barcode->setFontSize(10);
    				$code = $barcode->generate();
    				$additionalBarcodeImage = '<img src="data:image/png;base64,'.$code.'" />';
    				$pickupData['ShipmentDetail']['ShipmentIds'][$key]['barcode'] = $barcodeImage;
    			}
    		}
    
    
    
    		$barcode = new BarcodeGenerator();
    		$barcode->setText($pickupId);
    		$barcode->setType(BarcodeGenerator::Code39);
    		$barcode->setScale(2);
    		$barcode->setThickness(25);
    		$barcode->setFontSize(10);
    		$code = $barcode->generate();
    		$barcodeImage = '<img src="data:image/png;base64,'.$code.'" />';
    
    		//declare type
    		$dTypes = DB::table('product_type')->where("IS_ACTIVE",1)->orderBy("TYPE_SORT")->orderBy("TYPE_NAME")->get();
    		$declareTypes = array();
    		if(sizeof($dTypes)>0){
    		    foreach($dTypes as $dType){
    		        $declareTypes[$dType->TYPE_CODE] = $dType->TYPE_NAME;
    		    }
    		}
    		
    		$data = array(
    			'pickupID' => $pickupId,
    			'pickup_data' => $pickupData,
    			'customer_data' => $customerObj,
    			'status' => $status,
    			'barcode' => $barcodeImage,
    			'additionalBarcodeImage' => $additionalBarcodeImage,
    		    'declareTypes' => $declareTypes,
    		);
    		return view('pickup_invoice_print',$data);
    	}else{
    		return 'Pickup id is null.';
    	}
    }

    public function preparePickupList($page=1)
    {
    	//check customer login
    	if (session('customer.id') != null){
    		$customerId = session('customer.id');
    	}else{
    		return redirect('/')->with('msg','คุณยังไม่ได้เข้าระบบ กรุณาเข้าสู่ระบบเพื่อใช้งาน');
    	}

        $data['MinShippingRate'] = isset($_REQUEST['MinShippingRate'])?$_REQUEST['MinShippingRate']:"";
        $data['MaxShippingRate'] = isset($_REQUEST['MaxShippingRate'])?$_REQUEST['MaxShippingRate']:"";
        $data['MinWeight'] = isset($_REQUEST['MinWeight'])?$_REQUEST['MinWeight']:"";
        $data['MaxWeight'] = isset($_REQUEST['MaxWeight'])?$_REQUEST['MaxWeight']:"";
        $data['PickupName'] = isset($_REQUEST['PickupName'])?$_REQUEST['PickupName']:"";
        $data['PickupEmail'] = isset($_REQUEST['PickupEmail'])?$_REQUEST['PickupEmail']:"";
        $data['PickupPhoneNumber'] = isset($_REQUEST['PickupPhoneNumber'])?$_REQUEST['PickupPhoneNumber']:"";
        $data['PickupPostcode'] = isset($_REQUEST['PickupPostcode'])?$_REQUEST['PickupPostcode']:"";
        $data['PaymentMethod'] = isset($_REQUEST['PaymentMethod'])?$_REQUEST['PaymentMethod']:"";
        $data['PickupType'] = isset($_REQUEST['PickupType'])?$_REQUEST['PickupType']:"";
        $data['CreateDateSince'] = isset($_REQUEST['CreateDateSince'])?$_REQUEST['CreateDateSince']:"";
        $data['CreateDateTo'] = isset($_REQUEST['CreateDateTo'])?$_REQUEST['CreateDateTo']:"";
        $data['Status'] = isset($_REQUEST['Status'])?$_REQUEST['Status']:"";
        $data['NoStatuses'] = array("Cancelled");
        $data['Limit'] = isset($_REQUEST['Limit'])?$_REQUEST['Limit']:20;
        $data['Page'] = isset($page)?$page:1;

        //get api token
        Fastship::getToken($customerId);
        
        //prepare request data
        $searchDetails = array(
            'ShipmentDetail' => array(
            //  'ShipmentIds' => array("1519119482"),
                'MinShippingRate' => $data['MinShippingRate'],
                'MaxShippingRate' => $data['MaxShippingRate'],
                'MinWeight' => $data['MinWeight'],
                'MaxWeight' => $data['MaxWeight'],
            ),
            'PickupAddress' => array(
                'Name' => $data['PickupName'],
                'Email' => $data['PickupEmail'],
                'PhoneNumber' => $data['PickupPhoneNumber'],
                'Postcode' => $data['PickupPostcode'],
            ),
            'PaymentMethod' => $data['PaymentMethod'],
            'PickupType' => $data['PickupType'],
            'CreateDateSince' => $data['CreateDateSince'],
            'CreateDateTo' => $data['CreateDateTo'],
            'Status' => $data['Status'],
        	'NoStatuses' => $data['NoStatuses'],
        	'Limit' => $data['Limit'],
        	'Page' => $data['Page'],
        );

        //call api
        $resDetails = FS_Pickup::search($searchDetails);
        //alert($resDetails);die();
        if($resDetails === false){
            $resGetpickup = array();
            $msg = 'false';
        }else{
        	$resGetpickup = $resDetails;
//         	if(sizeof($resDetails) > 0 && is_array($resDetails)){
// 	            foreach ($resDetails as $pickupId) {
	                
// 	                //$resPickup = FS_Pickup::get($pickupId);
// 	                if(!$resPickup) continue;
// 	                $resGetpickup[] = $resPickup;
	                
// 	            }
//         	}
            //alert($resGetpickup);
            //$resGetpickup = FS_Pickup::get($pickupId);
            $msg = '';
        }
        $data = array(
        	'page' => $page,
            'pickup_list' => $resGetpickup,
            'msg' => $msg,
        );

        return view('pickup_list', $data);
    }

    //cancel pickup
    public function cancelPickup(Request $request)
    {
    
    	//check customer login
    	if (session('customer.id') != null){
    		$customerId = session('customer.id');
    	}else{
    		exit();
    	}
    	 
    	//check parameter
    	if ( !empty($request->input('pickupId')) && $request->input('pickupId') > 0){
    		$pickupId = $request->input('pickupId');
    	}else{
    		exit();
    	}
    
    	//get api token
    	Fastship::getToken($customerId);
    
    	//check pickup owner
    	$checkPickup = FS_Pickup::get($pickupId);
    	if(!$checkPickup) exit();
    
    	//call api
    	$result = FS_Pickup::cancel($pickupId);
    
    	echo json_encode($result);
    	 
    	exit();
    }



    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
    
    private function calculateDiscount($args){
        
        extract($args);
        //$refercode,$isFirstTime,$totalRate,$customerId
        
        $discount = 0;

        Fastship::getToken($customerId);
        $customerObj = FS_Customer::get($customerId);

        $inactiveUser = array(
            4393,3897,4007,4036,4213,3870,3973,4332,3812,3823,4074,3884,4324,4287,4191,4394,
            4146,4179,3844,3876,4286,4141,3927,3915,3878,4158,4410,4065,4070,4412,4355,3899,
            3817,4397,4055,3948,4003,4076,4357,3906,4165,4373,4235,4199,3885,4010,4267,4400,
            3984,4021,3951,4405,4048,4190,4009,4086,4177,3816,3924,3866,3857,4189,3851,4374,
            4364,4180,4319,3895,4004,4236,4403,4093,3933,3926,3809,4367,4008,4061,4411,4289,
            4370,4366,4051,3991,4193,4181,4306,4174,4769,4929,4485,4959,4669,4578,4670,4938,
            4496,4478,4833,4660,4876,4500,4675,4921,4807,4812,4538,4661,4932,4991,4692,4572,
            4583,4761,4490,4456,4555,4470,4900,4854,4613,4585,4648,4638,4438,4768,4522,4805,
            4464,4642,4513,4548,4404,4094,3974,4215,4245,3907,4185,4383,4230,
            294,5486);
        
        

        $promoObj = DB::table('promo_code')
            ->where("CODE_NAME",$refercode)
            ->where("CODE_TYPE",'Promo')
            //->where("CODE_ENDDATE",">",date("Y-m-d H:i:s"))
            ->where("IS_ACTIVE",1)->first();
        
        if(in_array($customerId, $inactiveUser)){
            
            $firstTime = array();

            $searchPickup = array(
                "Status" => "Paid",
                "CreateDateSince" => "2018-06-15 00:00:00",
            );
            $searchPickupResult = FS_Pickup::search($searchPickup);
            if(is_array($searchPickupResult)){
                $firstTime = array_merge($firstTime,$searchPickupResult);
            }
            
            $searchPickup = array(
                "Status" => "New",
                "CreateDateSince" => "2018-06-15 00:00:00",
            );
            $searchPickupResult = FS_Pickup::search($searchPickup);
            
            if(is_array($searchPickupResult)){
                $firstTime = array_merge($firstTime,$searchPickupResult);
            }
            
            $searchPickup = array(
                "Status" => "Received",
                "CreateDateSince" => "2018-06-15 00:00:00",
            );
            $searchPickupResult = FS_Pickup::search($searchPickup);
            if(is_array($searchPickupResult)){
                $firstTime = array_merge($firstTime,$searchPickupResult);
            }
            
            $searchPickup = array(
                "Status" => "Pickup",
                "CreateDateSince" => "2018-06-15 00:00:00",
            );
            $searchPickupResult = FS_Pickup::search($searchPickup);
            if(is_array($searchPickupResult)){
                $firstTime = array_merge($firstTime,$searchPickupResult);
            }
            
            if(sizeof($firstTime) == 0 && "2018-06-30 23:59:59" > date("Y-m-d H:i:s")){
                $discount = 500;
            }else{
                $discount = 0;
            }
            
        }else{
        
            if($promoObj != null){
                
                if( isset($totalRate) && $totalRate < $promoObj->CODE_MINRATE) { $discount = 0; }
                else{
                    if( ($promoObj->IS_FIRSTORDER && $isFirstTime) || !$promoObj->IS_FIRSTORDER){
                        if(($promoObj->CODE_ENDDATE != "0000-00-00 00:00:00" && $promoObj->CODE_ENDDATE > date("Y-m-d H:i:s")) || $promoObj->CODE_ENDDATE == "0000-00-00 00:00:00"){
                            
                            if($promoObj->CODE_DISCOUNTTYPE == "Amount"){
                                $discount = $promoObj->CODE_DISCOUNTAMOUNT;
                            }
                            if($promoObj->CODE_TIMELIMIT == "1M" && (time() - strtotime($customerObj['CreateDate']['date'])) > 3600*24*30 ){
                                $discount = 0;
                            }
//                             if(isset($agents)){
//                                 foreach($agents as $agent){
//                                     if(strstr($promoObj->CODE_AGENTS,$agent)){
//                                         $discount = $promoObj->CODE_DISCOUNTAMOUNT;
//                                         break;
//                                     }
//                                 }
//                             }

                        }
                    }
                }
            }elseif($refercode != ""){

                if(starts_with($refercode, "FGF") && $isFirstTime){
            		
            		$adviserId = substr($refercode, 3 );
            		
            		$customerObj = FS_Customer::get($adviserId);

            		if(isset($customerObj) && isset($customerObj['ID'])){
            			$discount = 300;
            		}else{
            			$discount = 0;
            		}
            	}
            }
            
        }
        return $discount;
    }
}
