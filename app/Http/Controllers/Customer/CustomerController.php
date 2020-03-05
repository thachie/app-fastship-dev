<?php

namespace App\Http\Controllers\Customer;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use App\Lib\Encryption;
use App\Lib\Fastship\Fastship;
use App\Lib\Fastship\FS_Shipment;
use App\Lib\Fastship\FS_Pickup;
use App\Lib\Fastship\FS_Customer;
use App\Lib\Thaitrade\ThaitradeManager;
use App\Lib\Zoho\ZohoApiV2;
use Illuminate\Support\Facades\Mail;
use Exception;

class CustomerController extends Controller
{

    public $marketplaces = array();
    
	public function __construct()
	{
		$this->dateTime = date_default_timezone_set("Asia/Bangkok");
		if($_SERVER['REMOTE_ADDR'] == "localhost"){
			include(app_path() . '\Lib\inc.functions.php');
		}else{
			include(app_path() . '/Lib/inc.functions.php');
		}

		$this->marketplaces = array(
		    "EBAY_US" => array(
		        "country" => "us",
		        "name" => "United States",
		    ),
		    "EBAY_AU" => array(
		        "country" => "au",
		        "name" => "Australia",
		    ),
		    "EBAY_CA" => array(
		        "country" => "ca",
		        "name" => "Canada",
		    ),
		    "EBAY_DE" => array(
		        "country" => "de",
		        "name" => "Germany",
		    ),
		    "EBAY_FR" => array(
		        "country" => "fr",
		        "name" => "France",
		    ),
		    "EBAY_GB" => array(
		        "country" => "gb",
		        "name" => "Great Britain",
		    ),
		    "EBAY_ES" => array(
		        "country" => "es",
		        "name" => "Spain",
		    ),
		    "EBAY_BE" => array(
		        "country" => "be",
		        "name" => "Belgium",
		    ),
		    "EBAY_AT" => array(
		        "country" => "at",
		        "name" => "Austria",
		    ),
		    "EBAY_CH" => array(
		        "country" => "ch",
		        "name" => "Switzerland",
		    ),
		    "EBAY_IE" => array(
		        "country" => "ie",
		        "name" => "Ireland",
		    ),
		    "EBAY_IT" => array(
		        "country" => "it",
		        "name" => "Italy",
		    ),
		    "EBAY_HK" => array(
		        "country" => "hk",
		        "name" => "Hong Kong",
		    ),
		    "EBAY_IN" => array(
		        "country" => "in",
		        "name" => "India",
		    ),
		    "EBAY_MY" => array(
		        "country" => "my",
		        "name" => "Malaysia",
		    ),
		    "EBAY_NL" => array(
		        "country" => "nl",
		        "name" => "Netherlands",
		    ),
		    "EBAY_PH" => array(
		        "country" => "ph",
		        "name" => "Philippines",
		    ),
		    "EBAY_PL" => array(
		        "country" => "pl",
		        "name" => "Poland",
		    ),
		    "EBAY_SG" => array(
		        "country" => "sg",
		        "name" => "Singapore",
		    ),
		    "EBAY_TH" => array(
		        "country" => "th",
		        "name" => "Thailand",
		    ),
		    "EBAY_TW" => array(
		        "country" => "tw",
		        "name" => "Taiwan",
		    ),
		    "EBAY_VN" => array(
		        "country" => "vn",
		        "name" => "Vietnam",
		    ),
		    
		);
	}

	public function login(Request $request)
	{

	    //get parameter
		$email = $request->input('username');
		$password = $request->input('password');
		
		//check parameter
		if(!empty($email) && !empty($password)){

		    //convert password to Encrypt
			$converter = new Encryption;
			$encryptPassword = $converter->encode($password);
			
			//check login via API
			Fastship::getToken();
			$params = array(
			    "Email" => $email,
			    "Password" => $password,
			);
			$customerId = FS_Customer::login($params);

			Fastship::getToken($customerId);
			
			//get customer
			$customerObj = FS_Customer::get($customerId);
			
			if($request->has("line_id") && $customerObj['LineId'] == null){
			     $lineId = $request->input('line_id');
			     $params = array(
			         "LineUserId" => $lineId,
			     );
			     FS_Customer::update($params);
			}
			
			if($customerObj == null){
				return back()->with('msg','ไม่พบข้อมูลผู้ใช้งาน');
			}else{
			    
			    //save to session
				$request->session()->put('customer.id', $customerId);
				$request->session()->put('customer.name', $customerObj['Firstname']);
				$request->session()->put('customer.line', $customerObj['LineId']);

				//get shipment in cart
				$searchDetails = array("Status" => "Pending");
				$response = FS_Shipment::search($searchDetails);
				if($response === false){
					$shipment_data = 0;
				}else{
					if(sizeof($response) > 0 && is_array($response)){
						$shipment_data = sizeof($response);
					}else{
						$shipment_data = 0;
					}
				}
				$request->session()->put('pending.shipment', $shipment_data);

				if($request->has("return")){
				    return Redirect::to($request->input('return'));
				}else{
				    return redirect('calculate_shipment_rate');
				}
			}
		}else{
		    return back()->with('msg','กรุณากรอกข้อมูลให้ครบถ้วน');
		}
	}

