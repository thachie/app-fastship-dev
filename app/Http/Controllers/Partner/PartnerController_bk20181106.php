<?php

namespace App\Http\Controllers\Partner;

use App\Models\Country as Country; // กำหนดชื่อ ของ Model จากที่อยู่ของ Model ที่เราเรียกใช้งาน
use DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Lib\Fastship\FS_Error;
use App\Lib\Fastship\Fastship;
use App\Lib\Fastship\FS_Shipment;
use App\Lib\TradeGov\TradeGovManager;
use App\Lib\Encryption;
use Mail;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Input;
use App\Lib\Fastship\FS_Address;
//use App\Lib\Ebay\EbayManager;
//use App\Lib\Ebay\eBayObjectMapping;
use CodeItNow\BarcodeBundle\Utils\BarcodeGenerator;
use CodeItNow\BarcodeBundle\Utils\QrCode;

class PartnerController extends Controller
{
    

    public function __construct()
    {
        date_default_timezone_set("Asia/Bangkok");
        include(app_path() . '/Lib/inc.functions.php');
    }

    public function prepareCalculateShipmentRatePartner(Request $request)
    {
        if (session('customer.id') != null){
            $customerId = session('customer.id');
        }else{
            return redirect('/')->with('msg','คุณยังไม่ได้เข้าระบบ กรุณาเข้าสู่ระบบเพื่อใช้งาน');
        }

        if ($customerId != null){
            Fastship::getToken($customerId);
            $countries = FS_Address::get_countries();
            
            $agent = 'DHL';
            $data = array(
                'default' => array(
                    'agent' => $agent,
                    
                    'country' => $countries,//$country_row,
                    'country_code' => 'AUS',
                ),
                'countries' => $countries,
                //'country' => $country_row,
                'declareType' => '',
                'deminimis' => '',
                'agent' => $agent,
            );
            return view('partner/create_shipment',$data);
        }
    }

