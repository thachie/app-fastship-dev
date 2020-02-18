<?php

namespace App\Http\Controllers\Partner;

use DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Lib\Fastship\FS_Customer;
use App\Lib\Fastship\FS_Error;
use App\Lib\Fastship\Fastship;
use App\Lib\Fastship\FS_Shipment;
use App\Lib\Fastship\FS_Pickup;
use App\Lib\TradeGov\TradeGovManager;
use App\Lib\Encryption;
use Session;
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

    public function index()
    {
        if (session('customer.id') != null){
            $customerId = session('customer.id');
            return redirect('partner/create-shipment');
        }else{
            return redirect('partner/login')->with('msg','คุณยังไม่ได้เข้าระบบ กรุณาเข้าสู่ระบบเพื่อใช้งาน');
        }
    }

    public function Login(Request $request)
    {
        //alert($request->all());die();

        if (session('customer.id') != null){
            $customerId = session('customer.id');
            //$customer = session()->get('keyLogin');
            return redirect('partner/create-shipment');
        }else{
            $this->validate($request, [
                'username' => 'required|email',
                'password' => 'required',
                'display_page' => 'required',
            ]);
            $email = $request->input('username');
            $password = $request->input('password');
            $display = $request->input('display_page');
            
            //call api login
            Fastship::getToken();
            try{
                $params = array(
                    "Email" => $email,
                    "Password" => $password,
                );
                $customerId = FS_Customer::login($params);
            }catch (Exception $e){
                return view('partner/login');
                //$msg = 'Username or Password ไม่ถูกต้องกรุณาลองใหม่อีกครั้ง';
                //return redirect('fastbox/customer/login-form')->with('msg',$msg);
            }

            //call api get
            Fastship::getToken($customerId);
            try{
                $res = FS_Customer::get($customerId);
                if ($res === false) {
                    return view('partner/login');
                    //$msg = 'Username or Password ไม่ถูกต้องกรุณาลองใหม่อีกครั้ง';
                    //return redirect('fastbox/customer/login-form')->with('msg',$msg);
                }else{
                    //Verify customer
                    
                   
                    $res = FS_Customer::get($customerId);
                    $request->session()->put('customer.id', $res['ID']);
                    $request->session()->put('customer.name', $res['Firstname']);
                    //$aa = Session::put('variableName', $res);
                    $customerId = Session::get('customer.id');
                    //alert($res);
                    //alert($customerId);
                    if ($display == 'front') {
                        return redirect('partner/create-shipment');
                    }elseif ($display == 'back'){
                        return redirect('partner/pickup');
                    }
                }
            }catch (Exception $e){
                return view('partner/login');
                //$msg = 'Username or Password ไม่ถูกต้องกรุณาลองใหม่อีกครั้ง';
                //return redirect('fastbox/customer/login-form')->with('msg',$msg);
            }
        }
    }

    public function registerPartner(Request $request)
    {
        alert($request->all());die();
        /*[firstname] => anusak
        [lastname] => jitchot
        [email] => anusak2527@gmail.com
        [telephone] => 0858884698
        [password] => 123456
        [c_password] => 123456
        [addressLine1] => 88/999 moo.3
        [addressLine2] => Banglen
        [city] => Bangyai
        [state] => Nonthaburi
        [postcode] => 11140
        [referal_code] => 123456*/

        

        //validate
        $this->validate($request, [
                'firstname' => 'required',
                'lastname' => 'required',
                'email' => 'required',
                'telephone' => 'required',
                'password' => 'required',
                'c_password' => 'required',
                'addressLine1' => 'required',
                'city' => 'required',
                'state' => 'required',
                'postcode' => 'required',
        ]);

        //prepare data
        $data=array();
        $data['firstname'] = $request->input('firstname');
        $data['lastname'] = $request->input('lastname');
        $data['email'] = strtolower($request->input('email'));
        $data['telephone'] = $request->input('telephone');
        $data['password'] = $request->input('password');
        $data['c_password'] = $request->input('c_password');
        $data['addressLine1'] = strtoupper($request->input('addressLine1'));
        $data['addressLine2'] = strtoupper($request->input('addressLine2'));
        $data['city'] = strtoupper($request->input('city'));
        $data['state'] = strtoupper($request->input('state'));
        $data['postcode'] = strtoupper($request->input('postcode'));
        $data['country'] = 'THA';
        $data['referal_code'] = strtoupper($request->input('referal_code'));

        //check password
        if($data['password'] == $data['c_password']){
                
            //Check existance email
            $validateEmail = DB::table('customer')
            ->select("CUST_ID","CUST_EMAIL")
            ->whereRaw('LOWER(CUST_EMAIL) = ?', strtolower($data['email']))
            ->where("IS_ACTIVE",1)
            ->first();
                
            if(!empty($validateEmail)){
                return redirect('/')->with('msg','อีเมลล์นี้มีผู้ใช้งานแล้ว');
            }else{

                $converter = new Encryption;
                $password = $converter->encode($data['password']);
                $decoded = $converter->decode($password);
                //alert($password);alert($decoded);

                //check referal code
                $class = "Standard";
                if($data['referal_code'] != ""){
                    $promoObj = DB::table('promo_code')->where("CODE_NAME",$data['referal_code'])
                    ->where("CODE_TYPE",'Referal')
                    ->where("CODE_DISCOUNTTYPE",'Class')
                    ->where("IS_ACTIVE",1)->first();
                    if($promoObj != null){
                        $class = $promoObj->CODE_DISCOUNTAMOUNT;
                    }
                }

                //insert to ZOHO
                $params = array(
                    'First_Name' => $data['firstname'],
                    'Last_Name' => $data['lastname'],
                    'Email' => $data['email'],
                    'Company' => '',
                    'LineID' => '',
                    'Mobile' => $data['telephone'],
                    'Street' => '',
                    'City' => '',
                    'State' => '',
                    'Zip_Code' => '',
                    'Country' => '',
                    'RefCode' => $data['referal_code'],
                );
                ZohoManager::createLead($params);


                //insert to API
                Fastship::getToken();
                /*$createDetails = array(
                        'Firstname' => $data['firstname'],
                        'Lastname' => $data['lastname'],
                        'PhoneNumber' => $data['telephone'],
                        'Email' => $data['email'],
                        'Password' => $data['password'],
                        'ReferCode' => $data['referal_code'],
                        'Group' => 'Standard',
                );*/
                $createDetails = array(
                    'Firstname' => $data['firstname'],
                    'Lastname' => $data['lastname'],
                    'PhoneNumber' => $data['telephone'],
                    'Email' => $data['email'],
                    'Password' => $data['password'],
                    'ReferCode' => $data['referal_code'],
                    'Group' => 'Standard',
                    'IsReseller' => 1,
                    'AddressLine1' => $data['addressLine1'],
                    'AddressLine2' => $data['addressLine2'],
                    'City' => $data['city'],
                    'State' => $data['state'],
                    'Postcode' => $data['postcode'],
                    'Country' => 'THA',
                );

                $customerId = FS_Customer::create($createDetails);

                //insert to DB
                $insert = DB::table('partner')->insert(
                        [
                                'CUST_ID' => $customerId,
                                'CUST_LEADSOURCE' => '5',
                                'BP_ID' => '0',
                                'CUST_FIRSTNAME' => $data['firstname'],
                                'CUST_LASTNAME' => $data['lastname'],
                                'CUST_COMPANY' => '',
                                'CUST_LINEID' => '',
                                'CUST_EMAIL' => $data['email'],
                                'CUST_TEL' => $data['telephone'],
                                'CUST_ADDR1' => $data['addressLine1'],
                                'CUST_ADDR2' => $data['addressLine2'],
                                'CUST_CITY' => $data['city'],
                                'CUST_STATE' => $data['state'],
                                'CUST_POSTCODE' => $data['postcode'],
                                'CNTRY_CODE' => 'THA',
                                'CUST_PASSWORD' => $password,
                                'CUST_APITOKEN' => '',
                                'CUST_GROUP' => $class,
                                'CUST_ROLE' => 'CUSTOMER',
                                'IS_RESELLER' => '1',
                                'IS_SELLER' => '1',
                                'IS_ADMIN' => '0',
                                'IS_SUPPLIER' => '0',
                                'CUST_PICKFOR' => 'INTERNATIONAL',
                                'CUST_REFERCODE' => $data['referal_code'],
                                'CUST_PAYPAL' => '',
                                'CUST_MARGIN' => '25',
                                'IS_APPROVE' => 1,
                                'IS_ACTIVE' => 1,
                                'CREATE_DATETIME' => date('Y-m-d H:i:s')
                        ]
                        );

                if($insert){
                        
                    //send email
                    $email_data = array(
                            'firstname' => $data['firstname'],
                            'email' => $data['email'],
                            'customerData' => $createDetails,
                    );
                        
                    Mail::send('email/register',$email_data,function($message) use ($email_data){
                        $message->to($email_data['email']);
                        $message->bcc(['oak@tuff.co.th','thachie@tuff.co.th']);
                        $message->from('info@fastship.co', 'FastShip');
                        $message->subject('FastShip - ยินดีต้อนรับคุณ'. $email_data['firstname'] ." บัญชีของคุณถูกสร้างเรียบร้อยแล้ว");
                    });
                            
                        return redirect('/register_complete');
                }else{
                    return redirect('/')->with('msg','Register ไม่สำเร็จ');
                }
            }
        }else{
            return redirect('/')->with('msg','Password not matches!');
        }
    }

    public function Logout(Request $request)
    {
        $customerId = session()->get('customer.id');
        alert($customerId);//die();


        Session::forget('customer.id');
        if(!Session::has('customer.id'))
        {
            return redirect('partner/login');
        }
        /*$customer = session()->get('keyLogin');
        $customerId = $customer['customerId'];
        $update = DB::table('customer_fastbox')
            ->where('CUST_ID', $customerId)
            ->update(
            [
                'STATUS_LOGIN' => 0,
                'LOGOUT_DATE' => date("Y-m-d H:i:s")
            ]
        );

        Session::forget('keyLogin');
        if(!Session::has('keyLogin'))
        {
            return redirect('partner/login');
        }*/
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


    ###### Ex Fastbox ######
    /*public function formLogin()
    {
        if (session('keyLogin') != null){
            //$customerId = session('customer.id');
            $customer = session()->get('keyLogin');
            return redirect('fastbox/catalog-list/');
        }else{
            return view('customer/fastbox-login-form');
            //return redirect('fastbox/customer/login-form')->with('msg',$msg);
        }
    }

    public function Login(Request $request)
    {
        if (session('keyLogin') != null){
            //$customerId = session('customer.id');
            $customer = session()->get('keyLogin');
            return redirect('fastbox/catalog-list/');
        }else{
            $this->validate($request, [
                'email' => 'required|email',
                'password' => 'required',
            ]);
            $email = $request->input('email');
            $password = $request->input('password');
            
            //call api login
            Fastship::getToken();
            try{
                $params = array(
                    "Email" => $email,
                    "Password" => $password,
                );
                $customerId = FS_Customer::login($params);
            }catch (Exception $e){
                return view('customer/fastbox-login-form');
                //$msg = 'Username or Password ไม่ถูกต้องกรุณาลองใหม่อีกครั้ง';
                //return redirect('fastbox/customer/login-form')->with('msg',$msg);
            }

            //call api get
            Fastship::getToken($customerId);
            try{
                $res = FS_Customer::get($customerId);
                if ($res === false) {
                    return view('customer/fastbox-login-form');
                    //$msg = 'Username or Password ไม่ถูกต้องกรุณาลองใหม่อีกครั้ง';
                    //return redirect('fastbox/customer/login-form')->with('msg',$msg);
                }else{
                    //Verify customer
                    $CUST_ID = $res['ID'];
                    $getCustomer = DB::table('customer_fastbox')
                        ->select("CUST_ID")
                        ->where('CUST_ID', $CUST_ID)
                        ->first();

                    if (empty($getCustomer)) {
                        $FIRST_NAME = $res['Firstname'];
                        $LAST_NAME = $res['Lastname'];
                        $EMAIL = $res['Email'];
                        $PASSWORD = $password;
                        $COMPANY = $res['Company'];
                        $ADDRESS_1 = $res['AddressLine1'];
                        $ADDRESS_2 = $res['AddressLine2'];
                        $CITY = $res['City'];
                        $STATE = $res['State'];
                        $POSTCODE = $res['Postcode'];
                        $COUNTRY = $res['Country'];
                        $STATUS_LOGIN = 1;

                        $insert = DB::table('customer_fastbox')->insert([
                            'CUST_ID' => $CUST_ID,
                            'FIRST_NAME' => $FIRST_NAME,
                            'LAST_NAME' => $LAST_NAME,
                            'EMAIL' => $EMAIL,
                            'PASSWORD' => $PASSWORD,
                            'COMPANY' => $COMPANY,
                            'ADDRESS_1' => $ADDRESS_1,
                            'ADDRESS_2' => $ADDRESS_2,
                            'CITY' => $CITY,
                            'STATE' => $STATE,
                            'POSTCODE' => $POSTCODE,
                            'COUNTRY' => $COUNTRY,
                            'STATUS_LOGIN' => $STATUS_LOGIN,
                            'LOGIN_DATE' => date("Y-m-d H:i:s")
                        ]);
                    }else{
                        $update_sku = DB::table('customer_fastbox')
                            ->where('CUST_ID', $CUST_ID)
                            ->update(
                            [
                                'STATUS_LOGIN' => 1,
                                'LOGIN_DATE' => date("Y-m-d H:i:s")
                            ]
                        );
                    }
                   
                    //$res = FS_Customer::get($customerId);
                    //$aa = Session::put('variableName', $customer);
                    $res['customerId'] = $res['ID'];
                    $res['customerName'] = $res['Firstname'] .' '.$res['Lastname'];
                   
                    $customer = $res;
                    session()->put('keyLogin', $customer);
                    $value = session()->get('keyLogin');
                    return redirect('fastbox/catalog-list/');
                }
            }catch (Exception $e){
                return view('customer/fastbox-login-form');
                //$msg = 'Username or Password ไม่ถูกต้องกรุณาลองใหม่อีกครั้ง';
                //return redirect('fastbox/customer/login-form')->with('msg',$msg);
            }
        }
    }*/



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