	public function register(Request $request)
	{

		//validate
		$this->validate($request, [
			'firstname' => 'required',
			'lastname' => 'required',
			'email' => 'required',
			'telephone' => 'required',
	        'state' => 'required',
	        'for' => 'required',
			'password' => 'required',
			'c_password' => 'required',
		]);

		//prepare data
		$data=array();
		$data['firstname'] = $request->input('firstname');
		$data['lastname'] = $request->input('lastname');
		$data['email'] = strtolower($request->input('email'));
		$data['telephone'] = $request->input('telephone');
		$data['state'] = $request->input('state');
		$data['for'] = $request->input('for');
		if($request->has('behavior')){
		    $data['behavior'] = $request->input('behavior');
		}else{
		    $data['behavior'] = "";
		}
		$data['password'] = $request->input('password');
		$data['c_password'] = $request->input('c_password');
		$data['referral'] = strtoupper($request->input('referral'));
        if($request->has('marketplace_ref_id')){
            $data['marketplace_ref_id'] = strtoupper($request->input('marketplace_ref_id'));
            $marketplaceRefId = strtoupper($request->input('marketplace_ref_id'));
        }else{
            $data['marketplace_ref_id'] = "";
        }
        if($request->has('marketplace_type')){
            $marketplaceType = strtoupper($request->input('marketplace_type'));
        }
        if($request->has('line_id')){
            $data['line_id'] = $request->input('line_id');
        }else{
            $data['line_id'] = "";
        }

		//check and replace email validate
		$data['email'] = preg_replace("/[^A-Za-z0-9-@\._]/", "", $data['email']); 

		//check password
		if($data['password'] == $data['c_password']){
				
		    Fastship::getToken();

		    try{
    			$validateEmail = FS_Customer::checkEmail($data['email']);
    			if($validateEmail == 1){
    			    return back()->with('msg','อีเมลล์นี้มีผู้ใช้งานแล้ว : ' . strtolower($data['email']));
    			}
		    }catch (Exception $e){
		        return back()->with('msg','อีเมลล์นี้มีผู้ใช้งานแล้ว : ' . strtolower($data['email']));
		    }
			
		    try{
    			$validateTel = FS_Customer::checkTel($data['telephone']);
    			if($validateTel == 1){
    			    return back()->with('msg','เบอร์โทรศัพท์นี้มีผู้ใช้งานแล้ว : ' . strtolower($data['telephone']));
    			}
		    }catch (Exception $e){
		        return back()->with('msg','เบอร์โทรศัพท์นี้มีผู้ใช้งานแล้ว : ' . strtolower($data['telephone']));
		    }
		    
			$converter = new Encryption;
			$password = $converter->encode($data['password']);
			$decoded = $converter->decode($password);

			//check referal code
			$class = "Standard";
			if($data['referral'] != ""){
				$promoObj = DB::table('promo_code')->where("CODE_NAME",$data['referral'])
				->where("CODE_TYPE",'Referal')
				->where("CODE_DISCOUNTTYPE",'Class')
				->where("IS_ACTIVE",1)->first();
				if($promoObj != null){
					$class = $promoObj->CODE_DISCOUNTAMOUNT;
				}
			}

			//get google cookie traffic
			$trackPrefix = "ttcpc";
			$referrerCampaign	= (isset($_COOKIE[$trackPrefix.'_campaign']) ? $_COOKIE[$trackPrefix.'_campaign'] : '');
			
			// Parse __utmz cookie
			if(isset($_COOKIE["__utmz"])){
				list($domainHash,$sourceTimestamp, $sessionNumber, $campaignNumber, $campaignData) = explode('.', $_COOKIE["__utmz"],5);
				$sourceData = parse_str(strtr($campaignData, '|', '&')); // Parse the __utmz data
			}
			$referrerMedium		= isset($utmcmd) ? $utmcmd:"";
			if(isset($utmgclid)){ // if from AdWords
			    $referrerMedium		= 'cpc';
			}

			//insert to API
			$createDetails = array(
				'Firstname' => $data['firstname'],
				'Lastname' => $data['lastname'],
				'PhoneNumber' => $data['telephone'],
		        'Email' => strtolower($data['email']),
		        'State' => strtolower($data['state']),
				'Password' => $data['password'],
				'ReferCode' => $data['referral'],
			    'MarketplaceId' => $data['marketplace_ref_id'],
		        'For' => strtolower($data['for']),
		        'TrafficSource' => $referrerMedium,
			    'AdsCampaign' => $referrerCampaign,
			    'Behavior' => $data['behavior'],
			    'LineUserId' => $data['line_id'],
				'Group' => $class,
			);
			$customerId = FS_Customer::create($createDetails);

			if($customerId == "" || $customerId <= 0){
			    return back()->with('msg','ข้อมูลไม่ถูกต้อง : ' . strtolower($data['email']));
			}

			//check SOOK
			if(isset($marketplaceType) && strtoupper($marketplaceType) == "SOOK"){

			    Fastship::getToken($customerId);
			    $params = array(
			        "CustomerID" => $customerId,
			        "ChannelType" => "SOOK",
			        "AccountName" => $marketplaceRefId,
			        "Token" => $marketplaceRefId,
			    );
			    FS_Customer::addChannel($params);
			    
			    //create seller to Thaitrade
			    $refId = $customerId;
			    $sellerId = $marketplaceRefId;
			    $registerDate = date("d/m/Y");
			    
			    ThaitradeManager::createContacts($refId,$sellerId,$registerDate);

			}
			
			//insert to ZOHO			
			try{
			    
			    $params = array(
			        'cust_id' => "$customerId",
			        'firstname' => $data['firstname'],
			        'lastname' => $data['lastname'],
			        'email' => strtolower($data['email']),
			        'phone' => $data['telephone'],
			        'refcode' => $data['referral'],
			        'traffic_src' => $referrerMedium,
			        'campaign' => $referrerCampaign,
			        'for' => $data['for'],
			        'behavior' => $data['behavior'],
			        'state' => $data['state'],
			    );
			    $leadId = ZohoApiV2::createLead($params);

			    if(!isset($leadId)){
			        $leadId = "";
			    }
			    
			}catch (Exception $e){
			    $leadId = "";
			}
			
			//update lead id
			Fastship::getToken($customerId);
			$params = array(
			    'ZohoLeadId' => $leadId,
			);
			FS_Customer::update($params);

			//send email
			$email_data = array(
			   'firstname' => $data['firstname'],
			   'email' => strtolower($data['email']),
			   'customerData' => $createDetails,
			);
				
			Mail::send('email/register',$email_data,function($message) use ($email_data){
				$message->to($email_data['email']);
				$message->bcc(['oak@tuff.co.th','thachie@tuff.co.th']);
				$message->from('cs@fastship.co', 'FastShip');
				$message->subject('FastShip - ยินดีต้อนรับคุณ'. $email_data['firstname'] ." บัญชีของคุณถูกสร้างเรียบร้อยแล้ว");
			});

			//save to session
			$request->session()->put('customer.id', $customerId);
			$request->session()->put('customer.name', $data['firstname']);
			$request->session()->put('customer.line', $data['line_id']);
			    
		    //set shipment in cart
			$shipment_data = 0;
			$request->session()->put('pending.shipment', $shipment_data);

			if(isset($marketplaceType) && strtoupper($marketplaceType) == "SOOK"){
			    return redirect('/register_complete')->with('marketplace','SOOK');
			}else{
			    return redirect('/register_complete');
			}

		}else{
			return redirect('/joinus')->with('msg','การยืนยันรหัสของคุณไม่ถูกต้อง กรุณาใส่ รหัสผ่าน และ ยืนยันรหัสผ่าน ให้ตรงกัน');
		}
	}

