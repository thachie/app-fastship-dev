<?php

namespace App\Http\Controllers\Shipment;

use App\Models\Country as Country; 
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Lib\Fastship\FS_Error;
use App\Lib\Fastship\Fastship;
use App\Lib\Fastship\FS_Shipment;
use App\Lib\Fastship\FS_Address;
use App\Lib\Fastship\FS_Customer;
use App\Lib\Thaitrade\ThaitradeManager;
use App\Lib\Encryption;
use Mail;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Input;
use App\Lib\Zoho\ZohoApiV2;


class ShipmentController extends Controller
{

    public function __construct()
    {
        if($_SERVER['REMOTE_ADDR'] == "localhost"){
            include(app_path() . '\Lib\inc.functions.php');
        }else{
            include(app_path() . '/Lib/inc.functions.php');
        }

    }

    //Prepare for check rate page
    public function prepareCalculateShipmentRate()
    {
        if (session('customer.id') != null){
            $customerId = session('customer.id');
        }else{
            return redirect('/')->with('msg','คุณยังไม่ได้เข้าระบบ กรุณาเข้าสู่ระบบเพื่อใช้งาน');
        }

        if ($customerId != null){
            
            $resource = DB::table('country')->where("IS_ACTIVE",1)->orderBy("CNTRY_NAME")->get();
            $country_row = array();
            foreach ($resource as $val) {
                $country_row[$val->CNTRY_CODE] = $val->CNTRY_NAME;
            }

            $data = array(
                'country' => $country_row,
                'rates' => '',
                'default' => array(
                'weight' => '',
                'type' => '',
                'width' => '',
                'height' => '',
                'length' => '',
                'country' => '',
                ),
            );
            return view('shipment_rate',$data);
        }
    }

    //Get rate (ajax)
    public function getRate(Request $request)
    {
    	//check customer login
        if (session('customer.id') != null){
			$customerId = session('customer.id');
		}else{
			exit();
		}

        //check parameter
        if ( (!empty($request->input('weight')) && $request->input('weight') > 0) && !empty($request->input('country'))){
            $weight = $request->input('weight');
            $country = $request->input('country');
        }else{
            
            exit();
            //return redirect('calculate_shipment_rate')->with('msg','Weight is null');
        }

        $type = $request->input('type');
        
        if($type == "box"){
            if( !empty($request->input('width')) ){
                $width = $request->input('width');
            }else{
                $width = 0;
            }
    
            if( !empty($request->input('height')) ){
                $height = $request->input('height');
            }else{
                $height = 0;
            }
    
            if( !empty($request->input('length')) ){
                $length = $request->input('length');
            }else{
                $length = 0;
            }
        }else{
            $width = 0;
            $height = 0;
            $length = 0;
        }

        /*if( !empty($request->input('country')) ){
            //$country = "'".$request->input('country')."'"; //$request->input('country');
            $country = $request->input('country');
        }else{
            $country = '';
        }*/


        //get api token
        Fastship::getToken($customerId);

        //prepare request data
        $rateDetails = array(
            'Weight' => $weight,
            'Width' => $width,
            'Height' => $height,
            'Length' => $length,
            'Country' => $country,
        );

        if($request->has('agent')){
            $rateDetails['Agent'] = $request->input('agent');
        }
        if($request->has('source')){
        	$rateDetails['Source'] = $request->input('source');
        }
        
        //call api
        try{
       		$rates = FS_Shipment::get_shipping_rates($rateDetails);
       		echo json_encode($rates);
        }catch (Exception $e){
        	echo false;
        }
        
        
        
//         $resource = DB::table('country')->where("IS_ACTIVE",1)->get();

//         foreach ($resource as $val) {
//             $country_row[] = $val;
//         }

        //if(sizeof($rates) > 0){
//             $data = array(
//                 'country' => $country_row,
//                 'rates' => $rates,
//             );

            /*foreach($rates as $rate){
                
            }*/
            
            //echo json_encode($rates);

            //return view('shipment_rate',$data);
            
       // }else{
            //$rates = array();
//             $rates = '';
//             $data = array(
//                 'country' => $country_row,
//                 'rates' => $rates,
//             );

          //  echo json_encode($rates);
            
            //return view('shipment_rate',$data);
        //}
        exit();
    }
    
    //Get fba address (ajax)
    public function getFbaAddress(Request $request)
    {
        //check customer login
        if (session('customer.id') != null){
            $customerId = session('customer.id');
        }else{
            exit();
        }

        $code = $request->input('code');
        
        
        $addressQry = DB::table("fba_address")->where("FBA_CODE",strtoupper($code));

        if($request->has('country')){
            $addressQry->where('fba_country',$request->input('country'));
        }
        
        $address = $addressQry->first();
        
        if(isset($address)){
            //prepare request data
            $return = array(
                'Code' => $address->FBA_CODE,
                'State' => $address->FBA_STATE,
                'City' => $address->FBA_CITY,
                'Address' => $address->FBA_ADDR,
            	'Postcode' => $address->FBA_POSTCODE,
            );
            
            echo json_encode($return);
        }else{
            echo false;
        }

        exit();
    }
    
    //Get fba address (ajax)
    public function getFbaAddresses(Request $request)
    {
        //check customer login
        if (session('customer.id') != null){
            $customerId = session('customer.id');
        }else{
            exit();
        }
        
        $country = $request->input('country');

        $addressesQry = DB::table("fba_address")
        ->select(
            "fba_code as code",
            "fba_state as state",
            "fba_city as city",
            "fba_addr as address",
            "fba_postcode as postcode"
        )->where("fba_country",strtoupper($country));
        
        if($request->has('term')){
            $term = $request->input('term');
            if($term != ""){
                $addressesQry->whereRaw('( UPPER(fba_code) LIKE UPPER("%' . $term . '%") OR UPPER(fba_addr) LIKE UPPER("%' . $term . '%") OR UPPER(fba_city) LIKE UPPER("%' . $term . '%") OR UPPER(fba_state) LIKE UPPER("%' . $term . '%") )');
            }
        }
        
        $addresses = $addressesQry->get();

        if(isset($addresses)){
            echo json_encode($addresses);
        }else{
            echo false;
        }
        
        exit();
    }
    
    
    //Prepare for check rate page
    public function prepareCreateShipment(Request $request)
    {
        if (session('customer.id') != null){
            $customerId = session('customer.id');
        }else{
            return redirect('/')->with('msg','คุณยังไม่ได้เข้าระบบ กรุณาเข้าสู่ระบบเพื่อใช้งาน');
        }
        if(isset($request->all()['data']['status']) && $request->all()['data']['status'] == 'Fail'){

            $receiver['firstname'] = $request->all()['data']['firstname'];
            $receiver['lastname'] = $request->all()['data']['lastname'];
            $receiver['phonenumber'] = $request->all()['data']['phonenumber'];
            $receiver['email'] = $request->all()['data']['email'];
            if(isset($request->all()['data']['company']) && $request->all()['data']['company'] != ""){
            	$receiver['company'] = $request->all()['data']['company'];
            }else{
            	$receiver['company'] = "";
            }
            $receiver['address1'] = $request->all()['data']['address1'];
            if(isset($request->all()['data']['address2']) && $request->all()['data']['address2'] != ""){
            	$receiver['address2'] = $request->all()['data']['address2'];
            }else{
            	$receiver['address2'] = "";
            }
            $receiver['city'] = $request->all()['data']['city'];
            $receiver['state'] = $request->all()['data']['state'];
            $receiver['postcode'] = $request->all()['data']['postcode'];
            if(isset($request->all()['data']['category']) && $request->all()['data']['category'] != ""){
                $category = $request->all()['data']['category'];
            }else{
                $category = "";
            }
            if(isset($request->all()['data']['amount']) && $request->all()['data']['amount'] != ""){
                $amount = $request->all()['data']['amount'];
            }else{
                $amount = "";
            }
            if(isset($request->all()['data']['value']) && $request->all()['data']['value'] != ""){
                $value = $request->all()['data']['value'];
            }else{
                $value = "";
            }
            $term = $request->all()['data']['term'];

            $weight = $request->all()['data']['weight'];
            $country = $request->all()['data']['country'];
            $agent = $request->all()['data']['agent'];
            $price = $request->all()['data']['price'];
            $delivery_time = $request->all()['data']['delivery_time'];

            if( !empty($request->all()['data']['width']) ){
                $width = $request->all()['data']['width'];
            }else{
                $width = 0;
            }
    
            if( !empty($request->all()['data']['height']) ){
                $height = $request->all()['data']['height'];
            }else{
                $height = 0;
            }
    
            if( !empty($request->all()['data']['length']) ){
                $length = $request->all()['data']['length'];
            }else{
                $length = 0;
            }
            $status = $request->all()['data']['status'];
            
            $insurance = 0;
            
        }else{
            $receiver['firstname'] = '';
            $receiver['lastname'] = '';
            $receiver['phonenumber'] = '';
            $receiver['email'] = '';
            $receiver['company'] = '';
            $receiver['address1'] = '';
            $receiver['address2'] = '';
            $receiver['city'] = '';
            $receiver['state'] = '';
            $receiver['postcode'] = '';
            $category = '';
            $amount = '';
            $value = '';
            $term = '';

            if($request->input("type") == "parcel"){
                $width = "";
                $height = "";
                $length = "";
            }else{
                $width = $request->input("width");
                $height = $request->input("height");
                $length = $request->input("length");
            }
            $weight = $request->input("weight");
            
            $country = $request->input("country");
            $agent = $request->input("agent");
            $price = $request->input('price');
            $delivery_time = $request->input('delivery_time');
            $status = '';
            
            $insurance = 0;
            
        }
   
        if($agent == ""){
        	return redirect('calculate_shipment_rate')->with('msg','Please select agent and inform shipment data');
//         	return redirect('calculate_shipment_rate')->with('msg','Please select agent and inform shipment data')->with('msg-type','success');
        }
        
        //เพิ่ม declareType by thachie
//         $dTypes = DB::table('product_type')->where("IS_ACTIVE",1)->orderBy("TYPE_SORT")->orderBy("TYPE_NAME")->get();
//         $declareTypes = array();
//         if(sizeof($dTypes)>0){
//          	foreach($dTypes as $dType){
//          		$declareTypes[$dType->TYPE_CODE] = $dType->TYPE_NAME . " (" . $dType->TYPE_NAME_TH . ")";
//          	}
//         }
        /*
        $declareTypes = array(
        	'FASHION' => 'Fashion',
        	'SPORTS' => 'Sports',
        	'HOME_GARDEN' => 'Home And Garden',
        	'BEAUTY_HEALTH' => 'Beauty And Health',
        	'GIFT_CRAFTS' => 'Gifts And Crafts',
        	'FOOD_BEVERAGE' => 'Food And Beverage',
        	'JEWELRY' => 'Jewelry And Accessories'
        );*/
       
        Fastship::getToken($customerId);
        $countryObj = FS_Address::get_country($country);
        
        /*
        $resource = DB::table('country')->where("CNTRY_CODE",$country)->first();
        if($resource){
        	$deminimis = TradeGovManager::search($resource->CNTRY_CODE2ISO);
        	if(isset($deminimis) && $deminimis){
        		$deminimis_text = $deminimis->de_minimis_value . " " . $deminimis->de_minimis_currency;
        	}else{
        		$deminimis_text = "";
        	}
        }else{
        	$deminimis_text = "";
        }
        */
        $deminimis_text = "";
        
        $data = array(
            'default' => array(
                'weight' => $weight,
                'agent' => $agent,
                'width' => $width,
                'height' => $height,
                'length' => $length,
                'country' => $country,
                'category' => $category,
                'amount' => $amount,
                'value' => $value,
                'term' => $term,
                'receiver' => $receiver,
                'status' => $status,
            	'price' => $price,
            	'delivery_time' => $delivery_time,
            	'insurance' => $insurance,
            ),
        	//'declareType' => $declareTypes,
            'country_2iso' => $countryObj['CNTRY_CODE2ISO'],
        	'deminimis' => $deminimis_text,
        );
        return view('create_shipment',$data);
    }
    
