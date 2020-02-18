<?php
namespace App\Lib\Zoho;

use Maidmaid\Zoho\Client;
use Maidmaid\Zoho\ZohoCRMException;

class ZohoManager{

    public static $apikey = "73037ca5203cf2bc132b9708c82f709c";
    
    public static function getContacts(){
        
        $client = new Client(self::$apikey);
        
        $records = $client->getRecords('Contacts');
       
        return $records;
    }
    public static function getLeads(){
        
        $client = new Client(self::$apikey);
        
        $records = $client->getRecords('Leads');
        
        return $records;
    }
    public static function searchLeads($args){
        
        $client = new Client(self::$apikey);
        
        extract($args);
        
        $records = $client->searchRecords('Leads', $criteria = '(Email:' . $email . ')');
        
        return $records;
    }
    public static function getLead($ids){
        
        $client = new Client(self::$apikey);
        
        $records = $client->getRecordById($module = 'Leads', $ids);
        
        return $records;
    }
    public static function getAccount($ids){
        
        $client = new Client(self::$apikey);
        
        $records = $client->getRecordById($module = 'Accounts', $ids);
        
        return $records;
    }
    public static function getContact($ids){
        
        $client = new Client(self::$apikey);
        
        $records = $client->getRecordById($module = 'Contacts', $ids);
        
        return $records;
    }
    
    public static function createContact($params){
        
        $client = new Client(self::$apikey);
        
        $record = $client->insertRecords($module = 'Contacts', $data = array(
                $params['cust_id'] => array(
                    'First Name' => $params['firstname'],
                    'Last Name' => $params['lastname'],
                    'Email' => $params['email'],
                    'Phone' => $params['phone'],
                    'Lead Source' => $params['phone'],
                )
            )
        );
    }
    
    public static function createLead1($params){
        
        extract($params);
        
        $url = 'https://crm.zoho.com/crm/WebToLeadForm';
        
        $ch = curl_init();
        $dataDetails = array(
            'xnQsjsdp' => "d863298390fc11817257a612937da061902b577c82a5a99d02740a36f7841153",
            'zc_gad' => "",
            'xmIwtLD' => "7327b52e32d9e9c5be67461d32dec83d49f257b35a1f6741cadaac71e1de4429",
            'actionType' => "TGVhZHM=",
            'returnURL' => "http://app.fastship.co",
            'Lead Source' => "app.fastship.co",
            'Company' => $Company,
            'Email' => $Email,
            'LEADCF2' => $LineID,
            'First Name' => $First_Name,
            'Last Name' => $Last_Name,
            'Mobile' => $Mobile,
            'Street' => $Street,
            'City' => $City,
            'State' => $State,
            'Zip_Code' => $Zip_Code,
            'Country' => $Country,
            'LEADCF5' => $RefCode,
        );
        
        //$headers = array('Content-Type: application/json');
        $headers = array();
        
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataDetails);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        
        $response = curl_exec($ch);
        
        if (false === $response) {
            echo 'Request unsuccessful' . PHP_EOL;
            curl_close($ch);
            exit(1);
        }
        $responseCode = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $responseBody = json_decode($response);
        curl_close($ch);
        
        
        if (200 !== $responseCode) {
            echo 'Authentication failed' . PHP_EOL;
            foreach ($responseBody->errors as $error) {
                echo $error->code . ': ' . $error->message . PHP_EOL;
            }
            exit(1);
        }
        //print_r($responseBody);
        //$authorisationToken = $responseBody->token;
        //echo 'Authentication success with token ' . $authorisationToken . PHP_EOL;
        
