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
        //alert($request->all());//die();
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
        $post['state'] = $state;
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
            
            );
        }
    }

    public function getShipment($id=null)
    {
        if (session('customer.id') != null){
            $customerId = session('customer.id');
        }else{
            return redirect('/')->with('msg','คุณยังไม่ได้เข้าระบบ กรุณาเข้าสู่ระบบเพื่อใช้งาน');
        }

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


        $data = array(
            'res' => $getShipment,
            'category' => $getOrder,
        );

        return view('partner/detail_shipment',$data);
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
