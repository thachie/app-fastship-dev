<?php

namespace App\Http\Controllers\Partner;

use App\Models\Country as Country; // กำหนดชื่อ ของ Model จากที่อยู่ของ Model ที่เราเรียกใช้งาน
use DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Lib\Fastship\Fastship;
use App\Lib\Fastship\FS_Shipment;
use App\Lib\Fastship\FS_Pickup;
use Mail;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Input;
use App\Lib\Fastship\FS_Address;
//use App\Lib\Ebay\EbayManager;
//use App\Lib\Ebay\eBayObjectMapping;
use CodeItNow\BarcodeBundle\Utils\BarcodeGenerator;
use CodeItNow\BarcodeBundle\Utils\QrCode;

class PartnerPickupController extends Controller
{
    

    public function __construct()
    {
        date_default_timezone_set("Asia/Bangkok");
        include(app_path() . '/Lib/inc.functions.php');
    }


    public function pickup()
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
                }
                $shipmentInCart = sizeof($response);
            }else{
                $shipmentInCart = 0;
            }
            $status = "";

        }
        Session()->put('pending.shipment', $shipmentInCart);

        //get sender
        $customerObj = DB::table('customer')->where("CUST_ID",$customerId)->where("IS_ACTIVE",1)->first();
        $customer_data = array();
        $customer_data['firstname'] = $customerObj->CUST_FIRSTNAME;
        $customer_data['lastname'] = $customerObj->CUST_LASTNAME;
        $customer_data['phonenumber'] = $customerObj->CUST_TEL;
        $customer_data['email'] = $customerObj->CUST_EMAIL;
        $customer_data['company'] = $customerObj->CUST_COMPANY;
        $customer_data['address1'] = $customerObj->CUST_ADDR1;
        $customer_data['address2'] = $customerObj->CUST_ADDR2;
        $customer_data['city'] = $customerObj->CUST_CITY;
        $customer_data['state'] = $customerObj->CUST_STATE;
        $customer_data['postcode'] = $customerObj->CUST_POSTCODE;
        $customer_data['country'] = $customerObj->CNTRY_CODE;
        $customer_data['latitude'] = $customerObj->CUST_LATITUDE;
        $customer_data['longitude'] = $customerObj->CUST_LONGITUDE;
        
        //check first
        $firstTime = array();
        if($status != "noShipment"){
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
            "refercode" => $customerObj->CUST_REFERCODE,
            "isFirstTime" => (sizeof($firstTime)==0),
            "totalRate" => $totalRate,
            "customerId" => $customerId,
        );
        $discount = $this->calculateDiscount($args);
        
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
                "Postcode" => $customerObj->CUST_POSTCODE,
            );
            $rates = FS_Pickup::get_pickup_rates($params);
        }else{
            $rates = array();
        }
        //alert($shipment_data);
        $data = array(
            'status' => $status,
            'shipment_data' => $shipment_data,
            'customer_data' => $customer_data,
            'credit' => ($row_credit > 0),
            'discount' => $discount,
            'rates' => $rates,
        );
        return view('partner/pickup',$data);
    }

    public function createPickup(Request $request)
    {

        //check customer login
        if (session('customer.id') != null){
            $customerId = session('customer.id');
        }else{
            return redirect('/')->with('msg','คุณยังไม่ได้เข้าระบบ กรุณาเข้าสู่ระบบเพื่อใช้งาน');
        }
        alert($request->all());//die();
        $type = $request->input('type');
        $pickaddress = $request->input('pickaddress');

        $customerObj = DB::table('customer')->where("CUST_ID",$customerId)->where("IS_ACTIVE",1)->first();
 
        $data['firstname'] = $customerObj->CUST_FIRSTNAME;
        $data['lastname'] = $customerObj->CUST_LASTNAME;
        $data['phonenumber'] = $customerObj->CUST_TEL;
        $data['email'] = $customerObj->CUST_EMAIL;
        $data['company'] = $customerObj->CUST_COMPANY;
        $data['address1'] = $customerObj->CUST_ADDR1;
        $data['address2'] = $customerObj->CUST_ADDR2;
        $data['city'] = $customerObj->CUST_CITY;
        $data['state'] = $customerObj->CUST_STATE;
        $data['postcode'] = $customerObj->CUST_POSTCODE;
        $data['country'] = $customerObj->CNTRY_CODE;
        $data['latitude'] = $customerObj->CUST_LATITUDE;
        $data['longitude'] = $customerObj->CUST_LONGITUDE;
        $data['PickupDate'] = "";
        $data['PickupTime'] = "";
       
        
       

        $shipmentIds = $request->input('shipment_id');
        
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
                //return redirect('create_pickup')->with('msg','กรุณาเลือกวันที่ให้เข้าไปรับพัสดุ');
                return redirect('partner/pickup')->with('msg','กรุณาเลือกวันที่ให้เข้าไปรับพัสดุ');
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
        
        //get api token
        Fastship::getToken($customerId);
        foreach ($shipmentIds as $key => $shipid) {
            $shipment_data[$key] = FS_Shipment::get($shipid);
            $arr[$key]['Weight'] = $shipment_data[$key]['ShipmentDetail']['Weight'];
            $arr[$key]['ShippingRate'] = $shipment_data[$key]['ShipmentDetail']['ShippingRate'];
        }
        
        //alert($shipment_data);
        //alert($arr);
        $Weight =0;
        $ShippingRate =0;
        foreach ($arr as $key => $value) {
            //alert($value);
            //$Weight = $value[$key]['Weight'];
            //$Weight = $value[$key]['Weight'];
            $Weight+= $value['Weight'];
            $ShippingRate+= $value['ShippingRate'];
        }
        //alert($Weight);
        //alert($ShippingRate);
        
        //$data['PaymentMethod'] = "Invoice";
        $data['PaymentMethod'] = "Bank_Transfer";
        //$data['PaymentMethod'] = $request->input('payment_method');
        
        $firstTime = array();
        
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
        
        $args = array(
            "refercode" => $customerObj->CUST_REFERCODE,
            "isFirstTime" => (sizeof($firstTime)==0),
            "totalRate" => $ShippingRate,
            "customerId" => $customerId,
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

//         alert($createDetails);
//         die();

        //create pickup
        $response = FS_Pickup::create($createDetails);
        //$response = false;
        if($response === false){
            return redirect('partner/pickup')->with('msg','สร้างใบรับพัสดุไม่สำเร็จ');
        }else{

            $request->session()->put('pending.shipment', 0);
             
            $pickupId = $response;

            // Sent mail process
            $validateCustomer = DB::table('customer')
                            ->select("CUST_FIRSTNAME","CUST_LASTNAME","CUST_EMAIL")
                            ->where('CUST_ID', $customerId)
                            ->where("IS_ACTIVE",1)
                            ->first();
           
            $toName = $validateCustomer->CUST_FIRSTNAME.' '.$validateCustomer->CUST_LASTNAME;
            $eMail = $validateCustomer->CUST_EMAIL;
            
            //Fastship::getToken($customerId);

            $response = FS_Pickup::get($pickupId);
            $shipment_data = array();
            $response = FS_Pickup::get($pickupId);
            foreach ($response['ShipmentDetail']['ShipmentIds'] as $shipmentId) {
                $shipment_data[] = FS_Shipment::get($shipmentId);
            }
            $data = array(
                    'pickupId' => $pickupId,
                    'email' => $eMail,
                    'pickupData' => $response,
                    'shipmentData' => $shipment_data,
            );

            Mail::send('email/new_order',$data,function($message) use ($data){
                $message->to($data['email']);
                $message->bcc(['thachie@tuff.co.th','oak@tuff.co.th']);
                $message->from('info@fastship.co', 'FastShip');
                $message->subject('FastShip - ใบรับพัสดุหมายเลข '. $data['pickupId'] ." ถูกสร้างแล้ว");
            });

            //return redirect('pickup_detail/'.$pickupId)->with('msg','ระบบได้ทำสร้างใบรับพัสดุ เรียบร้อยแล้ว')->with('msg-type','success');

            return redirect('partner/pickup-detail/'.$pickupId)->with('msg','ระบบได้ทำสร้างใบรับพัสดุ เรียบร้อยแล้ว')->with('msg-type','success');

        }
    }

    public function pickupDetail($pickupId=null)
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

            $data = array(
                'pickupID' => $pickupId, 
                'pickup_data' => $pickupData, 
                'status' => $status, 
                'labels' => $labels,
            );
            return view('partner/pickup_detail',$data);
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
        
        /*'MinShippingRate' => $_REQUEST['MinShippingRate'],
        'MaxShippingRate' => $_REQUEST['MaxShippingRate'],
        'MinWeight' => $_REQUEST['MinWeight'],
        'MaxWeight' => $_REQUEST['MaxWeight']
        'Name' => $_REQUEST['PickupName'],
        'Email' => $_REQUEST['PickupEmail'],
        'PhoneNumber' => $_REQUEST['PickupPhoneNumber'],
        'Postcode' => $_REQUEST['PickupPostcode'],
        'PaymentMethod' => $_REQUEST['PaymentMethod'],
        'PickupType' => $_REQUEST['PickupType'],
        'CreateDateSince' => $_REQUEST['StartDate'],
        'CreateDateTo' => $_REQUEST['EndDate'],
        'Status' => $_REQUEST['Status'],*/

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
//          if(sizeof($resDetails) > 0 && is_array($resDetails)){
//              foreach ($resDetails as $pickupId) {
                    
//                  //$resPickup = FS_Pickup::get($pickupId);
//                  if(!$resPickup) continue;
//                  $resGetpickup[] = $resPickup;
                    
//              }
//          }
            //alert($resGetpickup);
            //$resGetpickup = FS_Pickup::get($pickupId);
            $msg = '';
        }
        $data = array(
            'page' => $page,
            'pickup_list' => $resGetpickup,
            'msg' => $msg,
        );

        return view('partner/pickup_list', $data);
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
            return view('partner/pickup_detail_print',$data);
        }else{
            return 'Pickup id is null.';
        }
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
            
            $customerObj = DB::table('customer')->where("CUST_ID",$customerId)->where("IS_ACTIVE",1)->first();
            
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
            return view('partner/pickup_invoice_print',$data);
        }else{
            return 'Pickup id is null.';
        }
    }



    private function calculateDiscount($args){
        
        extract($args);
        
        $discount = 0;

        $customerObj = DB::table('customer')->where("CUST_ID",$customerId)->where("IS_ACTIVE",1)->first();
        
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
                            if($promoObj->CODE_TIMELIMIT == "1M" && (time() - strtotime($customerObj->CREATE_DATETIME)) > 3600*24*30 ){
                                $discount = 0;
                            }
                            
                        }
                    }
                }
            }else{
                if(startsWith($refercode, "FGF") && $isFirstTime){
                    
                    $adviserId = substr($refercode, 3 );
                    $customerObj = DB::table('customer')->where("CUST_ID",$adviserId)->where("IS_ACTIVE",1)->first();
                    if(isset($customerObj)){
                        $discount = 300;
                    }else{
                        $discount = 0;
                    }
                }
            }
            
        }
        return $discount;
    }

    public function genQrCode($tracking=null)
    {
        $tracking = str_replace(' ', '', $tracking);
        $redirectQrCode = url ('partner/action/qrcode/'.$tracking);

        $qrCode = new QrCode();
        $qrCode
            ->setText($redirectQrCode)
            ->setSize(180)
            ->setPadding(10)
            ->setErrorCorrection('high')
            ->setForegroundColor(array('r' => 0, 'g' => 0, 'b' => 0, 'a' => 0))
            ->setBackgroundColor(array('r' => 255, 'g' => 255, 'b' => 255, 'a' => 0))
            ->setLabel($tracking)
            ->setLabelFontSize(16)
            ->setImageType(QrCode::IMAGE_TYPE_PNG);
        //echo '<img src="data:'.$qrCode->getContentType().';base64,'.$qrCode->generate().'" />';

        //$code = $barcode->generate();
        //$barcodeImage = '<img src="data:image/png;base64,'.$code.'" />';
        return $qrCode;
    }

    public function genBarcodeCode128X($sku=null)
    {
        $sku = str_replace(' ', '', $sku);
        $sku = str_replace('-', '', $sku);
        $barcode = new BarcodeGenerator();
        $barcode->setText($sku);
        $barcode->setType(BarcodeGenerator::Code128);
        $barcode->setScale(1);
        $barcode->setThickness(40);
        $barcode->setFontSize(10);

        $code = $barcode->generate();
        //$barcodeImage = '<img src="data:image/png;base64,'.$code.'" />';
        return $code;
    }

    
    public static function generateRandomString($length = null) {
        //$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $characters = '0123456789';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }



    public function store(Request $request)
    {
        //
    }

   
    public function show($id)
    {
        //
    }

 
    public function edit($id)
    {
        //
    }

    
    public function update(Request $request, $id)
    {
        //
    }


    public function destroy($id)
    {
        //
    }
}
