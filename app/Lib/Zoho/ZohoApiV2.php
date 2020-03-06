<?php
namespace App\Lib\Zoho;

class ZohoApiV2{
    
    public static $refreshToken = "1000.6439c7e15ede31db61a401411a55d6d7.dd47bf028466b831f6c675b291a48ed3";
    public static $apiUrl = "https://admin.fastship.co/api/zohoapis/";

    /* ================= GET RECORDS ================= */
    public static function getLeads(){
        return self::getRecords("Leads");
    }
    public static function getAccounts(){
        return self::getRecords("Accounts");
    }
    public static function getContacts(){
        return self::getRecords("Contacts");
    }
    public static function getSalesOrders(){
        return self::getRecords("Sales_Orders");
    }
    public static function getCases(){
        return self::getRecords("Cases");
    }
    private static function getRecords($module,$params="per_page=10&page=1"){
        
        //get records url
        $url = self::$apiUrl . 'rest_get_records.php';
        
        $post = [
            'command' => "GET",
            'module_api_name' => $module,
            'record_id' => "",
            'parameter' => $params,
            'refresh_token' => self::$refreshToken,
        ];
        
        $Response = self::callZohoAPI("POST",$url,json_encode($post));
        
        return $Response['records']['data'];
        
    }
    /* ================= END GET RECORDS ================= */

    /* ================= SEARCH RECORDS ================= */
    public static function searchCases($args){
        
        $params = "";
        if(isset($args['account_id'])){
            $params.= "Account_Name:equals:" . $args['account_id'];
        }
        
        return self::searchRecords("Cases",$params);
    }
    
    private static function searchRecords($module,$params=""){
        
        //get records url
        $url = self::$apiUrl . 'rest_search_records.php';
        
        $post = [
            'command' => "GET",
            'module_api_name' => $module,
            'record_id' => "",
            'parameter' => $params,
            'refresh_token' => self::$refreshToken,
        ];
        
        $Response = self::callZohoAPI("POST",$url,json_encode($post));
        
        return $Response['records']['data'];
        
    }
    /* ================= END SEARCH RECORDS ================= */
    
    /* ================= GET RECORD ================= */
    public static function getLead($id){
        return self::getRecordById("Leads",$id);
    }
    public static function getAccount($id){
        return self::getRecordById("Accounts",$id);
    }
    public static function getContact($id){
        return self::getRecordById("Contacts",$id);
    }
    public static function getSalesOrder($id){
        return self::getRecordById("Sales_Orders",$id);
    }
    public static function getCase($id){
        return self::getRecordById("Cases",$id);
    }
    private static function getRecordById($module,$id){
        
        //get records url
        $url = self::$apiUrl . 'rest_get_specific_record.php';
        
        $post = [
            'command' => "GET",
            'module_api_name' => $module,
            'record_id' => $id,
            'parameter' => "",
            'refresh_token' => self::$refreshToken,
        ];
        
        $Response = self::callZohoAPI("POST",$url,json_encode($post));
        
        return $Response['records']['data'];
        
    }
    /* ================= END GET RECORD ================= */