	//Prepare for check rate page
	public function prepareMyAccount()
	{

		if (session('customer.id') != null){
			$customerId = session('customer.id');
		}else{
			return redirect('/')->with('msg','คุณยังไม่ได้เข้าระบบ กรุณาเข้าสู่ระบบเพื่อใช้งาน');
		}

		Fastship::getToken($customerId);
		$customer = FS_Customer::get($customerId);
		
		$customer_data = array();
		$customer_data['ID'] = $customer['ID'];
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
		$customer_data['group'] = $customer['Group'];
		$customer_data['refcode'] = $customer['ReferCode'];

		$creditCardsObj = DB::table('omise_customer')
			->select("ID","NUMBER","OMISE_LASTDIGITS","OMISE_BANK","OMISE_CARDNAME")
            ->where("CUST_ID",$customerId)
            ->where("IS_ACTIVE",1)
            ->get();
        $row_credit = $creditCardsObj->count();

		if($row_credit > 0){
			foreach ($creditCardsObj as $value) {
				$creditCards[] = $value;
			}
		}else{
			$creditCards = array();
		}
		
		$data = array(
			'customer_data' => $customer_data,
			'transactions' => array(),
			'creditCards' => $creditCards,
			'channels' => array(),
		);

		return view('myaccount',$data);
	}

	//Prepare for check rate page
	public function prepareAddNewCreditcard(Request $request, $id)
	{

		if (session('customer.id') != null){
			$customerId = session('customer.id');
		}else{
			return redirect('/')->with('msg','คุณยังไม่ได้เข้าระบบ กรุณาเข้าสู่ระบบเพื่อใช้งาน');
		}

		if (empty($id)) {
			$pickupId = '';
		}else{
			$pickupId = $id;
		}

		Fastship::getToken($customerId);
		$customer = FS_Customer::get($customerId);
		
		$customer_data = array();
		$customer_data['ID'] = $customer['ID'];
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
		$customer_data['group'] = $customer['Group'];
		$customer_data['refcode'] = $customer['ReferCode'];

		$creditCardsObj = DB::table('omise_customer')
			->select("ID","NUMBER","OMISE_LASTDIGITS","OMISE_BANK","OMISE_CARDNAME")
            ->where("CUST_ID",$customerId)
            ->where("IS_ACTIVE",1)
            ->get();
        $row_credit = $creditCardsObj->count();

		if($row_credit > 0){
			foreach ($creditCardsObj as $value) {
				$creditCards[] = $value;
			}
		}else{
			$creditCards = array();
		}
		
		$data = array(
			'customer_data' => $customer_data,
			'transactions' => array(),
			'creditCards' => $creditCards,
			'channels' => array(),
			'pickupId' => $pickupId,
		);

		return view('add_new_creditcard',$data);
	}
	
	public function prepareAccountOverview()
	{
	    
	    if (session('customer.id') != null){
	        $customerId = session('customer.id');
	    }else{
	        return redirect('/')->with('msg','คุณยังไม่ได้เข้าระบบ กรุณาเข้าสู่ระบบเพื่อใช้งาน');
	    }
	    
	    Fastship::getToken($customerId);
	    $customerObj = FS_Customer::get($customerId);
	    
	    $customer_data = array();
	    $customer_data['ID'] = $customerObj['ID'];
	    $customer_data['firstname'] = $customerObj['Firstname'];
	    $customer_data['lastname'] = $customerObj['Lastname'];
	    $customer_data['phonenumber'] = $customerObj['PhoneNumber'];
	    $customer_data['email'] = $customerObj['Email'];
	    $customer_data['company'] = $customerObj['Company'];
	    $customer_data['taxid'] = $customerObj['TaxId'];
	    $customer_data['address1'] = $customerObj['AddressLine1'];
	    $customer_data['address2'] = $customerObj['AddressLine2'];
	    $customer_data['city'] = $customerObj['City'];
	    $customer_data['state'] = $customerObj['State'];
	    $customer_data['postcode'] = $customerObj['Postcode'];
	    $customer_data['country'] = $customerObj['Country'];
	    $customer_data['group'] = $customerObj['Group'];
	    $customer_data['refcode'] = $customerObj['ReferCode'];
	    $customer_data['latitude'] = $customerObj['Latitude'];
	    $customer_data['longitude'] = $customerObj['Longitude'];

	    //prepare current month
	    $searchDetails = array(
	        'NoStatuses' => array("Cancelled","New","Pickup","Received","Unpaid","Verified"),
	        'CreateDateSince' => date("Y-m-01 00:00:00"),
	        'CreateDateTo' => date('Y-m-t 23:59:59',strtotime(date("Y-m-01"))),
	        'Limit' => 1000,
	    );
	    $currentMonthResponse = FS_Pickup::search($searchDetails);
	    $currentMonthSale = 0;
	    if($currentMonthResponse === false){
	        $currentMonthPickups = array();
	    }else{
	        if(is_array($currentMonthResponse) && sizeof($currentMonthResponse) > 0){
	            $currentMonthPickups = $currentMonthResponse;
    	        foreach($currentMonthPickups as $pickup){
    	            $currentMonthSale += $pickup['Amount'];
    	        }
	        }else{
	            $currentMonthPickups = array();
	        }
	    }
	    
	    //prepare previous month
	    $searchDetails = array(
	        'NoStatuses' => array("Cancelled"),
	        'CreateDateSince' => date("Y-m-01 00:00:00",strtotime(date('Y-m-01')." -1 month")),
	        'CreateDateTo' => date('Y-m-t 23:59:59',strtotime(date("Y-m-01")." -1 month")),
	    );
	    $previousMonthResponse = FS_Pickup::search($searchDetails);
	    $previousMonthSale = 0;
	    if($previousMonthResponse === false){

	    }else{

	        if(is_array($previousMonthResponse) &&  sizeof($previousMonthResponse) > 0){
    	        foreach($previousMonthResponse as $pickup){
    	            $previousMonthSale += $pickup['Amount'];
    	        }
	        }
	    }

	    //prepare two month ago
	    $searchDetails = array(
	        'NoStatuses' => array("Cancelled"),
	        'CreateDateSince' => date("Y-m-01 00:00:00",strtotime(date('Y-m-01')." -2 month")),
	        'CreateDateTo' => date('Y-m-t 23:59:59',strtotime(date("Y-m-01")." -2 month")),
	    );
	    $twoMonthAgoResponse = FS_Pickup::search($searchDetails);
	    $twoMonthAgoSale = 0;
	    if($twoMonthAgoResponse === false){
	        
	    }else{
	        if(is_array($twoMonthAgoResponse) && sizeof($twoMonthAgoResponse) > 0){
    	        foreach($twoMonthAgoResponse as $pickup){
    	            $twoMonthAgoSale += $pickup['Amount'];
    	        }
	        }
	    }
	    
	    $pickupCount = array();
	    
	    //prepare new pickup
	    $searchDetails = array(
	        'Status' => "New",
	        'Limit' => 1000,
	    );
	    $newResponse = FS_Pickup::search($searchDetails);
	    if($newResponse === false){
	        $pickupCount['new'] = 0;
	    }else{
	        if(is_array($newResponse) && sizeof($newResponse) > 0){
	            $pickupCount['new'] = sizeof($newResponse);
	        }else{
	            $pickupCount['new'] = 0;
	        }
	    }
	    
	    //prepare pick pickup
	    $searchDetails = array(
	        'Status' => "Pickup",
	        'Limit' => 1000,
	    );
	    $newResponse = FS_Pickup::search($searchDetails);
	    if($newResponse === false){
	        $pickupCount['pick'] = 0;
	    }else{
	        if(is_array($newResponse) && sizeof($newResponse) > 0){
	            $pickupCount['pick'] = sizeof($newResponse);
	        }else{
	            $pickupCount['pick'] = 0;
	        }
	    }
	    
	    //prepare unpaid pickup
	    $searchDetails = array(
	        'Status' => "Unpaid",
	        'Limit' => 1000,
	    );
	    $newResponse = FS_Pickup::search($searchDetails);
	    if($newResponse === false){
	        $pickupCount['unpaid'] = 0;
	    }else{
	        if(is_array($newResponse) && sizeof($newResponse) > 0){
	            $pickupCount['unpaid'] = sizeof($newResponse);
	        }else{
	            $pickupCount['unpaid'] = 0;
	        }
	    }
	    
	    //prepare completed pickup
	    $searchDetails = array(
	        'Status' => "Paid",
	        'Limit' => 1000,
	    );
	    $newResponse = FS_Pickup::search($searchDetails);
	    if($newResponse === false){
	        $pickupCount['completed'] = 0;
	    }else{
	        if(is_array($newResponse) && sizeof($newResponse) > 0){
	            $pickupCount['completed'] = sizeof($newResponse);
	        }else{
	            $pickupCount['completed'] = 0;
	        }
	    }
	    
	    
	    $data = array(
	        'customer_data' => $customer_data,
	        'transactions' => array(),
	        'pickup_list' => $currentMonthPickups,
	        'current_sale' => $currentMonthSale,
	        'previous_sale' => $previousMonthSale,
	        'twomonthago_sale' => $twoMonthAgoSale,
	        'channels' => array(),
	        'pickupCount' => $pickupCount,
	    );
	    
	    return view('account_overview',$data);
	}

