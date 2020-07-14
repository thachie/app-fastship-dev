<?php

namespace App\Http\Controllers\Shipment;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Lib\Fastship\FS_Customer;
use App\Lib\Fastship\Fastship;

class EtsyController extends Controller
{
    
    public function __construct()
    {
        date_default_timezone_set("Asia/Bangkok");
        include(app_path() . '/Lib/etsy.restfulapi.functions.php');
        
        $this->FastFeedAPIs = 'https://admin.fastship.co/api_marketplace/etsy_api/';
        
        session()->put('customer.id', 69);
        session()->put('lang', 'en');
        
    }
    
    public function addChannel(Request $request){
    
        if (session('customer.id') != null){
            $customerId = session('customer.id');
        }else{
            return redirect('/')->with('msg','คุณยังไม่ได้เข้าระบบ กรุณาเข้าสู่ระบบเพื่อใช้งาน');
        }
        
        print_r($request->all());
        
        $token = $request->input("token");
        $secret = $request->input("secret");
        $userId = $request->input("user_id");
        $shops = $request->input("shop");
        exit();

        Fastship::getToken($customerId);
        $params = array(
            "CustomerID" => $customerId,
            "ChannelType" => "ETSY",
            "UserId" => $userId,
            "Shops" => $shops,
            "Token" => $token,
        );
        FS_Customer::addChannel($params);
        
    }
    
    /***etsy Process ******/
    public function prepareCreateShipmentEtsy(Request $request){
        
        //check customer login
//         if (session('customer.id') != null){
//             $customerId = session('customer.id');
//         }else{
//             return redirect('/')->with('msg','คุณยังไม่ได้เข้าระบบ กรุณาเข้าสู่ระบบเพื่อใช้งาน');
//         }

        $customerId = 69;
        
        //get channel from api
        Fastship::getToken($customerId);
        $channel_data = FS_Customer::getChannel($customerId);
        
        $customerChannels = array();
        if($channel_data == false){
            return redirect('channel_list')->with('msg','คุณยังไม่ได้เพิ่มบัญชี Etsy');
        }
        foreach($channel_data as $channel){
            $customerChannels[$channel['AccountName']] = $channel;
        }
        
        /*
        //account by earth
        if($request->has("account")){
            $account = $request->input("account");
            
            if($account == "fs_add_etsy_channel"){
                return redirect('channel_list');
            }
            
            $customerChannel = $customerChannels[$account];
        }else{
            $account = "";
            $customerChannel = $channel_data[0];
            //return redirect('channel_list')->with('msg','คุณยังไม่ได้เพิ่มบัญชี Etsy');
        }
        
        if(!isset($customerChannel)){
            return redirect('channel_list')->with('msg','คุณยังไม่ได้เพิ่มบัญชี Etsy');
        }
        
        //token
        if($request->refresh_token){
            $refresh_token = $request->refresh_token;
        }else{
            $refresh_token = $customerChannel['Token'];
        }
        
        if (empty($refresh_token)) {
            return redirect('/channel_list')->with('msg','มีปัญหาการเชื่อมต่อ กรุณาต่ออายุบัญชีEtsyเพื่อใช้งานใหม่อีกครั้ง');
            exit();
        }
        */

        //pull = data from fastfeed database
        //get = data from Etsy APIs
        $command = strtoupper(isset($request->command) ? $request->command:"");
        
        // ######## change here ######
        $OAUTH_CONSUMER_KEY = 'iatq396n37t2e5geponxroky'; //from https://www.etsy.com/developers/your-apps
        $OAUTH_CONSUMER_SECRET = '4vsr5j1izj'; //from https://www.etsy.com/developers/your-apps
        $access_token = "fc9cbd16b504ad747bd90fec03bd14";//$acc_token['oauth_token'];
        $access_token_secret = "410c109280";//$acc_token['oauth_token_secret'];
        $user_id = '258386395';
        $shop_id = '21741257';
        $customerId = '4815';
        $sellerId = '258386395';
        // ###########################
        
        $url = $this->FastFeedAPIs . 'rest_get_orders.php';
        $call_back_url = url('/shipment/create_etsy');
        $etsy_url = "https://openapi.etsy.com/v2/";
        $filter='';
        $limit=50;
        $offset=0;
        // ../shops/:shop_id/receipts/:status =>(open, unshipped, unpaid, completed, processing, all)
        $url_request_etsy_api = $etsy_url."shops/".$shop_id."/receipts/all?limit=".$limit."&offset=".$offset;
        
        $post = [
            'command' => strtoupper($command),
            'customerId' => $customerId,
            'sellerId' => $sellerId,
            'shop_id' => $shop_id,
            'OAUTH_CONSUMER_KEY' => $OAUTH_CONSUMER_KEY,
            'OAUTH_CONSUMER_SECRET' => $OAUTH_CONSUMER_SECRET,
            'access_token' => $access_token,
            'access_token_secret' => $access_token_secret,
            'url_request_etsy_api' => $url_request_etsy_api,
        ];
        $json_string_data = json_encode($post);
        $params = array(
            "method" => "POST",
            "url" => $url,
            "call_back_url" => $call_back_url,
            "jsonData" => $json_string_data,
        );

        $Response = FS_RESTfulAPIs($params);
        $jsonDecode = json_decode($Response, true);
        
        $code = $jsonDecode['code'];
        $status = $jsonDecode['status'];
        if ($code == '203') {
            return redirect('/channel_list')->with('msg','กรุณาต่ออายุบัญชี etsy เพื่อใช้งาน');
            exit();
        }
        
        //
        $nodeData = $jsonDecode['data'];
        if (empty($nodeData['orders']) || $nodeData['total'] == 0) {
            //alert($command);alert('Not order is FastFeed.');alert('Push sync data from etsy.');
            $ordersList = array();
        }else{
            //alert($command);alert('Orders is FastFeed.');
            $ordersList = $nodeData['orders'];
        }
        
        //country 2iso list
        $countryQry = DB::table("country")->get();
        $country2iso = array();
        foreach($countryQry as $c){
            $country2iso[$c->CNTRY_CODE2ISO] = $c->CNTRY_NAME;
        }

        //get latest day
        if(isset($nodeData['last_feed'])){
            $latest_day = $nodeData['last_feed'];
            if (empty($latest_day)) {
                $latest_day = "-";
            }
        }else{
            $latest_day = "-";
        }
        
        $data = array(
            'customerChannels' => $customerChannels,
            'sellerId' => $sellerId,
            'account' => $sellerId,
            'ordersList' => $ordersList,
            'country2iso' => $country2iso,
            'latest_day' => $latest_day,
        );
        return view('etsy_import_shipment_list_sample',$data);
        
    }
    