    public function prepareCreateShipment(Request $request)
    {
        if (session('customer.id') != null){
            $customerId = session('customer.id');
        }else{
            return redirect('/')->with('msg','คุณยังไม่ได้เข้าระบบ กรุณาเข้าสู่ระบบเพื่อใช้งาน');
        }

        $customerName = session('customer.name');
        //alert($request->all());die();
        
        $post = array();
        $post['customerId'] = $customerId;
        $post['seller_name'] = $customerName;
        $post['seller_id'] = '99';
        $post['partner_id'] = '99';
        $post['firstname'] = $request->input('firstname');
        $post['lastname'] = $request->input('lastname');
        $post['phonenumber'] = $request->input('phonenumber');
        $post['email'] = $request->input('email');
        $post['company'] = $request->input('company');
        $post['address1'] = $request->input('address1');
        $post['address2'] = $request->input('address2');

        $city = $request->input('city');
        $state = $request->input('state');
        $country = $request->input('country');

        $cityArray = explode(",",$city);
        $stateArray = explode(",",$state);
        $countryArray = explode(",",$country);
        
        $post['city'] = $city;
        $post['state'] = $stateArray[1];//$state;
        $post['country'] = $countryArray[0];
        $post['country3code'] = $countryArray[1];
        $post['countryName'] = $countryArray[2];
        $post['postcode'] = $request->input('postcode');
        
        $post['sender_firstname'] = $request->input('sender_firstname');
        $post['sender_lastname'] = $request->input('sender_lastname');
        
        $post['term'] = strtoupper($request->input('term'));
        $post['type'] = $request->input("type");
        $post['weight'] = isset($request->weight)?$request->weight:'0';
        $post['width'] = isset($request->width)?$request->width:'0';
        $post['height'] = isset($request->height)?$request->height:'0';
        $post['length'] = isset($request->length)?$request->length:'0';
        $post['volumnWeight'] = isset($request->volumnWeightPost)?$request->volumnWeightPost:'0';
        $post['agent'] = $request->input("agent");
        $post['price'] = isset($request->price)?$request->price:'0';
        $post['delivery_time'] = $request->input('delivery_time');
        
        $post['ref'] = date("YmdHis").$this->generateRandomString(5);
        $post['des'] = 'Test '. date("Y-m-d H:i:s");

        /*$post['weight'] = isset($request->input("weight"))?$request->input("weight"):'0';
        $post['width'] = isset($request->input("width"))?$request->input("width"):'0';
        $post['height'] = isset($request->input("height"))?$request->input("height"):'0';
        $post['length'] = isset($request->input("length"))?$request->input("length"):'0';
        $post['volumnWeight'] = isset($request->input('volumnWeightPost'))?$request->input('volumnWeightPost'):'0';*/

        $category = $request->input('category');
        $amount = $request->input('amount');
        $value = $request->input('value');
        //alert($post);die();

        foreach ($category as $key => $cat) {
            $saveCat = $cat;
            $order_list[$key] = array(
                'category' => $saveCat,
                'amount' => $amount[$key],
                'value' => $value[$key],
            );
        }

        $customerObj = DB::table('customer')->where("CUST_ID",$customerId)->where("IS_ACTIVE",1)->first();
        // Start Create Shipment API
        $data['Sender_Firstname'] = $customerObj->CUST_FIRSTNAME;
        $data['Sender_Lastname'] = $customerObj->CUST_LASTNAME;
        $data['Sender_PhoneNumber'] = $customerObj->CUST_TEL;
        $data['Sender_Email'] = $customerObj->CUST_EMAIL;
        $data['Sender_Company'] = $customerObj->CUST_COMPANY;
        $data['Sender_AddressLine1'] = $customerObj->CUST_ADDR1;
        $data['Sender_AddressLine2'] = $customerObj->CUST_ADDR2;
        $data['Sender_City'] = $customerObj->CUST_CITY;
        $data['Sender_State'] = $customerObj->CUST_STATE;
        $data['Sender_Postcode'] = $customerObj->CUST_POSTCODE;
        $data['Sender_Country'] = $customerObj->CNTRY_CODE;

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
        //die();
        if ( (!empty($request->input('weight')) && $request->input('weight') > 0) && !empty($request->input('country'))){
            $data['Weight'] = $request->input('weight');
            $data['Receiver_Country'] = $post['country3code'];//$request->input('country');
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

        //$Remark = $request->input('note');
        //$Reference = $request->input('orderref');

        $post['remark'] = $request->input('note');
        $post['reference'] = $request->input('orderref');

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

        //get api token
        Fastship::getToken($customerId);

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
            'Reference' => $post['reference'],
            'Remark' => $post['remark'],
        );
        //alert('call api');
        //alert($createDetails);
        //die();
        //call api

        $response = FS_Shipment::create($createDetails);
        $post['tracking'] = $response;
        //return redirect('create_pickup')->with(['data' => $response]);
        //return redirect('create_pickup')->with('msg',$response);
        //return redirect('create_pickup/'.$response);
        alert($response);//die();
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

            /*return redirect()->action(
                'Shipment\ShipmentController@prepareCreateShipment', array('data' => $data_callback)
            );*/
            echo 'API FAIL';
            $shipment_status = 'FAIL';
        }else{
            $shipment_status = 'SUCCESS';
            
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
            //return redirect('create_pickup');
        }
        // End Create Shipment API