        //return $responseBody;
        
    }

    public static function createLead($params){
        
        $client = new Client(self::$apikey);
        $record = array();

        try {
            $record = $client->insertRecords($module = 'Leads', $data = array(
                    1 => array(
                        'First Name' => $params['firstname'],
                        'Last Name' => $params['lastname'],
                        'Email' => $params['email'],
                        'Phone' => $params['phone'],
                        'Mobile' => $params['phone'],
                        'State' => $params['state'],
                        'Referal Code' => $params['refcode'],
                        'Traffic Source' => $params['traffic_src'],
                        'Register For' => $params['for'],
                        'Lead Source' => "app.fastship.co",
                        'Ads Campaign' => $params['campaign'],
                        'Behavior' => $params['behavior'],
                        'Lead Owner' => "dew@fastship.co",
                    )
                )
            );
        } catch (ZohoCRMException $e) {
            echo $e->getMessage() . " error";
            //exit();
        }
        
        return $record;
        
    }
    
    public static function createSalesOrder($params){
        
        $client = new Client(self::$apikey);
        $record = array();
        
        try {
            $record = $client->insertRecords($module = 'SalesOrders', $data = array(
                1 => array(
                    'Subject' => $params['record_id'],
                    'Purchase Order' => $params['record_id'], //PickId or ShipmentId
                    'Customer No.' => $params['cust_id'],
                    'Carrier' => $params['agent'],
                    'Status' => 'Created',
                    'Sales Order Owner' => "title@fastship.co",
//                     'Sales Commission' => $params['total'], //Pick Total
                    'Account Name' => $params['account_name'],
                    'Pickup ID' => $params['pickid'],
//                     'Pickup Cost' => $params['cost'],
//                     'Sale Discount' => $params['discount'],
//                     'Payment Method' => $params['payment'],
                    'Billing Name' => $params['sender_firstname'] . " " . $params['sender_lastname'],
                    'Billing Street' => $params['sender_address'],
                    'Billing City' => $params['sender_city'],
                    'Billing State' => $params['sender_state'],
                    'Billing Code' => $params['sender_postcode'],
                    'Billing Country' => $params['sender_country'],
                    'Shipping Name' => $params['receiver_firstname'] . " " . $params['receiver_lastname'],
                    'Shipping Street' => $params['receiver_address'],
                    'Shipping City' => $params['receiver_city'],
                    'Shipping State' => $params['receiver_state'],
                    'Shipping Code' => $params['receiver_postcode'],
                    'Shipping Country' => $params['receiver_country'],
                    'Shipment Create Date' => date("Y-m-d H:i:s"),
                    'Product Details' => array(
                        1 => array(
                            'Product Id' => "2330098000020094061",
                            'Unit Price' => $params['total'],
                            'Quantity' => "1",
                            'Total' => $params['total'],
                            'Discount' => 0,
                            'Total After Discount' => $params['total'],
                            'List Price' => $params['total'],
                            'Net Total' => $params['total'],
                        ),
                    ),
                )
            )
                );
        } catch (ZohoCRMException $e) {
            echo $e->getMessage() . " error";
            //exit();
        }
        
        return $record;
        
    }
    
    public static function createSalesOrders($params){
        
        $client = new Client(self::$apikey);
        $record = array();
        
        try {
            $record = $client->insertRecords($module = 'SalesOrders', $data = array(
                1 => array(
                    'Subject' => $params['record_id'],
                    'Purchase Order' => $params['record_id'], //PickId or ShipmentId
                    'Customer No.' => $params['cust_id'],
                    'Carrier' => $params['agent'],
                    'Status' => 'Created',
                    'Sales Order Owner' => "title@fastship.co",
                    //                     'Sales Commission' => $params['total'], //Pick Total
                    'Account Name' => $params['account_name'],
                    'Pickup ID' => $params['pickid'],
                    //                     'Pickup Cost' => $params['cost'],
                //                     'Sale Discount' => $params['discount'],
                //                     'Payment Method' => $params['payment'],
                    'Billing Street' => $params['sender_address'],
                    'Billing City' => $params['sender_city'],
                    'Billing State' => $params['sender_state'],
                    'Billing Code' => $params['sender_postcode'],
                    'Billing Country' => $params['sender_country'],
                    'Shipping Street' => $params['receiver_address'],
                    'Shipping City' => $params['receiver_city'],
                    'Shipping State' => $params['receiver_state'],
                    'Shipping Code' => $params['receiver_postcode'],
                    'Shipping Country' => $params['receiver_country'],
                    'Product Details' => array(
                        1 => array(
                            'Product Id' => "2330098000020094061",
                            'Unit Price' => $params['total'],
                            'Quantity' => "1",
                            'Total' => $params['total'],
                            'Discount' => 0,
                            'Total After Discount' => $params['total'],
                            'List Price' => $params['total'],
                            'Net Total' => $params['total'],
                        ),
                    ),
                )
            )
                );
        } catch (ZohoCRMException $e) {
            echo $e->getMessage() . " error";
            //exit();
        }
        
        return $record;
        
    }
    
    public static function convertToAccount($id,$customer){
        
        $client = new Client(self::$apikey);
        $record = array();

        try {
//             $record = $client->call($module = 'Leads',"convertLead", array('newFormat' => 1, 'version' => 4, 'leadId'=>$params['zoho_id']),   $data = array(
//                 "1" => array(
//                     'createPotential' => false,
//                 ),
//             ));
            $record1 = $client->updateRecords($module = 'Leads', $data = array(
                    1 => array(
                        'Id' => $id,
                        'Company' => $customer['Firstname'] . " " . $customer['Lastname'] . " (". $customer['ID'] .")",
                    )
                )
            );

            $record = self::convertLead($module = 'Leads', $id);
            
            if(!isset($record['success'])) exit();
            
            $accountId = $record['success']['Account']['content'];

            $record2 = $client->updateRecords($module = 'Accounts', $data = array(
                    1 => array(
                        'Id' => $accountId,
                        'Referal Code' => $customer['ReferCode'],
                        'Lead Source' => 'app.fastship.co',
                        'Traffic Source' => $customer['TrafficSource'],
                        'Ads Campaign' => $customer['AdsCampaign'],
                        'Register For' => $customer['RegisterFor'],
                        'Behavior' => $customer['Behavior'],
                        'Company2' => $customer['Company'],
                        'CustID' => $customer['ID'],
                        'Join Date' => $customer['CreateDate'],
                    )
                )
            );
            
            
        } catch (ZohoCRMException $e) {
            echo $e->getMessage() . " error";
            //exit();
        }
        
        return $record;
        
    }
    public static function updateLead($params){
        
        $client = new Client(self::$apikey);
        $record = array();

        try {
            $record = $client->updateRecords($module = 'Leads', $data = array(
                    1 => array(
                        'Id' => $params['zoho_id'],
                        'First Name' => $params['firstname'],
                        'Last Name' => $params['lastname'],
                        'Phone' => $params['phone'],
                        'Mobile' => $params['phone'],
                        'Company2' => $params['company'] . "(".$params['taxid'].")",
                        'Street' => $params['address1']." ".$params['address2'],
                        'City' => $params['city'],
                        'State' => $params['state'],
                        'Zip Code' => $params['postcode'],
                    )
                
                )
            );
        } catch (ZohoCRMException $e) {
            echo $e->getMessage() . " error";
            //exit();
        }
     
        return $record;
    }
    public static function updateContact($params){
        
        $client = new Client(self::$apikey);
        $record = array();
        
        try {
            $record = $client->updateRecords($module = 'Contacts', $data = array(
                1 => array(
                    'Id' => $params['zoho_id'],
                    'First Name' => $params['firstname'],
                    'Last Name' => $params['lastname'],
                    'Phone' => $params['phone'],
                    'Mobile' => $params['phone'],
                    'Company' => $params['company'] . "(".$params['taxid'].")",
                    'Mailing Street' => $params['address1']." ".$params['address2'],
                    'Mailing City' => $params['city'],
                    'Mailing State' => $params['state'],
                    'Mailing Zip' => $params['postcode'],
                )
                
            )
                );
        } catch (ZohoCRMException $e) {
            echo $e->getMessage() . " error";
            //exit();
        }
        
        return $record;
    }
    public static function updateAccount($params){
        
        $client = new Client(self::$apikey);
        $record = array();
        
        try {
            $record = $client->updateRecords($module = 'Accounts', $data = array(
                1 => array(
                    'Id' => $params['zoho_id'],
                    'Referal Code' => $params['refcode'],
                    'Lead Source' => 'app.fastship.co',
                    'Traffic Source' => $params['traffic_src'],
                    'Ads Campaign' => $params['campaign'],
                    'Register For' => $params['for'],
                    'Behavior' => $params['behavior'],
                    'CustID' => $params['cust_id'],
                )
                
            )
                );
        } catch (ZohoCRMException $e) {
            echo $e->getMessage() . " error";
            //exit();
        }
        
        return $record;
    }
    /*
	public static function createLead($params){
		
		extract($params);
		
		$url = 'https://crm.zoho.com/crm/WebToLeadForm';

		$ch = curl_init();
		$dataDetails = array(
			'xnQsjsdp' => "d863298390fc11817257a612937da061902b577c82a5a99d02740a36f7841153",
		    'zc_gad' => "",
			'xmIwtLD' => "7327b52e32d9e9c5be67461d32dec83d49f257b35a1f6741cadaac71e1de4429",
			'actionType' => "TGVhZHM=",
			'returnURL' => "http://app.fastship.co",
			'Lead Source' => "app.fastship.co",
			'Company' => $Company,
			'Email' => $Email,
			'LEADCF2' => $LineID,
			'First Name' => $First_Name,
			'Last Name' => $Last_Name,
			'Mobile' => $Mobile,
			'Street' => $Street,
			'City' => $City,
			'State' => $State,
			'Zip_Code' => $Zip_Code,
			'Country' => $Country,
			'LEADCF5' => $RefCode,
		);
		
		//$headers = array('Content-Type: application/json');
		$headers = array();
		
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $dataDetails);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		
		$response = curl_exec($ch);

		if (false === $response) {
		    echo 'Request unsuccessful' . PHP_EOL;
		    curl_close($ch);
		    exit(1);
		}
		$responseCode = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
		$responseBody = json_decode($response);
		curl_close($ch);
		
		
		if (200 !== $responseCode) {
		    echo 'Authentication failed' . PHP_EOL;
		    foreach ($responseBody->errors as $error) {
		        echo $error->code . ': ' . $error->message . PHP_EOL;
		    }
		    exit(1);
		}
		//print_r($responseBody);
		//$authorisationToken = $responseBody->token;
		//echo 'Authentication success with token ' . $authorisationToken . PHP_EOL;

		//return $responseBody;
		
	}
	*/
    
    public static function updateSalesOrder($params){
        
        $client = new Client(self::$apikey);
        $record = array();
        
        try {
            
            $record = $client->updateRecords($module = 'SalesOrders', $data = array(
                1 => array(
                    'Id' => $params['zoho_id'],
                    'Carrier' => $params['agent'],
                    'Status' => $params['status'],
                    'Sales Commission' => $params['total'], //Pick Total
                    'Pickup Type' => $params['type'],
                    'Pickup Cost' => $params['cost'],
                    'Sale Discount' => $params['discount'],
                    'Payment Method' => $params['payment'],
                    'Billing Street' => $params['sender_address'],
                    'Billing City' => $params['sender_city'],
                    'Billing State' => $params['sender_state'],
                    'Billing Code' => $params['sender_postcode'],
                    'Billing Country' => $params['sender_country'],
                    'Shipping Street' => $params['receiver_address'],
                    'Shipping City' => $params['receiver_city'],
                    'Shipping State' => $params['receiver_state'],
                    'Shipping Code' => $params['receiver_postcode'],
                    'Shipping Country' => $params['receiver_country'],
                )
                
            )
                );
            
        } catch (ZohoCRMException $e) {
            echo $e->getMessage() . " error";
            //exit();
        }
        
        return $record;
    }
    
    public static function updateSalesOrderStatus($params){
        
        $client = new Client(self::$apikey);
        $record = array();
        
        try {
            
            $record = $client->updateRecords($module = 'SalesOrders', $data = array(
                1 => array(
                    'Id' => $params['zoho_id'],
                    'Tracking' => $params['tracking'],
                    'Status' => $params['status'],
                )
                
            )
                );
            
        } catch (ZohoCRMException $e) {
            echo $e->getMessage() . " error";
            //exit();
        }
        
        return $record;
    }
    
    private static function convertLead($module, $id)
    {
        $client = new Client(self::$apikey);
        $response = $client->call($module, 'convertLead', array('newFormat' => 1, 'version' => 4, 'leadId'=> $id),   $data = array(
            "1" => array(
                'createPotential' => false,
                'assignTo' => "title@fastship.co",
            ),
        ));
        
        return json_decode($response,true);
        //return $client->serializer->deserialize($response, null, 'zoho', array('errors' => &$client->lastErrors, 'module' => $module));
    }
}
?>