    public function prepareCreateShipmentEtsyDetail(Request $request){
        
        //check customer login
        //         if (session('customer.id') != null){
        //             $customerId = session('customer.id');
        //         }else{
        //             return redirect('/')->with('msg','คุณยังไม่ได้เข้าระบบ กรุณาเข้าสู่ระบบเพื่อใช้งาน');
        //         }
            $customerId = 69;
        
        //Set parameter
        $etsy_id = $request->etsyId;
        $sellerId = $request->account;
        
        $url = $this->FastFeedAPIs .'pull_order.php';
        $call_back_url = '';
        
        $post = [
            'command' => 'PULL',
            'etsy_id' => $etsy_id,
            'sellerId' => $sellerId,
        ];
        
        $json_string_data = json_encode($post);
        $params = array(
            "method" => "POST",
            "url" => $url,
            "call_back_url" => $call_back_url,
            "jsonData" => $json_string_data,
        );
        
        $Response = FS_PullOrder($params);
        $jsonDecode = json_decode($Response,true);
        $detail = $jsonDecode['data']['orders'];
        
        //print_r($detail);exit();
        
        //$etsyId = $request->etsyId;
        //$detail = json_decode($request->get("detail"),true);
        //$account = $request->account;
        
        //default value from etsy
        $countryObj = DB::table("country")->where("cntry_code",$detail['country_code'])
        ->select("cntry_name as name","cntry_code as code")->first();
        
        $fullname = explode(" ",$detail['full_name']);
        $firstname = $fullname[0];
        $lastname = (isset($fullname[1]))?$fullname[1]:"";
        $default = array(
            'etsy_id' => $detail['etsy_id'],
            'account' => $sellerId,
            'sellerid' => $detail['seller_user_id'],
            'buyer' => $detail['buyer_user_id'],
            'firstname' => $firstname,
            'lastname' => $lastname,
            'phone' => $detail['phone'],
            'email' => $detail['buyer_email'],
            'company' => "",
            'address1' => $detail['address_line1'],
            'address2' => $detail['address_line2'],
            'city' => $detail['city'],
            'state' => $detail['state'],
            'postcode' => $detail['postal_code'],
            'country_code' => $detail['country_code'],
            'country' => $countryObj->name,
            'remark' => $detail['message_from_buyer'],
            'seller_note' => $detail['message_from_seller'],
            'orderref' => $detail['order_id'],
            'width' => $detail['width'],
            'height' => $detail['height'],
            'length' => $detail['length'],
            'agent_code' => $detail['shipping_method'],
            'receipt_id' => $detail['receipt_id'],
        );
        //check agent
        if(isset($detail['shipping_method'])){
            if($detail['shipping_method'] == "Economy Shipping"){
                $default['agent'] = "GM_Packet_Plus";
            }else if($detail['shipping_method'] == "Express Shipping"){
                $default['agent'] = "UPS";
            }else if($detail['shipping_method'] == "Standard Shipping"){
                $default['agent'] = "FS_Epacket";
            }else{
                $default['agent'] = "FS";
            }
        }
        
        $items = array();
        if(isset($detail['lineItems'])){
            foreach($detail['lineItems'] as $item){
                $items[] = array(
                    "type" => $item['title'],
                    "qty" => $item['qty'],
                    "value" => ceil($item['price']*32),
                );
            }
        }
        
        
        //validate
        $validateEnglish = "^[a-zA-Z0-9 /+=%&_\.,~?\'\-\#@!$^*()<>{}]+$";
        $validateDeclare = "^[a-zA-Z0-9 /+&]+$";
        
        //alert($etsyId);
        //alert($detail);
        
        $data = array(
            'etsyId' => $etsy_id,
            'default' => $default,
            'items' => $items,
            'validateEnglish' => $validateEnglish,
            'validateDeclare' => $validateDeclare,
        );
        
        return view('etsy_import_shipment_detail',$data);
    }
    
