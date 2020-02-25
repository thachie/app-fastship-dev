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
    
    public function prepareCredit($amount="")
    {
        if(session('customer.id') != null){
            $customerId = session('customer.id');
        }else{
            return redirect('/')->with('msg','คุณยังไม่ได้เข้าระบบ กรุณาเข้าสู่ระบบเพื่อใช้งาน');
        }
        
        //pickup status
        $pickupStatus = array(
            '1' => "New",
            '2' => "Pickup",
            '3' => "Received",
            '5' => "Verified",
            '4' => "Paid",
            '6' => "Sent",
            '11' => "Unpaid",
            '100' => "Cancelled",
        );
        
        //statement status
        $statementStatus = array(
            '1' => "Request",
            '2' => "Received",
            '3' => "Approved",
            '4' => "Rejected",
            '100' => "Cancelled",
        );
        
        //api
        Fastship::getToken($customerId);
        
        $creditBalance = FS_CreditBalance::get();
        $unpaid = FS_CreditBalance::getUnpaid();
        $creditCards = FS_CreditCard::get_credit_cards();
        
        $statementObjs = FS_CreditBalance::get_statements();
        $statements = array();
        if(sizeof($statementObjs) > 0){
            foreach($statementObjs as $statementObj){
                
                $createDate = date("d/m/y",strtotime($statementObj['create_dt']));
                $type = $statementObj['type'];
                if($type == "TOPUP"){
                    $description = "เติมเงิน (".$statementObj['transaction'].")";
                    $total = "<span class='text-success'>+" . $statementObj['payin'] . "</span>";
                }elseif($type == "FEE"){
                    $description = "ชำระเงิน pickup ".$statementObj['pickupid'];
                    $total = "<span class='text-danger'>-" . $statementObj['payout'] . "</span>";
                }
                $status = $statementStatus[$statementObj['status']];
                $statements[] = array(
                    "create_dt" => $createDate,
                    "description" => $description,
                    "status" => $status,
                    "amount" => $total,
                    "type" => $type,
                );
            }
        }

        $data = array(
            'creditBalance' => $creditBalance['Balance'],
        	'unpaid'=> $unpaid['Unpaid'],
            'unpaidPickups'=> $unpaid['Pickups'],
            'statements' => $statements,
            'pickupStatus' => $pickupStatus,
            'creditCards' => $creditCards,
            'amount' => $amount,
        );
        return view('add_credit',$data);
    }

    
    public function create(Request $request)
    {
        alert($request->all()['slip']);
        
        $data['transfer_type'] = $request->input('transfer_type');
        $data['amount'] = $request->input('amount');
        $data['bank'] = $request->input('bank');
        $data['transfer_no'] = $request->input('transfer_no');
        $data['tranfer_date'] = $request->input('tranfer_date');
        alert($data);

    }

    public function saveCredit(Request $request)
    {
        if(session('customer.id') != null){
            $customerId = session('customer.id');
        }else{
            return redirect('/')->with('msg','คุณยังไม่ได้เข้าระบบ กรุณาเข้าสู่ระบบเพื่อใช้งาน');
        }
        
        
        date_default_timezone_set("Asia/Bangkok");
        //alert($request->all());
        /*$this->validate($request, [
            // check validtion for image or file
            'uplode_image_file' => 'required|image|mimes:jpg,png,jpeg,gif,svg|max:2048',
        ]);*/
        // rename image name or file name 
        //$getimageName = time().'.'.$request->slip->getClientOriginalExtension();

        if(!isset($request->slip)){
            $file_name = '';
        }else{
            
            /*alert($request->slip->getClientOriginalExtension());
            alert($request->slip->getClientOriginalName());
            alert($request->slip->getClientSize());
            alert($request->slip->getClientmimeType());*/
            $getimageName = 'SLIP'.date("YmdHis").substr($_SERVER['REQUEST_TIME'],-3).'.'.$request->slip->getClientOriginalExtension();
            $type = $request->slip->getClientOriginalExtension();
            if ($type=="pdf" || $type=="gif" || $type=="jpg" || $type=="jpeg" || $type=="png") {
                //$target_dir = storage_path('app\public\slip_upload');
                //$target_dir = $target_dir.$getimageName;alert($target_dir);

                if($_SERVER['REMOTE_ADDR'] == "localhost"){
                    //$target_dir = storage_path("app\\public\\slip_upload\\" . $customerId);
                	$target_dir = public_path("slip_upload\\" . $customerId . "\\");
                }else{
                    //$target_dir = storage_path("app/public/slip_upload/" . $customerId);
                	$target_dir = public_path("slip_upload/" . $customerId . "/");
                	
                }
                
                $uploaded = $request->slip->move($target_dir, $getimageName);
                if($uploaded){
                    $file_name = $getimageName;
                }else{
                    $file_name = '';
                }
            }else {
                $file_name = '';
            }
        }
        
        $transfer_type = $request->input('transfer_type');
        $amount = $request->input('amount');
        $bank = $request->input('bank');
        $transfer_no = $request->input('transfer_no');
        $tranfer_date = $request->input('tranfer_date');

        if($transfer_type=='Bank_Transfer'){
            $tran_id = 'BANK';
        }

        
        $Y =  date("y");
        $digits = 2;
        $rand =  str_pad(rand(0, pow(10, $digits)-1), $digits, '0', STR_PAD_LEFT);
        if(!empty($bank) && $transfer_type == 'Bank_Transfer' && $tran_id == 'BANK'){
            //$tran_id = $select_bank.$Y.date("mdHis");
            $tran_id = $bank.$Y.date("md").$rand;
        }else{
            //$tran_id = $tran_id.$Y.date("mdHis");
            $tran_id = $tran_id.$Y.date("md").$rand;
        }
        //alert($tran_id);
        $date_Time = date("Y-m-d H:i:s");
        $balance_in = $amount;
        $balance_out = 0;
        $tran_ref = $tran_id;
        $transfer_no = $transfer_no;
        $tran_type = 'Credit'; //debit credit
        $payment_method = $transfer_type;
        $verified = 'Pending';
        $verified_by = 'System';
        $file_upload = $file_name;
        $memo = 'Create Credit';
        $dateTime = $date_Time;
        if(!empty($tranfer_date)){
            //$balance_in_date = date('Y-m-d 00:00:00', strtotime($tranfer_date));
            $balance_in_date = $tranfer_date;
        }else{
            $balance_in_date = date("Y-m-d H:i:s");
        }
        $payment_transfer = $transfer_type;              
        $balance_out_date = '';
        $create_date = $date_Time;
       
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

        if($insert){
            
            
        	Fastship::getToken($customerId);
        	 
        	$createDetails = array(
        		'TransactionID' => $tran_id,
        		'Amount' => $amount,
        		'TransferRef' => $transfer_no,
        		'PaymentMethod' => $payment_method,
        		'FileUpload' => $file_upload,
        	);
        	
        	//create payment request
        	$response = FS_CreditBalance::requestCredit($createDetails);
        	
        	/* 
        	$createDetails['Type'] = "TOPUP";
        	$createDetails['Reference'] = $transfer_no;
        	$createDetails['Remark'] = "topup by customer";
        	$createDetails['Status'] = 1;
        	
        	//create statement
        	$response = FS_CreditBalance::create($createDetails);
        	*/
        	
        	//$response = false;
        	if($response === false){
        		return redirect('/payment_submission')->with('msg','ทำรายการไม่สมบูรณ์ กรุณาทำรายการใหม่อีกครั้ง');
        	}
        	

            return redirect('/payment_submission')->with('msg','ทำรายการเรียบร้อย กรุณารอการตรวจสอบข้อมูลเพื่อยืนยันยอดการโอน')->with('msg-type','success');
        }else{
            //echo 'Fail';
            return redirect('/payment_submission')->with('msg','ทำรายการไม่ถูกต้อง กรุณาทำรายการใหม่อีกครั้ง');
        }


    
        /*return back()
            ->with('success','images Has been You uploaded successfully.')
            ->with('image',$getimageName);*/
    }

    public function topupQr($amount)
    {
        
        if(session('customer.id') != null){
            $customerId = session('customer.id');
        }else{
            return redirect('/')->with('msg','คุณยังไม่ได้เข้าระบบ กรุณาเข้าสู่ระบบเพื่อใช้งาน');
        }
        
        Fastship::getToken($customerId);

        $transaction = date("YmdHis");

        $createDetail = array(
            'TransactionID' => $transaction,
            'Amount' => $amount,
            'Type' => "TOPUP",
            'PaymentMethod' => "QR",
            'Reference' => "",
            'Remark' => "topup by customer",
            "Status" => 1,
        );
        
        //create pickup
        $response = FS_CreditBalance::create($createDetail);
        
        if($response === false){
            return redirect('/add_credit')->with('msg','ทำรายการไม่สมบูรณ์ กรุณาทำรายการใหม่อีกครั้ง');
        }
        
        return redirect('/add_credit/'.$amount)->with('msg','เลือกยอดชำระเรียบร้อย กรุณาสแกน QR Code เพื่อทำการโอน :' . print_r($response,true) )->with('msg-type','success');
            

        
    }
    
    public function topupCreditcard(Request $request)
    {
        
        if(session('customer.id') != null){
            $customerId = session('customer.id');
        }else{
            return redirect('/')->with('msg','คุณยังไม่ได้เข้าระบบ กรุณาเข้าสู่ระบบเพื่อใช้งาน');
        }

        Fastship::getToken($customerId);
       
        //OmiseCharge
        $omiseCustomerId = $request->input('omise_id');
        
        $amount = $request->input('amount');
        //$amountSatang = ($amount)*100;
        $amountSatang = 2000;
        $url = 'https://admin.fastship.co/api/omise/CreditChargeAction.php';

        //omise api params
        $apiParams = array(
            "amount" => $amountSatang,
            "currency" => "thb",
            "customer" => $omiseCustomerId,
            "pickupId" => "",
        );

        //print_r($apiParams);
        $Response = callAPI('POST', $url, json_encode($apiParams));
        $omiseResult = json_decode($Response, true);

        $STATUS = $omiseResult['data']['status'];
        $TRANSACTION = $omiseResult['data']['transaction'];
//         $insert = DB::table('omise_charge_status')->insert([
//             'omise_id' => $omiseResult['data']['id'],
//             'transaction' => $omiseResult['data']['transaction'],
//             'cust_id' => $pickup->custid,
//             'customer_payment' => $omiseResult['data']['customer'],
//             'amount' => $omiseResult['data']['amount'],
//             'object' => $omiseResult['data']['object'],
//             'status' => $omiseResult['data']['status'],
//             'location' => $omiseResult['data']['location'],
//             'message' => $omiseResult['data']['desc'],
//             'capture' => $omiseResult['data']['capture'],
//             'authorized' => $omiseResult['data']['authorized'],
//             'paid' => $omiseResult['data']['paid'],
//             'paid_at' => $omiseResult['data']['paid_at'],
//         ]);

        //successful
        if($STATUS == 'successful'){

            $createDetails = array(
                'TransactionID' => $TRANSACTION,
                'Amount' => $amount,
                'Type' => "TOPUP",
                'PaymentMethod' => "Credit_Card",
                'Reference' => "",
                'Remark' => "topup by customer",
                "Status" => 2,
            );
            
            //create pickup
            $response = FS_CreditBalance::create($createDetails);

            if($response === false){
                return redirect('/add_credit/'.$amount)->with('msg','ทำรายการไม่สมบูรณ์ กรุณาทำรายการใหม่อีกครั้ง');
            }
            
            return redirect('/myaccount')->with('msg','ทำรายการเรียบร้อย กรุณารอการตรวจสอบข้อมูลเพื่อยืนยันยอดการโอน')->with('msg-type','success');
            
        }else{
            
            //echo 'Fail';

            return redirect('/add_credit/'.$amount)->with('msg','ทำรายการไม่ถูกต้อง กรุณาทำรายการใหม่อีกครั้ง');
        }
        
        
    }
    
    public function omiseAddCreditCard(Request $request)
    {
        if(session('customer.id') != null){
            $customerId = session('customer.id');
        }else{
            return redirect('/')->with('msg','คุณยังไม่ได้เข้าระบบ กรุณาเข้าสู่ระบบเพื่อใช้งาน');
        }
        alert($request->all());die();
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
                        	$response = FS_CreditCard::create($createDetails);
                        	
                        	//$response = false;
                        	if($response === false){
                        		return redirect('/myaccount')->with('msg','ทำรายการไม่สมบูรณ์ กรุณาทำรายการใหม่อีกครั้ง');
                        	}
                        	
                            //echo 'Success';
                            return redirect('/myaccount')->with('msg','ทำรายการเพิ่มบัตรเรียบร้อยแล้ว')->with('msg-type','success');
                        }else{
                            //echo 'Fail';
                            return redirect('/myaccount')->with('msg','ทำรายการไม่ถูกต้อง กรุณาทำรายการใหม่อีกครั้ง2');
                        }
                    }else{
                        //echo 'Fail';
                        return redirect('/myaccount')->with('msg','ทำรายการไม่ถูกต้อง กรุณาทำรายการใหม่อีกครั้ง3');
                    }
                }else{
                    //echo 'Fail';
                    return redirect('/myaccount')->with('msg','ทำรายการไม่ถูกต้อง กรุณาทำรายการใหม่อีกครั้ง');
                }
            }
        }else{
            //echo 'Fail';
            return redirect('/myaccount')->with('msg','ทำรายการไม่ถูกต้อง กรุณาทำรายการใหม่อีกครั้ง');
        }
    }

    public function omiseAddNewCreditCard(Request $request)
    {
        if(session('customer.id') != null){
            $customerId = session('customer.id');
        }else{
            return redirect('/')->with('msg','คุณยังไม่ได้เข้าระบบ กรุณาเข้าสู่ระบบเพื่อใช้งาน');
        }
        alert('omiseAddNewCreditCard');
        alert($request->all());
        if (empty($request->pickupId)) {
            $pickupId = '';
        }else{
            $pickupId = $request->pickupId;
        }
        alert($pickupId);
        die();
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
                            $response = FS_CreditCard::create($createDetails);
                            
                            //$response = false;
                            if($response === false){
                                //return redirect('/myaccount')->with('msg','ทำรายการไม่สมบูรณ์ กรุณาทำรายการใหม่อีกครั้ง');
                                return redirect('new_creditcard/'.$pickupId)->with('msg','ทำรายการไม่สมบูรณ์ กรุณาทำรายการใหม่อีกครั้ง');
                            }else{
                                return redirect('/pickup_detail/'.$pickupId)->with('msg','ทำรายการเพิ่มบัตรเรียบร้อยแล้ว')->with('msg-type','success');
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

    public function omiseAutoChargeAction($pickupId)
    {
        if(session('customer.id') != null){
            $customerId = session('customer.id');
        }else{
            return redirect('/')->with('msg','คุณยังไม่ได้เข้าระบบ กรุณาเข้าสู่ระบบเพื่อใช้งาน');
        }
        alert('omiseAutoChargeAction');
        alert('$pickupId');die();
        
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

    public function payplusPay($token1,$token2,Request $request)
    {

        $converter = new Encryption;
        
        $email = $converter->decode($token1);
        $pickupId = $converter->decode($token2);
        
        //check customer existed
        $customerObj = DB::table('customer')->select("CUST_ID")
        ->whereRaw('LOWER(CUST_EMAIL) = ?', strtolower($email))
        ->where("CUST_LEADSOURCE",5)
        ->where("IS_ACTIVE",1)->first();
        
        if($customerObj == null || sizeof($customerObj) > 1){
            exit();
        }
        $customerId = $customerObj->CUST_ID;

        //get pickup
        Fastship::getToken($customerId);
        $response = FS_Pickup::get($pickupId);
        
        if($response == null){
            exit();
        }
        //$amountNumber = $response['Amount'];
        $amountNumber = 1;
        
        //prepare params
        $merchantCode = 10604;
        $amount = str_pad($amountNumber, 10, "0", STR_PAD_LEFT)."00";
        $url = "https://app.fastship.co";
        $respUrl = "https://api.fastship.co/kbank/payment_status";
        $ipCust = "52.77.249.67";
        $detail = "Cross-border Logistic Service";
        $invoice = $pickupId;
        $shopId = "00";
        $md5Key = "QxMjcGFzc3MOIQ=vZz09Z3hNME1UTnpj";
        $ref1 = str_pad($customerId, 8, "0", STR_PAD_LEFT);
        $info1 = "ส่งพัสดุไปต่างประเทศ เลขที่ " . $pickupId;
        $info2 = "";
        $timeout = date("Ymd H:i:s",time()+36000);
        $checksum = md5($merchantCode.$amount.$url.$respUrl.$ipCust.$detail.$invoice.$shopId.$md5Key.$ref1.$info1.$info2.$timeout);

        //call Payplus
        $apiParams = array(
            "MERCHANT2" => $merchantCode,
            "AMOUNT2" => $amount,
            "URL2" => $url,
            "RESPURL" => $respUrl,
            "IPCUST2" => $ipCust,
            "DETAIL2" => $detail,
            "INVMERCHANT" => $invoice,
            "SHOPID" => $shopId,
            "checksum" => $checksum,
            "REF1" => $ref1,
            "INFO1" => $info1,
            "INFO2" => $info2,
            "TIMEOUT" => $timeout
        );
        
        $url = 'https://rt05.kasikornbank.com/payplus/payment.aspx';
        
        $ch = curl_init();
        
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS,http_build_query($apiParams));

        // Receive server response ...
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, false);
        
        $Response = curl_exec($ch);
        
        curl_close ($ch);
       
        exit();

    }
    
    public function getCustomerById($customerId)
    {
        try
        {
            if(!empty($customerId)){

                Fastship::getToken($customerId);
                $customerObj = FS_Customer::get($customerId);
                
//                 $customerObj = DB::table('customer')
//                     ->select("CUST_ID","CUST_FIRSTNAME","CUST_LASTNAME","CUST_EMAIL")
//                     ->where('CUST_ID', $customerId)
//                     ->where("IS_ACTIVE",1)
//                     ->first();
                return $customerObj;
            }else{
                $customerObj = null;
                return $customerObj;
            } 
        }catch(Exception $e){
            echo 'Error -- $e';
        }
    }

    public function insertToCreditBalance($ccID, $customerId)
    {
        $date_Time = date("Y-m-d H:i:s");
        try
        {
            if (!empty($ccID) && !empty($customerId)){
                //Get Balance
                $amount_old = $this->getBalance($customerId);
                $creditObj = DB::table('create_credit')
                    ->where("cc_id",$ccID)
                    ->where("cus_id",$customerId)
                    ->where("status",0)
                    ->get();
                $row_credit = $creditObj->count();
                //alert($row_credit);
                //alert($creditObj);
                if($row_credit > 0){
                    $data = $creditObj[0];
                    $verified = 'Complete';
                    $verified_by = 'Omise';
                    $status = 1;
                    $create_date = $date_Time;
                    $amount_sum = $data->balance_in+$amount_old;
                    $insert = DB::table('credit_balance')->insert([
                        'tran_id' => $data->tran_id,
                        'cus_id' => $data->cus_id,
                        'cc_id' => $data->cc_id,
                        'amount' => $amount_sum,
                        'balance_in' => $data->balance_in,
                        'balance_out' => $data->balance_out,
                        'tran_ref' => $data->tran_ref,
                        'transfer_no' => $data->transfer_no,
                        'tran_type' => $data->tran_type,
                        'payment_method' => $data->payment_method,
                        'payment_transfer' => $data->payment_transfer,
                        'verified' => $verified,
                        'verified_by' => $verified_by,
                        'file_upload' => $data->file_upload,
                        'memo' => $data->memo,
                        'balance_in_date' => $data->balance_in_date,
                        'create_date' => $create_date
                    ]);
                    if($insert){
                        //echo 'success';
                        $update = DB::table('create_credit')
                            ->where('cc_id', $ccID)
                            ->update(
                                [
                                    'verified' =>  'Complete',
                                    'status' =>  1,
                                    'approve_by' =>  $verified_by,
                                    'create_date' =>  $date_Time
                                ]
                            );
                        if($update){
                            return 'Success';
                        }else{
                            return 'Fail';
                        }
                    }else{
                        return 'Insert data to table credit_balance fail';
                    }
                }else{
                    return 'No data in table create credit';
                }
            }else{
                $row = null;
                return 8; // $ccID or $customerId is null
            } 
        }catch(Exception $e){
            echo 'Error -- $e';
        }
    }

    public function getBalance($cusID=null)
    {
        try
        {
            if(!empty($cusID)){
                $amountObj = DB::table('credit_balance')
                    ->select("c_id","cus_id","amount")
                    ->where("cus_id",$cusID)
                    ->orderBy('c_id', 'DESC')
                    ->limit(1)
                    ->get();
                $row_count = $amountObj->count();
                if($row_count > 0){
                    //$total_balance = $amountObj[0]->amount;
                    return $amountObj[0]->amount;
                }else{
                    return 0;
                }
            }else{
                $total_balance = 0;
                return $total_balance;
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
        //alert($request->all());   
        $this->validate($request, [
            'card_id' => 'required',
        ]);
        
        //alert($request->input('card_id')); 
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
        if($update){

        	Fastship::getToken($customerId);
        	         	
        	//delete credit card
        	$omise_data = DB::table('omise_customer')->where('ID', $card_id)->first();
        	$response = FS_CreditCard::delete($omise_data->OMISE_ID);
        	
        	if($response === false){
            	return redirect('/myaccount')->with('msg','ทำรายการไม่สมบูรณ์ กรุณาทำรายการใหม่อีกครั้ง');
            }

            return redirect('/myaccount')->with('msg','ทำรายการเรียบร้อย')->with('msg-type','success');
            
        }else{
            return redirect('/myaccount')->with('msg','ไม่สามารถทำรายการได้ กรุณาทำรายการใหม่อีกครั้ง');
        }
    }

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

    public function base_url()
    {
        $base_url   = "http://".$_SERVER['HTTP_HOST'].str_replace(basename($_SERVER['SCRIPT_NAME']),"",$_SERVER['SCRIPT_NAME']);
        return $base_url;
    }

    public function update(Request $request, Credit $credit)
    {
        //
    }

    public function destroy(Credit $credit)
    {
        //
    }
}