    //Prepare for create FBA
    public function prepareCreateShipmentFBA($country="USA",Request $request)
    {
        
        //check customer login
        if (session('customer.id') != null){
            $customerId = session('customer.id');
        }else{
            return redirect('/')->with('msg','คุณยังไม่ได้เข้าระบบ กรุณาเข้าสู่ระบบเพื่อใช้งาน');
        }
        
        $country = strtoupper($country);
        switch ($country){
            case 'USA':
                $requireIOR = false;
                $agent = "FS_FBA_PLUS";
                $whCode = "";
                $whAddress1 = "";
                $whCity = "";
                $whState = "";
                $whPostcode = "";
            break;
            case 'SGP':
                $requireIOR = true;
                $agent = "FS_FBA_SG";
                $whCode = "#SIN8";
                $whAddress1 = "5B Toh Guan Road East Level3 Units 1/2/3";
                $whCity = "Singapore";
                $whState = "Singapore";
                $whPostcode = "608829";
            break;
            case 'JPN':
                $requireIOR = true;
                $agent = "FS_FBA_JP";
                $whCode = "";
                $whAddress1 = "";
                $whCity = "";
                $whState = "";
                $whPostcode = "";
//                 $whCode = "#HND8";
//                 $whAddress1 = "2970-3 Ishikawamachi";
//                 $whCity = "Hachioji";
//                 $whState = "Tokyo";
//                 $whPostcode = "192-0032";
            break;
            default: $requireIOR = false; break;
        }

        $data = array(
            'country' => $country,
            'requireIOR' => $requireIOR,
            'agent' => $agent,
            'defaultWarehouse' => array(
                'code' => $whCode,
                'address1' => $whAddress1,
                'city' => $whCity,
                'state' => $whState,
                'postcode' => $whPostcode,
            ),
        );
        return view('create_shipment_fba',$data);

    }
    
    //Prepare for create FBA
    public function prepareCreateShipmentFBA2($country="USA",Request $request)
    {
        
        //check customer login
        if (session('customer.id') != null){
            $customerId = session('customer.id');
        }else{
            return redirect('/')->with('msg','คุณยังไม่ได้เข้าระบบ กรุณาเข้าสู่ระบบเพื่อใช้งาน');
        }

        $customerName = session('customer.name');
        
        $country = strtoupper($country);
        switch ($country){
            case 'USA':
                $requireIOR = false;
                $agent = "FS_FBA_PLUS";
                $whCode = "";
                $whAddress1 = "";
                $whCity = "";
                $whState = "";
                $whPostcode = "";
                break;
            case 'SGP':
                $requireIOR = true;
                $agent = "FS_FBA_SG";
                $whCode = "#SIN8";
                $whAddress1 = "5B Toh Guan Road East Level3 Units 1/2/3";
                $whCity = "Singapore";
                $whState = "Singapore";
                $whPostcode = "608829";
                break;
            case 'JPN':
                $requireIOR = true;
                $agent = "FS_FBA_JP";
                $whCode = "";
                $whAddress1 = "";
                $whCity = "";
                $whState = "";
                $whPostcode = "";
                //                 $whCode = "#HND8";
                //                 $whAddress1 = "2970-3 Ishikawamachi";
                //                 $whCity = "Hachioji";
                //                 $whState = "Tokyo";
                //                 $whPostcode = "192-0032";
                break;
            default: $requireIOR = false; break;
        }
        
        $data = array(
            'country' => $country,
            'requireIOR' => $requireIOR,
            'agent' => $agent,
            'customerName' => $customerName,
            'defaultWarehouse' => array(
                'code' => $whCode,
                'address1' => $whAddress1,
                'city' => $whCity,
                'state' => $whState,
                'postcode' => $whPostcode, 
            ),
        );
        return view('create_shipment_fba2',$data);
        
    }
        
    /*
    public function prepareCreateShipment2(Request $request)
    {
        if (session('customer.id') != null){
            $customerId = session('customer.id');
        }else{
            return redirect('/')->with('msg','คุณยังไม่ได้เข้าระบบ กรุณาเข้าสู่ระบบเพื่อใช้งาน');
        }
        
        $receiver['firstname'] = '';
        $receiver['lastname'] = '';
        $receiver['phonenumber'] = '';
        $receiver['email'] = '';
        $receiver['company'] = '';
        $receiver['address1'] = '';
        $receiver['address2'] = '';
        $receiver['city'] = '';
        $receiver['state'] = '';
        $receiver['postcode'] = '';
        $category = '';
        $amount = '';
        $value = '';
        $term = '';
        $status = '';
        $price = 0;
        $delivery_time = '';
        $insurance = 0;
        
        $agent = "UPS";
        $country = "USA";
        $weight = 1400;

        Fastship::getToken($customerId);
        $countryObj = FS_Address::get_country($country);
        
        
        $deminimis_text = "";
        
        $data = array(
            'default' => array(
                'weight' => $weight,
                'agent' => $agent,
                'width' => 0,
                'height' => 0,
                'length' => 0,
                'country' => $country,
                'category' => $category,
                'amount' => $amount,
                'value' => $value,
                'term' => $term,
                'receiver' => $receiver,
                'status' => $status,
                'price' => $price,
                'delivery_time' => $delivery_time,
                'insurance' => $insurance,
            ),
            'country_2iso' => $countryObj['CNTRY_CODE2ISO'],
            'deminimis' => $deminimis_text,
        );
        return view('create_shipment2',$data);
    }
    */
    
    //Prepare for check rate page
    public function prepareQuotations()
    {
        if (session('customer.id') != null){
            $customerId = session('customer.id');
        }else{
            return redirect('/')->with('msg','คุณยังไม่ได้เข้าระบบ กรุณาเข้าสู่ระบบเพื่อใช้งาน');
        }
        
        if ($customerId != null){
            
            Fastship::getToken($customerId);
            
            //prepare request data
            $searchDetails = array(
                "Status" => 'Quotation',
            );
            $response = FS_Shipment::search($searchDetails);
            if($response === false){
                $shipment_data = array();
            }else{
                $shipment_data = array();
                if(sizeof($response) > 0 && is_array($response)){
                    foreach ($response as $shipmentId) {
                        $shipment = FS_Shipment::get($shipmentId);
                        $shipment_data[] = $shipment;
                    }
                }
            }
            
            $data = array(
                "shipment_data" => $shipment_data,
            );
            
            return view('quotations',$data);
        }
    }
    
    public function prepareShipmentList($page=1,Request $request)
    {
        //check customer login
        if (session('customer.id') != null){
            $customerId = session('customer.id');
        }else{
            return redirect('/')->with('msg','คุณยังไม่ได้เข้าระบบ กรุณาเข้าสู่ระบบเพื่อใช้งาน');
        }
        
        //status mapping
        $statuses = array(
//             "Pending" => "สร้างพัสดุแล้ว",
//             "Imported" => "นำเข้าพัสดุแล้ว",
//             "Quotation" => "ขอใบเสนอราคา",
//             "Created" => "กำลังไปรับพัสดุ",
//             "ReadyToShip" => "เตรียมการจัดส่ง",
//             "Sent" => "ตัวแทนส่งรับพัสดุแล้ว",
//             'PreTransit' => "เตรียมส่งออก",
//             'InTransit' => "อยู่ระหว่างการส่ง",
//             'OutForDelivery' => "จำหน่ายพัสดุ",
//             'Delivered' => "ถึงที่หมาย",
//             'Return' => "ส่งคืนพัสดุ",
//             "Cancelled" => "ยกเลิก",
            "Quotation" => "quoted",
            "Created" => "new",
            "ReadyToShip" => "ready",
            "Sent" => "agent collected",
            'PreTransit' => "pre-transit",
            'InTransit' => "in-transit",
            'OutForDelivery' => "out for delivery",
            'Delivered' => "delivered",
            'Return' => "return to sender",
            "Cancelled" => "cancelled",
            "Onhold" => "onhold",
        );

        $limit = 20;
        
        Fastship::getToken($customerId);
        
        //prepare request data
        $searchDetails = array(
            "NoStatuses" => array('Pending','Imported','Cancelled'),
            'Limit' => $limit,
            'Page' => $page,
        );
        if($request->has("start_create_date")){
             $searchDetails['ShipmentDetail']['CreateDateSince'] = date("Y-m-d H:i:s",strtotime($request->get("start_create_date")));
        }
        if($request->has("end_create_date")){
            $searchDetails['ShipmentDetail']['CreateDateTo'] = date("Y-m-d H:i:s",strtotime($request->get("end_create_date")));
        }
        if($request->has("status")){
            $searchDetails['Status'] = $request->get("status");
        }
        if($request->has("shipment_id")){
            $searchDetails['ShipmentID'] = $request->get("shipment_id");
        }
        if($request->has("country")){
            $searchDetails['ReceiverDetail']['Country'] = $request->get("country");
        }
        
        $response = FS_Shipment::fullsearch($searchDetails);
        if(isset($response['data'])){
            $shipment_data = $response['data'];
            $total = $response['totalRecords'];
        }else{
            $shipment_data = array();
            $total = 0;
        }
        
        /*
        $shipment_data = array();
        if($response !== false){
            if(sizeof($response) > 0 && is_array($response)){
                foreach ($response as $shipmentId) {
                    $shipment = FS_Shipment::get($shipmentId);
                    $shipment_data[] = $shipment;
                }
            }   
        }*/
        
        //get countries
        $countries = FS_Address::get_countries();
        foreach($countries as $key => $country){
            $mapCountries[$country['CNTRY_CODE']] = $country['CNTRY_NAME'];
        }
        
        //default search
        $default = array(
            "start_create_date" => ($request->has("start_create_date")) ? $request->get("start_create_date") : "",
            "end_create_date" => ($request->has("end_create_date")) ? $request->get("end_create_date") : "",
            "status" => ($request->has("status")) ? $request->get("status") : "",
            "shipment_id" => ($request->has("shipment_id")) ? $request->get("shipment_id") : "",
            "country" => ($request->has("country")) ? $request->get("country") : "",
        );

        
        $data = array(
            'page' => $page,
            'limit' => $limit,
            'total' => $total,
            'statuses' => $statuses,
            'countries' => $mapCountries,
            'shipment_data' => $shipment_data,
            'default' => $default,
        );
        return view('shipment_list',$data);
    }