        //Insert DB //
        $ORDER_NUMBER = DB::table('fs_partner')->insertGetId([
            'CUST_ID' => $post['customerId'],
            'SELLER_ID' => $post['seller_id'],
            'SELLER_NAME' => $post['seller_name'],
            'IS_SELLER' => $post['seller_id'],
            'PARTNER_ID' => $post['partner_id'],
            'TRACKING_NUMBER' => $post['tracking'],
            'RECEIVER_FIRSTNAME' => $post['firstname'],
            'RECEIVER_LASTNAME' => $post['lastname'],
            'RECEIVER_COMPANY' => $post['company'],
            'RECEIVER_ADDRESS_1' => $post['address1'],
            'RECEIVER_ADDRESS_2' => $post['address2'],
            'RECEIVER_CITY' => $post['city'],
            'RECEIVER_STATE' => $post['state'],
            'RECEIVER_POSTCODE' => $post['postcode'],
            'RECEIVER_COUNTRY' => $post['countryName'],
            'RECEIVER_COUNTRY_CODE' => $post['country3code'],
            'RECEIVER_PHONE' => $post['phonenumber'],
            'RECEIVER_FAX' => '',
            'RECEIVER_EMAIL' => $post['email'],
            'REF_NUMBER' => $post['ref'],
            'DESCRIPTION' => $post['des'],
            'SENDER_FIRSTNAME' => $post['sender_firstname'],
            'SENDER_LASTNAME' => $post['sender_lastname'],
            'SENDER_PHONE' => '',
            'SENDER_FAX' => '',
            'SENDER_EMAIL' => '',
            'WEIGHT' => $post['weight'],
            'LENGTH' => $post['length'],
            'WIDTH' => $post['width'],
            'HEIGHT' => $post['height'],
            'DIMENSION' => $post['volumnWeight'],
            'PICKUP_TYPE' => '',
            'PICKUP_COST' => '0',
            'VAT' => '0',
            'INSURANCE' => '0',
            'PRICE' => $post['price'],
            'AGENT' => $post['agent'],
            'DELIVERY_TYPE' => '',
            'DELIVERY_COST' => '0',
            'DELIVERY_TIME' => $post['delivery_time'],
            'TYPE' => $post['type'],
            'FEE' => '0',
            'TERM' => $post['term'],
            'SHIPMENT_STATUS' => $shipment_status,
            'REMARK' => $post['remark'],
            'REFERENCE' => $post['reference'],
            'STATUS' => 'PENDING',
            'CREATE_DATETIME' => date("Y-m-d H:i:s")
        ]);
        //echo "ITEM_NUMBER = ".$ORDER_NUMBER.'<br>';
        if ($ORDER_NUMBER) {
            foreach ($order_list as $v) {
                $insert_list_status = DB::table('partner_order_product_list')->insert([
                    'ORDER_NUMBER' => $ORDER_NUMBER,
                    'CUST_ID' => $post['customerId'],
                    'SELLER_ID' => $post['seller_id'],
                    'PARTNER_ID' => $post['partner_id'],
                    'CATEGORY' => $v['category'],
                    'QTY' => $v['amount'],
                    'VALUE' => $v['value'],
                    'STATUS' => "PENDING",
                    'CREATE_DATETIME'=> date("Y-m-d H:i:s")
                ]);
            }
            
        }else{
            echo "Insert FS Partner Fail";
        }
        //alert($ORDER_NUMBER);