    public function deleteEtsyOrder(Request $request){
        
//         if (session('customer.id') != null){
//             $customerId = session('customer.id');
//         }else{
//             return redirect('/')->with('msg','คุณยังไม่ได้เข้าระบบ กรุณาเข้าสู่ระบบเพื่อใช้งาน');
//         }
        $customerId = 69;

        $sellerId = $request->sellerId;
        $etsyId = $request->etsyId;
        $account = $request->account;
        $receiptId = $request->receiptId;
        $shipmentId = '';

        $orders = array();
        $orders[] = array(
            'customerId' => $customerId,
            'etsy_id' => $etsyId,
            'sellerId' => $sellerId,
            'receipt_id' => $receiptId,
            'status' => "CANCELLED",
            'shipmentId' => $shipmentId
        );
        
        $post = [
            'command' => "UPDATE",
            'customerId' => $customerId,
            'sellerId' => $account,
            'status' => "CANCELLED",
            'shipmentId' => $shipmentId,
            'orders' => $orders,
        ];
        
        $json_string_data = json_encode($post);
        $params = array(
            "method" => "POST",
            "url" => $this->FastFeedAPIs.'rest_update_orders.php',
            "call_back_url" => url('shipment/etsy-delete'),
            "jsonData" => $json_string_data,
        );
        
        $Response = FS_RESTfulAPIs($params);
        $jsonDecode = json_decode($Response, true);
        
        return redirect('shipment/create_etsy?account='.$account);
    }
    
    public function getToken(Request $request){
        
        //get params
        $request_token_secret = session('request_secret');
        $request_token = $request->get('oauth_token');
        $verifier = $request->get('oauth_verifier');
        
        // ##### get etsy token #####
        $requestArray = array(
            "request_token_secret" => $request_token_secret,
            "request_token" => $request_token,
            "verifier" => $verifier,
        );
        $url = "https://admin.fastship.co/api_marketplace/etsy_api/get_token.php";
        $response = call_api($url,$requestArray);
        // ##### get etsy login url #####

        print_r($response);
        
        $token = "";
        $secret = "";
        $shops = array();
        if($response != ""){
            
            $res = json_decode($response,true);
            
            $token = $res['oauth_token'];
            $secret = $res['oauth_token_secret'];
            
            // ##### get etsy shops #####
            $requestArray = array(
                "token" => $token,
                "secret" => $secret,
            );
            $url = "https://admin.fastship.co/api_marketplace/etsy_api/get_shops.php";
            $response = call_api($url,$requestArray);
            
            $res = json_decode($response,true);
            
            $shops = $res['results'];
            // ##### get etsy shops #####
            
        }

        $data = array(
            "shops" => $shops,
            "token" => $token,
            "secret" => $secret,
        );
        
        return view('add_channel_etsyshop',$data);
        
    }
    
}
