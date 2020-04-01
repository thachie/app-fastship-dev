<?php 
namespace App\Http\Controllers;
 
use Mail;
use DB;
use App\Http\Controllers\Liff\WebhookController;
use App\Lib\Fastship\FS_Customer;
use App\Lib\Fastship\FS_Line;
use App\Lib\Fastship\Fastship;
use App\Lib\Fastship\FS_Shipment;
use App\Lib\Fastship\FS_Pickup;
use App\Lib\Mailchimp\MailchimpManager;
use App\Lib\Zoho\ZohoManager;
use App\Lib\TrafficTracker\TrafficTracker;
use CodeItNow\BarcodeBundle\Utils\QrCode;
use App\Lib\Kbank\KbankManager;
use App\Lib\Line\LineManager;
use App\Events\LineEvent;
use App\Lib\Encryption;

class TestController extends Controller {

    public function __construct()
    {
        $this->dateTime = date_default_timezone_set("Asia/Bangkok");
        if($_SERVER['REMOTE_ADDR'] == "localhost"){
            include(app_path() . '\Lib\inc.functions.php');
        }else{
            include(app_path() . '/Lib/inc.functions.php');
        }
    }
    
	public function getIndex(){
		return view('test');
	}

	public function testSendRegisterEmail(){
		
		//prepare data
		$data=array();
		$data['firstname'] = "TestFirst";
		$data['lastname'] = "TestLast";
		$data['email'] = "thachie@mousework.com";
		$data['telephone'] = "922100123";
		$data['password'] = "abc123";
		$data['referal_code'] = "AMZ";
		
		$createDetails = array(
				'Firstname' => $data['firstname'],
				'Lastname' => $data['lastname'],
				'PhoneNumber' => $data['telephone'],
				'Email' => $data['email'],
				'Password' => $data['password'],
				'ReferCode' => $data['referal_code'],
				'Group' => "Standard",
		);
		
		//send email
		$email_data = array(
				'firstname' => $data['firstname'],
				'email' => $data['email'],
				'customerData' => $createDetails,
		);
		
		Mail::send('email/register',$email_data,function($message) use ($email_data){
			$message->to($email_data['email']);
			$message->bcc(['thachie@tuff.co.th','nice@tuff.co.th']);
			$message->from('info@fastship.co', 'FastShip');
			$message->subject('TEST ยินดีต้อนรับสู่ FastShip - '. $email_data['firstname'] ." ");
		});
	}
	
	public function testSendResetPasswordEmail(){
	    
	    //prepare data
	    $data=array();
	    $data['firstname'] = "TestFirst";
	    $data['lastname'] = "TestLast";
	    $data['email'] = "thachie@mousework.com";
	    $data['telephone'] = "922100123";
	    $data['password'] = "abc123";
	    $data['referal_code'] = "AMZ";
	    
	    $createDetails = array(
	        'Firstname' => $data['firstname'],
	        'Lastname' => $data['lastname'],
	        'PhoneNumber' => $data['telephone'],
	        'Email' => $data['email'],
	        'Password' => $data['password'],
	        'ReferCode' => $data['referal_code'],
	        'Group' => "Standard",
	    );
	    
	    //send email
	    $email_data = array(
	        'firstname' => $data['firstname'],
	        'email' => $data['email'],
	        'customerData' => $createDetails,
	    );
	    
	    Mail::send('email/reset_password',$email_data,function($message) use ($email_data){
	        $message->to($email_data['email']);
	        $message->bcc(['thachie@tuff.co.th','nice@tuff.co.th']);
	        $message->from('info@fastship.co', 'FastShip');
	        $message->subject('TEST FastShip - Reset '. $email_data['firstname'] ." ");
	    });
	}
	