    /* ================= INSERT RECORD ================= */
    public static function createLead($params){
        
        $accountName = $params['firstname'] . " " . $params['lastname'] . " (" . $params['cust_id'] . ")";
        $insertDetail = array(
            "data" => array(
                array(
                    'First_Name' => $params['firstname'],
                    'Last_Name' => $params['lastname'],
                    'Company' => $accountName,
                    'Email' => $params['email'],
                    'Phone' => $params['phone'],
                    'Mobile' => $params['phone'],
                    'State' => $params['state'],
                    'Referal_Code' => $params['refcode'],
                    'Traffic_Source' => $params['traffic_src'],
                    'Register_For' => $params['for'],
                    'Lead_Source' => "app.fastship.co",
                    'Ads_Campaign' => $params['campaign'],
                    'Behavior' => $params['behavior'],
                    'Owner' => array(
                        'id' => "2330098000005563023", //dew
                    ),
                    'Cust_ID' => $params['cust_id'],
                    'Line_SystemID' => $params['line_id'],
                ),
            )
        );

        $insertDetail['trigger'] = array();
        
        return self::insertRecords("Leads",$insertDetail);
        
    }
    public static function createSalesOrder($params){
        
        $insertDetail = array(
            "data" => array(
                array(
                    'Subject' => $params['record_id'],
                    'Purchase_Order' => $params['record_id'],
                    'Customer_No' => $params['cust_id'],
                    'Carrier' => $params['agent'],
                    'Status' => 'Approved',
                    'Owner' => array(
                        'id' => "2330098000005563013", //tle
                    ),
                    'Sales_Commission' => $params['total'],
                    'Account_Name' => array(
                        'id' => $params['account_id'],
                    ),
                    "Pickup" => $params['pick_id'], 
                    "Tracking" => $params['tracking'], 
                    'Billing_Name' => $params['sender_firstname'] . " " . $params['sender_lastname'],
                    'Billing_Email' => $params['sender_email'],
                    'Billing_Phone' => $params['sender_phone'],
                    'Billing_Street' => $params['sender_address'],
                    'Billing_City' => $params['sender_city'],
                    'Billing_State' => $params['sender_state'],
                    'Billing_Code' => $params['sender_postcode'],
                    'Billing_Country' => $params['sender_country'],
                    'Shipping_Name' => $params['receiver_firstname'] . " " . $params['receiver_lastname'],
                    'Shipping_Email' => $params['receiver_email'],
                    'Shipping_Phone' => $params['receiver_phone'],
                    'Shipping_Street' => $params['receiver_address'],
                    'Shipping_City' => $params['receiver_city'],
                    'Shipping_State' => $params['receiver_state'],
                    'Shipping_Code' => $params['receiver_postcode'],
                    'Shipping_Country' => $params['receiver_country'],
                    'Shipment_Create_Date' => date("Y-m-d H:i:s"),
                    'Product_Details' => array(
                        array(
                            'product' => array(
                                "Product_Code" => $params['agent'],
                                "name" => $params['receiver_firstname'] . " " . $params['receiver_lastname'],
                                "id" => "2330098000020094061",
                            ),
                            'unit_price' => $params['total'],
                            'quantity' => 1,
                            'total' => $params['total'],
                            'Discount' => 0,
                            'total_after_discount' => $params['total'],
                            'list_price' => $params['total'],
                            'net_total' => $params['total'],
                        ),
                    ),
                )
            )
        );

        return self::insertRecords("Sales_Orders",$insertDetail);
        
    }
    public static function createCase($params){

        $insertDetail = array(
            "data" => array(
                array(
                    'Subject' => $params['subject'],
                    'Status' => "New",
                    'Case_Origin' => "Web",
                    'Owner' => array(
                        'id' => "2330098000020948015", //yeen
                    ),
                    'Description' => $params['description'],
                    'Internal_Comments' => $params['category'],
                    'Case_Creator' => "Customer",
                    'Priority' => $params['priority'],
                    'Account_Name' => array(
                        'id' => $params['account_id'],
                    ),
                    'Shipment_ID' => $params['ship_id'],
                ),
            )
        );

        return self::insertRecords("Cases",$insertDetail);
        
    }
    
    private static function insertRecords($module,$insertDetail){
        
        //get records url
        $url = self::$apiUrl . 'rest_create_records.php';
        
        $post = [
            'command' => "CREATE",
            'module_api_name' => $module,
            'refresh_token' => self::$refreshToken,
            'records_detail' => $insertDetail,
        ];

        $Response = self::callZohoAPI("POST",$url,json_encode($post));
        if(isset($Response['records']['data'][0]['details']['id'])){
            $id = $Response['records']['data'][0]['details']['id'];
        }else{
            $id = null;
        }
        return $id;
        
    }
    /* ================= END INSERT RECORD ================= */
    