	public function prepareEditCustomer()
	{

	    //check login
		if (session('customer.id') != null){
			$customerId = session('customer.id');
		}else{
			return redirect('/')->with('msg','คุณยังไม่ได้เข้าระบบ กรุณาเข้าสู่ระบบเพื่อใช้งาน');
		}

		Fastship::getToken($customerId);
		$customerObj = FS_Customer::get($customerId);

		$customer_data = array();
		$customer_data['ID'] = $customerObj['ID'];
		$customer_data['firstname'] = $customerObj['Firstname'];
		$customer_data['lastname'] = $customerObj['Lastname'];
		$customer_data['phonenumber'] = $customerObj['PhoneNumber'];
		$customer_data['email'] = $customerObj['Email'];
		$customer_data['company'] = $customerObj['Company'];
		$customer_data['taxid'] = $customerObj['TaxId'];
		$customer_data['address1'] = $customerObj['AddressLine1'];
		$customer_data['address2'] = $customerObj['AddressLine2'];
		$customer_data['city'] = $customerObj['City'];
		$customer_data['state'] = $customerObj['State'];
		$customer_data['postcode'] = $customerObj['Postcode'];
		$customer_data['country'] = $customerObj['Country'];
		$customer_data['group'] = $customerObj['Group'];
		$customer_data['refcode'] = $customerObj['ReferCode'];
		$customer_data['latitude'] = $customerObj['Latitude'];
		$customer_data['longitude'] = $customerObj['Longitude'];
		
		$provinces = DB::table('provinces')->select("name_in_thai as name_th","name_in_english as name_en")->orderBy('name_in_thai')->get();

		$data = array(
			'customer_data' => $customer_data,
		    'provinces' => $provinces,
		);

		return view('edit_customer',$data);
	}

	//Prepare for check rate page
	public function prepareChannelList()
	{

		if (session('customer.id') != null){
			$customerId = session('customer.id');
		}else{
			return redirect('/')->with('msg','คุณยังไม่ได้เข้าระบบ กรุณาเข้าสู่ระบบเพื่อใช้งาน');
		}

		Fastship::getToken($customerId);
		$channel_data = FS_Customer::getChannel($customerId);

		if($channel_data == false){
		  $channel_data = array();
		}

		$data = array(
			"channels" => $channel_data,
		);

		return view('channel_list',$data);
	}

	//Prepare for check rate page
	public function prepareAddChannel()
	{
	    
	    if (session('customer.id') != null){
	        $customerId = session('customer.id');
	    }else{
	        return redirect('/')->with('msg','คุณยังไม่ได้เข้าระบบ กรุณาเข้าสู่ระบบเพื่อใช้งาน');
	    }

	    $marketplaces = $this->marketplaces;
	    
	    $data = array(
	        "marketplaces" => $marketplaces,
	    );
	    
	    return view('add_channel',$data);
	}
	
	//Prepare for check rate page
	public function prepareAddChannelEbay($site)
	{
	    
	    if (session('customer.id') != null){
	        $customerId = session('customer.id');
	    }else{
	        return redirect('/')->with('msg','คุณยังไม่ได้เข้าระบบ กรุณาเข้าสู่ระบบเพื่อใช้งาน');
	    }
	    
	    $marketplaces = $this->marketplaces;

	    $marketplace = $marketplaces[$site];
	    
	    $data = array(
	        "site" => $site,
	        "marketplace" => $marketplace,
	    );
	    
	    return view('add_channel_ebay',$data);
	}
	
	//Prepare for check rate page
	public function preparePromotion()
	{

		if (session('customer.id') != null){
			$customerId = session('customer.id');
		}else{
			return redirect('/')->with('msg','คุณยังไม่ได้เข้าระบบ กรุณาเข้าสู่ระบบเพื่อใช้งาน');
		}


		Fastship::getToken($customerId);
		$customerObj = FS_Customer::get($customerId);
		
		$customer_data = array();
		$customer_data['ID'] = $customerObj['ID'];
		$customer_data['firstname'] = $customerObj['Firstname'];
		$customer_data['lastname'] = $customerObj['Lastname'];
		$customer_data['phonenumber'] = $customerObj['PhoneNumber'];
		$customer_data['email'] = $customerObj['Email'];
		$customer_data['company'] = $customerObj['Company'];
		$customer_data['taxid'] = $customerObj['TaxId'];
		$customer_data['address1'] = $customerObj['AddressLine1'];
		$customer_data['address2'] = $customerObj['AddressLine2'];
		$customer_data['city'] = $customerObj['City'];
		$customer_data['state'] = $customerObj['State'];
		$customer_data['postcode'] = $customerObj['Postcode'];
		$customer_data['country'] = $customerObj['Country'];
		$customer_data['group'] = $customerObj['Group'];
		$customer_data['refcode'] = $customerObj['ReferCode'];
		$customer_data['latitude'] = $customerObj['Latitude'];
		$customer_data['longitude'] = $customerObj['Longitude'];
		

		$data = array(
				"customer_data" => $customer_data,
		);

		return view('promotion',$data);
	}