	public function testSendNewOrderEmail(){
	    /*
	    //prepare data
	    $data=array();
	    $data['firstname'] = "TestFirst";
	    $data['lastname'] = "TestLast";
	    $data['email'] = "thachie@mousework.com";
	    $data['telephone'] = "922100123";
	    $data['password'] = "abc123";
	    $data['referal_code'] = "AMZ";
	    
	    $createDetails = array(
	        'Firstname' => $data['firstname'],
	        'Lastname' => $data['lastname'],
	        'PhoneNumber' => $data['telephone'],
	        'Email' => $data['email'],
	        'Password' => $data['password'],
	        'ReferCode' => $data['referal_code'],
	        'Group' => "Standard",
	    );
	    
	    $customerId = "5223";
	    $pickupId = "266432";
	    
	    
	    //send email
	    $validateCustomer = DB::table('customer')
	    ->select("CUST_FIRSTNAME","CUST_LASTNAME","CUST_EMAIL")
	    ->where('CUST_ID', $customerId)
	    ->where("IS_ACTIVE",1)
	    ->first();
	    
	    $toName = $validateCustomer->CUST_FIRSTNAME.' '.$validateCustomer->CUST_LASTNAME;
	    $eMail = $validateCustomer->CUST_EMAIL;
	    */
	    
	    $eMail = "thachie@tuff.co.th";
	    $customerId = 90;
	    $pickupId = 290178;
	    Fastship::getToken($customerId);

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
	    
	    
	    if($sent = Mail::send('email/new_order',$data,function($message) use ($data){
	        $message->to($data['email']);
	        $message->from('info@fastship.co', 'FastShip');
	        $message->subject( time() . ' TEST ใบรับพัสดุจาก FastShip หมายเลข '. $data['pickupId'] ." ถูกสร้างแล้ว");
	    })){
	        echo "sent";
	    }else{
	        echo "not send1";
	        print_r($sent);
	    }
	    
	}
	
	public function testSendPaymentEmail(){
	    
	    $converter = new Encryption;
	    
	    if(isset($_REQUEST['p'])){
	        $pickupId = $_REQUEST['p'];
	        $customerId = $_REQUEST['c'];
	    }else{
	        $pickupId = 295643;
	        $customerId = 5223;
	    }
	    $code1 = $converter->encode_short($pickupId);
	    $code2 = $converter->encode_short($customerId);
	    $url = "https://app.fastship.co/kbank/qr/".$code1."/".$code2;
	    echo $url;
	    echo "<br /><a href='".$url."' target='_blank'>link</a>";
	    exit();
	    
	    //prepare data
	    $data=array();
	    $data['firstname'] = "TestFirst";
	    $data['lastname'] = "TestLast";
	    $data['email'] = "thachie@mousework.com";
	    $data['telephone'] = "922100123";
	    $data['password'] = "abc123";
	    $data['referal_code'] = "AMZ";
	    
	    $createDetails = array(
	        'Firstname' => $data['firstname'],
	        'Lastname' => $data['lastname'],
	        'PhoneNumber' => $data['telephone'],
	        'Email' => $data['email'],
	        'Password' => $data['password'],
	        'ReferCode' => $data['referal_code'],
	        'Group' => "Standard",
	    );
	    
	    $customerId = "5223";
	    $pickupId = "266432";
	    
	    //send email
	    $validateCustomer = DB::table('customer')
	    ->select("CUST_FIRSTNAME","CUST_LASTNAME","CUST_EMAIL")
	    ->where('CUST_ID', $customerId)
	    ->where("IS_ACTIVE",1)
	    ->first();
	    
	    $toName = $validateCustomer->CUST_FIRSTNAME.' '.$validateCustomer->CUST_LASTNAME;
	    $eMail = $validateCustomer->CUST_EMAIL;
	    
	    Fastship::getToken($customerId);
	    
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
	    
	    Mail::send('email/notify_payment',$data,function($message) use ($data){
	        $message->to($data['email']);
	        $message->bcc(['thachie@tuff.co.th','nice@tuff.co.th','oak@tuff.co.th']);
	        $message->from('info@fastship.co', 'FastShip');
	        $message->subject( time() . ' TEST Fastship - แจ้งการชำระเงิน ใบรับหมายเลข '. $data['pickupId'] ."");
	    });
	        
	}
	