        if (empty($ORDER_NUMBER)) {
            return 'Not Create Shipment';
        }else{

            return redirect('partner/get/shipment_detail/'.$ORDER_NUMBER);
            exit();
        }
    }

    public function getShipment($id=null)
    {
        if (session('customer.id') != null){
            $customerId = session('customer.id');
        }else{
            return redirect('/')->with('msg','คุณยังไม่ได้เข้าระบบ กรุณาเข้าสู่ระบบเพื่อใช้งาน');
        }

        if ($id == '') {
            return 'ID is null';
        }else{
            $ORDER_NUMBER = $id;
            $getShipment = DB::table('fs_partner as p')
                //->join('partner_order_product_list as l', 'p.ORDER_NUMBER', '=', 'l.ORDER_NUMBER')
                ->select(
                    'p.*'
                )
                ->where('p.ORDER_NUMBER', $ORDER_NUMBER)
                ->where('p.CUST_ID', $customerId)
                //->where('l.ORDER_NUMBER', $ORDER_NUMBER)
                ->first();

            $getOrder = DB::table('partner_order_product_list')
                ->select(
                    'CATEGORY', 'QTY', 'VALUE'
                )
                ->where('ORDER_NUMBER', $ORDER_NUMBER)
                ->where('CUST_ID', $customerId)
                ->get();
            //alert($getShipment);die();
            $qrCode = $this->genQrCode(trim($getShipment->TRACKING_NUMBER));
            //$qrCode = $this->genQrCode(trim($getShipment->TRACKING_NUMBER));
            //echo '<img src="data:'.$qrCode->getContentType().';base64,'.$qrCode->generate().'" />';
            $Remark = '';
            $Reference = '';
            $data = array(
                'res' => $getShipment,
                'category' => $getOrder,
                //'Remark' => $Remark,
                //'Reference' => $Reference,
                'qrCode' => $qrCode,
            );

            return view('partner/detail_shipment',$data);
        }
    }

    public function trackingStatus($tracking=null)
    {
        if ($tracking == '') {
            return 'Tracking is null';
        }else{
            return 1234;
            $getShipment = DB::table('fs_partner as p')
                //->join('partner_order_product_list as l', 'p.ORDER_NUMBER', '=', 'l.ORDER_NUMBER')
                ->select(
                    'p.*'
                )
                //->where('p.CUST_ID', $customerId)
                ->where('p.TRACKING_NUMBER', $tracking)
                //->where('l.ORDER_NUMBER', $ORDER_NUMBER)
                ->first();

            /*$getOrder = DB::table('partner_order_product_list')
                ->select(
                    'CATEGORY', 'QTY', 'VALUE'
                )
                ->where('ORDER_NUMBER', $ORDER_NUMBER)
                ->where('CUST_ID', $customerId)
                ->get();*/
            alert($getShipment);
            //$qrCode = $this->genQrCode(trim($getShipment->TRACKING_NUMBER));
            //$qrCode = $this->genQrCode(trim($getShipment->TRACKING_NUMBER));
            //echo '<img src="data:'.$qrCode->getContentType().';base64,'.$qrCode->generate().'" />';
            die();
            $data = array(
                'res' => $getShipment,
                'category' => $getOrder,
            );

            return view('partner/detail_shipment',$data);
        }
    }

    public function apiShipmentDetail($id)
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
        );
        
        if( !in_array($ShipmentDetail['Status'],array("Created","Pending","Cancelled")) && $ShipmentDetail['ShipmentDetail']['Tracking'] != ""){
                
            $tracking = $ShipmentDetail['ShipmentDetail']['Tracking'];
            
            //call api
            $tracking_data = FS_Shipment::track($tracking);

            //alert($shipment_data);
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
        $id = $ShipmentDetail['ID'];
        $qrCode = $this->genQrCode(trim($id));
        
        //alert($ShipmentDetail);
        $data = array(
            'ShipmentDetail' => $ShipmentDetail,
            'amounts' => null,
            'tracking_data' => $tracking_data,
            'trackingStatus' => $trackingStatus,
            'declareTypes' => $declareTypes,
            'qrCode' => $qrCode,
         );
        //return view('shipment_detail',$data);
        return view('partner/api_shipment_detail',$data);
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

            return redirect('partner/pickup')->with("msg","ลบพัสดุหมายเลข " .$shipmentId . " เรียบร้อยแล้ว")->with('msg-type','success');
            
        }else{
            return redirect('partner/pickup')->with("msg","ไม่สามารถลบพัสดุ " . $shipmentId . " ได้");
        }

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

    public function getStates(Request $request)
    {
        if (session('customer.id') != null){
            $customerId = session('customer.id');
        }else{
            return redirect('/')->with('msg','คุณยังไม่ได้เข้าระบบ กรุณาเข้าสู่ระบบเพื่อใช้งาน');
        }

        Fastship::getToken($customerId);
        
        $states = FS_Address::get_states($request->get("country_id"));
        
        return response()->json(['states'=>$states]);
    }
    
    public function getCities(Request $request)
    {
        return $request->all();
        if (session('customer.id') != null){
            $customerId = session('customer.id');
        }else{
            return redirect('/')->with('msg','คุณยังไม่ได้เข้าระบบ กรุณาเข้าสู่ระบบเพื่อใช้งาน');
        }
        
        Fastship::getToken($customerId);
        
        $cities = FS_Address::get_cities($request->get("country_id"),$request->get("state_id"));

        return response()->json(['cities'=>$cities]);
    }
    
    public function getPostcodes(Request $request)
    {
        
        if (session('customer.id') != null){
            $customerId = session('customer.id');
        }else{
            return redirect('/')->with('msg','คุณยังไม่ได้เข้าระบบ กรุณาเข้าสู่ระบบเพื่อใช้งาน');
        }
        
        Fastship::getToken($customerId);
        
        $modifyCity = str_replace(" ","_",$request->get("city_name"));
        $postcodes = FS_Address::get_postcodes($request->get("country_id"),$modifyCity);
        
        return response()->json(['postcodes'=>$postcodes]);
    }


    ############# BK All 2018-11-01 #############
    //Prepare for check rate page
    public function prepareCalculateShipmentRatePartner_BK(Request $request)
    {
        if (session('customer.id') != null){
            $customerId = session('customer.id');
        }else{
            return redirect('/')->with('msg','คุณยังไม่ได้เข้าระบบ กรุณาเข้าสู่ระบบเพื่อใช้งาน');
        }

        if ($customerId != null){
            

            
            Fastship::getToken($customerId);
            $countries = FS_Address::get_countries();
            
            if(isset($request->all()['data']['status']) && $request->all()['data']['status'] == 'Fail'){
                //alert($request->all());
                //alert($request->all()['data']['status']);
                $receiver['firstname'] = $request->all()['data']['firstname'];
                $receiver['lastname'] = $request->all()['data']['lastname'];
                $receiver['phonenumber'] = $request->all()['data']['phonenumber'];
                $receiver['email'] = $request->all()['data']['email'];
                if($request->all()['data']['company'] != ""){
                    $receiver['company'] = $request->all()['data']['company'];
                }else{
                    $receiver['company'] = "";
                }
                $receiver['address1'] = $request->all()['data']['address1'];
                if($request->all()['data']['address2'] != ""){
                    $receiver['address2'] = $request->all()['data']['address2'];
                }else{
                    $receiver['address2'] = "";
                }
                $receiver['city'] = $request->all()['data']['city'];
                $receiver['state'] = $request->all()['data']['state'];
                $receiver['postcode'] = $request->all()['data']['postcode'];
                $category = $request->all()['data']['category'];
                $amount = $request->all()['data']['amount'];
                $value = $request->all()['data']['value'];
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

                $weight = 20;
                $width = '';
                $height = '';
                $length = '';
                $agent = '';
                $price = 200;
                $delivery_time ='';
                $status = '';
                $insurance = 0;
                
            }

            /*$data = array(
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
            );*/
            $agent = 'DHL';
            $data = array(
                'default' => array(
                    'weight' => $weight,
                    'agent' => $agent,
                    'width' => $width,
                    'height' => $height,
                    'length' => $length,
                    'country' => $countries,//$country_row,
                    'country_code' => 'AUS',
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
                'countries' => $countries,
                //'country' => $country_row,
                'declareType' => '',
                'deminimis' => '',
                'agent' => $agent,
            );
            return view('partner/create_shipment',$data);
        }
    }

    // From Data Base //
    public function getAppStateList(Request $request)
    {
        $states = DB::table("states")
            ->select(array("COUNTRY_CODE","STATE_CODE","STATE_NAME"))
            ->where("COUNTRY_CODE",$request->country_id)
            ->get();
        return response()->json(['states'=>$states]);
    }

    public function getAppCityList(Request $request)
    {
        $cities = DB::table("cities")
            ->select(array("CITY_ID","COUNTRY_CODE","STATE_CODE","CITY_NAME"))
            ->where("COUNTRY_CODE",$request->countryCode)
            ->where("STATE_CODE",$request->state_id)
            ->get();
        return response()->json(['cities'=>$cities]);
    }

    public function getAppPostCode(Request $request)
    {
        $postcode = DB::table("agent_world_city")
            ->select(array("COUNTRY_CODE","STATE_CODE","CITY_NAME","POST_CODE"))
            ->where("AGENT",$request->agent)
            ->where("COUNTRY_CODE",$request->countryCode)
            ->where("CITY_NAME",$request->cityName)
            ->get();
        
        return response()->json(['postcode'=>$postcode]);
    }

    // End From Data Base //

    //Prepare for check rate page
    public function prepareCreateShipment_BK20181101(Request $request)
    {
        if (session('customer.id') != null){
            $customerId = session('customer.id');
        }else{
            return redirect('/')->with('msg','คุณยังไม่ได้เข้าระบบ กรุณาเข้าสู่ระบบเพื่อใช้งาน');
        }

        $customerName = session('customer.name');
        //alert($request->all());die();
        $post = array();

        $post['customerId'] = $customerId;
        $post['seller_name'] = $customerName;
        $post['seller_id'] = '99';
        $post['partner_id'] = '99';
        $post['firstname'] = $request->input('firstname');
        $post['lastname'] = $request->input('lastname');
        $post['phonenumber'] = $request->input('phonenumber');
        $post['email'] = $request->input('email');
        $post['company'] = $request->input('company');
        $post['address1'] = $request->input('address1');
        $post['address2'] = $request->input('address2');

        $city = $request->input('city');
        $state = $request->input('state');
        $country = $request->input('country');

        $cityArray = explode(",",$city);
        $stateArray = explode(",",$state);
        $countryArray = explode(",",$country);

        $post['city'] = $cityArray[3];
        $post['state'] = $stateArray[2];
        $post['country'] = $countryArray[0];
        $post['country3code'] = $countryArray[1];
        $post['postcode'] = $request->input('postcode');
        
        $post['sender_firstname'] = $request->input('sender_firstname');
        $post['sender_lastname'] = $request->input('sender_lastname');
        
        $post['term'] = strtoupper($request->input('term'));
        $post['type'] = $request->input("type");
        $post['weight'] = isset($request->weight)?$request->weight:'0';
        $post['width'] = isset($request->width)?$request->width:'0';
        $post['height'] = isset($request->height)?$request->height:'0';
        $post['length'] = isset($request->length)?$request->length:'0';
        $post['volumnWeight'] = isset($request->volumnWeightPost)?$request->volumnWeightPost:'0';
        $post['agent'] = $request->input("agent");
        $post['price'] = isset($request->price)?$request->price:'0';
        $post['delivery_time'] = $request->input('delivery_time');
        $post['tracking'] = date("YmdHis").$this->generateRandomString(5);
        $post['ref'] = date("YmdHis").$this->generateRandomString(5);
        $post['des'] = 'Test '. date("Y-m-d H:i:s");

        /*$post['weight'] = isset($request->input("weight"))?$request->input("weight"):'0';
        $post['width'] = isset($request->input("width"))?$request->input("width"):'0';
        $post['height'] = isset($request->input("height"))?$request->input("height"):'0';
        $post['length'] = isset($request->input("length"))?$request->input("length"):'0';
        $post['volumnWeight'] = isset($request->input('volumnWeightPost'))?$request->input('volumnWeightPost'):'0';*/

        $category = $request->input('category');
        $amount = $request->input('amount');
        $value = $request->input('value');
        //alert($post);die();

        foreach ($category as $key => $cat) {
            $saveCat = $cat;
            $order_list[$key] = array(
                'category' => $saveCat,
                'amount' => $amount[$key],
                'value' => $value[$key],
            );
        }
        //Column not found: 1054 Unknown column '' in 'field list' (SQL: insert into 'fs_partner' ('') values (2018-10-30 16:12:51))
        $ORDER_NUMBER = DB::table('fs_partner')->insertGetId([
            'CUST_ID' => $post['customerId'],
            'SELLER_ID' => $post['seller_id'],
            'SELLER_NAME' => $post['seller_name'],
            'IS_SELLER' => $post['seller_id'],
            'PARTNER_ID' => $post['partner_id'],
            'TRACKING_NUMBER' => $post['tracking'],
            'RECEIVER_FIRSTNAME' => $post['firstname'],
            'RECEIVER_LASTNAME' => $post['lastname'],
            'RECEIVER_COMPANY' => $post['company'],
            'RECEIVER_ADDRESS_1' => $post['address1'],
            'RECEIVER_ADDRESS_2' => $post['address2'],
            'RECEIVER_CITY' => $post['city'],
            'RECEIVER_STATE' => $post['state'],
            'RECEIVER_POSTCODE' => $post['postcode'],
            'RECEIVER_COUNTRY' => $post['country'],
            'RECEIVER_PHONE' => $post['phonenumber'],
            'RECEIVER_FAX' => '',
            'RECEIVER_EMAIL' => $post['email'],
            'REF_NUMBER' => $post['ref'],
            'DESCRIPTION' => $post['des'],
            'RECEIVER_COUNTRY_CODE' => $post['country3code'],
            'SENDER_FIRSTNAME' => $post['sender_firstname'],
            'SENDER_LASTNAME' => $post['sender_lastname'],
            'SENDER_PHONE' => '',
            'SENDER_FAX' => '',
            'SENDER_EMAIL' => '',
            'WEIGHT' => $post['weight'],
            'LENGTH' => $post['length'],
            'WIDTH' => $post['width'],
            'HEIGHT' => $post['height'],
            'DIMENSION' => $post['volumnWeight'],
            'PICKUP_TYPE' => '',
            'PICKUP_COST' => '0',
            'VAT' => '0',
            'INSURANCE' => '0',
            'PRICE' => $post['price'],
            'AGENT' => $post['agent'],
            'DELIVERY_TYPE' => '',
            'DELIVERY_COST' => '0',
            'DELIVERY_TIME' => $post['delivery_time'],
            'TYPE' => $post['type'],
            'FEE' => '0',
            'TERM' => $post['term'],
            'STATUS' => 'PENDING',
            'CREATE_DATETIME' => date("Y-m-d H:i:s")
        ]);
        echo "ITEM_NUMBER = ".$ORDER_NUMBER.'<br>';
        if ($ORDER_NUMBER) {
            foreach ($order_list as $v) {
                alert($v);
                $insert_list_status = DB::table('partner_order_product_list')->insert([
                    'ORDER_NUMBER' => $ORDER_NUMBER,
                    'CUST_ID' => $post['customerId'],
                    'SELLER_ID' => $post['seller_id'],
                    'PARTNER_ID' => $post['partner_id'],
                    'CATEGORY' => $v['category'],
                    'QTY' => $v['amount'],
                    'VALUE' => $v['value'],
                    'STATUS' => "PENDING",
                    'CREATE_DATETIME'=> date("Y-m-d H:i:s")
                ]);
            }

           
            
        }else{
            echo "Insert FS Partner Fail";
        }

        return view('partner/detail_shipment',$data);

        die();
        
        
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
            'agent' => '',
            'declareType' => $declareTypes,
            'deminimis' => $deminimis_text,
        );
        return view('partner/create_shipment_address',$data);
    }

    

    public function prepareCreateShipment_BK(Request $request)
    {
        if (session('customer.id') != null){
            $customerId = session('customer.id');
        }else{
            return redirect('/')->with('msg','คุณยังไม่ได้เข้าระบบ กรุณาเข้าสู่ระบบเพื่อใช้งาน');
        }

        alert($request->all());die();
        if(isset($request->all()['data']['status']) && $request->all()['data']['status'] == 'Fail'){
            //alert($request->all());
            //alert($request->all()['data']['status']);
            $receiver['firstname'] = $request->all()['data']['firstname'];
            $receiver['lastname'] = $request->all()['data']['lastname'];
            $receiver['phonenumber'] = $request->all()['data']['phonenumber'];
            $receiver['email'] = $request->all()['data']['email'];
            if($request->all()['data']['company'] != ""){
                $receiver['company'] = $request->all()['data']['company'];
            }else{
                $receiver['company'] = "";
            }
            $receiver['address1'] = $request->all()['data']['address1'];
            if($request->all()['data']['address2'] != ""){
                $receiver['address2'] = $request->all()['data']['address2'];
            }else{
                $receiver['address2'] = "";
            }
            $receiver['city'] = $request->all()['data']['city'];
            $receiver['state'] = $request->all()['data']['state'];
            $receiver['postcode'] = $request->all()['data']['postcode'];
            $category = $request->all()['data']['category'];
            $amount = $request->all()['data']['amount'];
            $value = $request->all()['data']['value'];
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

            $weight = $request->input("weight");
            $width = $request->input("width");
            $height = $request->input("height");
            $length = $request->input("length");
            $country = $request->input("country");
            $agent = $request->input("agent");
            $price = $request->input('price');
            $delivery_time = $request->input('delivery_time');
            $status = '';
            
            $insurance = 0;
            
        }
   
        if($agent == ""){
            return redirect('calculate_shipment_rate')->with('msg','Please select agent and inform shipment data');
//          return redirect('calculate_shipment_rate')->with('msg','Please select agent and inform shipment data')->with('msg-type','success');
        }
        //เพิ่ม declareType by thachie
        
        $dTypes = DB::table('product_type')->where("IS_ACTIVE",1)->orderBy("TYPE_SORT")->orderBy("TYPE_NAME")->get();
        
        $declareTypes = array();
        if(sizeof($dTypes)>0){
            foreach($dTypes as $dType){
                $declareTypes[$dType->TYPE_CODE] = $dType->TYPE_NAME . " (" . $dType->TYPE_NAME_TH . ")";
            }
        }
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
            'agent' => '',
            'declareType' => $declareTypes,
            'deminimis' => $deminimis_text,
        );
        return view('partner/create_shipment_address',$data);
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

        //call api
        try{
            $rates = FS_Shipment::get_shipping_rates($rateDetails);
            echo json_encode($rates);
        }catch (Exception $e){
            echo false;
        }
        exit();
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