	public function prepareLoginWithCode($ref="",Request $request)
	{
	    
	    //check ref
	    $request->session()->put('login.ref', $ref);

	    //prepare to view
	    $data = array();
	    
	    return view('login',$data);
	}
	
	public function prepareRegister($ref="",Request $request)
	{

	    $provinces = DB::table('provinces')->select("name_in_thai as name_th","name_in_english as name_en")->orderBy('name_in_thai')->get();
	    
	    //check ref
	    if($request->has('ref')){
	        $code = $request->input('ref');
	    }else{
	        $code = $ref;
	    }
	    
	    //default
	    $defaultFirstname = "";
	    $defaultLastname = "";
	    $defaultEmail = "";
	    $defaultTelephone = "";
	    $marketplaceRefId = "";
	    
	    //if marketplace
	    if($request->has('mkp_type')){
	        
	        //get marketplace type
	        $code = $request->input('mkp_type');
	        
	        if($request->has('mkp_id')){
	            $marketplaceRefId = $request->input('mkp_id');
	        }
	        
	        if($request->has('mkp_firstname')){
	            $defaultFirstname = $request->input('mkp_firstname');
	        }
	        
	        if($request->has('mkp_lastname')){
	            $defaultLastname = $request->input('mkp_lastname');
	        }
	        
	        if($request->has('mkp_email')){
	            $defaultEmail = $request->input('mkp_email');
	        }
	        
	        if($request->has('mkp_phone')){
	            $defaultTelephone = $request->input('mkp_phone');
	        }

	    }
	    
	    //prepare to view
	    $data = array(
	        'provinces' => $provinces,
	        'code' => $code,
	        'marketplaceRefId' => $marketplaceRefId,
	        'default' => array(
	            "firstname" => $defaultFirstname,
	            "lastname" => $defaultLastname,
	            "email" => $defaultEmail,
	            "telephone" => $defaultTelephone,
	        ),
	    );
	    
	    return view('register',$data);
	}
	
	public function prepareRegisterLine($ref="",Request $request)
	{
	    
	    $provinces = DB::table('provinces')->select("name_in_thai as name_th","name_in_english as name_en")->orderBy('name_in_thai')->get();
	    
	    //check ref
	    if($request->has('ref')){
	        $code = $request->input('ref');
	    }else{
	        $code = $ref;
	    }
	    
	    
	    if($request->has('line_id')){
	        $lineId = $request->input('line_id');
	        
	        //genearate password
	        $generatePassword = uniqid();
	        
	    }else{
	        $lineId = "";
	        $generatePassword = "";
	    }
	    
	    //default
	    $defaultFirstname = "";
	    $defaultLastname = "";
	    $defaultEmail = "";
	    $defaultTelephone = "";
	    $marketplaceRefId = "";
	    
	    //if marketplace
	    if($request->has('mkp_type')){
	        
	        //get marketplace type
	        $code = $request->input('mkp_type');
	        
	        if($request->has('mkp_id')){
	            $marketplaceRefId = $request->input('mkp_id');
	        }
	        
	        if($request->has('mkp_firstname')){
	            $defaultFirstname = $request->input('mkp_firstname');
	        }
	        
	        if($request->has('mkp_lastname')){
	            $defaultLastname = $request->input('mkp_lastname');
	        }
	        
	        if($request->has('mkp_email')){
	            $defaultEmail = $request->input('mkp_email');
	        }
	        
	        if($request->has('mkp_phone')){
	            $defaultTelephone = $request->input('mkp_phone');
	        }
	        
	    }
	    
	    //prepare to view
	    $data = array(
	        'provinces' => $provinces,
	        'code' => $code,
	        'lineId' => $lineId,
	        'marketplaceRefId' => $marketplaceRefId,
	        'default' => array(
	            "firstname" => $defaultFirstname,
	            "lastname" => $defaultLastname,
	            "email" => $defaultEmail,
	            "telephone" => $defaultTelephone,
	            "password" => $generatePassword,
	        ),
	    );
	    
	    return view('register_line',$data);
	    
	}
	
	public function prepareRegisterWithCode($ref="")
	{
	    
	    $provinces = DB::table('provinces')->select("name_in_thai as name_th","name_in_english as name_en")->orderBy('name_in_thai')->get();
	    
	    //default
	    $defaultFirstname = "";
	    $defaultLastname = "";
	    $defaultEmail = "";
	    $defaultTelephone = "";
	    $marketplaceRefId = "";
	    
	    $data = array(
	        'provinces' => $provinces,
	        'ref' => $ref,
	        'marketplaceRefId' => $marketplaceRefId,
	        'default' => array(
	            "firstname" => $defaultFirstname,
	            "lastname" => $defaultLastname,
	            "email" => $defaultEmail,
	            "telephone" => $defaultTelephone,
	        ),
	    );
	    
	    return view('register',$data);
	}
	
	public function prepareRegisterFacebook(Request $request)
	{
	    
	    $provinces = DB::table('provinces')->select("name_in_thai as name_th","name_in_english as name_en")->orderBy('name_in_thai')->get();

	    //default
	    $defaultFirstname = "";
	    $defaultLastname = "";
	    $defaultEmail = "";
	    $defaultTelephone = "";
	    $marketplaceRefId = "";
	    
	    
	    //prepare to view
	    $data = array(
	        'provinces' => $provinces,
	    );
	    
	    return view('register_facebook',$data);
	}
	
	public function prepareRegisterPayoneer(Request $request)
	{
	    
	    $provinces = DB::table('provinces')->select("name_in_thai as name_th","name_in_english as name_en")->orderBy('name_in_thai')->get();
	    
	    //default
	    $defaultFirstname = "";
	    $defaultLastname = "";
	    $defaultEmail = "";
	    $defaultTelephone = "";
	    $marketplaceRefId = "";
	    
	    
	    //prepare to view
	    $data = array(
	        'provinces' => $provinces,
	    );
	    
	    return view('register_payoneer',$data);
	}
	
