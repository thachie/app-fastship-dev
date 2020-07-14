<?php

namespace App\Http\Controllers\Credit;

use DB;
use App\Models\Credit;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Lib\Fastship\Fastship;
use App\Lib\Fastship\FS_Shipment;
use App\Lib\Fastship\FS_CreditBalance;
use App\Lib\Fastship\FS_Pickup;
use App\Lib\Fastship\FS_CreditCard;
use App\Lib\Fastship\FS_Customer;
use App\Lib\Encryption;
use Mail;

class CreditBalanceController extends Controller
{
    public function __construct()
    {
        date_default_timezone_set("Asia/Bangkok");
        
        if($_SERVER['REMOTE_ADDR'] == "localhost" || $_SERVER['REMOTE_ADDR'] == "127.0.0.1"){
            include(app_path() . '\Lib\inc.functions.php');
        }else{
            include(app_path() . '/Lib/inc.functions.php');
        }
    }
    
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
        if (session('customer.id') != null){
            $customerId = session('customer.id');
        }else{
            return redirect('/')->with('msg','คุณยังไม่ได้เข้าระบบ กรุณาเข้าสู่ระบบเพื่อใช้งาน');
        }
        
        //
        Fastship::getToken($customerId);
        
        //get customer
        $customer = FS_Customer::get($customerId);

        //get store credit
        $storecredit = FS_CreditBalance::get();
        
        //get payment statement
        $statements = FS_CreditBalance::get_statements();

        //get credit card
        $creditCards = FS_CreditCard::get_credit_cards();
        
        $paymentMapping = array(
            "QR" => "ชำระเงินผ่าน QR Code",
            "Credit_Card" => "ชำระเงินผ่าน Credit Card",
            "Bank_Transfer" => "ชำระเงินโดยการโอนผ่านธนาคาร",
            "Cash" => "ชำระเงินสด",
            "Invoice" => "ชำระเงินแบบวางบิล",
            "Store_Credit" => "รับเครดิตเงินคืน",
            "Withdraw" => "ถอนเงิน",
            "Use_Credit" => "ใช้เครดิตสะสม",
        );
        
        $data = array(
            'customer_data' => $customer,
            'storecredit' => $storecredit,
            'statements' => $statements,
            'credit_cards' => $creditCards,
            'payment_mapping' => $paymentMapping,
        );
        