	public function testSendTrackingEmail(){
	
    	$email = "thachie@tuff.co.th";
    	$shipmentId = "1527066365";

    	$customerObj = DB::table('customer')->select("CUST_ID")
    	->whereRaw('LOWER(CUST_EMAIL) = ?', strtolower($email))
    	->where("CUST_LEADSOURCE",5)
    	->where("IS_ACTIVE",1)->first();
    	if($customerObj == null){
    	    exit();
    	}
    	
    	$customerId = $customerObj->CUST_ID;
    	
    	Fastship::getToken($customerId);
    	
    	$ShipmentDetail = FS_Shipment::get($shipmentId);
    	
    	//alert($ShipmentDetail);die();
    	
    	//if($ShipmentDetail['ShipmentDetail']['Tracking'] == "") exit();
    	
    	$dTypes = DB::table('product_type')->where("IS_ACTIVE",1)->orderBy("TYPE_SORT")->orderBy("TYPE_NAME")->get();
    	
    	$declareTypes = array();
    	if(sizeof($dTypes)>0){
    	    foreach($dTypes as $dType){
    	        $declareTypes[$dType->TYPE_CODE] = $dType->TYPE_NAME . " (" . $dType->TYPE_NAME_TH . ")";
    	    }
    	}
    	
    	$tracking = $ShipmentDetail['ShipmentDetail']['Tracking'];
    	$data = array(
    	    'shipmentId' => $shipmentId,
    	    'email' => $email,
    	    'shipmentData' => $ShipmentDetail,
    	    'tracking' => $tracking,
    	    'declareType' => $declareTypes,
    	);
    	
    	Mail::send('email/tracking',$data,function($message) use ($data){
    	    $message->to($data['email']);
    	    $message->bcc(['thachie@tuff.co.th','nice@tuff.co.th']);
    	    $message->from('info@fastship.co', 'FastShip');
    	    $message->subject(time() . ' FastShip พัสดุหมายเลข '. $data['shipmentId'] ."(".$data['tracking'].")  ส่งออกเรียบร้อยแล้ว");
    	});
    	    
    	    echo "sent";
	}

	public function testEmail(){
	    
	    $to = "thachie@tuff.co.th";
	    $subject = 'Fastship.co : Test Simple Email ' . date("H:i d/m/Y");
	    $message = "this is test";
	    $headers = 'From: error@fastship.co' . "\r\n" .
	   	    'Reply-To: no-reply@fastship.co' . "\r\n" .
	   	    'X-Mailer: PHP/' . phpversion();

	    if(mail($to, $subject, $message,$headers)){
	        echo "Success";
	        
	    }else{
	        echo "Fail";
	    }
	    
	    exit();
	    
	    
	}
	public function testMailchimp(){

	    
	    $params = array(
	        "email" => "thachie.thachie2@tuff.co.th",
	        "firstname" => "ThachananTest",
	        "lastname" => "SangTest",
	        "address" => "BangkokTest",
	        "phone" => "1212312121",
	        "join_date" => date("Y-m-d"),
	        "cust_id" => "12220",
	        "refcode" => "AMZ2",
	        "sales" => 100,
	        "pickup" => 2,
	    );
	    
	    $ids = array("12220");
	    $result = ZohoManager::getLead($ids);
	    print_r($result);exit();
	    //print_r($client);
	    //$records = ZohoManager::getLeads();
// 	    foreach($records as $record){
// 	        print_r($record);
// 	        echo "<hr />";
// 	    }
	    
	    ZohoManager::createLead($params);
	    
// 	    $record = $client->insertRecords($module = 'Contacts', $data = array(
//     	        $params['cust_id'] => array(
//     	            'Last Name' => $params['lastname'],
//     	            'First Name' => $params['firstname'],
//     	            'Email' => $params['email'],
//     	        )
// 	        )
// 	    );
// 	    print_r($record);
	    exit();
	    
	    echo "testing ...";

	    $params = array(
	        "email" => "thachie.thachie2@tuff.co.th",
	        "firstname" => "ThachananTest",
	        "lastname" => "SangTest",
	        "address" => "BangkokTest",
	        "phone" => "1212312121",
	        "join_date" => date("Y-m-d"),
	        "cust_id" => "12220",
	        "refcode" => "AMZ2",
	        "sales" => 100,
	        "pickup" => 2,
	    );
	    MailchimpManager::update($params);

	}
	
	public function testTrafficTracker(){
	    
	    $trafficTracker = new TrafficTracker(
	        'localhost',
	        'root',
	        '59lAdf91XTaU',
	        'fastship_app',
	        'ttcpc',
	        60
	    );
	    
	    exit();
	    
	}
	