	//Prepare for case list
	public function prepareCaseList()
	{
	    
	    if (session('customer.id') != null){
	        $customerId = session('customer.id');
	    }else{
	        return redirect('/')->with('msg','คุณยังไม่ได้เข้าระบบ กรุณาเข้าสู่ระบบเพื่อใช้งาน');
	    }

	    //get customer
	    Fastship::getToken($customerId);
	    $customerObj = FS_Customer::get($customerId);
	    
	    //get params
	    $accountId = $customerObj["ZohoAccountId"];
	    
	    //get cases
	    $args = array(
	        "account_id" => $accountId,
	    );
	    $cases = ZohoApiV2::searchCases($args);
	    $data = array(
	        "cases" => $cases,
	    );
	    
	    return view('case_list',$data);
	}
	
	//Prepare for check rate page
	public function prepareAddCase()
	{
	    
	    if (session('customer.id') != null){
	        $customerId = session('customer.id');
	    }else{
	        return redirect('/')->with('msg','คุณยังไม่ได้เข้าระบบ กรุณาเข้าสู่ระบบเพื่อใช้งาน');
	    }
	    
	    //get shipment sent
	    Fastship::getToken($customerId);
	    $searchDetails = array(
	        "NoStatuses" => array('Pending','Imported','Cancelled','15','16','17','25'),
	    );
	    $response = FS_Shipment::fullsearch($searchDetails);
	    if(isset($response['data'])){
	        $shipments = $response['data'];
	    }else{
	        $shipments = array();
	    }

	    //get 
	    $data = array(
	        "shipments" => $shipments,
	    );
	    
	    return view('create_case',$data);
	}
	
	
	//Update Customer Info
	public function update(Request $request)
	{
		
	    if (session('customer.id') != null){
			$customerId = session('customer.id');
		}else{
			return redirect('/')->with('msg','คุณยังไม่ได้เข้าระบบ กรุณาเข้าสู่ระบบเพื่อใช้งาน');
		}
		$this->validate($request, [
				'firstname' => 'required',
				'telephone' => 'required',
				'address1' => 'required',
				'city' => 'required',
				'state' => 'required',
				'postcode' => 'required',
		]);

		$data=array();
		$data['firstname'] = $request->input('firstname');
		$data['lastname'] = $request->input('lastname');
		$data['telephone'] = $request->input('telephone');
		$data['company'] = $request->input('company');
		$data['taxid'] = $request->input('taxid');
		$data['address1'] = $request->input('address1');
		$data['address2'] = $request->input('address2');
		$data['city'] = $request->input('city');
		$data['state'] = $request->input('state');
		$data['postcode'] = $request->input('postcode');
		$data['latitude'] = $request->input('latitude');
		$data['longitude'] = $request->input('longitude');

		//update to API
		Fastship::getToken($customerId);
		$updateDetails = array(
		    'Firstname' => $data['firstname'],
		    'Lastname' => $data['lastname'],
		    'PhoneNumber' => $data['telephone'],
		    'Company' => $data['company'],
		    'TaxId' => $data['taxid'],
		    'AddressLine1' => $data['address1'],
		    'AddressLine2' => $data['address2'],
		    'City' => $data['city'],
		    'State' => $data['state'],
		    'PostCode' => $data['postcode'],
		    'Latitude' => $data['latitude'],
		    'Longitude' => $data['longitude'],
		);
		$updateCompleted = FS_Customer::update($updateDetails);
		
		if($updateCompleted){
		    
    		//update to db
    		$update = DB::table('customer')
    		->where('CUST_ID', $customerId)
    		->update(
				[
					'CUST_FIRSTNAME' => $data['firstname'],
					'CUST_LASTNAME' => $data['lastname'],
					'CUST_TEL' => $data['telephone'],
					'CUST_COMPANY' => $data['company'],
					'CUST_TAXID' => $data['taxid'],
					'CUST_ADDR1' => $data['address1'],
					'CUST_ADDR2' => $data['address2'],
					'CUST_CITY' => $data['city'],
					'CUST_STATE' => $data['state'],
					'CUST_POSTCODE' => $data['postcode'],
					'CUST_LATITUDE' => $data['latitude'],
					'CUST_LONGITUDE' => $data['longitude'],
					'UPDATE_DATETIME' =>  date('Y-m-d H:i:s')
				]
			);
    		
    		if($update){

    		    $customerObj = FS_Customer::get($customerId);
    		    if(isset($customerObj['ZohoContactId']) && $customerObj['ZohoContactId'] != ""){
    		        $zohoId = $customerObj['ZohoContactId'];
    		    }else{
    		        $zohoId = $customerObj['ZohoLeadId'];
    		    }
    		    $params = array(
    		        'cust_id' => $customerId,
    		        'zoho_id' => $zohoId,
    		        'firstname' => $data['firstname'],
    		        'lastname' => $data['lastname'],
    		        'phone' => $data['telephone'],
    		        'company' => $data['company'],
    		        'taxid' => $data['taxid'],
    		        'address1' => $data['address1'],
    		        'address2' => $data['address2'],
    		        'city' => $data['city'],
    		        'state' => $data['state'],
    		        'postcode' => $data['postcode'],
    		    );
    		    if($customerObj['ZohoContactId'] != ""){
    		        
    		        //$test = ZohoManager::updateContact($params);
    		        ZohoApiV2::updateContact($params);
    		    }else{
    		        //$test = ZohoManager::updateLead($params);
    		        ZohoApiV2::updateLead($params);
    		    }

    			if($request->input('return') != ""){
    				return redirect('/'.$request->input('return'))->with('msg','ระบบได้ทำการอัปเดทข้อมูล เรียบร้อยแล้ว')->with('msg-type','success');
    			}else{
    				return redirect('/myaccount')->with('msg','ระบบได้ทำการอัปเดทข้อมูล เรียบร้อยแล้ว')->with('msg-type','success');
    			}
    		}else{
    			return redirect('/edit_customer')->with('msg','อัปเดทข้อมูลไม่สำเร็จ กรุณาลองใหม่อีกครั้ง2');
    		}
		}else{
		    return redirect('/edit_customer')->with('msg','อัปเดทข้อมูลไม่สำเร็จ กรุณาลองใหม่อีกครั้ง1');
		}
	}