        return view('cust_balance',$data);
        
    }

    public function omiseAddCreditCard(Request $request)
    {
        if(session('customer.id') != null){
            $customerId = session('customer.id');
        }else{
            return redirect('/')->with('msg','คุณยังไม่ได้เข้าระบบ กรุณาเข้าสู่ระบบเพื่อใช้งาน');
        }
        
        $this->validate($request, [
            'command' => 'required',
            'omise_token' => 'required',
            'card_number' => 'required',
            'cvv_number' => 'required',
            '_token' => 'required',
            'holder_name' => 'required',
            'expiration_month' => 'required',
            'expiration_year' => 'required',
        ]);

        $command = $request->input('command');
        $token = $request->input('omise_token');
        $number = $request->input('card_number');
        $cvv = $request->input('cvv_number');
        if($command == 'collect-card'){
            //$customer = getCustomer($customerId);
            $customer = $this->getCustomerById($customerId);
            //alert('customer');alert($customer);
            if(empty($customer)){
                return redirect('/customer_balance')->with('msg','ไม่มีข้อมูลในระบบ');
            }else{
                //$customerId = $customer['CUST_ID'];
                $customerName = $customer['Firstname'];
                $customerLastname = $customer['Lastname'];
                $customerEmail = $customer['Email'];
//                 $customerName = $customer->CUST_FIRSTNAME;
//                 $customerLastname = $customer->CUST_LASTNAME;
//                 $customerEmail = $customer->CUST_EMAIL;

                $JSON = '{
                    "command": "'.$command.'",
                    "customerId" : "'.$customerId.'",
                    "customerName": "'.$customerName.'",
                    "customerLastname": "'.$customerLastname.'",
                    "customerEmail": "'.$customerEmail.'",
                    "token": "'.$token.'",
                    "number": "'.$number.'",
                    "cvv": "'.$cvv.'"
                }'; 

                //alert($JSON);

                if($_SERVER['REMOTE_ADDR'] == "localhost" || $_SERVER['REMOTE_ADDR'] == "127.0.0.1"){
                    $url = 'http://localhost/Omise/AddcardAction.php';
                }else{
                    $url = 'https://app.fastship.co/Omise/AddcardAction.php';
                    //$url = 'http://13.229.75.39/Omise/AddcardAction.php';
                }
                $Response = callAPI('POST', $url, $JSON);
                $res = json_decode($Response, true);

                $statusCode = $res['respones']['Code'];

                if($statusCode == 200){
                    $omisecard = $res['customerPayment'];
                    if(!empty($omisecard)){
                        $CUST_ID = $omisecard['CUST_ID'];
                        $OMISE_ID = $omisecard['OMISE_ID'];
                        $OMISE_CARD = $omisecard['OMISE_CARD'];
                        $OMISE_CARDNAME = $omisecard['OMISE_CARDNAME'];
                        $OMISE_CARDTYPE = $omisecard['OMISE_CARDTYPE'];
                        $NUMBER = $omisecard['NUMBER'];
                        $OMISE_LASTDIGITS = $omisecard['OMISE_LASTDIGITS'];
                        $CVV = $omisecard['CVV'];
                        $OMISE_EXPIRE = $omisecard['OMISE_EXPIRE'];
                        $OMISE_BANK = $omisecard['OMISE_BANK'];
                        $OMISE_COUNTRY = $omisecard['OMISE_COUNTRY'];
                        $OMISE_DESC = $omisecard['OMISE_DESC'];
                        $IS_ACTIVE = $omisecard['IS_ACTIVE'];
                        $CREATE_DATETIME = date("Y-m-d H:i:s");
                       
                        $omise_creditcard_insert = DB::table('omise_customer')->insert([
                            'CUST_ID' => $CUST_ID,
                            'OMISE_ID' => $OMISE_ID,
                            'OMISE_CARD' => $OMISE_CARD,
                            'OMISE_CARDNAME' => $OMISE_CARDNAME,
                            'OMISE_CARDTYPE' => $OMISE_CARDTYPE,
                            'NUMBER' => $NUMBER,
                            'OMISE_LASTDIGITS' => $OMISE_LASTDIGITS,
                            'CVV' => $CVV,
                            'OMISE_EXPIRE' => $OMISE_EXPIRE,
                            'OMISE_BANK' => $OMISE_BANK,
                            'OMISE_COUNTRY' => $OMISE_COUNTRY,
                            'OMISE_DESC' => $OMISE_DESC,
                            'IS_ACTIVE' => $IS_ACTIVE,
                            'CREATE_DATETIME' => $CREATE_DATETIME
                        ]);

                        if($omise_creditcard_insert){
                        	
                        	Fastship::getToken($customerId);
                        	
                        	$createDetails = array(
                        		'OmiseId' => $OMISE_ID,
                        		'OmiseCard' => $OMISE_CARD,
                        		'CardName' => $OMISE_CARDNAME,
                        		'CardType' => $OMISE_CARDTYPE,
                        		'LastDigits' => $OMISE_LASTDIGITS,
                        		'Expired' => $OMISE_EXPIRE,
                        		'Bank' => $OMISE_BANK,
                        		'Country' => $OMISE_COUNTRY,
                        		'Description' => $OMISE_DESC,
                        	);

                        	//create pickup
                        	$response = FS_CreditCard::create($createDetails);
                        	
                        	//$response = false;
                        	if($response === false){
                        		return redirect('/customer_balance')->with('msg','ทำรายการไม่สมบูรณ์ กรุณาทำรายการใหม่อีกครั้ง');
                        	}
                        	
                            //echo 'Success';
                            return redirect('/customer_balance')->with('msg','ทำรายการเพิ่มบัตรเรียบร้อยแล้ว')->with('msg-type','success');
                        }else{
                            //echo 'Fail';
                            return redirect('/customer_balance')->with('msg','ทำรายการไม่ถูกต้อง กรุณาทำรายการใหม่อีกครั้ง2');
                        }
                    }else{
                        //echo 'Fail';
                        return redirect('/customer_balance')->with('msg','ทำรายการไม่ถูกต้อง กรุณาทำรายการใหม่อีกครั้ง3');
                    }
                }else{
                    //echo 'Fail';
                    return redirect('/customer_balance')->with('msg','ทำรายการไม่ถูกต้อง กรุณาทำรายการใหม่อีกครั้ง');
                }
            }
        }else{
            //echo 'Fail';
            return redirect('/customer_balance')->with('msg','ทำรายการไม่ถูกต้อง กรุณาทำรายการใหม่อีกครั้ง');
        }
    }

    public function omiseAddNewCreditCard(Request $request)
    {
        if(session('customer.id') != null){
            $customerId = session('customer.id');
        }else{
            return redirect('/')->with('msg','คุณยังไม่ได้เข้าระบบ กรุณาเข้าสู่ระบบเพื่อใช้งาน');
        }
        //alert('omiseAddNewCreditCard');
        //alert($request->all());
        if (empty($request->pickupId)) {
            $pickupId = '';
        }else{
            $pickupId = $request->pickupId;
        }
        //alert($pickupId);
        //die();
        $this->validate($request, [
            'command' => 'required',
            'omise_token' => 'required',
            'card_number' => 'required',
            'cvv_number' => 'required',
            '_token' => 'required',
            'holder_name' => 'required',
            'expiration_month' => 'required',
            'expiration_year' => 'required',
        ]);

        $command = $request->input('command');
        $token = $request->input('omise_token');
        $number = $request->input('card_number');
        $cvv = $request->input('cvv_number');
        if($command == 'collect-card'){
            //$customer = getCustomer($customerId);
            $customer = $this->getCustomerById($customerId);
            //alert('customer');alert($customer);
            if(empty($customer)){
                return redirect('/myaccount')->with('msg','ไม่มีข้อมูลในระบบ');
            }else{
                //$customerId = $customer['CUST_ID'];
                $customerName = $customer['Firstname'];
                $customerLastname = $customer['Lastname'];
                $customerEmail = $customer['Email'];
//                 $customerName = $customer->CUST_FIRSTNAME;
//                 $customerLastname = $customer->CUST_LASTNAME;
//                 $customerEmail = $customer->CUST_EMAIL;

                $JSON = '{
                    "command": "'.$command.'",
                    "customerId" : "'.$customerId.'",
                    "customerName": "'.$customerName.'",
                    "customerLastname": "'.$customerLastname.'",
                    "customerEmail": "'.$customerEmail.'",
                    "token": "'.$token.'",
                    "number": "'.$number.'",
                    "cvv": "'.$cvv.'"
                }'; 

                //alert($JSON);

                if($_SERVER['REMOTE_ADDR'] == "localhost" || $_SERVER['REMOTE_ADDR'] == "127.0.0.1"){
                    $url = 'http://localhost/Omise/AddcardAction.php';
                }else{
                    $url = 'https://app.fastship.co/Omise/AddcardAction.php';
                    //$url = 'http://13.229.75.39/Omise/AddcardAction.php';
                }
                $Response = callAPI('POST', $url, $JSON);
                $res = json_decode($Response, true);

                $statusCode = $res['respones']['Code'];

                if($statusCode == 200){
                    $omisecard = $res['customerPayment'];
                    if(!empty($omisecard)){
                        $CUST_ID = $omisecard['CUST_ID'];
                        $OMISE_ID = $omisecard['OMISE_ID'];
                        $OMISE_CARD = $omisecard['OMISE_CARD'];
                        $OMISE_CARDNAME = $omisecard['OMISE_CARDNAME'];
                        $OMISE_CARDTYPE = $omisecard['OMISE_CARDTYPE'];
                        $NUMBER = $omisecard['NUMBER'];
                        $OMISE_LASTDIGITS = $omisecard['OMISE_LASTDIGITS'];
                        $CVV = $omisecard['CVV'];
                        $OMISE_EXPIRE = $omisecard['OMISE_EXPIRE'];
                        $OMISE_BANK = $omisecard['OMISE_BANK'];
                        $OMISE_COUNTRY = $omisecard['OMISE_COUNTRY'];
                        $OMISE_DESC = $omisecard['OMISE_DESC'];
                        $IS_ACTIVE = $omisecard['IS_ACTIVE'];
                        $CREATE_DATETIME = date("Y-m-d H:i:s");
                       
                        $omise_creditcard_insert = DB::table('omise_customer')->insert([
                            'CUST_ID' => $CUST_ID,
                            'OMISE_ID' => $OMISE_ID,
                            'OMISE_CARD' => $OMISE_CARD,
                            'OMISE_CARDNAME' => $OMISE_CARDNAME,
                            'OMISE_CARDTYPE' => $OMISE_CARDTYPE,
                            'NUMBER' => $NUMBER,
                            'OMISE_LASTDIGITS' => $OMISE_LASTDIGITS,
                            'CVV' => $CVV,
                            'OMISE_EXPIRE' => $OMISE_EXPIRE,
                            'OMISE_BANK' => $OMISE_BANK,
                            'OMISE_COUNTRY' => $OMISE_COUNTRY,
                            'OMISE_DESC' => $OMISE_DESC,
                            'IS_ACTIVE' => $IS_ACTIVE,
                            'CREATE_DATETIME' => $CREATE_DATETIME
                        ]);

                        if($omise_creditcard_insert){
                            
                            Fastship::getToken($customerId);
                            
                            $createDetails = array(
                                'OmiseId' => $OMISE_ID,
                                'OmiseCard' => $OMISE_CARD,
                                'CardName' => $OMISE_CARDNAME,
                                'CardType' => $OMISE_CARDTYPE,
                                'LastDigits' => $OMISE_LASTDIGITS,
                                'Expired' => $OMISE_EXPIRE,
                                'Bank' => $OMISE_BANK,
                                'Country' => $OMISE_COUNTRY,
                                'Description' => $OMISE_DESC,
                            );

                            //create pickup
                            $card = FS_CreditCard::create($createDetails);
                            
                            //$response = false;
                            if($card === false){
                                //return redirect('/myaccount')->with('msg','ทำรายการไม่สมบูรณ์ กรุณาทำรายการใหม่อีกครั้ง');
                                return redirect('new_creditcard/'.$pickupId)->with('msg','ทำรายการไม่สมบูรณ์ กรุณาทำรายการใหม่อีกครั้ง');
                            }else{
                                return redirect('credit/omise_auto_charge/'.$pickupId.'/'.$card)->with('msg','ทำรายการเพิ่มบัตรเรียบร้อยแล้ว')->with('msg-type','success');
                            }
                            
                            //echo 'Success';die();
                            //return redirect('/pickup_detail/'.$pickupId)->with('msg','ทำรายการเพิ่มบัตรเรียบร้อยแล้ว')->with('msg-type','success');
                        }else{
                            //echo 'Fail';
                            //return redirect('/myaccount')->with('msg','ทำรายการไม่ถูกต้อง กรุณาทำรายการใหม่อีกครั้ง2');
                            return redirect('new_creditcard/'.$pickupId)->with('msg','ทำรายการไม่ถูกต้อง กรุณาทำรายการใหม่อีกครั้ง2');
                        }
                    }else{
                        //echo 'Fail';
                        //return redirect('/myaccount')->with('msg','ทำรายการไม่ถูกต้อง กรุณาทำรายการใหม่อีกครั้ง3');
                        return redirect('new_creditcard/'.$pickupId)->with('msg','ทำรายการไม่ถูกต้อง กรุณาทำรายการใหม่อีกครั้ง3');
                    }
                }else{
                    //echo 'Fail';
                    //return redirect('/myaccount')->with('msg','ทำรายการไม่ถูกต้อง กรุณาทำรายการใหม่อีกครั้ง');
                    return redirect('new_creditcard/'.$pickupId)->with('msg','ทำรายการไม่ถูกต้อง กรุณาทำรายการใหม่อีกครั้ง');
                }
            }
        }else{
            //echo 'Fail';
            //return redirect('/myaccount')->with('msg','ทำรายการไม่ถูกต้อง กรุณาทำรายการใหม่อีกครั้ง');
            return redirect('new_creditcard/'.$pickupId)->with('msg','ทำรายการไม่ถูกต้อง กรุณาทำรายการใหม่อีกครั้ง');
        }
    }

    public function omiseChargeAction()
    {
        if(session('customer.id') != null){
            $customerId = session('customer.id');
        }else{
            return redirect('/')->with('msg','คุณยังไม่ได้เข้าระบบ กรุณาเข้าสู่ระบบเพื่อใช้งาน');
        }

        $tran_id = 'CC';
        $cus_id = $customerId;
        $Y =  date("y");
        $digits = 2;
        $rand =  str_pad(rand(0, pow(10, $digits)-1), $digits, '0', STR_PAD_LEFT);
        $tran_id = $tran_id.$Y.date("md").$rand;
        $date_Time = date("Y-m-d H:i:s");
        $balance_in = $amount;
        $balance_out = 0;
        $tran_ref = $tran_id;
        $transfer_no = 'cust_test_5bbwabybamcjd70aqjk';
        $tran_type = 'Credit'; //debit credit
        $payment_method = 'credit_card';
        $verified = 'Pending';
        $verified_by = 'System';
        $file_upload = '';
        $memo = 'Create Credit';
        $dateTime = $date_Time;
        $tranfer_date = '';
        if(!empty($tranfer_date)){
            $balance_in_date = $tranfer_date;
        }else{
            $balance_in_date = date("Y-m-d H:i:s");
        }
        $payment_transfer = 'bank_transfer_api';              
        $balance_out_date = '';
        $create_date = $date_Time;

        $min = 2000;
        $max = 100000000;
        $calAmount = ($amount)*100;
        if(!empty($transfer_no) && ($calAmount >= $min && $calAmount <= $max)){
            $insert = DB::table('create_credit')->insert([
                'tran_id' => $tran_id,
                'cus_id' => $customerId,
                'amount' => $amount,
                'balance_in' => $balance_in,
                'balance_out' => $balance_out,
                'tran_ref' => $tran_ref,
                'transfer_no' => $transfer_no,
                'tran_type' => $tran_type,
                'payment_method' => $payment_method,
                'payment_transfer' => $payment_transfer,
                'verified' => $verified,
                'verified_by' => $verified_by,
                'file_upload' => $file_upload,
                'memo' => $memo,
                'balance_in_date' => $balance_in_date,
                'create_date' => $create_date
            ]);
            $cc_id = DB::getPdo()->lastInsertId();
            if($insert){
                //echo 'Success';
                
                //OmiseCharge
                if($_SERVER['REMOTE_ADDR'] == "localhost" || $_SERVER['REMOTE_ADDR'] == "127.0.0.1"){
                    $url = 'http://localhost/Omise/CreditChargeAction.php';
                }else{
                    $url = 'https://app.fastship.co/Omise/CreditChargeAction.php';
                }

                $JSON = '{
                    "amount": "'.$calAmount.'",
                    "currency" : "thb",
                    "customer": "'.$transfer_no.'"
                }'; 
                //alert($JSON);
                $Response = callAPI('POST', $url, $JSON);
                $res = json_decode($Response, true);
                //alert($res);
                $OBJECT = $res['Omise']['OBJECT'];
                $STATUS = $res['Omise']['STATUS'];
                $LOCATION = $res['Omise']['LOCATION'];
                $CODE = $res['Omise']['CODE'];
                $MESSAGE = $res['Omise']['MESSAGE'];
                if($OBJECT == 'charge'){
                    $res = $this->insertToCreditBalance($cc_id, $customerId);
                    $insert = DB::table('omise_charge_status')->insert([
                        'CUST_ID' => $customerId,
                        'CUSTOMER_PAYMENT' => $transfer_no,
                        'AMOUNT' => $AMOUNT,
                        'OBJECT' => $OBJECT,
                        'STATUS' => $STATUS,
                        'LOCATION' => $LOCATION,
                        'CODE' => $CODE,
                        'MESSAGE' => $MESSAGE
                    ]);
                    //echo 'Success';
                    return redirect('/add_credit')->with('msg','ทำรายการเรียบร้อย ระบบกำลังตรวจสอบข้อมูล')->with('msg-type','success');
                }else{
                    $insert = DB::table('omise_charge_status')->insert([
                        'CUST_ID' => $customerId,
                        'CUSTOMER_PAYMENT' => $transfer_no,
                        'AMOUNT' => $AMOUNT,
                        'OBJECT' => $OBJECT,
                        'STATUS' => $STATUS,
                        'LOCATION' => $LOCATION,
                        'CODE' => $CODE,
                        'MESSAGE' => $MESSAGE
                    ]);
                    //echo 'Fail';
                    return redirect('/add_credit')->with('msg','ระบบไม่สามารถตัดเงินได้ กรุณาทำรายการใหม่อีกครั้ง');
                }

                //return redirect('/add_credit')->with('msg','ทำรายการเรียบร้อย ระบบกำลังตรวจสอบข้อมูล')->with('msg-type','success');
            }else{
                echo 'Fail';
                return redirect('/add_credit')->with('msg','ทำรายการไม่ถูกต้อง กรุณาทำรายการใหม่อีกครั้ง');
            }
        }else{
            //echo 'Fail';
            return redirect('/add_credit')->with('msg','จำนวนเงินไม่ถูกต้อง กรุณาทำรายการใหม่อีกครั้ง');
        }
    }

    public function withdraw(Request $request)
    {
        
        if(session('customer.id') != null){
            $customerId = session('customer.id');
        }else{
            return redirect('/')->with('msg','คุณยังไม่ได้เข้าระบบ กรุณาเข้าสู่ระบบเพื่อใช้งาน');
        }
        
        $this->validate($request, [
            'amount' => 'required',
        ]);
        
        $amount = $request->input('amount');
        
        //get token
        Fastship::getToken($customerId);
        
        $balance = FS_CreditBalance::get();
        
        if($amount > $balance){
            return redirect('/customer_balance')->with('msg','จำนวนเงินที่ต้องการถอน ' . $amount . ' บาท เกินจำนวนที่ถอนได้ (ยอดที่ถอนได้ ' . $balance . ' บาท)');
        }
        
        //add customer credit
        $params = array(
            "Amount" => $amount,
        );
        $result = FS_CreditBalance::withdraw($params);
        
        if($result){
            return redirect('/customer_balance')->with('msg','ทำรายการเรียบร้อย')->with('msg-type','success');
        }else{
            return redirect('/customer_balance')->with('msg','ทำรายการไม่ถูกต้อง กรุณาทำรายการใหม่อีกครั้ง');
        }
        

    }
    
    public function updateRefund(Request $request)
    {
        
        if (session('customer.id') != null){
            $customerId = session('customer.id');
        }else{
            return redirect('/')->with('msg','คุณยังไม่ได้เข้าระบบ กรุณาเข้าสู่ระบบเพื่อใช้งาน');
        }
        $this->validate($request, [
            'refund_bank' => 'required',
            'refund_account' => 'required',
            'refund_name' => 'required',
            'refund_branch' => 'required',
        ]);
        
        $data=array();
        $data['refund_bank'] = $request->input('refund_bank');
        $data['refund_account'] = $request->input('refund_account');
        $data['refund_name'] = $request->input('refund_name');
        $data['refund_branch'] = $request->input('refund_branch');
        
        //update to API
        Fastship::getToken($customerId);
        $updateDetails = array(
            'RefundBank' => $data['refund_bank'],
            'RefundAccount' => $data['refund_account'],
            'RefundName' => $data['refund_name'],
            'RefundBranch' => $data['refund_branch'],
        );
        $updateCompleted = FS_Customer::update($updateDetails);
        
        if($updateCompleted){
            return redirect('/customer_balance')->with('msg','ระบบได้ทำการอัปเดทข้อมูล เรียบร้อยแล้ว')->with('msg-type','success');
        }else{
            return redirect('/customer_balance')->with('msg','อัปเดทข้อมูลไม่สำเร็จ กรุณาลองใหม่อีกครั้ง');
        }

    }
    
    public function getCustomerById($customerId)
    {
        try
        {
            if(!empty($customerId)){

                Fastship::getToken($customerId);
                $customerObj = FS_Customer::get($customerId);
                return $customerObj;
                
            }else{
                $customerObj = null;
                return $customerObj;
            } 
        }catch(Exception $e){
            echo 'Error -- $e';
        }
    }

    public function deleteCreditCard(Request $request)
    {
        if(session('customer.id') != null){
            $customerId = session('customer.id');
        }else{
            return redirect('/')->with('msg','คุณยังไม่ได้เข้าระบบ กรุณาเข้าสู่ระบบเพื่อใช้งาน');
        }

        $this->validate($request, [
            'card_id' => 'required',
        ]);
        
        $date_Time = date("Y-m-d H:i:s");
        $card_id = $request->input('card_id');
		
        $update = DB::table('omise_customer')
        ->where('ID', $card_id)
        ->update(
            [
                'IS_ACTIVE' =>  0,
                'UPDATE_DATETIME' =>  $date_Time
            ]
        );
        
        Fastship::getToken($customerId);
        
        //delete credit card
        $response = FS_CreditCard::delete($card_id);
        
        if($response === false){
            return redirect('/customer_balance')->with('msg','ทำรายการไม่สมบูรณ์ กรุณาทำรายการใหม่อีกครั้ง');
        }
        
        return redirect('/customer_balance')->with('msg','ทำรายการเรียบร้อย')->with('msg-type','success');

    }

    /*
    public function preparePaymentSubmission($amount="")
    {
    	//check customer id
    	if(session('customer.id') != null){
    		$customerId = session('customer.id');
    	}else{
    		return redirect('/')->with('msg','คุณยังไม่ได้เข้าระบบ กรุณาเข้าสู่ระบบเพื่อใช้งาน');
    	}
    	
    	Fastship::getToken($customerId);
    	
    	//$creditBalance = FS_CreditBalance::get();
    	//prepare request data
    	$searchDetails = array(
    	    'NoStatuses' => array("Cancelled","New","Pickup","Received","Sent","Paid"),
    	);

    	//call api
    	$resDetails = FS_Pickup::search($searchDetails);
    	if($resDetails === false){
    		$resGetpickup = array();
    	}else{
    	    $resGetpickup = $resDetails;
    		
    	}

    	$data = array(
    		'pickup_list' => $resGetpickup,
    	    'amount' => $amount,
    	);
        return view('payment_submission',$data);
    }
    */
    
    public function sendPaymentNotifyEmail($token1,$token2,Request $request){

        $converter = new Encryption;
        
        $email = $converter->decode($token1);
        $pickupId = $converter->decode($token2);

        $customerObj = DB::table('customer')->select("CUST_ID")
        ->whereRaw('LOWER(CUST_EMAIL) = ?', strtolower($email))
        ->where("CUST_LEADSOURCE",5)
        ->where("IS_ACTIVE",1)->first();
        
        if($customerObj == null || sizeof($customerObj) > 1){
            exit();
        }

        $customerId = $customerObj->CUST_ID;
        
        Fastship::getToken($customerId);

        //$response = FS_Pickup::get($pickupId);
        $shipment_data = array();
        $response = FS_Pickup::get($pickupId);
        if(isset($response['ShipmentDetail']['ShipmentIds']) && sizeof($response['ShipmentDetail']['ShipmentIds']) > 0){
            foreach ($response['ShipmentDetail']['ShipmentIds'] as $shipmentId) {
                $shipment_data[] = FS_Shipment::get($shipmentId);
            }
        }
        
        $data = array(
            'pickupId' => $pickupId,
            'email' => $email,
            'pickupData' => $response,
            'shipmentData' => $shipment_data,
        );
        
        Mail::send('email/notify_payment',$data,function($message) use ($data){
            $message->to($data['email']);
            $message->bcc(['thachie@tuff.co.th','oak@tuff.co.th']);
            $message->from('cs@fastship.co', 'FastShip');
            $message->subject('FastShip - แจ้งการชำระเงิน ใบรับหมายเลข '. $data['pickupId']);
        });
  
        exit();
            
    }

    public function omiseAutoChargeAction($pickupId,$card)
    {
        if(session('customer.id') != null){
            $customerId = session('customer.id');
        }else{
            return redirect('/')->with('msg','คุณยังไม่ได้เข้าระบบ กรุณาเข้าสู่ระบบเพื่อใช้งาน');
        }
        //http://devapp.fastship.co/credit/omise_auto_charge/298268/2863
        Fastship::getToken($customerId);
        //get params
        $method = 'Credit_Card';
        //$customerOmise = 'cust_5j0jdesp2ul3q14i25x';//tae
        //$customerOmise = 'cust_5ehe1wz2ysiv200f3ci';//earht
        $creditCard = FS_CreditCard::get($card);
        $customerOmise = $creditCard['OMISE_ID'];
                
        $response = FS_Pickup::get($pickupId);
        $amount = $response['Amount'];
        $amountSatang = ($amount)*100;
        $min = 2000;
        $max = 100000000;
        $calAmount = ($amount)*100;
        /*alert('amount = '.$amount);
        alert('min = '.$min);
        alert('max = '.$max);
        alert('calAmount = '.$calAmount);*/

        if(empty($customerOmise)){
            return redirect('pickup_detail/'.$pickupId)->with('msg','ข้อมูลไม่ถูกต้อง ระบบไม่สามารถตัดเงินได้ กรุณาทำรายการใหม่อีกครั้งหรือติดต่อเจ้าหน้าที่');
        }
        if(!empty($customerOmise) && ($calAmount >= $min && $calAmount <= $max)){
            //omise api params
            $apiParams = array(
                "amount" => $amountSatang,
                "currency" => "thb",
                "customer" => $customerOmise,
                "pickupId" => $pickupId,
                "customerId" => $customerId,
            );
            
            //call api
            $url = 'https://admin.fastship.co/api/omise/CreditChargeAction.php';
            //$url = 'https://admin.fastship.co/api/omise/DevCreditChargeAction.php';
            $Response = callAPI("POST",$url, json_encode($apiParams));
            $omiseResult = json_decode($Response, true);
            //alert($omiseResult);
            
            //api success
            if($omiseResult['data']['status'] == "successful"){
                $params = array(
                    "PickupId"=>$pickupId,
                    "Status"=>1,
                    "Card"=>$card,
                    "ActualPayment"=> "Credit_Card",
                    "PaidDate" => date("Y-m-d H:i:s"),
                    "IsPaid" => 1,
                );
                $updateStatus = FS_Pickup::updateStatus($params);
                usleep(10);
                
                // ##### call notify #####
                $token = md5("fastship".$pickupId);
                $requestArray = array(
                    'id' => $pickupId,
                    'token' => $token,
                );
                
                $url = "https://admin.fastship.co/notify/pickup_paid";
                call_api($url,$requestArray);
                $url = "https://admin.fastship.co/notify/pickingup";
                call_api($url,$requestArray);
                if($pickupId > 325135){
                    $url = "https://admin.fastship.co/notify/create_tracking";
                    call_api($url,$requestArray);
                }
                // ##### call notify #####
                
                return redirect('pickup_detail/'.$pickupId)->with('msg','ระบบได้ทำการตัดเงินบัตรเครดิตและสร้างใบรับพัสดุ เรียบร้อยแล้ว')->with('msg-type','success');

            }else{
                //echo "fail";
                //$msg = 'ระบบได้ทำการตัดเงินบัตรเครดิตไม่สำเร็จ กรุณาตรวจสอบบัตรเครดิตและทำรายการใหม่อีกครั้ง สถานะรายการบัตรเครดิต '.$omiseResult['data']['status'];
                $msg = 'ระบบได้ทำการตัดเงินบัตรเครดิตไม่สำเร็จ กรุณาตรวจสอบบัตรเครดิตและทำรายการใหม่อีกครั้ง';
                return redirect('pickup_detail/'.$pickupId)->with('msg',$msg);
            }
        }else{
            $msg = 'ยอดชำระขั้นต่ำที่สามารถทำรายการได้ต้อง 20 บาทขึ้นไป กรุณาทำรายการใหม่อีกครั้ง';
            //echo $msg;
            return redirect('pickup_detail/'.$pickupId)->with('msg',$msg);
        }
    }

}
