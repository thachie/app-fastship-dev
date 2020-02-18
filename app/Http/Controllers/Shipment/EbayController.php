<?php

namespace App\Http\Controllers\Shipment;

use App\Http\Controllers\Controller;
use DTS\eBaySDK\Trading\Enums;
use DTS\eBaySDK\Trading\Types;
use Hkonnet\LaravelEbay\EbayServices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Lib\Fastship\Fastship;
use App\Lib\Fastship\FS_Shipment;
use App\Lib\Fastship\FS_Customer;

class EbayController extends Controller
{
    public function __construct()
    {
        date_default_timezone_set("Asia/Bangkok");
        include(app_path() . '/Lib/ebay.restfulapi.functions.php');
        include(app_path() . '/Lib/inc.functions.php');
//         $this->sellerId = 'muaythaistuff';
//         $this->sellerPass = 'TuffStuff2014';
        $this->FastFeedAPIs = 'https://admin.fastship.co/api_marketplace/ebay_api/';
    }

    public function importFromEbay(Request $request){

        //check customer login
        if (session('customer.id') != null){
            $customerId = session('customer.id');
        }else{
            echo false;
            exit();
        }

        $customerChannels = DB::table('customer_channel')->where("CUST_ID",$customerId)->where("IS_ACTIVE",1)->get();

        $tokenObjQuery = DB::table('customer_channel')->where("CUST_ID",$customerId)->where("IS_ACTIVE",1);
        if(isset($request) && $request->has("account")){
            $tokenObjQuery->where('cust_accountname',$request->input("account"));
        }
        $tokenObj = $tokenObjQuery->first();

        if($tokenObj == null){
            return redirect('channel_list')->with('msg','คุณยังไม่ได้เพิ่มบัญชี eBay');
        }

        /**
         * Create the service object.
         */
        $ebay_service = new EbayServices();
        $service = $ebay_service->createTrading();

        /**
         * Create the request object.
         */
        $request = new Types\GetOrdersRequestType();

        /**
         * An user token is required when using the Trading service.
         */
        $request->RequesterCredentials = new Types\CustomSecurityHeaderType();
        //$authToken = Ebay::getAuthToken();

        $authToken = $tokenObj->CUST_APITOKEN;
        $request->RequesterCredentials->eBayAuthToken = $authToken;

        /**
         * Request that eBay returns the list of actively selling items.
         * We want 10 items per page and they should be sorted in descending order by the current price.
         */
        //$request->ActiveList = new Types\ItemListCustomizationType();
        $request->NumberOfDays = 5;
        $request->OrderStatus = "Completed";
        $request->OrderRole = "Seller";
        $request->Pagination = new Types\PaginationType();
        $request->Pagination->EntriesPerPage = 10;
        $request->SortingOrder = Enums\SortOrderCodeType::C_ASCENDING;
        //$request->SortingOrder = Enums\SortOrderCodeType::C_DESCENDING;
        $pageNum = 1;



        //get api token
        //Fastship::getToken($customerId);

        $upload_data = array();
        $ebayOrders = array();

        //country mapping
        $countryIso3 = DB::table("country")->where("IS_ACTIVE",1)->select("CNTRY_CODE2ISO","CNTRY_CODE")->get();
        $mapCountries = array();
        foreach($countryIso3 as $cntry){
            $mapCountries[$cntry->CNTRY_CODE2ISO] = $cntry->CNTRY_CODE;
        }

        //ignore list
        $ignoreList = DB::table("ebay_cancel_order")->where("cust_id",$customerId)->pluck('ebay_ref')->toArray();


        do {

            $request->Pagination->PageNumber = $pageNum;

            /**
             * Send the request.
             */
            $response = $service->getOrders($request);

            /**
             * Output the result of calling the service operation.
             */
            //echo "==================\nResults for page $pageNum\n==================\n<br />";
            if (isset($response->Errors)) {
                foreach ($response->Errors as $error) {
                    printf(
                        "%s: %s\n%s\n\n",
                        $error->SeverityCode === Enums\SeverityCodeType::C_ERROR ? 'Error' : 'Warning',
                        $error->ShortMessage,
                        $error->LongMessage
                        );
                    echo "<hr />";
                }
            }
            if ($response->Ack !== 'Failure' && isset($response)) {


                foreach ($response->OrderArray->Order as $order) {
                    $ebayOrders[] = $order;
                }
            }
            $pageNum += 1;
            //break;
            usleep(100);

        } while (isset($response) && isset($response->PaginationResult) && $pageNum <= $response->PaginationResult->TotalNumberOfPages);


        if(sizeof($ebayOrders)>0){
            foreach ($ebayOrders as $order) {

                $Errors = "";
                $data_row = array();

                //Reference
                $orderId = $order->OrderID;
                if(!isset($orderId)){
                    $data_row['Reference'] = "";
                }else{
                    $data_row['Reference'] = $orderId;
                }


                if($customerId == 8521){
//                     echo "order: ";
//                     print_r($orderId);
//                     echo "<br />";
//                     print_r($order->ShippedTime['date']);
//                     echo "<hr />";

                    //exit();
                }


                //check cancel list
                if(in_array($orderId,$ignoreList)) continue;


                //check own shipment
//                 $searchDetails = array(
//                     "Reference" => $orderId,
//                     "NoStatuses" => array('Cancelled'),
//                 );
//                 $checkShipment = FS_Shipment::search($searchDetails);
//                 if(is_array($checkShipment)) continue;

                //check shipped
                if($order->ShippedTime != "") continue;

                //$firstname = $order->TransactionArray->Transaction[0]->Buyer->UserFirstName;
                if(strstr($order->ShippingAddress->Name," ") != FALSE){
                    list($firstname,$lastname) = explode(" ",$order->ShippingAddress->Name,2);
                }else{
                    $firstname = $order->ShippingAddress->Name;
                    $lastname = null;
                }
                if(!isset($firstname)){
                    $Errors .= "กรุณาใส่ชื่อผู้รับ\n";
                    $data_row['Receiver_Firstname'] = "";
                }else{
                    $data_row['Receiver_Firstname'] = $firstname;
                }

                if(!isset($lastname)){
                    $data_row['Receiver_Lastname'] = "";
                }else{
                    $data_row['Receiver_Lastname'] = $lastname;
                }

                $data_row['Receiver_Company'] = "";

                if($order->TransactionArray->Transaction[0]->Buyer->Email != "Invalid Request"){
                    $email = $order->TransactionArray->Transaction[0]->Buyer->Email;
                }else{
                    $email = $order->TransactionArray->Transaction[0]->Buyer->StaticAlias;
                }
                if(!isset($email)){
                    $Errors .= "กรุณาใส่ข้อมูลอีเมล์\n";
                    $data_row['Receiver_Email'] = "";
                }else{
                    $data_row['Receiver_Email'] = $email;
                }

                $telephone = $order->ShippingAddress->Phone;
                if(!isset($telephone)){
                    $Errors .= "กรุณาใส่เบอร์ติดต่อผู้รับ\n";
                    $data_row['Receiver_PhoneNumber'] = "";
                }else{
                    $data_row['Receiver_PhoneNumber'] = $telephone;
                }

                $address1 = $order->ShippingAddress->Street1;
                if(!isset($address1)){
                    $Errors .= "กรุณาใส่ที่อยู่ผู้รับให้ครบถ้วน\n";
                    $data_row['Receiver_AddressLine1'] = "";
                }else{
                    $data_row['Receiver_AddressLine1'] = $address1;
                }

                $address2 = $order->ShippingAddress->Street2;
                if(!isset($address2)){
                    $data_row['Receiver_AddressLine2'] = "";
                }else{
                    $data_row['Receiver_AddressLine2'] = $address2;
                }

                $city = $order->ShippingAddress->CityName;
                if(!isset($city)){
                    $Errors .= "กรุณาใส่เมือง\n";
                    $data_row['Receiver_City'] = "";
                }else{
                    $data_row['Receiver_City'] = $city;
                }

                $state = $order->ShippingAddress->StateOrProvince;
                if(!isset($state)){
                    $Errors .= "กรุณาใส่รัฐ\n";
                    $data_row['Receiver_State'] = "";
                }else{
                    $data_row['Receiver_State'] = $state;
                }

                $postcode = $order->ShippingAddress->PostalCode;
                if(!isset($postcode)){
                    $Errors .= "กรุณาใส่รหัสไปรษณีย์\n";
                    $data_row['Receiver_Postcode'] = "";
                }else{
                    $data_row['Receiver_Postcode'] = $postcode;
                }

                $country = $order->ShippingAddress->Country;
                if(!isset($country)){
                    $Errors .= "กรุณาใส่ประเทศ\n";
                    $data_row['Receiver_Country'] = "";
                }else{
                    $data_row['Receiver_Country'] = isset($mapCountries[$country]) ? $mapCountries[$country]:"";
                }

                $data_row['TermOfTrade'] = "DDU";

                $data_row['Weight'] = "";
                $data_row['Width'] = 0;
                $data_row['Height'] = 0;
                $data_row['Length'] = 0;

                $data_row['DeclareType'] = "";
                $data_row['DeclareQty'] = 1;

                $subtotal = $order->Subtotal;
                if(!isset($subtotal)){
                    $Errors .= "กรุณาใส่มูลค่าของ\n";
                    $data_row['DeclareValue'] = "";
                }else{
                    $data_row['DeclareValue'] = ($subtotal->value)*34;
                }

                $orderDate = date_format($order->CreatedTime,"Y/m/d H:i:s");
                if(!isset($orderDate)){
                    $data_row['CreateDate'] = "";
                }else{
                    $data_row['CreateDate'] = $orderDate;
                }


                $data_row['Remark'] = "";
                $data_row['Remark'] .= "Agent: " . $order->ShippingServiceSelected->ShippingService . "\n";

                if(sizeof($order->TransactionArray->Transaction) > 0){
                    foreach($order->TransactionArray->Transaction as $item){
                        $data_row['Remark'] .= "- " . $item->Item->Title . "\n";
                    }
                }


                if($Errors == ""){
                    $data_row['IconClass'] = "fa-check-circle fa-success";
                    $data_row['IconTitle'] = "Ready to Import.\nPlease select shipping agent.";

                    $data_row['ShippingAgent'] = "กรุณาใส่ข้อมูลให้ครบถ้วน";


                }else{
                    $data_row['IconClass'] = "fa-exclamation-circle fa-danger";
                    $data_row['IconTitle'] = $Errors;

                    $data_row['ShippingAgent'] = "<span style='color:red'>" . nl2br($Errors) . "</span>";
                }
                $data_row['RefAccount'] = $tokenObj->CUST_ACCOUNTNAME;

                $upload_data[] = $data_row;

//                 print_r($data_row);
//                 echo "<hr />";

            }
        }
        //exit();

        $data = array(
            'upload_data' => $upload_data,
            'customer_channels' => $customerChannels,
        );

        return view('ebay_shipment_confirm',$data);
    }

    
    /***eBay Process ******/
    public function prepareCreateShipmentEbay(Request $request){
        
        //check customer login
        if (session('customer.id') != null){
            $customerId = session('customer.id');
        }else{
            echo false;
            exit();
        }
        
        if($customerId != 5223 && $customerId != 90){
        //    return redirect('upgrading');
        }
        
        //get channel from api
        Fastship::getToken($customerId);
        $channel_data = FS_Customer::getChannel($customerId);
        
        //$marketplaceId = $request->marketplaceId;
        //$language = $request->language;
        
        $customerChannels = array();
        if($channel_data == false){
            return redirect('channel_list')->with('msg','คุณยังไม่ได้เพิ่มบัญชี eBay');
        }
        foreach($channel_data as $channel){
            $customerChannels[$channel['AccountName']] = $channel;
        }
        
        //account  by earth
        if($request->has("account")){
            $account = $request->input("account");
            
            if($account == "fs_add_ebay_channel"){
                return redirect('add_channel');
            }
            
            $customerChannel = $customerChannels[$account];
        }else{
            $account = "";
            $customerChannel = $channel_data[0];
            //return redirect('channel_list')->with('msg','คุณยังไม่ได้เพิ่มบัญชี eBay');
        }
        
        if(!isset($customerChannel)){
            return redirect('channel_list')->with('msg','คุณยังไม่ได้เพิ่มบัญชี eBay');
        }
        
        //token
        if($request->refresh_token){
            $refresh_token = $request->refresh_token;
        }else{
            $refresh_token = $customerChannel['Token'];
        }
        
        if (empty($refresh_token)) {
            return redirect('/channel_list')->with('msg','มีปัญหาการเชื่อมต่อ กรุณาต่ออายุบัญชีeBayเพื่อใช้งานใหม่อีกครั้ง');
            exit();
        }
        
        //pull = data from fastfeed database
        //get = data from eBay APIs
        $command = strtoupper(isset($request->command) ? $request->command:"PULL");
        $filter_type = isset($request->filter_type) ? $request->filter_type:"";
        //$account -> token
        $action = "";
        
        //$sellerId = $customerChannel->CUST_ACCOUNTNAME;
        $sellerId = $customerChannel['AccountName'];
        $marketplaceId = $customerChannel['Marketplace'];
        
        if($filter_type == 1){ //creationdate
            //Ex.creationdate:%5B2016-03-21T08:25:43.511Z..2016-04-21T08:25:43.511Z%5D
            //Date("Y-m-d", strtotime("2013-12-31 -2 Month"));
            //$sdate = date("Y-m-d H:i:s", strtotime("-2 Month")); //last 2 Month
            $sdate = date("Y-m-d H:i:s", strtotime('-1 week')); //last 7 days
            $tdate = date("Y-m-d H:i:s");
            $convsDate = str_replace('+00:00', '.000Z', gmdate('c', strtotime($sdate)));
            $convtDate = str_replace('+00:00', '.000Z', gmdate('c', strtotime($tdate)));
            $creationdate = 'creationdate:%5B'.$convsDate.'..'.$convtDate.'%5D';
            $filter = $creationdate;
        }elseif($filter_type == 2){ //lastmodifieddate
            //$date = date("Y-m-d H:i:s"); //last 24-hour
            $date = date("Y-m-d H:i:s", strtotime('-1 week')); //last 7 days
            $convDate = str_replace('+00:00', '.000Z', gmdate('c', strtotime($date)));
            $lastmodifieddate = 'lastmodifieddate:%5B'.$convDate.'%5D';
            $filter = $lastmodifieddate;
        }elseif($filter_type == 3){ //orderfulfillmentstatus
            //Ex.orderfulfillmentstatus:%7BNOT_STARTED%7CIN_PROGRESS%7D
            $notStarted = 'NOT_STARTED';
            $inProcess = 'IN_PROGRESS';
            $date = date("Y-m-d H:i:s", strtotime('-1 week')); //last 7 days
            $convDate = str_replace('+00:00', '.000Z', gmdate('c', strtotime($date)));
            $orderfulfillmentstatus = 'orderfulfillmentstatus:%7B'.$notStarted.'%7C'.$inProcess.'%7D';
            $filter = $orderfulfillmentstatus;
        }else{
            $notStarted = 'NOT_STARTED';
            $inProcess = 'IN_PROGRESS';
            $date = date("Y-m-d H:i:s", strtotime('-1 week')); //last 7 days
            $convDate = str_replace('+00:00', '.000Z', gmdate('c', strtotime($date)));
            $orderfulfillmentstatus = 'orderfulfillmentstatus:%7B'.$notStarted.'%7C'.$inProcess.'%7D';
            $filter = $orderfulfillmentstatus;
            //$filter = '';
        }
        /*
         if ($sellerId == 'ebay_au') {
         $marketplaceId = 'EBAY_AU';
         }else{
         $marketplaceId = 'EBAY_US';
         }
         */
        
        if (empty($marketplaceId)) {
            $marketplaceId = 'EBAY_US';
            //$filter = '';
        }

        $url = $this->FastFeedAPIs.'rest_get_orders.php';
        $call_back_url = url('shipment/create_ebay');
        $orderIds='';
        $filter=$filter;
        $limit=100;
        $offset=0;
        $url_request_ebay_api = "https://api.ebay.com/sell/fulfillment/v1/order?filter=".$filter."&limit=".$limit."&offset=".$offset;
        ###Adjust Parameters
        
        $marketplaceLanguage = $this->eBaymarketplace($marketplaceId);
        $language = $marketplaceLanguage;
        $marketplace_site = '';
        
        $post = [
            'command' => $command,
            'customerId' => $customerId,
            'sellerId' => $sellerId,
            'refresh_token' => $refresh_token,
            'url_request_ebay_api' => $url_request_ebay_api,
            'filter_type' => $filter_type,
            'filter' => $filter,
            'conditions' => array(),
            'marketplaceId' => $marketplaceId,
            'marketplace_site' => $marketplace_site,
            'language' => $language
        ];
        
        //alert($post);//die();
        $json_string_data = json_encode($post);
        $params = array(
            "method" => "POST",
            "url" => $url,
            "call_back_url" => $call_back_url,
            "jsonData" => $json_string_data,
        );
        //alert($params);//die();
        $Response = FS_RESTfulAPIs($params);
        $jsonDecode = json_decode($Response,true);
        
        $code = $jsonDecode['code'];
        $status = $jsonDecode['status'];
        if ($code == '203') {
            return redirect('/channel_list')->with('msg','กรุณาต่ออายุบัญชีeBayเพื่อใช้งาน');
            exit();
        }
        
        $nodeData = $jsonDecode['data'];
        if (empty($nodeData['orders']) || $nodeData['total'] == 0) {
            //alert($command);alert('Not order is FastFeed.');alert('Push sync data from ebay.');
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
        
        /*if (!array_key_exists('last_feed', $nodeData)) {
         $latest_day = "-";
         }else{
         $latest_day = $nodeData['last_feed'];
         if (empty($latest_day)) {
         $latest_day = "-";
         }
         }*/
        if(isset($nodeData['last_feed'])){
            $latest_day = $nodeData['last_feed'];
            if (empty($latest_day)) {
                $latest_day = "-";
            }
        }else{
            $latest_day = "-";
        }
        $data = array(
            'customerId' => $customerId,
            'sellerId' => $sellerId,
            'ordersList' => $ordersList,
            'customerChannels' => $customerChannels,
            'account' => $sellerId,
            'country2iso' => $country2iso,
            'latest_day' => $latest_day,
        );
        return view('ebay_import_shipment_list',$data);
        
        
        
    }

    public function deleteeBayOrder(Request $request){
        if (session('customer.id') != null){
            $customerId = session('customer.id');
        }else{
            return redirect('/')->with('msg','คุณยังไม่ได้เข้าระบบ กรุณาเข้าสู่ระบบเพื่อใช้งาน');
        }

        //$customerId = '5223';
        $customerId = $customerId;
        $sellerId = $request->sellerId;
        $eBayId = $request->ebayId;
        $account = $request->account;
        $command = 'DELETE';
        $status = 'CANCELLED';
        $shipmentId = '';
        $url = $this->FastFeedAPIs.'rest_delete_orders.php';
        $call_back_url = url('shipment/ebay-delete');
        
        $orders = array();
        $orders[] = array(
            'customerId' => $customerId,
            'eBayId' => $eBayId,
            'sellerId' => $sellerId,
            'status' => $status,
            'shipmentId' => $shipmentId
        );

        $post = [
            'command' => $command,
            'ebayId' => $eBayId,
            'customerId' => $customerId,
            'sellerId' => $sellerId,
            'status' => $status,
            'orders' => $orders,
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
        return redirect('shipment/create_ebay?account='.$account);
    }

    public function prepareCreateShipmentEbayDetail(Request $request){
        
        //check customer login
        if (session('customer.id') != null){
            $customerId = session('customer.id');
        }else{
            return redirect('/')->with('msg','คุณยังไม่ได้เข้าระบบ กรุณาเข้าสู่ระบบเพื่อใช้งาน');
        }
        
        //alert($request->all());die();
        //Set parameter
        /*$customerId = 34;
         $ebayId = 222;
         $account = 'nidabusra';*/
        $ebayId = $request->ebayId;
        $account = $request->account;
        $url = $this->FastFeedAPIs.'pull_order.php';
        $call_back_url = url('shipment/ebay');
        
        $post = [
            'command' => 'PULL',
            'ebayId' => $ebayId,
            'customerId' => $customerId,
            'sellerId' => $account,
        ];
        
        $json_string_data = json_encode($post);
        $params = array(
            "method" => "POST",
            "url" => $url,
            "call_back_url" => $call_back_url,
            "jsonData" => $json_string_data,
        );
        //alert($post);
        $Response = FS_PullOrder($params);
        $jsonDecode = json_decode($Response,true);
        //alert($jsonDecode);
        //$detail = json_decode($request->get("detail"),true);
        $detail = $jsonDecode['data']['orders'];
        
        //alert($detail);
        //die();
        
        //default value from ebay
        $countryObj = DB::table("country")->where("cntry_code2iso",$detail['country_code'])
        ->select("cntry_name as name","cntry_code as code")->first();
        
        $fullname = explode(" ",$detail['full_name']);
        $firstname = $fullname[0];
        $lastname = (isset($fullname[1]))?$fullname[1]:"";
        $default = array(
            'ebay_id' => $detail['ebay_id'],
            'account' => $account,
            'sellerid' => $detail['seller_id_from_ebay'],
            'buyer' => $detail['buyer'],
            'firstname' => $firstname,
            'lastname' => $lastname,
            'phone' => $detail['phone'],
            'email' => $detail['email'],
            'company' => "",
            'address1' => $detail['address_line1'],
            'address2' => $detail['address_line2'],
            'city' => $detail['city'],
            'state' => $detail['state'],
            'postcode' => $detail['postal_code'],
            //'country' => $detail['country_code'],
            'country_code' => $countryObj->code,
            'country' => $countryObj->name,
            'remark' => $detail['note'],
            'seller_note' => $detail['note'],
            'orderref' => $detail['order_id'],
            'width' => $detail['width'],
            'height' => $detail['height'],
            'length' => $detail['length'],
            'agent_code' => $detail['shipping_service_code'],
        );
        //check agent
        if(isset($detail['shipping_service_code'])){
            if($detail['shipping_service_code'] == "EconomyShippingFromOutsideUS"){
                $default['agent'] = "GM_Packet_Plus";
            }else if($detail['shipping_service_code'] == "ExpeditedShippingFromOutsideUS"){
                $default['agent'] = "UPS";
            }else if($detail['shipping_service_code'] == "StandardShippingFromOutsideUS"){
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
                    "value" => ceil($item['total_value']*32),
                );
            }
        }
        
        //validate
        $validateEnglish = "^[a-zA-Z0-9 /+=%&_\.,~?\'\-\#@!$^*()<>{}]+$";
        $validateDeclare = "^[a-zA-Z0-9 /+&]+$";
        
        //alert($ebayId);
        //alert($detail);
        
        $data = array(
            'ebayId' => $ebayId,
            'default' => $default,
            'items' => $items,
            'validateEnglish' => $validateEnglish,
            'validateDeclare' => $validateDeclare,
        );
        
        return view('ebay_import_shipment_detail',$data);
        
    }
    
    public function acceptToken(Request $request){

        if (session('customer.id') != null){
            $customerId = session('customer.id');
        }else{
            return redirect('/')->with('msg','คุณยังไม่ได้เข้าระบบ กรุณาเข้าสู่ระบบเพื่อใช้งาน');
        }
        
        if($request->has('code')){
            $userCode = $request->input('code');
        }else{
            return redirect('/channel_list')->with('msg','ไม่พบรหัสในการเชื่อมต่อ กรุณาลองอีกครั้ง');
        }
        
        $channel = session('ebay.channel');
        $command = session('ebay.command');
        $marketplaceId = session('ebay.marketplace');

        $refreshToken = $this->eBayCreateToken($userCode);
        if(!$refreshToken){
        	return redirect('/channel_list')->with('msg','พบข้อผิดพลาดในการเชื่อมต่อ กรุณาลองอีกครั้ง');
        }
        
        if($command == "add"){
            
            //insert to db
    		$insert = DB::table('customer_channel')->insert(
    		[
    			'CUST_ID' => $customerId,
    			'CUST_CHANNEL' => 'EBAY',
    		    'CUST_MARKETPLACE' => $marketplaceId,
    			'CUST_ACCOUNTNAME' => $channel,
    		    'CUST_APITOKEN' => $refreshToken,
    			'IS_ACTIVE' => 1,
    			'CREATE_DATETIME' => date('Y-m-d H:i:s'),
    		]
    		);
    		
    		Fastship::getToken($customerId);
    		$params = array(
    		    "CustomerID" => $customerId,
    		    "ChannelType" => "EBAY",
    		    "Marketplace" => $marketplaceId,
    		    "AccountName" => $channel,
    		    "Token" => $refreshToken,
    		);
    		FS_Customer::addChannel($params);
    		
        }else if($command == "update"){
            
            //insert to db
            $insert = DB::table('customer_channel')->where('CUST_ID',$customerId)->where('CUST_CHANNEL','EBAY')->where('CUST_ACCOUNTNAME',$channel)
            ->update(
            [
                'CUST_APITOKEN' => $refreshToken,
                'CREATE_DATETIME' => date('Y-m-d H:i:s'),
            ]
            );
            
            Fastship::getToken($customerId);
            $params = array(
                "CustomerID" => $customerId,
                "ChannelType" => "EBAY",
                "Marketplace" => $marketplaceId,
                "AccountName" => $channel,
                "Token" => $refreshToken,
            );
            FS_Customer::updateChannel($params);
            
        }
		
		$request->session()->forget('ebay.channel');
		$request->session()->forget('ebay.command');
		$request->session()->forget('ebay.marketplace');

		return redirect('/shipment/create_ebay?account='.$channel)->with('msg','เชื่อมต่อสำเร็จแล้ว')->with('msg-type','success');
 
    }
    
    public function addChannel(Request $request){
    
    	if (session('customer.id') != null){
    		$customerId = session('customer.id');
    	}else{
    		return redirect('/')->with('msg','คุณยังไม่ได้เข้าระบบ กรุณาเข้าสู่ระบบเพื่อใช้งาน');
    	}
    	
    	if($request->has('command')){
    	    $command = $request->input('command');
    	}else{
    	    $command = "add";
    	}
    	
    	if($request->has('marketplace')){
    	    $marketplaceId = $request->input('marketplace');
    	}else{
    	    $marketplaceId = "EBAY_US";
    	}
    	
    
    	if($request->has('channel')){
    		$channel = $request->input('channel');
    		$request->session()->put('ebay.channel', $channel);
    		$request->session()->put('ebay.command', $command);
    		$request->session()->put('ebay.marketplace', $marketplaceId);
    	}else{
    	    return redirect('channel_list')->with('msg','ไม่พบบัญชี eBay');
    	}
    	$url = "https://auth.ebay.com/oauth2/authorize?client_id=TUFFComp-CloudCom-PRD-1ab9522e2-1a463403&response_type=code&redirect_uri=TUFF_Company-TUFFComp-CloudC-qjqlrnfod&scope=https://api.ebay.com/oauth/api_scope https://api.ebay.com/oauth/api_scope/sell.marketing.readonly https://api.ebay.com/oauth/api_scope/sell.marketing https://api.ebay.com/oauth/api_scope/sell.inventory.readonly https://api.ebay.com/oauth/api_scope/sell.inventory https://api.ebay.com/oauth/api_scope/sell.account.readonly https://api.ebay.com/oauth/api_scope/sell.account https://api.ebay.com/oauth/api_scope/sell.fulfillment.readonly https://api.ebay.com/oauth/api_scope/sell.fulfillment https://api.ebay.com/oauth/api_scope/sell.analytics.readonly https://api.ebay.com/oauth/api_scope/sell.finances https://api.ebay.com/oauth/api_scope/sell.payment.dispute https://api.ebay.com/oauth/api_scope/commerce.identity.readonly";
    	
    	return redirect($url);
    }

    public function updateInProgress(Request $request){
    	if (session('customer.id') != null){
            $customerId = session('customer.id');
        }else{
            return redirect('/')->with('msg','คุณยังไม่ได้เข้าระบบ กรุณาเข้าสู่ระบบเพื่อใช้งาน');
        }

        $command =  "UPDATE";
        $filter_type =  "";
        $action =  "";
        $customerId = $customerId;
        $sellerId = $request->sellerId;
        $refresh_token = '';
        $filter = '';
        $call_back_url = base_url().'update-order.php';
        $eBayId = $request->eBayId;

        $url = $this->FastFeedAPIs.'rest_update_orders.php';
        $call_back_url = url('shipment/update-order');

        $status = 'IN_PROGRESS';
        $shipmentId = $request->shipmentId;

        //$orders['orders'][] = array();
        $orders = array();
        $orders[] = array(
            'customerId' => $customerId,
            'eBayId' => $eBayId,
            'sellerId' => $sellerId,
            'status' => $status,
            'shipmentId' => $shipmentId
        );

        $post = [
            'command' => $command,
            'customerId' => $customerId,
            'sellerId' => $sellerId,
            'status' => $status,
            'shipmentId' => $shipmentId,
            'refresh_token' => $refresh_token,
            'orders' => $orders,
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
    }

    public function updateTrackingEbay(Request $request){
        
        if (session('customer.id') != null){
            $customerId = session('customer.id');
        }else{
            return redirect('/')->with('msg','คุณยังไม่ได้เข้าระบบ กรุณาเข้าสู่ระบบเพื่อใช้งาน');
        }
        
        $marketplaceId = $request->marketplaceId;
        $marketplaceLanguage = $this->eBaymarketplace($marketplaceId);
        $language = $marketplaceLanguage;
        $marketplace_site = "";
        
        $req = json_encode($request->all());
        $LOGPROCESS = storage_path('logs/ebay_tracking.log');
        $str = '>>> Start eBay Update Tracking <<<';
        GEN_Logs(date("Y-m-d H:i:s")."|Start|$str",$LOGPROCESS,'l' );
        GEN_Logs(date("Y-m-d H:i:s")."|Request|$req",$LOGPROCESS,'l' );
        GEN_Logs(date("Y-m-d H:i:s")."|Lang|$language",$LOGPROCESS,'l' );
        
        $command =  "UPDATE";
        $filter_type =  "";
        $action =  "";
        $customerId = $customerId;
        $sellerId = $request->sellerId;
        
        $eBayId = $request->eBayId;
        $orderId = $request->orderId;
        $tracking = $request->tracking;
        $shippingCarrierCode = $request->shippingCarrierCode;
        
        $url = $this->FastFeedAPIs.'rest_update_tracking.php';
        $call_back_url = url('shipment/ebay-uptracking');
        
        $customerChannels = DB::table('customer_channel')->where("CUST_ID",$customerId)->where("IS_ACTIVE",1)->get();
        
        if(sizeof($customerChannels) == 0){
            //return redirect('channel_list')->with('msg','คุณยังไม่ได้เพิ่มบัญชี eBay');
        }
        //get customer channel (token) by earth
        $customerChannelQry = DB::table('customer_channel')->where("CUST_ID",$customerId)->where("CUST_CHANNEL","EBAY")->where("IS_ACTIVE",1);
        
        $customerChannel = $customerChannelQry->first();
        
        if(!isset($customerChannel)){
            return redirect('channel_list')->with('msg','คุณยังไม่ได้เพิ่มบัญชี eBay');
        }
        
        //token
        if($request->refresh_token){
            $refresh_token = $request->refresh_token;
        }else{
            $refresh_token = $customerChannel->CUST_APITOKEN;
        }
        
        $status = 'SHIPPED';
        $post = [
            'command' => $command,
            'eBayId' => $eBayId,
            'customerId' => $customerId,
            'sellerId' => $sellerId,
            'orderId' => $orderId,
            'status' => $status,
            'tracking' => $tracking,
            'shippingCarrierCode' => $shippingCarrierCode,
            'refresh_token' => $refresh_token,
            'marketplaceId' => $marketplaceId,
            'marketplace_site' => $marketplace_site,
            'language' => $language
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
        
        GEN_Logs(date("Y-m-d H:i:s")."|Response|$Response",$LOGPROCESS,'l' );
        $str = '>>> End eBay Update Tracking <<<';
        GEN_Logs(date("Y-m-d H:i:s")."|End|$str",$LOGPROCESS,'l' );
    }

    private function eBayCreateToken($userCode){
        $url = "https://api.ebay.com/identity/v1/oauth2/token";
        $data = 'grant_type=authorization_code&code='.$userCode.'&redirect_uri='.ruName;
        $params = array(
           "method" => "POST",
           "url" => $url,
           "jsonData" => $data,
        );

        $Response = eBayAccessToken($params);
        $res = json_decode($Response, true);
        
        if(isset($res['error'])){
        	return false;
        }else{
        	return $res['refresh_token'];
        }
    }

    public function eBaymarketplace($marketplaceId)
    {
        if ($marketplaceId == 'EBAY_US') { //USA 1
            $marketplace_site ='https://api.ebay.com';
            $language = 'en-US';
        }elseif($marketplaceId == 'EBAY_AU'){ //Australia 2
            $marketplace_site ='https://api.ebay.com.au';
            $language = 'en-AU';
        }elseif($marketplaceId == 'EBAY_FR'){ //France 3 
            $marketplace_site ='https://api.ebay.fr';
            $language = 'fr-FR';
        }elseif($marketplaceId == 'EBAY_CA'){ //Canada 4
            $marketplace_site ='https://api.ebay.ca';
            $language = 'en-CA';
        }elseif($marketplaceId == 'EBAY_DE'){ //Germany 5
            $marketplace_site ='https://api.ebay.de';
            $language = 'de-DE';
        }elseif($marketplaceId == 'EBAY_ES'){ //Spain 6
            $marketplace_site ='https://api.ebay.es'; 
            $language = 'es-ES';
        }elseif($marketplaceId == 'EBAY_GB'){ //Great Britain 7
            $marketplace_site ='https://api.ebay.co.uk';
            $language = 'en-GB';
        }elseif($marketplaceId == 'EBAY_IT'){ //Italy 8
            $marketplace_site ='https://api.ebay.it';
            $language = 'it-IT';
        }elseif($marketplaceId == 'EBAY_AT'){ //Austria 9
            $marketplace_site ='https://api.ebay.at';
            $language = 'de-AT';
        }elseif($marketplaceId == 'EBAY_BE'){ //Belgium 10
            $marketplace_site ='https://api.ebay.be';
            $language = 'fr-BE';
        }elseif($marketplaceId == 'EBAY_CH'){ //Switzerland 11
            $marketplace_site ='https://api.ebay.ch';
            $language = 'it-IT';
        }elseif($marketplaceId == 'EBAY_HK'){ //Hong Kong 12
            $marketplace_site ='https://api.ebay.it';
            $language = 'de-CH';
        }elseif($marketplaceId == 'EBAY_IE'){ //Ireland 13
            $marketplace_site ='https://api.ebay.ie';
            $language = 'en-IE';
        }elseif($marketplaceId == 'EBAY_IN'){ //India 14
            $marketplace_site ='https://api.ebay.in';
            $language = 'en-GB';
        }elseif($marketplaceId == 'EBAY_MY'){ //Malaysia 15
            $marketplace_site ='https://api.ebay.my';
            $language = 'en-US';
        }elseif($marketplaceId == 'EBAY_NL'){ //Netherlands 16
            $marketplace_site ='https://api.ebay.nl';
            $language = 'nl-NL';
        }elseif($marketplaceId == 'EBAY_PH'){ //Philippines 17
            $marketplace_site ='https://api.ebay.ph';
            $language = 'en-PH';
        }elseif($marketplaceId == 'EBAY_PL'){ //Poland 18
            $marketplace_site ='https://api.ebay.pl';
            $language = 'pl-PL';
        }elseif($marketplaceId == 'EBAY_SG'){ //Singapore 19
            $marketplace_site ='https://api.ebay.sg';
            $language = 'en-US';
        }elseif($marketplaceId == 'EBAY_TH'){ //Thailand 20
            $marketplace_site ='https://api.ebay.th';
            $language = 'th-TH';
        }elseif($marketplaceId == 'EBAY_TW'){ //Taiwan 21
            $marketplace_site ='https://api.ebay.tw';
            $language = 'zh-TW';
        }elseif($marketplaceId == 'EBAY_VN'){ //Vietnam 22
            $marketplace_site ='https://api.ebay.vn';
            $language = 'en-US';
        }elseif($marketplaceId == 'EBAY_MOTORS_US'){ //United States 23
            $marketplace_site ='https://api.ebay.com';
            $language = 'en-US';
        }else{
            $marketplace_site ='https://api.ebay.com'; //Default USA
            $language = 'en-US';
        }
        //return $marketplace = array('marketplace_site' => $marketplace_site, 'language' => $language );
        return $language;
    }
//////////////////////// End Environment Developer Options ////////////////////////////////////////

}