	public function testZoho(){
	    
	    
	    
	    $args = array(
	        "email" => "siripon.chooyu@gmail.com",
	    );
	    $leads = ZohoManager::searchLeads($args);
	    print_r($leads);

	    foreach($leads as $lead){
	        $leadId = $lead[1]['LEADID'];
	    }
	    exit();
	    
	    /*
	    $trackPrefix = "ttcpc";
	    $referrerCampaign	= (isset($_COOKIE[$trackPrefix.'_campaign']) ? $_COOKIE[$trackPrefix.'_campaign'] : '');

	    // Parse __utmz cookie
	    list($domainHash,$sourceTimestamp, $sessionNumber, $campaignNumber, $campaignData) = explode('.', $_COOKIE["__utmz"],5);
	    
	    $sourceData = parse_str(strtr($campaignData, '|', '&')); // Parse the __utmz data

	    $referrerMedium		= isset($utmcmd) ? $utmcmd:"";
	    if(isset($utmgclid)){ // if from AdWords
    	    $referrerMedium		= 'cpc';
    	}
	    
	    $params = array(
	        'cust_id' => 69,
	        'zoho_id' => '2330098000016364001',
	        'firstname' => "Tha555chie".time(),
	        'lastname' => "Last".date("Ymd"),
	        'email' => "tha".time()."chie@gmail.com",
	        'phone' => "00011112222",
	        'refcode' => "AMZ",
	        'traffic_src' => $referrerMedium,
	        'campaign' => $referrerCampaign,
	    );
   
	    $response = ZohoManager::createLead($params);
	    print_r($response);
 	    exit();
	    */
	    
	    //$leadId = "2330098000010129001";
	    //$accountId = "2330098000016671026";
	    $leadId = "2330098000020109001";
	    $customerId = "24169";

	    $accountId = "2330098000020113032";

	    if($accountId != ""){
	        
	       $account = ZohoManager::getAccount(array($accountId));
	       print_r($account[1]['Account Name']);
	       exit();
	       
	       $shipmentId = "1529106134";
	       Fastship::getToken($customerId);
	       $shipmentObj = FS_Shipment::get($shipmentId);
	       
	       //print_r($shipmentObj);
	       /*
	       $params = array(
	           "zoho_id" => $accountId,
	           "refcode" => "TST",
	           "traffic_src" => "organic",
	           "campaign" => "nice",
	           "for" => "ebay",
	           "behavior" => "10_20",
	           "cust_id" => $customerId,
	       );
	       ZohoManager::updateAccount($params);
	       */
	       $params = array(
	           "record_id" => $shipmentId,
	           "cust_id" => $customerId,
	           "agent" => $shipmentObj['ShipmentDetail']['ShippingAgent'],
	           "total" => $shipmentObj['ShipmentDetail']['ShippingRate'],
	           "account_name" => $shipmentObj['SenderDetail']['Firstname'] . "(" . $shipmentObj['SenderDetail']['Email'] . ")",
	           "type" => "",
	           "cost" => $shipmentObj['ShipmentDetail']['ShippingRate'],
	           "discount" => 0,
	           "payment" => "",
	           "sender_address" => $shipmentObj['SenderDetail']['AddressLine1'],
	           "sender_city" => $shipmentObj['SenderDetail']['City'],
	           "sender_state" => $shipmentObj['SenderDetail']['State'],
	           "sender_postcode" => $shipmentObj['SenderDetail']['Postcode'],
	           "sender_country" => $shipmentObj['SenderDetail']['Country'],
	           "receive_address" => $shipmentObj['ReceiverDetail']['AddressLine1'],
	           "receive_city" => $shipmentObj['ReceiverDetail']['City'],
	           "receive_state" => $shipmentObj['ReceiverDetail']['State'],
	           "receive_postcode" => $shipmentObj['ReceiverDetail']['Postcode'],
	           "receive_country" => $shipmentObj['ReceiverDetail']['Country'],
	       );
	       print_r($params);
	       ZohoManager::createSalesOrder($params);
	       
	    }
	    if($accountId == "" || empty($account)){
	        
	        //update lead company (for account name)
	        Fastship::getToken($customerId);
	        $customerObj = FS_Customer::get($customerId);

	        $leadId = $customerObj['ZohoLeadId'];
	        
	        if($leadId != ""){
	            $lead = ZohoManager::getLead(array($leadId));
	        }
	        if($leadId != "" && !empty($lead)){
	            
	            $response = ZohoManager::convertToAccount($leadId,$customerObj);
	            //print_r($response);
	            
	            if(isset($response['success']) && isset($response['success']['Account']) && isset($response['success']['Account']['content'])){
	               $accountId = $response['success']['Account']['content'];
	            }
	            if(isset($response['success']) && isset($response['success']['Contact']) && isset($response['success']['Contact']['content'])){
	               $contactId = $response['success']['Contact']['content'];
	            }
	            
	            echo "converted account id: " . $accountId;
	            
	        }else{
	            echo "lead not found";
	        }
	        
	        
	    }else{
	        echo "already converted";
	        print_r($account);
	    }
	    
	    
	    //print_r(ZohoManager::getLead(array('2330098000016358001')));
	    //print_r(ZohoManager::getAccount(array('2330098000016671026')));
	    
	    
	     	    exit();
	    
	    //$test = ZohoManager::updateLead($params);
	    
	    echo "<hr />";
	    
	    //print_r(ZohoManager::getLead(array('2330098000016358001')));
	   // echo "<hr />";

	    
	    
	}
	