    /* ================= UPDATE RECORD ================= */
    public static function updateLead($params){
        
        $updateDetail = array(
            "data" => array(
                array(
                    'id' => $params['zoho_id'],
                    'First_Name' => $params['firstname'],
                    'Last_Name' => $params['lastname'],
                    'Phone' => $params['phone'],
                    'Mobile' => $params['phone'],
                    'Company2' => $params['company'] . "(".$params['taxid'].")",
                    'Street' => $params['address1']." ".$params['address2'],
                    'City' => $params['city'],
                    'State' => $params['state'],
                    'Zip_Code' => $params['postcode'],
                ),
            )
        );

        return self::updateRecords("Leads",$updateDetail);
        
    }
    public static function fixLeadNoAccountName($data){
        
        $updateDetail = array(
            "data" => $data
        );
        
        return self::updateRecords("Leads",$updateDetail);
        
    }
    public static function updateContact($params){
        
        $updateDetail = array(
            "data" => array(
                array(
                    'id' => $params['zoho_id'],
                    'First_Name' => $params['firstname'],
                    'Last_Name' => $params['lastname'],
                    'Phone' => $params['phone'],
                    'Mobile' => $params['phone'],
                    'Company2' => $params['company'] . "(".$params['taxid'].")",
                    'Street' => $params['address1']." ".$params['address2'],
                    'City' => $params['city'],
                    'State' => $params['state'],
                    'Zip_Code' => $params['postcode'],
                ),
            )
        );
        
        return self::updateRecords("Contacts",$updateDetail);
        
    }
    public static function updateAccount($params){
        
        $updateDetail = array(
            "data" => array(
                array(
                    'id' => $params['zoho_id'],
                    'Referal_Code' => $params['refcode'],
                    'Lead_Source' => 'app.fastship.co',
                    'Traffic_Source' => $params['traffic_src'],
                    'Ads_Campaign' => $params['campaign'],
                    'Register_For' => $params['for'],
                    'Behavior' => $params['behavior'],
                    'CustID' => $params['cust_id'],
                )
            )
        );
        
        return self::updateRecords("Accounts",$updateDetail);
        
    }
    public static function updateSalesOrderStatus($params){
        
        $updateDetail = array(
            "data" => array(
                array(
                    'id' => $params['zoho_id'],
                    'Status' => $params['status'],
                )
            )
        );
        
        return self::updateRecords("Sales_Orders",$updateDetail);
        
    }
    
    private static function updateRecords($module,$updateDetail){
        
        //get records url
        $url = self::$apiUrl . 'rest_update_records.php';
        
        $post = [
            'command' => "UPDATE",
            'module_api_name' => $module,
            'refresh_token' => self::$refreshToken,
            'record_id' => "", 
            'records_detail' => $updateDetail,
        ];

        $Response = self::callZohoAPI("POST",$url,json_encode($post));

        return $Response;
        
    }
    /* ================= END UPDATE RECORD ================= */
    
    /* ================= CONVERT LEAD ================= */
    public static function convertLead($id){
        
        //get records url
        $url = self::$apiUrl . 'rest_convert_lead.php';
        
        $convertDetail = array(
            "data" => array(
                array(
                    'overwrite' => false,
                    'notify_lead_owner' => false,
                    'notify_new_entity_owner' => false,
                )
            )
        );

        $post = [
            'command' => "CONVERT",
            'module_api_name' => "Leads",
            'refresh_token' => self::$refreshToken,
            'record_id' => $id, 
            'leads_detail' => $convertDetail,
        ];

        $Response = self::callZohoAPI("POST",$url,json_encode($post));

        return $Response;
        
    }
    /* ================= END CONVERT LEAD ================= */

    /* API CALLER */
    private static function callZohoAPI($method, $url, $data){
        
        
        $curl = curl_init();
        switch ($method){
            case "POST":
                curl_setopt($curl, CURLOPT_POST, true);
                if ($data)
                    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
                    break;
            case "PUT":
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
                if ($data)
                    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
                    break;
            default:
                if ($data)
                    $url = sprintf("%s?%s", $url, http_build_query($data));
        }
        
        // OPTIONS:
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json') );
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        
        // EXECUTE:
        $response = curl_exec($curl);

        //print_r($response);
        
        if($response === false){
            curl_close($curl);
            die("Connection Failure");
        }
        
        $responseCode = (int) curl_getinfo($curl,CURLINFO_HTTP_CODE);
        $responseBody = json_decode($response,true);
        
        curl_close($curl);
        
        return $responseBody;
        
    }
    
}
?>