	//Change Password by Customer
	public function changePassword(Request $request) //post
	{
		if (session('customer.id') != null){
			$customerId = session('customer.id');
		}else{
			return redirect('/')->with('msg','คุณยังไม่ได้เข้าระบบ กรุณาเข้าสู่ระบบเพื่อใช้งาน');
		}
		
		$this->validate($request, [
			'currentpassword' => 'required',
			'newcurrentpassword' => 'required',
			'repassword' => 'required',
		]);
		$currentpassword = $request->input('currentpassword');;
		$newcurrentpassword = $request->input('newcurrentpassword');;
		$repassword = $request->input('repassword');;
		if($newcurrentpassword == $repassword){
			
		    //update to API
		    Fastship::getToken($customerId);
		    $updateDetails = array(
		        'Password' => $newcurrentpassword,
		    );
		    $updateCompleted = FS_Customer::changePassword($updateDetails);

		    if($updateCompleted){
		    
    			//Check validate email
    			$validatePassword = DB::table('customer')
    			->select("CUST_PASSWORD")
    			->where('CUST_ID', $customerId)
    			->where("IS_ACTIVE",1)
    			->first();
    
    			$currentPassDB = $validatePassword->CUST_PASSWORD;
    			$converter = new Encryption;
    			$oldPassword = $converter->encode($currentpassword);
    			$newPassword = $converter->encode($newcurrentpassword);
    			if($currentPassDB === $oldPassword){
    				$update = DB::table('customer')
    				->where('CUST_ID', $customerId)
    				->update(
    						[
    								'CUST_PASSWORD' => $newPassword,
    								'UPDATE_DATETIME' =>  date('Y-m-d H:i:s')
    						]
    						);
    				if($update){
    					return redirect('/myaccount')->with('msg','ระบบได้ทำการเปลี่ยนรหัสผ่าน เรียบร้อยแล้ว')->with('msg-type','success');
    				}else{
    					return redirect('/change_password')->with('msg','เปลี่ยนรหัสผ่านไม่สำเร็จ กรุณาลองใหม่อีกครั้ง');
    				}
    			}else{
    				return redirect('/change_password')->with('msg','รหัสผ่านไม่ถูกต้อง กรุณาลองใหม่อีกครั้ง');
    			}
		    }
		    
		}else{
			return redirect('/change_password')->with('msg','รหัสผ่านไม่ถูกต้อง กรุณาลองใหม่อีกครั้ง2');
		}
	}
	
	//Reset Password
	public function resetPassword(Request $request) //post
	{
		
		$this->validate($request, [
			'email' => 'required',
		]);
		$email = $request->input('email');;

		$customerObj = DB::table('customer')->whereRaw('LOWER(CUST_EMAIL) = ?', strtolower($email))->where("IS_ACTIVE",1)->first();
		if(!isset($customerObj)){
		    return redirect('/forget_password')->with('msg','ไม่พบอีเมล์ในระบบ กรุณาลองใหม่อีกครั้ง');
		}
		
		$randomPassword = generateRandomString(6);
		$converter = new Encryption;
		$password = $converter->encode($randomPassword);
		$customerObj->CUST_PASSWORD = $password;
		
		if($email != "" && isset($customerObj->CUST_ID) && $customerObj->CUST_ID > 0 ){
		
			$update = DB::table('customer')
				->where('CUST_ID', $customerObj->CUST_ID)
				->update([
					'CUST_PASSWORD' => $password
				]);
			
			//update to API
			Fastship::getToken($customerObj->CUST_ID);
			$updateDetails = array(
			    'Password' => $randomPassword,
			);
			$updateCompleted = FS_Customer::changePassword($updateDetails);
				
			// ###### send email #####
			$createDetails = array(
				"Email" => $email,
				"Password" => $randomPassword,
				"Firstname" => $customerObj->CUST_FIRSTNAME,
			);
			$email_data = array(
				'email' => $email,
				'customerData' => $createDetails,
			);
				
			Mail::send('email/reset_password',$email_data,function($message) use ($email_data){
				$message->to($email_data['email']);
				$message->bcc(['thachie@tuff.co.th']);
				$message->from('cs@fastship.co', 'FastShip');
				$message->subject('FastShip - รหัสของคุณถูกรีเซตแล้ว ('. $email_data['email'] .")");
			});		
			// ###### send email #####
		
			return redirect('/login')->with('msg','ระบบได้ทำการรีเซตรหัสผ่าน เรียบร้อยแล้ว')->with('msg-type','success');
		
		}else{
		
			return redirect('/forget_password')->with('msg','อีเมล์ไม่ถูกต้อง กรุณาลองใหม่อีกครั้ง');
		}

	}

	//Remove Marketplace Channel
	public function removeChannel(Request $request) //post
	{
	    if (session('customer.id') != null){
	        $customerId = session('customer.id');
	    }else{
	        return redirect('/')->with('msg','คุณยังไม่ได้เข้าระบบ กรุณาเข้าสู่ระบบเพื่อใช้งาน');
	    }
	    
	    //validate data
	    if($request->input('cc_id') == ""){
	        return redirect('/channel_list')->with('msg','ไม่พบช่องทางที่ต้องการลบ');
	    }
	    
	    $ccId = $request->input('cc_id');

	    Fastship::getToken($customerId);
	    $delete = FS_Customer::removeChannel($ccId);
	    
	    if($delete){
	        return redirect('/channel_list')->with('msg','ลบช่องทางใหม่เรียบร้อยแล้ว')->with('msg-type','success');
	    }else{
	        return redirect('/channel_list')->with('msg','เกิดปัญหาในการเชื่อมต่อช่องทางดังกล่าว');
	    }
	    
	}
	
	//Prepare for check rate page
	public function testRegsiterEmail()
	{
	
		if (session('customer.id') != null){
			$customerId = session('customer.id');
		}else{
			return redirect('/')->with('msg','คุณยังไม่ได้เข้าระบบ กรุณาเข้าสู่ระบบเพื่อใช้งาน');
		}
	
		Fastship::getToken($customerId);
		$customerObj = FS_Customer::get($customerId);
		
		//$customerObj = DB::table('customer')->where("CUST_ID",$customerId)->where("IS_ACTIVE",1)->first();
	
		$customer_data = array();
		$customer_data['Firstname'] = $customerObj['Firstname'];
		$customer_data['Lastname'] = $customerObj['Lastnames'];
		$customer_data['PhoneNumber'] = $customerObj['PhoneNumber'];
		$customer_data['Email'] = $customerObj['Email'];
		
		$customer_data['Password'] = "abc123";
		$customer_data['ReferCode'] = $customerObj['ReferCode'];
		$customer_data['Group'] = $customerObj['Group'];

	
		
		//alert($creditCards);
		$data = array(
			'customerData' => $customer_data,
		);
	
		return view('email/register',$data);
	}
	