    public function createShipment(Request $request)
    {
    	
    	include(app_path() . '/Lib/ebay.restfulapi.functions.php');
    	
    	//check customer login
    	if (session('customer.id') != null){
    		$customerId = session('customer.id');
    	}else{
    		return redirect('/')->with('msg','คุณยังไม่ได้เข้าระบบ กรุณาเข้าสู่ระบบเพื่อใช้งาน');
    	}
    	
        $data = array();
       
        //get api token
        Fastship::getToken($customerId);
        
        $customerObj = FS_Customer::get($customerId);
        
        //remove save session
        $request->session()->forget('shipment');
        
        if($customerObj == null){ 
            return redirect('create_shipment')->with('msg','Customer id is null');
        }else{
        	
            $data['Sender_Firstname'] = $customerObj['Firstname'];
            $data['Sender_Lastname'] = $customerObj['Lastname'];
            $data['Sender_PhoneNumber'] = $customerObj['PhoneNumber'];
            $data['Sender_Email'] = $customerObj['Email'];
            $data['Sender_Company'] = $customerObj['Company'];
            $data['Sender_AddressLine1'] = $customerObj['AddressLine1'];
            $data['Sender_AddressLine2'] = $customerObj['AddressLine2'];
            $data['Sender_City'] = $customerObj['City'];
            $data['Sender_State'] = $customerObj['State'];
            $data['Sender_Postcode'] = $customerObj['Postcode'];
            $data['Sender_Country'] = $customerObj['Country'];

            //Receiver
            $data['Receiver_Firstname'] = $request->input('firstname');
            $data['Receiver_Lastname'] = $request->input('lastname');
            $data['Receiver_PhoneNumber'] = $request->input('phonenumber');
            $data['Receiver_Email'] = $request->input('email');
            $data['Receiver_Company'] = $request->input('company');
            $data['Receiver_AddressLine1'] = $request->input('address1');
            $data['Receiver_AddressLine2'] = $request->input('address2');
            $data['Receiver_City'] = $request->input('city');
            $data['Receiver_State'] = $request->input('state');
            $data['Receiver_Postcode'] = $request->input('postcode');
            //$data['Receiver_Country'] = $request->input('country');
            $data['TermOfTrade'] = strtoupper($request->input('term'));
            $data['ShippingAgent'] = $request->input('agent');
            $data['Weight'] = $request->input('weight');

            if ( (!empty($request->input('weight')) && $request->input('weight') > 0) && !empty($request->input('country'))){
                $data['Weight'] = $request->input('weight');
                $data['Receiver_Country'] = $request->input('country');
            }else{
                return redirect('create_shipment')->with('msg','Weight is null');
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

            $category = $request->input('category');
            $amount = $request->input('amount');
            $value = $request->input('value');
            $other = $request->input('other');
            $Remark = $request->input('note');
            $Reference = $request->input('orderref');

            if(sizeof($category) == 1 && $category[0] == ""){
                return redirect()->back()->with('msg','กรุณาระบุประเภทพัสดุ');
            }

            foreach ($category as $key => $cat) {
            	if($cat == "OTHERS"){
            		$saveCat = $other[$key];
            	}else{
            		$saveCat = $cat;
            	}
                $Declarations[$key] = array(
                    'DeclareType' => $saveCat,
                    'DeclareQty' => $amount[$key],
                    'DeclareValue' => $value[$key],
                );
            }
            
            //check ebay feed
            $ebayId = ($request->has('ebay_id'))?$request->input('ebay_id'):false;
            $etsyId = ($request->has('etsy_id'))?$request->input('etsy_id'):false;
            $account = $request->input('account');
            $receiptId = false;

            if($data['ShippingAgent'] == "Quotation"){
                $source = "Quatation";
            }else{
	            if($ebayId){
	            	$source = "EbayFeed";
	            }else if($etsyId){
	                $source = "EtsyFeed";
	                $receiptId = ($request->has('receipt_id'))?$request->input('receipt_id'):false;
	            }else{
                	$source = "Web";
	            }
            }
            
            
            //prepare request data
            $createDetails = array(
                'ShipmentDetail' => array(
                    'ShippingAgent' => $data['ShippingAgent'],
                    'Weight' => $data['Weight'],
                    'Width' => $data['Width'],
                    'Height' => $data['Height'],
                    'Length' => $data['Length'],
                    'Declarations' => $Declarations,
                    'TermOfTrade' => $data['TermOfTrade'],
                ),
                'SenderDetail' => array(
                    'Firstname' => $data['Sender_Firstname'],
                    'Lastname' => $data['Sender_Lastname'],
                    'PhoneNumber' => $data['Sender_PhoneNumber'],
                    'Email' => $data['Sender_Email'],
                    'Company' => $data['Sender_Company'],
                    'AddressLine1' => $data['Sender_AddressLine1'],
                    'AddressLine2' => $data['Sender_AddressLine2'],
                    'City' => $data['Sender_City'],
                    'State' => $data['Sender_State'],
                    'Postcode' => $data['Sender_Postcode'],
                    'Country' => $data['Sender_Country'],
                ),
                'ReceiverDetail' => array(
                    'Firstname' => $data['Receiver_Firstname'],
                    'Lastname' => $data['Receiver_Lastname'],
                    'PhoneNumber' => $data['Receiver_PhoneNumber'],
                    'Email' => $data['Receiver_Email'],
                    'Company' => $data['Receiver_Company'],
                    'AddressLine1' => $data['Receiver_AddressLine1'],
                    'AddressLine2' => $data['Receiver_AddressLine2'],
                    'City' => $data['Receiver_City'],
                    'State' => $data['Receiver_State'],
                    'Postcode' => $data['Receiver_Postcode'],
                    'Country' => $data['Receiver_Country'],
                ),
                'Reference' => $Reference,
                'Remark' => $Remark,
                'Source' => $source,
            );

            if($ebayId){
                $createDetails['TransactionId'] = $ebayId;
                $createDetails['RefAccount'] = $account;
            }else if($etsyId){
                $createDetails['TransactionId'] = $etsyId;
                $createDetails['RefAccount'] = $account;
            }
            
            
            //call api
            $response = FS_Shipment::create($createDetails);

            if($response === false){
                $data_callback['firstname'] = $request->input('firstname');
                $data_callback['lastname'] = $request->input('lastname');
                $data_callback['phonenumber'] = $request->input('phonenumber');
                $data_callback['email'] = $request->input('email');
                $data_callback['company'] = $request->input('company');
                $data_callback['address1'] = $request->input('address1');
                $data_callback['address2'] = $request->input('address2');
                $data_callback['city'] = $request->input('city');
                $data_callback['state'] = $request->input('state');
                $data_callback['postcode'] = $request->input('postcode');
                $data_callback['country'] = $request->input('country');
                $data_callback['term'] = strtoupper($request->input('term'));
                $data_callback['agent'] = $request->input('agent');
                $data_callback['price'] = $request->input('price');
                $data_callback['delivery_time'] = $request->input('delivery_time');
                $data_callback['weight'] = $request->input('weight');
                $data_callback['width'] = $request->input('width');
                $data_callback['height'] = $request->input('height');
                $data_callback['length'] = $request->input('length');
                $data_callback['category'] = $request->input('category');
                $data_callback['amount'] = $request->input('amount');
                $data_callback['value'] = $request->input('value');
                $data_callback['status'] = "Fail";
                //return redirect('calculate_shipment_rate');
                //return redirect('create_shipment_data', array('view' => "true", 'query' => $data))->with(array("status" => "Fail"));
                //return redirect('create_shipment_data', array('data' => $data));
                //return redirect()->route('create_shipment_data', ['data' =>  $data])->with('message', 'State');

                return redirect()->back()->with('msg','ไม่สามารถสร้างพัสดุได้ กรุณาตรวจสอบข้อมูลอีกครั้ง');
                
//                 return redirect()->action(
//                     'Shipment\ShipmentController@prepareCreateShipment', array('data' => $data_callback)
//                 );
                
            }else{
                
                $status = 'Success';
                //return redirect('create_pickup/'.$status);
                
                //update ebay IN-PROGRESS
                if($ebayId){

                 	$customerChannel = DB::table('customer_channel')->where("CUST_ID",$customerId)->where("CUST_CHANNEL","EBAY")
                 	->where("IS_ACTIVE",1)->where("CUST_ACCOUNTNAME",$account)->first();

			        $orders = array();
			        $orders[] = array(
			            'customerId' => $customerId,
			            'eBayId' => $ebayId,
			            'sellerId' => $account,
			            'status' => "IN_PROGRESS",
			            'shipmentId' => $response
			        );

			        $post = [
			            'command' => "UPDATE",
			            'customerId' => $customerId,
			            'sellerId' => $account,
			            'status' => 'IN_PROGRESS',
			            'shipmentId' => $response,
			            'refresh_token' => $customerChannel->CUST_APITOKEN,
			            'orders' => $orders,
			        ];

			        $json_string_data = json_encode($post);
			        $params = array(
			           "method" => "POST",
			           "url" => 'https://admin.fastship.co/api_marketplace/ebay_api/rest_update_orders.php',
			           "call_back_url" => base_url().'update-order.php',
			           "jsonData" => $json_string_data,
			        );

			        //$Response = FSRESTfulAPIs($params);
			        $Response = FS_RESTfulAPIs($params);
			        $jsonDecode = json_decode($Response, true);

                }else if($etsyId){ //update etsy IN-PROGRESS
                        
                    $orders = array();
                    $orders[] = array(
                        'customerId' => $customerId,
                        'etsy_id' => $etsyId,
                        'sellerId' => $account,
                        'receipt_id' => $receiptId,
                        'status' => "IN_PROGRESS",
                        'shipmentId' => $response
                    );
                    
                    $post = [
                        'command' => "UPDATE",
                        'customerId' => $customerId,
                        'sellerId' => $account,
                        'status' => "IN_PROGRESS",
                        'shipmentId' => $response,
                        'orders' => $orders,
                    ];
                    
                    $json_string_data = json_encode($post);
                    $params = array(
                        "method" => "POST",
                        "url" => 'https://admin.fastship.co/api_marketplace/etsy_api/rest_update_orders.php',
                        "call_back_url" => base_url().'update-order.php',
                        "jsonData" => $json_string_data,
                    );

                    $Response = FS_RESTfulAPIs($params);
                    $jsonDecode = json_decode($Response, true);
                        
                }
                
                if($data['ShippingAgent'] == "Quotation"){
                
                    //send email to admin
                    $data = array(
                        "ShipmentID" => $response,
                        "Weight" => $data['Weight'],
                        "Receiver_Country" => $data['Receiver_Country'],
                    );

                    Mail::send('email/new_quotation',$data,function($message) use ($data){
    
                        $msg = $data['ShipmentID'] . " - " . $data['Weight'] . "g. to " . $data['Receiver_Country'];
                                               
                        $message->to(['thachie@tuff.co.th','oak@tuff.co.th','vee@tuff.co.th']);
                        $message->from('cs@fastship.co', 'FastShip');
                        $message->subject('FastShip - New Quotation : ' . $msg);
                    });
                    
                    return redirect('quotations');  
                
                }else{
                    
                    //call api
                    $searchDetails = array("Status" => "Pending");
                    $response = FS_Shipment::search($searchDetails);
                    if($response === false){ $shipmentInCart = 0;
                    }else{
                        if(sizeof($response) > 0 && is_array($response)){
                            $shipmentInCart = sizeof($response);
                        }else{
                            $shipmentInCart = 0;
                        }
                    }
                    $request->session()->put('pending.shipment', $shipmentInCart);
                    
                    //$request->session()->put('pending.shipment', session('pending.shipment')+1);
                    
                    if($ebayId){
                        return redirect('shipment/create_ebay')->with('msg-type','shipment-success');
                    }else{
                        return redirect('create_pickup')->with('msg-type','shipment-success');
                    }
                }
            }
        }
    }
    
    //create FBA Shipments
    public function createShipmentFBA(Request $request)
    {
        //check customer login
        if (session('customer.id') != null){
            $customerId = session('customer.id');
        }else{
            return redirect('/')->with('msg','คุณยังไม่ได้เข้าระบบ กรุณาเข้าสู่ระบบเพื่อใช้งาน');
        }
        
        $data = array();
        
        //get api token
        Fastship::getToken($customerId);
        
        $customerObj = FS_Customer::get($customerId);
        
        if($customerObj == null){
            return redirect('create_shipment')->with('msg','Customer id is null');
        }else{
            
            $data['Sender_Firstname'] = $customerObj['Firstname'];
            $data['Sender_Lastname'] = $customerObj['Lastname'];
            $data['Sender_PhoneNumber'] = $customerObj['PhoneNumber'];
            $data['Sender_Email'] = $customerObj['Email'];
            $data['Sender_Company'] = $customerObj['Company'];
            $data['Sender_AddressLine1'] = $customerObj['AddressLine1'];
            $data['Sender_AddressLine2'] = $customerObj['AddressLine2'];
            $data['Sender_City'] = $customerObj['City'];
            $data['Sender_State'] = $customerObj['State'];
            $data['Sender_Postcode'] = $customerObj['Postcode'];
            $data['Sender_Country'] = $customerObj['Country'];
            
            //Receiver
            
            // string contains only english letters & digits
            if($request->has('receiver_firstname')){
                $firstname = $request->input('receiver_firstname');
            }else{
                if (preg_match('/[^A-Za-z0-9]/', $customerObj['Firstname'])) // check isn;t eng '/[^a-z\d]/i' should also work.
                {
                    $firstname = "As shipping label";
                }else{
                    $firstname = $customerObj['Firstname'];
                }
            }
            if (preg_match('/[^A-Za-z0-9]/', $customerObj['Lastname'])) // '/[^a-z\d]/i' should also work.
            {
                $lastname = "";
            }else{
                $lastname = $customerObj['Lastname'];
            }
            $data['Receiver_Firstname'] = $firstname;
            $data['Receiver_Lastname'] = $lastname;
            $data['Receiver_PhoneNumber'] = $data['Sender_PhoneNumber'];
            $data['Receiver_Email'] = $data['Sender_Email'];
            $data['Receiver_Company'] = "Amazon LLC.";
            $data['Receiver_AddressLine1'] = $request->input('receiver_address1');
            $data['Receiver_AddressLine2'] = $request->input('address2');
            $data['Receiver_City'] = $request->input('receiver_city');
            $data['Receiver_State'] = $request->input('receiver_state');
            $data['Receiver_Postcode'] = $request->input('receiver_postcode');
            $data['Receiver_Country'] = $request->input('country');
            $data['TermOfTrade'] = "DDP";
            $data['ShippingAgent'] = $request->input('agent');
            if($request->has("ior")){
                $remark = "IOR: " . $request->input('ior');
            }else{
                $remark = "";
            }
            
            $references = $request->input('reference');
            $weights = $request->input('weight');
            $widths = $request->input('width');
            $heights = $request->input('height');
            $lengths = $request->input('length');
            $declareTypes = $request->input('declare_type');
            $declareQtys = $request->input('declare_qty');
            $declareValues = $request->input('declare_value');

            foreach($references as $k => $reference){
                
                foreach ($declareTypes[$k] as $key => $cat) {

                    if($cat == "") continue;
                    
                    $Declarations[$key] = array(
                        'DeclareType' => $cat,
                        'DeclareQty' => $declareQtys[$k][$key],
                        'DeclareValue' => $declareValues[$k][$key],
                    );
                }
                
                $source = "Web_FBA";
                
                //prepare request data
                $createDetails = array(
                    'ShipmentDetail' => array(
                        'ShippingAgent' => $data['ShippingAgent'],
                        'Weight' => $weights[$k],
                        'Width' => $widths[$k],
                        'Height' => $heights[$k],
                        'Length' => $lengths[$k],
                        'Declarations' => $Declarations,
                        'TermOfTrade' => $data['TermOfTrade'],
                    ),
                    'SenderDetail' => array(
                        'Firstname' => $data['Sender_Firstname'],
                        'Lastname' => $data['Sender_Lastname'],
                        'PhoneNumber' => $data['Sender_PhoneNumber'],
                        'Email' => $data['Sender_Email'],
                        'Company' => $data['Sender_Company'],
                        'AddressLine1' => $data['Sender_AddressLine1'],
                        'AddressLine2' => $data['Sender_AddressLine2'],
                        'City' => $data['Sender_City'],
                        'State' => $data['Sender_State'],
                        'Postcode' => $data['Sender_Postcode'],
                        'Country' => $data['Sender_Country'],
                    ),
                    'ReceiverDetail' => array(
                        'Firstname' => $data['Receiver_Firstname'],
                        'Lastname' => $data['Receiver_Lastname'],
                        'PhoneNumber' => $data['Receiver_PhoneNumber'],
                        'Email' => $data['Receiver_Email'],
                        'Company' => $data['Receiver_Company'],
                        'AddressLine1' => $data['Receiver_AddressLine1'],
                        'AddressLine2' => $data['Receiver_AddressLine2'],
                        'City' => $data['Receiver_City'],
                        'State' => $data['Receiver_State'],
                        'Postcode' => $data['Receiver_Postcode'],
                        'Country' => $data['Receiver_Country'],
                    ),
                    'Reference' => $reference,
                    'Remark' => $remark,
                    'Source' => $source,
                );

                //call api
                $response = FS_Shipment::create($createDetails);
                
                if($response === false){
                    return back();
                }
            }
            
            //call api
            $searchDetails = array("Status" => "Pending");
            $response = FS_Shipment::search($searchDetails);
            if($response === false){
                $shipmentInCart = 0;
            }else{
                if(sizeof($response) > 0 && is_array($response)){
                    $shipmentInCart = sizeof($response);
                }else{
                    $shipmentInCart = 0;
                }
            }
            $request->session()->put('pending.shipment', $shipmentInCart);
            
            return redirect('create_pickup')->with('msg-type','shipment-success');
            
        }
    }

    
	//cancel shipment
    public function cancelShipment(Request $request)
    {

    	//check customer login
    	if (session('customer.id') != null){
    		$customerId = session('customer.id');
    	}else{
    		exit();
    	}
    	
    	//check parameter
    	if ( !empty($request->input('shipmentId')) && $request->input('shipmentId') > 0){
    		$shipmentId = $request->input('shipmentId');
    	}else{
    		exit();
    	}
    
    	//get api token
    	Fastship::getToken($customerId);
    
    	//check shipment owner
    	$checkShipment = FS_Shipment::get($shipmentId);
    	if(!$checkShipment) {
    		return redirect('shipment_detail/'.$shipmentId)->with("msg","ไม่พบพัสดุหมายเลข " . $shipmentId . " ในระบบ");
    	}

    	//call api
    	$result = FS_Shipment::cancel($shipmentId);
    	
    	if($result){

    		//call api
    		$searchDetails = array("Status" => "Pending");
    		$response = FS_Shipment::search($searchDetails);
    		if($response === false){ $shipmentInCart = 0;
    		}else{
    			if(sizeof($response) > 0 && is_array($response)){
    				$shipmentInCart = sizeof($response);
    			}else{
    				$shipmentInCart = 0;
    			}
    		}
    		$request->session()->put('pending.shipment', $shipmentInCart);
	        //$request->session()->put('pending.shipment',  session('pending.shipment') - 1);

	        //update zoho
    		if(isset($checkShipment['ZohoId']) && $checkShipment['ZohoId'] != ""){
    		    $params = array(
    		        "zoho_id" => $checkShipment['ZohoId'],
    		        "status" => "Cancelled By Sender",
    		    );
    		    ZohoApiV2::updateSalesOrderStatus($params);
    		}
    		
    		return redirect('create_pickup')->with("msg","ลบพัสดุหมายเลข " .$shipmentId . " เรียบร้อยแล้ว")->with('msg-type','success');
    		
    	}else{
    		return redirect('create_pickup')->with("msg","ไม่สามารถลบพัสดุ " . $shipmentId . " ได้");
    	}

    }
    
    public function prepareShipmentDetail($id)
    {
    	//check customer login
    	if (session('customer.id') != null){
    		$customerId = session('customer.id');
    	}else{
    		return redirect('/')->with('msg','คุณยังไม่ได้เข้าระบบ กรุณาเข้าสู่ระบบเพื่อใช้งาน');
    	}
    	
        //http://localhost:8000/shipment_detail/1523419955
        Fastship::getToken($customerId);
        $ShipmentDetail = FS_Shipment::get($id);
        
        //static variable
        $trackingStatus = array(
        	"pre_transit" => "Pre-Transit",
        	"in_transit" => "In-Transit",
        	"out_for_delivery" => "Out for Delivery",
        	"delivered" => "Delivered",
        	"return_to_sender" => "Return to Sender",
        	"unknown" => "Unknown",
            "1001" => "Pre-Transit",
            "1002" => "In-Transit",
            "1003" => "Out for Delivery",
            "1004" => "Delivered",
            "1005" => "Return to Sender",
            "1006" => "On-hold",
            "1007" => "Unknown",
            "1000" => "Unknown",
        );
        
        if( isset($ShipmentDetail) && isset($ShipmentDetail['Status']) && !in_array($ShipmentDetail['Status'],array("Created","Pending","Cancelled")) && $ShipmentDetail['ShipmentDetail']['Tracking'] != ""){
        		
        	$tracking = $ShipmentDetail['ShipmentDetail']['Tracking'];
        	
        	//call api
        	$tracking_data = FS_Shipment::track($tracking);

        	if(empty($tracking_data['Events'])){
        		$tracking_data = array();
        	}
        }else{
        	$tracking_data = array();
        }
        
        //declare type
        $dTypes = DB::table('product_type')->where("IS_ACTIVE",1)->orderBy("TYPE_SORT")->orderBy("TYPE_NAME")->get();
        $declareTypes = array();
        if(sizeof($dTypes)>0){
        	foreach($dTypes as $dType){
        		$declareTypes[$dType->TYPE_CODE] = $dType->TYPE_NAME . " (" . $dType->TYPE_NAME_TH . ")";
        	}
        }

        $data = array(
            'ShipmentDetail' => $ShipmentDetail,
            'amounts' => null,
        	'tracking_data' => $tracking_data,
        	'trackingStatus' => $trackingStatus,
        	'declareTypes' => $declareTypes,
         );
        return view('shipment_detail',$data);
    }


    public function prepareShipmentChannel()
    {
    	//check customer login
    	if (session('customer.id') != null){
    		$customerId = session('customer.id');
    	}else{
    		return redirect('/')->with('msg','คุณยังไม่ได้เข้าระบบ กรุณาเข้าสู่ระบบเพื่อใช้งาน');
    	}
    	
    	Fastship::getToken($customerId);
    	
    	$status = "Imported";
    	$searchDetails = array(
    		"Status" => $status
    	);
    	
    	//call api
    	$response = FS_Shipment::search($searchDetails);

    	if($response === false){
    		$status = "noShipment";
    		$shipment_data = array();
    	}else{
    		$shipment_data = array();
    		if(sizeof($response) > 0 && is_array($response)){
    			foreach ($response as $shipmentId) {
    				$shipment_data[] = FS_Shipment::get($shipmentId);
    			}
    		}

    		$status = "";
    	
    	}

        $data = array(
        	'shipment_data' => $shipment_data,
        );

    	return view('shipment_channel',$data);
    }
    
    public function prepareShipmentImport(Request $request)
    {
    	
    	if(session('customer.id') != null){
            $customerId = session('customer.id');
        }else{
            return redirect('/')->with('msg','คุณยังไม่ได้เข้าระบบ กรุณาเข้าสู่ระบบเพื่อใช้งาน');
        }

        //get upload
        if(!isset($request->upload)){
            return redirect()->back()->with('msg', 'Choose file please! ');
        }else{

            //file upload
            $file = Input::file('upload');
            $file_name = $file->getClientOriginalName();
            if($_SERVER['REMOTE_ADDR'] == "localhost" || $_SERVER['REMOTE_ADDR'] == "127.0.0.1"){
            	$target_dir = storage_path("app\\public\\files_upload\\");
            }else{
              	$target_dir = storage_path("app/public/files_upload/");
            }
            //echo $target_dir;
            
            $file->move($target_dir,$file_name);

            //get api token
            Fastship::getToken($customerId);
            
            Excel::load($target_dir.$file_name, function ($reader) {
            	$reader->each(function($sheet) {
            		foreach ($sheet->toArray() as $row) {
            			$this->data[] = $row;
            		}
            	});
            });
            $data_import = $this->data;
            
            $count = 1;
            $upload_data = array();
            foreach ($data_import as $v) {

            	if($count > 50) break;
            	if(!isset($v['firstname']) || $v['firstname'] == "") continue;

            	$Errors = "";
            			 
            			//Receiver
            			if(!isset($v['firstname'])){
            				$Errors .= "Firstname is missing\n";
            				$data_row['Receiver_Firstname'] = "";
            			}else{
            			    if(!self::checkEnglishOnly($v['firstname'])){
            			        $Errors .= "Firstname not in english\n";
            			        $data_row['Receiver_Firstname'] = "<span style='color:red'>" . $v['firstname'] . "</span>";
            			    }else{
            			        $data_row['Receiver_Firstname'] = $v['firstname'];
            			    }
            				
            			}
            			if(!isset($v['lastname'])){
            				$data_row['Receiver_Lastname'] = "";
            			}else{
            			    if(!self::checkEnglishOnly($v['lastname'])){
            			        $Errors .= "Lastname not in english\n";
            			        $data_row['Receiver_Lastname'] = "<span style='color:red'>" . $v['lastname'] . "</span>";
            			    }else{
            			        $data_row['Receiver_Lastname'] = $v['lastname'];
            			    }
            			}
            			if(!isset($v['email'])){
            				$Errors .= "Email is missing\n";
            				$data_row['Receiver_Email'] = "";
            			}else{
            				$data_row['Receiver_Email'] = $v['email'];
            			}
            			if(!isset($v['phone'])){
            				$Errors .= "PhoneNumber is missing\n";
            				$data_row['Receiver_PhoneNumber'] = "";
            			}else{
            				$data_row['Receiver_PhoneNumber'] = $v['phone'];
            			}
            			
            			if(!isset($v['address_line_1'])){
            				$Errors .= "AddressLine1 is missing\n";
            				$data_row['Receiver_AddressLine1'] = "";
            			}else{
            			    if(!self::checkEnglishOnly($v['address_line_1'])){
            			        $Errors .= "Address Line 1 not in english\n";
            			        $data_row['Receiver_AddressLine1'] = "<span style='color:red'>" . $v['address_line_1'] . "</span>";
            			    }else{
            			        $data_row['Receiver_AddressLine1'] = $v['address_line_1'];
            			    }
            			}
            			if(!isset($v['address_line_2'])){
            				$data_row['Receiver_AddressLine2'] = "";
            			}else{
            			    if(!self::checkEnglishOnly($v['address_line_2'])){
            			        $Errors .= "Address Line 2 not in english\n";
            			        $data_row['Receiver_AddressLine2'] = "<span style='color:red'>" . $v['address_line_2'] . "</span>";
            			    }else{
            			        $data_row['Receiver_AddressLine2'] = $v['address_line_2'];
            			    }
            			}
            			if(!isset($v['city'])){
            				$Errors .= "City is missing\n";
            				$data_row['Receiver_City'] = "";
            			}else{
            			    if(!self::checkEnglishOnly($v['city'])){
            			        $Errors .= "City not in english\n";
            			        $data_row['Receiver_City'] = "<span style='color:red'>" . $v['city'] . "</span>";
            			    }else{
            			        $data_row['Receiver_City'] = $v['city'];
            			    }
            			}
            			if(!isset($v['state'])){
            				$Errors .= "State is missing\n";
            				$data_row['Receiver_State'] = "";
            			}else{
            			    if(!self::checkEnglishOnly($v['state'])){
            			        $Errors .= "State not in english\n";
            			        $data_row['Receiver_State'] = "<span style='color:red'>" . $v['state'] . "<span>";
            			    }else{
            			        $data_row['Receiver_State'] = $v['state'];
            			    }
            			}
            			if(!isset($v['postcode'])){
            				$Errors .= "Receiver_Postcode is missing\n";
            				$data_row['Receiver_Postcode'] = "";
            			}else{
            				$data_row['Receiver_Postcode'] = $v['postcode'];
            			}
            			if(!isset($v['country'])){
            				$Errors .= "Country name is missing\n";
            				$data_row['Receiver_Country'] = "";
            			}else{
            			    //$countryIso3 = DB::table("country")->where("CNTRY_NAME",$v['country'])->select("CNTRY_CODE")->first();
            			    $countryIso3 = DB::table("country")->where("IS_ACTIVE",1)->whereRaw('upper(CNTRY_NAME) like "' . strtoupper(trim($v['country'])) . '"')->select("CNTRY_CODE")->first();
            			    if($countryIso3){
            			        $data_row['Receiver_Country'] = $countryIso3->CNTRY_CODE;
            			    }else{
            			        $Errors .= "Country name not existed \n";
            			        $data_row['Receiver_Country'] = "<span style='color:red'>" . $v['country'] . "</span>";
            			    }
            			}
            			
            			$data_row['TermOfTrade'] = "DDU";
            			$data_row['Remark'] = "";
            			$data_row['Reference'] = "";
            			$data_row['Receiver_Company'] = "";
            			
//             			if(!isset($v['term_of_trade'])){
//             				$Errors .= "TermOfTrade is missing\n";
//             				$data_row['TermOfTrade'] = "DDU";
//             			}else{
//             				$data_row['TermOfTrade'] = $v['term_of_trade'];
//             			}
            			if(!isset($v['weight'])){
            				$Errors .= "Weight is missing\n";
            				$data_row['Weight'] = "";
            			}else{
            			    if(is_numeric($v['weight']) && floatval($v['weight']) > 0 ){
            			        $data_row['Weight'] = $v['weight'];
            			    }else{
            			        $Errors .= "Weight must greater than 0 \n";
            			        $data_row['Weight'] = "<span style='color:red'>" . $v['weight'] . "</span>";
            			    }
            			}
            			if(!isset($v['width'])){
            				$data_row['Width'] = 0;
            			}else{
            			    if(is_numeric($v['width']) && floatval($v['width']) >= 0 ){
            			        $data_row['Width'] = $v['width'];
            			    }else{
            			        $Errors .= "Width must greater than 0 \n";
            			        $data_row['Width'] = "<span style='color:red'>" . $v['width'] . "</span>";
            			    }
            			}
            			if(!isset($v['height'])){
            				$data_row['Height'] = 0;
            			}else{
            			    if(is_numeric($v['height']) && floatval($v['height']) >= 0 ){
            			        $data_row['Height'] = $v['height'];
            			    }else{
            			        $Errors .= "Height must greater than 0 \n";
            			        $data_row['Height'] = "<span style='color:red'>" . $v['height'] . "</span>";
            			    }
            			}
            			if(!isset($v['length'])){
            				$data_row['Length'] = 0;
            			}else{
            			    if(is_numeric($v['length']) && floatval($v['length']) >= 0 ){
            			        $data_row['Length'] = $v['length'];
            			    }else{
            			        $Errors .= "Length must greater than 0 \n";
            			        $data_row['Length'] = "<span style='color:red'>" . $v['length'] . "</span>";
            			    }
            			}
            			if(!isset($v['category'])){
            				$Errors .= "DeclareType is missing\n";
            				$data_row['DeclareType'] = "";
            			}else{
            				$data_row['DeclareType'] = $v['category'];
            			}
            			if(!isset($v['qty'])){
            				$Errors .= "Qty is missing\n";
            				$data_row['DeclareQty'] = "";
            			}else{
            			    if(is_numeric($v['qty']) && intval($v['qty']) > 0 ){
            			        $data_row['DeclareQty'] = $v['qty'];
            			    }else{
            			        $Errors .= "Qty must greater than 0 \n";
            			        $data_row['DeclareQty'] = "<span style='color:red'>" . $v['qty'] . "</span>";
            			    }
            			}
            			if(!isset($v['declare_value'])){
            				$Errors .= "Declare Value is missing\n";
            				$data_row['DeclareValue'] = "";
            			}else{
            			    if(is_numeric($v['declare_value']) && floatval($v['declare_value']) >= 0 ){
            			        $data_row['DeclareValue'] = $v['declare_value'];
            			    }else{
            			        $Errors .= "Declare Value must greater than 0 \n";
            			        $data_row['DeclareValue'] = "<span style='color:red'>" . $v['declare_value'] . "</span>";
            			    }
            			}
            			
            		
            			if($Errors == ""){
            				$data_row['IconClass'] = "fa-check-circle fa-success";
            				$data_row['IconTitle'] = "Ready to Import.\nPlease select shipping agent.";
            				 
            				//get shipping agent
            				//prepare request data
            				$rateDetails = array(
            					'Weight' => $data_row['Weight'],
            					'Width' => $data_row['Width'],
            					'Height' => $data_row['Height'],
            					'Length' => $data_row['Length'],
            					'Country' => $data_row['Receiver_Country'],
            				);
            		
            				//call api
            				try{
            					$rates = FS_Shipment::get_shipping_rates($rateDetails);
            				}catch (FS_Error $e){
            		            $rates = array();
            				}
            				//$data_row['ShippingAgent'] = "กรุณาใส่ข้อมูลให้ครบถ้วน";
            				$data_row['ShippingAgent'] = "<select name='agent'>";
            				$data_row['ShippingAgent'].= "<option value='' >กรุณาเลือกวิธีการส่ง</option>";
            				if(sizeof($rates) > 0){
            					foreach($rates as $rate){
            					    $data_row['ShippingAgent'].= "<option value='" . $rate['Name'] . "' ><b>" . $rate['Name'] . "</b> (" . $rate['AccountRate'] . " บาท) - " . $rate['DeliveryTime'] . "</option>";
            					}
            				}
            				$data_row['ShippingAgent'].= "</select>";
            				 
            			}else{
            				$data_row['IconClass'] = "fa-exclamation-circle fa-danger";
            				$data_row['IconTitle'] = $Errors;
            				 
            				$data_row['ShippingAgent'] = "<span style='color:red'>" . nl2br($Errors) . "</span>";
            			}
            		
            	$upload_data[] = $data_row;
            		
            	$count++;
            }

        }
        
        $data = array(
        	'upload_data' => $upload_data,
        );
        
        return view('import_shipment_confirm',$data);

    	
    }
    
    //prepare import Magento
    public function prepareShipmentMagentoImport(Request $request)
    {
        
        if(session('customer.id') != null){
            $customerId = session('customer.id');
        }else{
            return redirect('/')->with('msg','คุณยังไม่ได้เข้าระบบ กรุณาเข้าสู่ระบบเพื่อใช้งาน');
        }

        //get upload
        if(!isset($request->upload)){
            return redirect()->back()->with('msg', 'Choose file please! ');
        }else{
            
            //file upload
            $file = Input::file('upload');
            $file_name = $file->getClientOriginalName();
            if($_SERVER['REMOTE_ADDR'] == "localhost" || $_SERVER['REMOTE_ADDR'] == "127.0.0.1"){
                $target_dir = storage_path("app\\public\\files_upload\\");
            }else{
                $target_dir = storage_path("app/public/files_upload/");
            }
            //echo $target_dir;
            
            $file->move($target_dir,$file_name);
            
            //get api token
            //Fastship::getToken($customerId);
            
            $row = 0;
            Excel::load($target_dir.$file_name, function ($reader) {
                $reader->each(function($sheet) {
                    foreach ($sheet->toArray() as $row) {
                        $this->data[] = $row;
                    }
                });
            });
            $data_import = $this->data;

            //check header format
            $check = ($data_import[0] == "Order #") && ($data_import[1] == "Purchased On");
            echo $check;
            
            $Errors = "";
            $count = 1;
            $upload_data = array();
            $data_row = array();
            foreach ($data_import as $key=>$v) {
                
                if($count > 50*7) break;

                if($key%7==0){
                    $data_row['Reference'] = $v;  
                }else if($key%7==3){
                    list($first,$last) = explode(" ", $v);
                    $data_row['Receiver_Firstname'] = $first;
                    $data_row['Receiver_Lastname'] = $last;
                }else if($key%7==4){
                    $data_row['DeclareValue'] = $v;
                }

                if($key % 7 == 6) {
                    
                    $data_row['CreateDate'] = date("Y-m-d H:i:s");
                    $data_row['Receiver_Email'] = "";
                    $data_row['Receiver_PhoneNumber'] = "";
                    $data_row['Receiver_AddressLine1'] = "";
                    $data_row['Receiver_AddressLine2'] = "";
                    $data_row['Receiver_City'] = "";
                    $data_row['Receiver_State'] = "";
                    $data_row['Receiver_Postcode'] = "";
                    $data_row['Receiver_Country'] = "";
                    $data_row['TermOfTrade'] = "DDU";
                    $data_row['Remark'] = "";
                    $data_row['Receiver_Company'] = "";
                    $data_row['Weight'] = "";
                    $data_row['Width'] = 0;
                    $data_row['Height'] = 0;
                    $data_row['Length'] = 0;
                    $data_row['DeclareType'] = "";
                    $data_row['DeclareQty'] = "";
                    
                    if($Errors == ""){
                        $data_row['ShippingAgent'] = "กรุณาใส่ข้อมูลให้ครบถ้วน";
                        
                    }else{
                        $data_row['ShippingAgent'] = "<span style='color:red'>" . nl2br($Errors) . "</span>";
                    }
                    
                    $upload_data[] = $data_row;

                    $data_row = array();
                    $Errors = "";
                }

                $count++;
            }
        
        }
        //print_r($upload_data);
        
        Fastship::getToken($customerId);
        $countries = FS_Address::get_countries();
        
        $data = array(
            'upload_data' => $upload_data,
            'countries' => $countries,
            'source' => 'MagentoFileImport',
        );
        
        return view('import_shipment_3rdparty_confirm',$data);
    
    
    }
    
    //prepare import Ebay
    public function prepareShipmentEbayImport(Request $request)
    {
        
        if(session('customer.id') != null){
            $customerId = session('customer.id');
        }else{
            return redirect('/')->with('msg','คุณยังไม่ได้เข้าระบบ กรุณาเข้าสู่ระบบเพื่อใช้งาน');
        }
        
        //get upload
        if(!isset($request->upload)){
            return redirect()->back()->with('msg', 'Choose file please! ');
        }else{
            
            //get coutries
            Fastship::getToken($customerId);
            $countries = FS_Address::get_countries();
            $mapCountries = array();
            foreach($countries as $key => $country){
                $mapCountries[$country['CNTRY_NAME']] = $country['CNTRY_CODE'];
            }
            
            //file upload
            $file = Input::file('upload');
            $file_name = $file->getClientOriginalName();
            if($_SERVER['REMOTE_ADDR'] == "localhost" || $_SERVER['REMOTE_ADDR'] == "127.0.0.1"){
                $target_dir = storage_path("app\\public\\files_upload\\");
            }else{
                $target_dir = storage_path("app/public/files_upload/");
            }
            //echo $target_dir;
            
            $file->move($target_dir,$file_name);
            
            //get api token
            //Fastship::getToken($customerId);
            
            $rows = array();
            $file = fopen($target_dir.$file_name, 'r');
            while (($line = fgetcsv($file)) !== FALSE) {
                $rows[] = $line;
            }
            fclose($file);

            $upload_data = array();
            foreach ($rows as $key=>$v) {
                
                $data_row = array();
                $Errors = "";
                
                if($key > 50) break;
                if($key < 2) continue;
                if($key +2 == sizeof($rows)) break;
                
                //check header and empty row
                if($v[0] == "") continue;
                if($v[2] == "") continue;

                list($first,$last) = explode(" ", $v[2]);
                $data_row['Receiver_Firstname'] = $first;
                $data_row['Receiver_Lastname'] = $last;
                $data_row['Reference'] = $v[0];
                $data_row['CreateDate'] = $v[23];
                $data_row['Receiver_Email'] = $v[4];
                $data_row['Receiver_PhoneNumber'] = $v[5];
                $data_row['Receiver_AddressLine1'] = $v[42];
                $data_row['Receiver_AddressLine2'] = $v[43];
                $data_row['Receiver_City'] = $v[44];
                $data_row['Receiver_State'] = $v[45];
                $data_row['Receiver_Postcode'] = $v[46];
                $data_row['Receiver_Country'] = $v[47];
                $data_row['TermOfTrade'] = "DDU";
                $data_row['Remark'] = $v[28];
                $data_row['Receiver_Company'] = "";
                $data_row['Weight'] = "";
                $data_row['Width'] = 0;
                $data_row['Height'] = 0;
                $data_row['Length'] = 0;
                if($v[12] == ""){
                    $data_row['DeclareType'] = "";
                }else{
                    $data_row['DeclareType'] = $v[12];
                }
                $data_row['DeclareQty'] = $v[14];
                $data_row['DeclareValue'] = $v[15];
                
                if($Errors == ""){
                    $data_row['ShippingAgent'] = "กรุณาใส่ข้อมูลให้ครบถ้วน";
                    
                }else{
                    $data_row['ShippingAgent'] = "<span style='color:red'>" . nl2br($Errors) . "</span>";
                }
                
                $upload_data[] = $data_row;

            }
        }

        //print_r($upload_data);
        
        $data = array(
            'upload_data' => $upload_data,
            'countries' => $mapCountries,
            'source' => 'MagentoFileImport',
        );
        
        return view('import_shipment_3rdparty_confirm',$data);
        
        
    }
    
    //prepare import Amazon
    public function prepareShipmentAmazonImport(Request $request)
    {
        
        if(session('customer.id') != null){
            $customerId = session('customer.id');
        }else{
            return redirect('/')->with('msg','คุณยังไม่ได้เข้าระบบ กรุณาเข้าสู่ระบบเพื่อใช้งาน');
        }
        
        //get upload
        if(!isset($request->upload)){
            return redirect()->back()->with('msg', 'Choose file please! ');
        }else{
            
            //get coutries
            Fastship::getToken($customerId);
            $countries = FS_Address::get_countries();
            $mapCountries = array();
            foreach($countries as $key => $country){
                $mapCountries[$country['CNTRY_NAME']] = $country['CNTRY_CODE'];
            }
            
            //file upload
            $file = Input::file('upload');
            $file_name = $file->getClientOriginalName();
            if($_SERVER['REMOTE_ADDR'] == "localhost" || $_SERVER['REMOTE_ADDR'] == "127.0.0.1"){
                $target_dir = storage_path("app\\public\\files_upload\\");
            }else{
                $target_dir = storage_path("app/public/files_upload/");
            }
            //echo $target_dir;
            
            $file->move($target_dir,$file_name);
            
            //get api token
            //Fastship::getToken($customerId);
            
            $rows = array();
            $file = fopen($target_dir.$file_name, 'r');
            while (($line = fgetcsv($file)) !== FALSE) {
                $rows[] = $line;
            }
            fclose($file);
            
            $upload_data = array();
            foreach ($rows as $key=>$v) {
                
                $data_row = array();
                $Errors = "";
                
                if($key > 50) break;
                if($key < 2) continue;
                if($key +2 == sizeof($rows)) break;
                
                //check header and empty row
                if($v[0] == "") continue;
                if($v[2] == "") continue;
                
                list($first,$last) = explode(" ", $v[2]);
                $data_row['Receiver_Firstname'] = $first;
                $data_row['Receiver_Lastname'] = $last;
                $data_row['Reference'] = $v[0];
                $data_row['CreateDate'] = $v[23];
                $data_row['Receiver_Email'] = $v[4];
                $data_row['Receiver_PhoneNumber'] = $v[5];
                $data_row['Receiver_AddressLine1'] = $v[42];
                $data_row['Receiver_AddressLine2'] = $v[43];
                $data_row['Receiver_City'] = $v[44];
                $data_row['Receiver_State'] = $v[45];
                $data_row['Receiver_Postcode'] = $v[46];
                $data_row['Receiver_Country'] = $v[47];
                $data_row['TermOfTrade'] = "DDU";
                $data_row['Remark'] = $v[28];
                $data_row['Receiver_Company'] = "";
                $data_row['Weight'] = "";
                $data_row['Width'] = 0;
                $data_row['Height'] = 0;
                $data_row['Length'] = 0;
                if($v[12] == ""){
                    $data_row['DeclareType'] = "";
                }else{
                    $data_row['DeclareType'] = $v[12];
                }
                $data_row['DeclareQty'] = $v[14];
                $data_row['DeclareValue'] = $v[15];
                
                if($Errors == ""){
                    $data_row['ShippingAgent'] = "กรุณาใส่ข้อมูลให้ครบถ้วน";
                    
                }else{
                    $data_row['ShippingAgent'] = "<span style='color:red'>" . nl2br($Errors) . "</span>";
                }
                
                $upload_data[] = $data_row;
                
            }
        }
        
        //print_r($upload_data);
        
        $data = array(
            'upload_data' => $upload_data,
            'countries' => $mapCountries,
            'source' => 'MagentoFileImport',
        );
        
        return view('import_shipment_3rdparty_confirm',$data);
        
        
    }
    
    public function prepareShipmentThaitradeImport(Request $request)
    {
        
        if(session('customer.id') != null){
            $customerId = session('customer.id');
        }else{
            return redirect('/')->with('msg','คุณยังไม่ได้เข้าระบบ กรุณาเข้าสู่ระบบเพื่อใช้งาน');
        }
        
        //get api token
        Fastship::getToken($customerId);
        
        $customer = FS_Customer::get($customerId);
        $marketplaceId = $customer['MarketplaceId'];

        //check connect with SOOK
        if(!isset($marketplaceId) || $marketplaceId == ""){
            return redirect('/')->with('msg','คุณยังไม่ได้เชื่อมต่อกับ SOOK <a href="https://www.thaitrade.com/customer/account/login" target="_blank">เชื่อมต่อ</a>');
        }
        
        //get orders from Thaitrade
        $orders = ThaitradeManager::getSoldOrders($marketplaceId);
        //print_r($orders);
        //ignore list
        $ignoreList = DB::table("sook_cancel_order")->where("cust_id",$customerId)->pluck('sook_ref')->toArray();

        $count = 1;
        $upload_data = array();
        foreach($orders as $order){

            $sender = $order['sender'];
            $reciever = $order['reciever'];
            $create_at = $order['create_at'];
            $status = $order['status'];
            $agent = $order['agent'];
            $orderId = $order['orderId'];
            $products = $order['products'];

            if(in_array($orderId,$ignoreList)) continue;

            //check exist
            $params = array(
                "Reference" => $orderId,
                "NoStatuses" => 5,
                "Source" => 'ThaitradeFeed',
            );
            $checkOrders = FS_Shipment::search($params);

            if($checkOrders != "No Shipment were found that match the specified criteria.") continue;

            if($count > 50) break;

            if(!isset($reciever['firstname']) || $reciever['firstname'] == "") continue;

            $Errors = "";
            $data_row = array();
            
            //Receiver
            if(!isset($reciever['firstname'])){
                $Errors .= "Firstname is missing\n";
                $data_row['Receiver_Firstname'] = "";
            }else{
                if(!self::checkEnglishOnly($reciever['firstname'])){
                    $Errors .= "Firstname not in english\n";
                    $data_row['Receiver_Firstname'] = "<span style='color:red'>" . $reciever['firstname'] . "</span>";
                }else{
                    $data_row['Receiver_Firstname'] = $reciever['firstname'];
                }
                
            }
            if(!isset($reciever['lastname'])){
                $data_row['Receiver_Lastname'] = "";
            }else{
                if(!self::checkEnglishOnly($reciever['lastname'])){
                    $Errors .= "Lastname not in english\n";
                    $data_row['Receiver_Lastname'] = "<span style='color:red'>" . $reciever['lastname'] . "</span>";
                }else{
                    $data_row['Receiver_Lastname'] = $reciever['lastname'];
                }
            }
            if(!isset($reciever['email'])){
                $Errors .= "Email is missing\n";
                $data_row['Receiver_Email'] = "";
            }else{
                $data_row['Receiver_Email'] = str_replace("_a_","@",$reciever['email']);
            }
            if(!isset($reciever['telephone'])){
                $Errors .= "PhoneNumber is missing\n";
                $data_row['Receiver_PhoneNumber'] = "";
            }else{
                $data_row['Receiver_PhoneNumber'] = $reciever['telephone'];
            }
            
            if(!isset($reciever['street'])){
                $Errors .= "AddressLine1 is missing\n";
                $data_row['Receiver_AddressLine1'] = "";
            }else{
                if(!self::checkEnglishOnly($reciever['street'])){
                    $Errors .= "Address Line 1 not in english\n";
                    $data_row['Receiver_AddressLine1'] = "<span style='color:red'>" . $reciever['street'] . "</span>";
                }else{
                    $data_row['Receiver_AddressLine1'] = $reciever['street'];
                }
            }
//             if(!isset($reciever['address_line_2'])){
//                 $data_row['Receiver_AddressLine2'] = "";
//             }else{
//                 if(!self::checkEnglishOnly($reciever['address_line_2'])){
//                     $Errors .= "Address Line 2 not in english\n";
//                     $data_row['Receiver_AddressLine2'] = "<span style='color:red'>" . $reciever['address_line_2'] . "</span>";
//                 }else{
//                     $data_row['Receiver_AddressLine2'] = $reciever['address_line_2'];
//                 }
//             }
            if(!isset($reciever['city'])){
                $Errors .= "City is missing\n";
                $data_row['Receiver_City'] = "";
            }else{
                if(!self::checkEnglishOnly($reciever['city'])){
                    $Errors .= "City not in english\n";
                    $data_row['Receiver_City'] = "<span style='color:red'>" . $reciever['city'] . "</span>";
                }else{
                    $data_row['Receiver_City'] = $reciever['city'];
                }
            }
            if(!isset($reciever['region'])){
                $Errors .= "State is missing\n";
                $data_row['Receiver_State'] = "";
            }else{
                if(!self::checkEnglishOnly($reciever['region'])){
                    $Errors .= "State not in english\n";
                    $data_row['Receiver_State'] = "<span style='color:red'>" . $reciever['region'] . "<span>";
                }else{
                    $data_row['Receiver_State'] = $reciever['region'];
                }
            }
            if(!isset($reciever['postcode'])){
                $Errors .= "Receiver_Postcode is missing\n";
                $data_row['Receiver_Postcode'] = "";
            }else{
                $data_row['Receiver_Postcode'] = $reciever['postcode'];
            }
            if(!isset($reciever['country_id'])){
                $Errors .= "Country name is missing\n";
                $data_row['Receiver_Country'] = "";
            }else{
                //$countryIso3 = DB::table("country")->where("CNTRY_NAME",$reciever['country'])->select("CNTRY_CODE")->first();
                $countryIso3 = DB::table("country")->where("IS_ACTIVE",1)->whereRaw('CNTRY_CODE2ISO = "' . strtoupper(trim($reciever['country_id'])) . '"')->select("CNTRY_CODE")->first();
                if($countryIso3){
                    $data_row['Receiver_Country'] = $countryIso3->CNTRY_CODE;
                }else{
                    $Errors .= "Country name not existed \n";
                    $data_row['Receiver_Country'] = "<span style='color:red'>" . $reciever['country_id'] . "</span>";
                }
            }
                
            $data_row['TermOfTrade'] = "DDU";
            $data_row['Remark'] = "";
            $data_row['Reference'] = $orderId;
            $data_row['RefAccount'] = "SOOK#".$marketplaceId;
            $data_row['Receiver_Company'] = "";
            $data_row['CustAgent'] = str_replace("fastship_", "", $agent);
            $data_row['CreateDate'] = $create_at;

            $weight = 0;
            $width = 0;
            $height = 0;
            $length = 0;
            $declarations = array();
            foreach($products as $product){
                $weight += $product['wght']*intval($product['qty']);
                $width += $product['w']*intval($product['qty']);
                $height += $product['h']*intval($product['qty']);
                $length += $product['d']*intval($product['qty']);
                
                $declarations[] = array(
                    "Type" => $product['name'],
                    "Qty" => intval($product['qty']),
                    "Value" => $product['price']*intval($product['qty']),
                );
                
                //print_r($product);
            }

            if($weight == 0){
                $Errors .= "กรุณาระบุน้ำหนักสินค้า\n";
                $data_row['Weight'] = "";
            }else{
                $data_row['Weight'] = $weight;
            }
            
            if($width == 0){
                $Errors .= "กรุณาระบุความกว้างสินค้า\n";
                $data_row['Width'] = "";
            }else{
                $data_row['Width'] = $width;
            }
            
            if($height == 0){
                $Errors .= "กรุณาระบุความสูงสินค้า\n";
                $data_row['Height'] = "";
            }else{
                $data_row['Height'] = $height;
            }
            
            if($length == 0){
                $Errors .= "กรุณาระบุความยาวสินค้า\n";
                $data_row['Length'] = "";
            }else{
                $data_row['Length'] = $length;
            }

            $data_row['Declarations'] = $declarations;
            
            if($Errors == ""){
                $data_row['IconClass'] = "fa-check-circle fa-success";
                $data_row['IconTitle'] = "Ready to Import.\nPlease select shipping agent.";
                
                //get shipping agent
                //prepare request data
                $rateDetails = array(
                    'Weight' => $data_row['Weight'],
                    'Width' => $data_row['Width'],
                    'Height' => $data_row['Height'],
                    'Length' => $data_row['Length'],
                    'Country' => $data_row['Receiver_Country'],
                );
                
                //call api
                try{
                    $rates = FS_Shipment::get_shipping_rates($rateDetails);
                }catch (FS_Error $e){
                    $rates = array();
                }
                //$data_row['ShippingAgent'] = "กรุณาใส่ข้อมูลให้ครบถ้วน";
                $data_row['ShippingAgent'] = "<select name='agent'>";
                $data_row['ShippingAgent'].= "<option value='' >กรุณาเลือกวิธีการส่ง</option>";
                if(sizeof($rates) > 0){
                    foreach($rates as $rate){
                        $data_row['ShippingAgent'].= "<option value='" . $rate['Name'] . "' ><b>" . $rate['Name'] . "</b> (" . $rate['AccountRate'] . " บาท) - " . $rate['DeliveryTime'] . "</option>";
                    }
                }
                $data_row['ShippingAgent'].= "</select>";
                
            }else{
                $data_row['IconClass'] = "fa-exclamation-circle fa-danger";
                $data_row['IconTitle'] = $Errors;
                
                $data_row['ShippingAgent'] = "<span style='color:red'>" . nl2br($Errors) . "</span>";
            }
            
            $upload_data[] = $data_row;
            
            $count++;
            
        }

        //prepare to page
        $data = array(
            'upload_data' => $upload_data,
        );
        
        return view('sook_shipment_confirm',$data);
        
        
    }
    
    /* import shipment ajax*/
    public function importShipment(Request $request)
    {
    	
    	//check customer login
    	if (session('customer.id') != null){
    		$customerId = session('customer.id');
    	}else{
    		echo false;
    		exit();
    	}
    	 
    	//get api token
    	Fastship::getToken($customerId);
    	
    	if($request->has("source")){
    	    $source = $request->get("source");
    	}else{
    	    $source = "Unknown";
    	}
    	
    	$data = array();
    	 
    	$customerObj = FS_Customer::get($customerId);
    	//$customerObj = DB::table('customer')->where("CUST_ID",$customerId)->where("IS_ACTIVE",1)->first();
    
    	if($customerObj == null){
    		echo false;
    		exit();
    	}else{
  		
    		//Sender
    		$data['Sender_Firstname'] = $customerObj['Firstname'];
    		$data['Sender_Lastname'] = $customerObj['Lastname'];
    		$data['Sender_PhoneNumber'] = $customerObj['PhoneNumber'];
    		$data['Sender_Email'] = $customerObj['Email'];
    		$data['Sender_Company'] = $customerObj['Company'];
    		$data['Sender_AddressLine1'] = $customerObj['AddressLine1'];
    		$data['Sender_AddressLine2'] = $customerObj['AddressLine2'];
    		$data['Sender_City'] = $customerObj['City'];
    		$data['Sender_State'] = $customerObj['State'];
    		$data['Sender_Postcode'] = trim($customerObj['Postcode']);
    		$data['Sender_Country'] = $customerObj['Country'];
    
    		//Receiver
    		$data['Receiver_Firstname'] = $request->input('firstname');
    		$data['Receiver_Lastname'] = $request->input('lastname');
    		$data['Receiver_PhoneNumber'] = $request->input('phonenumber');
    		$data['Receiver_Email'] = $request->input('email');
    		$data['Receiver_Company'] = $request->input('company');
    		$data['Receiver_AddressLine1'] = $request->input('address1');
    		$data['Receiver_AddressLine2'] = $request->input('address2');
    		$data['Receiver_City'] = $request->input('city');
    		$data['Receiver_State'] = $request->input('state');
    		$data['Receiver_Postcode'] = trim($request->input('postcode'));
    		$data['Receiver_Country'] = $request->input('country');
    		$data['TermOfTrade'] = strtoupper($request->input('term'));
    		$data['ShippingAgent'] = $request->input('agent');
    		$data['Weight'] = $request->input('weight');
    		
    
    		if ( (!empty($request->input('weight')) && $request->input('weight') > 0) && !empty($request->input('country'))){
    			$data['Weight'] = $request->input('weight');
    			$data['Receiver_Country'] = $request->input('country');
    		}else{
    			return redirect('create_shipment')->with('msg','Weight is null');
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
    
    		$categories = $request->input('category');
    		$amounts = $request->input('amount');
    		$values = $request->input('value');
    		
    		$Remark = $request->input('note');
    		$Reference = $request->input('orderref');
    		$RefAccount = $request->input('refaccount');
    		
    		if(is_array($categories)){
    		    foreach($categories as $key=>$category){
    		        $Declarations[] = array(
    		            'DeclareType' => $category,
    		            'DeclareQty' => $amounts[$key],
    		            'DeclareValue' => $values[$key],
    		        );
    		    }
    		}else{
    		    $Declarations[0] = array(
    		        'DeclareType' => $categories,
    		        'DeclareQty' => $amounts,
    		        'DeclareValue' => $values,
    		    );
    		}
    		
    
    		if($customerId == 18377){
    		    //return $Declarations;
    		}
    		
    		//prepare request data
    		$createDetails = array(
    				'ShipmentDetail' => array(
    						'ShippingAgent' => $data['ShippingAgent'],
    						'Weight' => $data['Weight'],
    						'Width' => $data['Width'],
    						'Height' => $data['Height'],
    						'Length' => $data['Length'],
    						'Declarations' => $Declarations,
    						'TermOfTrade' => $data['TermOfTrade'],
    				),
    				'SenderDetail' => array(
    						'Firstname' => $data['Sender_Firstname'],
    						'Lastname' => $data['Sender_Lastname'],
    						'PhoneNumber' => $data['Sender_PhoneNumber'],
    						'Email' => $data['Sender_Email'],
    						'Company' => $data['Sender_Company'],
    						'AddressLine1' => $data['Sender_AddressLine1'],
    						'AddressLine2' => $data['Sender_AddressLine2'],
    						'City' => $data['Sender_City'],
    						'State' => $data['Sender_State'],
    						'Postcode' => $data['Sender_Postcode'],
    						'Country' => $data['Sender_Country'],
    				),
    				'ReceiverDetail' => array(
    						'Firstname' => $data['Receiver_Firstname'],
    						'Lastname' => $data['Receiver_Lastname'],
    						'PhoneNumber' => $data['Receiver_PhoneNumber'],
    						'Email' => $data['Receiver_Email'],
    						'Company' => $data['Receiver_Company'],
    						'AddressLine1' => $data['Receiver_AddressLine1'],
    						'AddressLine2' => $data['Receiver_AddressLine2'],
    						'City' => $data['Receiver_City'],
    						'State' => $data['Receiver_State'],
    						'Postcode' => $data['Receiver_Postcode'],
    						'Country' => $data['Receiver_Country'],
    				),
    				'Reference' => $Reference,
    				'Remark' => $Remark,
    		        'Source'=> $source,
    		        'RefAccount' => $RefAccount,
    		);

    		//call api
    		$response = FS_Shipment::create($createDetails);

    		if($response != false){

    			//call api
    		    $searchDetails = array("Status" => "Pending");
    		    $response = FS_Shipment::search($searchDetails);
    		    if($response === false){ $shipmentInCart = 0;
    		    }else{
    		        if(sizeof($response) > 0 && is_array($response)){
    		            $shipmentInCart = sizeof($response);
    		        }else{
    		            $shipmentInCart = 0;
    		        }
    		    }
    		    Session()->put('pending.shipment', $shipmentInCart);
    			
    			echo json_encode($response);

    		}else{
    			echo json_encode(false);
    		}
    		exit();
    	}
    }
    
    public function ebayCancelOrder(Request $request)
    {
        
        //check customer login
        if (session('customer.id') != null){
            $customerId = session('customer.id');
        }else{
            echo false;
            exit();
        }
        
        $data = array();
        
        //get api token
        Fastship::getToken($customerId);
        
        $customerObj = FS_Customer::get($customerId);
        //$customerObj = DB::table('customer')->where("CUST_ID",$customerId)->where("IS_ACTIVE",1)->first();
        
        if($customerObj == null){
            echo false;
            exit();
        }else{
  
            $Reference = $request->input('orderref');

            $insert = DB::table('ebay_cancel_order')->insert([
                'ebay_ref' => $Reference,
                'cust_id' => $customerId,
            ]);
            
            
            if($insert){
                echo true;
            }else{
                echo false;
            }
            exit();
        }
    }
    
    public function sookCancelOrder(Request $request)
    {
        
        //check customer login
        if (session('customer.id') != null){
            $customerId = session('customer.id');
        }else{
            echo false;
            exit();
        }
        
        $data = array();
        
        //get api token
        Fastship::getToken($customerId);
        
        $customerObj = FS_Customer::get($customerId);
        //$customerObj = DB::table('customer')->where("CUST_ID",$customerId)->where("IS_ACTIVE",1)->first();
        
        if($customerObj == null){
            echo false;
            exit();
        }else{
            
            $Reference = $request->input('orderref');
            
            $insert = DB::table('sook_cancel_order')->insert([
                'sook_ref' => $Reference,
                'cust_id' => $customerId,
            ]);
            
            
            if($insert){
                echo true;
            }else{
                echo false;
            }
            exit();
        }
    }

    public function sendTrackingEmail($token1,$token2,Request $request){

        $dataToLog = json_encode($request->all());
        file_put_contents(storage_path('app/public/test_log.txt'), $dataToLog . PHP_EOL, FILE_APPEND);
        
    	$converter = new Encryption;
    	
    	$email = $converter->decode($token1);
    	$shipmentId = $converter->decode($token2);

    	$customerObj = DB::table('customer')->select("CUST_ID")
    						->whereRaw('LOWER(CUST_EMAIL) = ?', strtolower($email))
    						->where("CUST_LEADSOURCE",5)
    						->where("IS_ACTIVE",1)->first();

    	if($customerObj == null || sizeof($customerObj) > 1){
    		exit();
    	}
    	
    	$customerId = $customerObj->CUST_ID;
    	
    	Fastship::getToken($customerId);
    	
    	$ShipmentDetail = FS_Shipment::get($shipmentId);

    	$declareTypes = array();
    	
    	if(!$ShipmentDetail || !isset($ShipmentDetail['ShipmentDetail'])) {
    	    //echo $email." ".$shipmentId;
    	    exit();
    	}
    	
    	$tracking = $ShipmentDetail['ShipmentDetail']['Tracking'];
    	
    	$data = array(
    		'shipmentId' => $shipmentId,
    		'email' => $email,
    	    'tracking' => $tracking,
    		'shipmentData' => $ShipmentDetail,
    	    'declareType' => $declareTypes,
    	);
    	
    	Mail::send('email/tracking',$data,function($message) use ($data){
    		$message->to($data['email']);
    		$message->bcc(['thachie@tuff.co.th','oak@tuff.co.th']);
    		$message->from('cs@fastship.co','FastShip');
    		$message->subject('FastShip - พัสดุหมายเลข  '. $data['shipmentId'] ." (".$data['tracking'].")  ส่งออกเรียบร้อยแล้ว");
    	});
    	
    	//$dataToLog = "Output: \n";
    	//file_put_contents(storage_path('app/public/test_log.txt'), $dataToLog . PHP_EOL, FILE_APPEND);

    	exit();
    	
    }
    
    public function exportShipment(Request $request){
        
        
        //check customer login
        if (session('customer.id') != null){
            $customerId = session('customer.id');
        }else{
            return redirect('/')->with('msg','คุณยังไม่ได้เข้าระบบ กรุณาเข้าสู่ระบบเพื่อใช้งาน');
        }

        Fastship::getToken($customerId);
        
        $limit = 100;
        
        //prepare request data
        $searchDetails = array(
            "NoStatuses" => array('Pending','Imported','Cancelled'),
            'Limit' => $limit,
            'Page' => 1,
        );
        if($request->has("start_create_date")){
            $searchDetails['ShipmentDetail']['CreateDateSince'] = date("Y-m-d H:i:s",strtotime($request->get("start_create_date")));
        }
        if($request->has("end_create_date")){
            $searchDetails['ShipmentDetail']['CreateDateTo'] = date("Y-m-d H:i:s",strtotime($request->get("end_create_date")));
        }
        if($request->has("status")){
            $searchDetails['Status'] = $request->get("status");
        }
        if($request->has("shipment_id")){
            $searchDetails['ShipmentID'] = $request->get("shipment_id");
        }
        if($request->has("country")){
            $searchDetails['ReceiverDetail']['Country'] = $request->get("country");
        }
        
        $response = FS_Shipment::fullsearch($searchDetails);
        if(isset($response['data'])){
            $shipment_data = $response['data'];
        }else{
            $shipment_data = array();
        }

        $countries = FS_Address::get_countries();

        $cntryMap = array();
        foreach($countries as $country){
            $cntryMap[$country['CNTRY_CODE']] = $country['CNTRY_NAME'];
        }

        $headers = array(
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=Shipment_Export" . date("Ymd") . ".csv",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        );
        
        $columns = array('ShipmentID','Reference','PickupID','CreateDate','Recevier Name','Destination Country','Shipping','Agent','Tracking','Declaration','Qty','Value');

        $callback = function() use ($shipment_data, $columns,$cntryMap)
        {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);
            
            foreach($shipment_data as $data) {
                
                $createDate = date("Y-m-d H:i:s",strtotime($data['CreateDate']['date']));
                $country = isset($cntryMap[$data['ReceiverDetail']['Country']]) ? $cntryMap[$data['ReceiverDetail']['Country']] : $data['ReceiverDetail']['Country'];
                $declareQty = explode(";", $data['ShipmentDetail']['DeclareQty']);
                $declareValue = explode(";", $data['ShipmentDetail']['DeclareValue']);
                
                $DeclareTypeStr = $data['ShipmentDetail']['DeclareType'];
                if(substr($DeclareTypeStr,strlen($DeclareTypeStr)-1) === ";"){
                    $DeclareTypeStr =  rtrim($DeclareTypeStr, ';');
                }
                $declareTypes = explode(";", $DeclareTypeStr);

                $count = 0;
                if(sizeof($declareTypes) > 0){
                    foreach($declareTypes as $declareType){
                        if($count == 0){
                            fputcsv($file, array(
                                $data['ID'],
                                $data['Reference'],
                                $data['PickupID'],
                                $createDate,
                                $data['ReceiverDetail']['Custname'],
                                $country,
                                $data['ShipmentDetail']['ShippingRate'],
                                $data['ShipmentDetail']['ShippingAgent'],
                                $data['ShipmentDetail']['Tracking'],
                                $declareTypes[$count],
                                $declareQty[$count],
                                $declareValue[$count],
                            ));
                        }else{
                            fputcsv($file, array(
                                "",
                                "",
                                "",
                                "",
                                "",
                                "",
                                "",
                                "",
                                "",
                                $declareTypes[$count],
                                $declareQty[$count],
                                $declareValue[$count],
                            ));
                        }
                        
                        $count++;
                    }
                }
            }
            fclose($file);
        };

        return Response::stream($callback, 200, $headers);
        exit();
        
        $columns = array('ReviewID', 'Provider', 'Title', 'Review', 'Location', 'Created', 'Anonymous', 'Escalate', 'Rating', 'Name');
        
        $callback = function() use ($data, $columns)
        {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);
            
            foreach($reviews as $review) {
                fputcsv($file, array($data->reviewID, $data->provider, $data->title, $data->review, $data->location, $data->review_created, $data->anon, $review->escalate, $review->rating, $review->name));
            }
            fclose($file);
        };
        return Response::stream($callback, 200, $headers);
    }
    
    public function cloneShipment(Request $request)
    {
        
        //remove save session
        $request->session()->forget('shipment');
        
        //check customer login
        if (session('customer.id') != null){
            $customerId = session('customer.id');
        }else{
            echo false;
            exit();
        }
        
        //get param
        $id = $request->input('shipment_id');
        
        //get api token
        Fastship::getToken($customerId);

        //get shipment 
        $shipment = FS_Shipment::get($id);
        
        $request->session()->put('shipment.weight', $shipment['ShipmentDetail']['Weight']);
        $request->session()->put('shipment.width', $shipment['ShipmentDetail']['Width']);
        $request->session()->put('shipment.height', $shipment['ShipmentDetail']['Height']);
        $request->session()->put('shipment.length', $shipment['ShipmentDetail']['Length']);
        $request->session()->put('shipment.declaretype', $shipment['ShipmentDetail']['DeclareType']);
        $request->session()->put('shipment.declarevalue', $shipment['ShipmentDetail']['DeclareValue']);
        $request->session()->put('shipment.declareqty', $shipment['ShipmentDetail']['DeclareQty']);
        $request->session()->put('shipment.term', $shipment['ShipmentDetail']['TermOfTrade']);
        
        $request->session()->put('shipment.firstname', $shipment['ReceiverDetail']['Firstname']);
        $request->session()->put('shipment.lastname', $shipment['ReceiverDetail']['Lastname']);
        $request->session()->put('shipment.company', $shipment['ReceiverDetail']['Company']);
        $request->session()->put('shipment.phonenumber', $shipment['ReceiverDetail']['PhoneNumber']);
        $request->session()->put('shipment.email', $shipment['ReceiverDetail']['Email']);
        $request->session()->put('shipment.address1', $shipment['ReceiverDetail']['AddressLine1']);
        $request->session()->put('shipment.address2', $shipment['ReceiverDetail']['AddressLine2']);
        $request->session()->put('shipment.city', $shipment['ReceiverDetail']['City']);
        $request->session()->put('shipment.state', $shipment['ReceiverDetail']['State']);
        $request->session()->put('shipment.postcode', $shipment['ReceiverDetail']['Postcode']);
        $request->session()->put('shipment.country', $shipment['ReceiverDetail']['Country']);
        $request->session()->put('shipment.reference', isset($shipment['ReceiverDetail']['Reference']) ? $shipment['ReceiverDetail']['Reference']:"" );
        
        return redirect('/calculate_shipment_rate')->with('msg','ระบบทำการคัดลอกข้อมูลพัสดุต้นแบบไว้แล้ว เริ่มสร้างพัสดุใหม่ได้เลย')->with('msg-type','success');

    }
    
    public function getDeclarations(Request $request)
    {
        
        if (session('customer.id') != null){
            $customerId = session('customer.id');
        }else{
            return redirect('/')->with('msg','คุณยังไม่ได้เข้าระบบ กรุณาเข้าสู่ระบบเพื่อใช้งาน');
        }
        
        Fastship::getToken($customerId);
        
        $term = $request->get("term");
        $term = str_replace("?","",$term);
        $declares = FS_Shipment::get_declarations($term);
        
        return response()->json(['declares'=>$declares]);
    }

    private function checkEnglishOnly($str){

        //if (preg_match('/[^A-Za-z0-9 _+-\/.\(\)#@,]/', $str)) // '/[^a-z\d]/i' should also work.
        //if (preg_match("/[a-zA-Z0-9 \/=%&_\.,~?'\-#@!$^*()<>{}]/", $str)) // '/[^a-z\d]/i' should also work.
        if (preg_match("/[^a-zA-Z0-9 \/+=%&_\.,~?\'\"\-#@!$^*():<>{}]/", $str)) // '/[^a-z\d]/i' should also work.
        {
            
            // string contains only english letters & digitsv
            return false;
        }
        
        return true;
        
    }

}