	public function testKbankQRPayment(){
	    	    
	    $params = array(
	        "amount" => 1,
	        "pickupId" => 284284,
	        "customerId" => 69,
	        "shipments" => array(
	            "1521521231" => 970,
	            "1521521232" => 1500,
	        ),
	    );
	    
	    $qrResponse = KbankManager::getQr($params);
	    $qr = $qrResponse['qrCode'];
	    echo "end1";

	    $params['originId'] = $qrResponse['partnerTxnUid'];
	    $qr = KbankManager::inquirePayment($params);
	    echo "end2";
	    exit();
	    
	    if(isset($qr) && $qr != ""){
    	    $qrCode = new QrCode();
    	    $qrCode
    	    ->setText($qr)
    	    ->setSize(150)
    	    ->setPadding(10)
    	    ->setErrorCorrection('high')
    	    ->setForegroundColor(array('r' => 0, 'g' => 0, 'b' => 0, 'a' => 0))
    	    ->setBackgroundColor(array('r' => 255, 'g' => 255, 'b' => 255, 'a' => 0))
    	    ->setLabel($tracking)
    	    ->setLabelFontSize(16)
    	    ->setImageType(QrCode::IMAGE_TYPE_PNG);
    
    	    echo '<img src="data:'.$qrCode->getContentType().';base64,'.$qrCode->generate().'" />';
	    }
	    exit();
	}
	
	public function testAny(){

	    // ##### call notify #####
	    $pickupId = 302088;
	    $token = md5("fastship".$pickupId);
	    $requestArray = array(
	        'id' => $pickupId,
	        'token' => $token,
	    );
	    $url = "https://admin.fastship.co/notify/newpickup";
	    call_api($url,$requestArray);
	    // ##### call notify #####
	    
	    exit();
	    
	    echo "AAA";
	    print_r(preg_match('/[^A-Za-z0-9]/', "asda" ));
	    
	    exit();
	    
	    $content = LineManager::getContent(11077892542410);
	    print_r($content);
	    exit();
	    
	    $adminUrl = "https://admin.fastship.co/download/";
	    $token = file_get_contents($adminUrl . 'zoho_token.txt');
	    
	    print_r($token);
	    
	    exit();
	    
	    echo "Trigger Event 3 : ";
	    
	    Fastship::getToken();
	    $line = FS_Line::get(110);
	    print_r($line);
	    exit();
	    
	    // Notify client-side listeners with the
	    // Laravel `event` helper function.
	    $params = array(
	        "id" => 102,
	        "type" => "text",
	        "userId" => "U06ef2762df7dbe7aa4a38c6f947350f9",
	        "adminId" => null,
	        "params" => null,
	        "message" => "ยืนยัน88",
	        "createDate" => date("d/m/Y H:i:s")
	    );
	    event(new LineEvent($params));

	    exit();
	    
	    
	    $countries = array();
	    $countriesQry = \Illuminate\Support\Facades\DB::table('country')->select('cntry_name','cntry_code')->get();
	    foreach($countriesQry as $country){
	        $countries[$country->cntry_code] = $country->cntry_name;
	    }
	    print_r($countries);
	    exit();
	    
	    $messageId = "10744276371267";
	    $content = LineManager::getContent($messageId);
	    echo "<img src='" . $content . "' style='width:80px;'/><br />";
	    exit();
	    
	    $profile = LineManager::getProfile("U06ef2762df7dbe7aa4a38c6f947350f9");
	    
	    echo "<img src='" . $profile->pictureUrl . "' style='width:80px;'/><br />";
	    echo $profile->displayName;
	    
	    exit();
	    
	    Fastship::getToken();
	    
	    $response = array();
	    $response['MessageId'] = "b1c6d6d4-7d49-4fee-a1fe-540d7f714e6b-f6406966";
	    $response['Text'] = "tesst this is test";
	    $response['Command'] = "create-pickup";
	    $response['ReplyToken'] = "f72a477b81e14ee18ff346f8f118afba";
	    $response['Timestamp'] = "1570607461443E12";
	    $response['UserId'] = "U06ef2762df7dbe7aa4a38c6f947350f9";
	    $response['Parameters'] = '{"command":"create-pickup"}';
	    
	    
	    echo "call...";
	    print_r($response);
	    $resp = FS_Line::create($response);
echo "<hr />";	    
	    print_r($resp);
	}

}