	//Bulk Regenerate New Password
	public function regenPass($id)
	{
	
	    $customerObj = DB::table('customer')->where("CUST_ID",$id)->get();
		$converter = new Encryption;
		
		foreach($customerObj as $customerObj){
			$password = $customerObj->CUST_PASSWORD;
			$encoded = $converter->encode($password);
			$decoded = $converter->decode($password);
			
			print_r($decoded);
			echo "<hr />";

		}
		die();

	}

	
	//Add Zoho Case
	public function createCase(Request $request) //post
	{
	    if (session('customer.id') != null){
	        $customerId = session('customer.id');
	    }else{
	        return redirect('/')->with('msg','คุณยังไม่ได้เข้าระบบ กรุณาเข้าสู่ระบบเพื่อใช้งาน');
	    }
	    
	    //validate data
	    if($request->input('subject') == ""){
	        return redirect('/add_case')->with('msg','กรุณากรอกหัวข้อ');
	    }
	    if($request->input('priority') == ""){
	        return redirect('/add_case')->with('msg','กรุณาเลือกระดับความสำคัญ');
	    }
	    if($request->input('detail') == ""){
	        return redirect('/add_case')->with('msg','กรุณากรอกรายละเอียด');
	    }
 
	    //get customer
	    Fastship::getToken($customerId);
	    $customerObj = FS_Customer::get($customerId);
	    
	    //get params
	    $accountId = $customerObj["ZohoAccountId"];

	    //create zoho case
	    $params = array(
	        'subject' => $request->input('subject'),
	        'priority' => $request->input('priority'),
	        'description' => $request->input('detail'),
	        'account_id' => $accountId,
	        'ship_id' => $request->input('ship_id'),
	        'category' => $request->input('category'),
	    );
	    $insert = ZohoApiV2::createCase($params);
	    
	    if($insert){
	        return redirect('/case_list')->with('msg','เพิ่ม Case ใหม่เรียบร้อยแล้ว')->with('msg-type','success');
	    }else{
	        return back()->with('msg','เกิดปัญหาในการสร้าง Case');
	    }
	    
	}
	
	//Logout
	public function logout()
	{

		Session::flush();

		return redirect('/');
		
	}


	public function connectLine(Request $request)
	{

	    if (session('customer.id') != null){
	        $customerId = session('customer.id');
	    }else{
	        return redirect('/')->with('msg','คุณยังไม่ได้เข้าระบบ กรุณาเข้าสู่ระบบเพื่อใช้งาน');
	    }
	    
	    $code = $request->input('code');
	    
	    $apiParam = array(
	        "grant_type" => "authorization_code",
	        "code" => $code,
	        "redirect_uri" => "https://app.fastship.co/liff/connectline",
	        "client_id" => 1653805752,
	        "client_secret" => "51553ab61c328d346ee3e49a898cbe0a",
	    );

	    $url = 'https://api.line.me/oauth2/v2.1/token';

	    $Response1 = callAPI_Line('POST', $url, http_build_query($apiParam));
	    $res1 = json_decode($Response1,true);
	    if(!isset($res1)){
	        return redirect('/')->with('msg','ไม่สามารถเชื่อมต่อได้');
	    }

	    $apiParam = array(
	        "id_token" => $res1['id_token'],
	        "client_id" => 1653805752,
	    );
	    
	    $url = 'https://api.line.me/oauth2/v2.1/verify';

	    $Response2 = callAPI_Line('POST', $url, http_build_query($apiParam));
	    $res2 = json_decode($Response2,true);
	    if(!isset($res2)){
	        return redirect('/')->with('msg','ไม่สามารถเชื่อมต่อได้');
	    }
	    $userId = $res2['sub'];

	    if(!$userId){
	        return redirect('/')->with('msg','ไม่สามารถเชื่อมต่อได้');
	    }
	    
	    Fastship::getToken($customerId);
	    $checkLineId = FS_Customer::checkLineUserId($userId);
	    
	    if($checkLineId > 0){
	        return redirect('/')->with('msg','line นี้ถูกเชื่อมต่อแล้ว');
	    }
	    
	    $params = array(
	        "LineUserId" => $userId,
	    );
	    FS_Customer::update($params);

	    return redirect('/')->with('msg','เชื่อมต่อเรียบร้อยแล้ว')->with('msg-type','success');
	    
	}
	
	public function loginLine(Request $request)
	{
	    
	    //params
	    $code = $request->input('code');
	    $state = $request->input('state');
	    
	    $apiParam = array(
	        "grant_type" => "authorization_code",
	        "code" => $code,
	        "redirect_uri" => "https://app.fastship.co/liff/loginline",
	        "client_id" => 1653844645,
	        "client_secret" => "4d5a1865c03ed8839a39c00be9563498",
	    );
	    
	    $url = 'https://api.line.me/oauth2/v2.1/token';
	    
	    $Response1 = callAPI_Line('POST', $url, http_build_query($apiParam));
	    $res1 = json_decode($Response1,true);

	    if(!isset($res1) || !isset($res1['id_token'])){
	        return redirect('/login')->with('msg','ไม่สามารถเชื่อมต่อได้');
	    }
	    
	    $apiParam = array(
	        "id_token" => $res1['id_token'],
	        "client_id" => 1653844645,
	    );
	    $url = 'https://api.line.me/oauth2/v2.1/verify';
	    
	    $Response2 = callAPI_Line('POST', $url, http_build_query($apiParam));
	    $res2 = json_decode($Response2,true);
	    
	    if(!isset($res2)){
	        return redirect('/login')->with('msg','ไม่สามารถเชื่อมต่อได้');
	    }
	    $userId = $res2['sub'];
	    
	    if(!$userId){
	        return redirect('/login')->with('msg','ไม่สามารถเชื่อมต่อได้');
	    }
	    
	    Fastship::getToken($customerId);
	    $checkLineId = FS_Customer::checkLineUserId($userId);
    
        if($checkLineId > 0){
            
            //existed customer
            Fastship::getToken($checkLineId);
            $customer = FS_Customer::get($checkLineId);
            
        }else{
            
            //new customer
            if(session('login.ref') != null){
                $code = session('login.ref');
            }else{
                $code = "";
            }
            return redirect('register_line/' . $code . '?line_id='.$userId);
            
        }

	    //save to session
	    $request->session()->put('customer.id', $checkLineId);
	    $request->session()->put('customer.name', $customer['Firstname']);
	    $request->session()->put('customer.line', $customer['LineId']);
	    
	    //get shipment in cart
	    $searchDetails = array("Status" => "Pending");
	    $response = FS_Shipment::search($searchDetails);
	    if($response === false){
	        $shipment_data = 0;
	    }else{
	        if(sizeof($response) > 0 && is_array($response)){
	            $shipment_data = sizeof($response);
	        }else{
	            $shipment_data = 0;
	        }
	    }
	    $request->session()->put('pending.shipment', $shipment_data);
	    
	    return redirect('calculate_shipment_rate');

	}

}
