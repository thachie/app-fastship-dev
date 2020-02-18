<?php

namespace App\Http\Controllers\Payment;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Lib\Fastship\FS_Pickup;
use App\Lib\Fastship\FS_Shipment;
use App\Lib\Fastship\FS_Payment;
use App\Lib\Fastship\Fastship;
use App\Lib\Encryption;

class PaymentController extends Controller
{
    public $kbank_url;
    
    public function __construct()
    {
        date_default_timezone_set("Asia/Bangkok");
        include(app_path() . '/Lib/inc.functions.php');
        $this->kbank_url = "https://kpaymentgateway-services.kasikornbank.com/qr/v2/qr/";
    }

    public function prepareQr($code1,$code2,Request $request)
    {
        //decode params
        $converter = new Encryption;
        $pickupId = $converter->decode_short($code1);
        $customerId = $converter->decode_short($code2);
        
        //get pickup
        Fastship::getToken($customerId);
        $pickup = FS_Pickup::get($pickupId);
        $shipmentIds = $pickup['ShipmentDetail']['ShipmentIds'];
        foreach ($shipmentIds as $key => $shipid) {
            $pickup['ShipmentDetail']['ShipmentIds'][$key] = FS_Shipment::get($shipid);
        }
        
        //prepare to Kbank
        $amount = $pickup['Amount'];
        $description = "Pickup # " . $pickup['ID'] . " - Pickup by " . $pickup['PickupType'];
        $jsonCreateOrderId = '{
            "amount": '.$amount.',
            "currency": "THB",
            "description": "'.$description.'",
            "source_type": "qr",
            "reference_order": "'.$pickupId.'"
        }';
        $method = "POST";
        $url = "https://kpaymentgateway-services.kasikornbank.com/qr/v2/order";
        $jsonData = $jsonCreateOrderId;

        $response = callAPI_Kbank($method, $url, $jsonData);
        $res = json_decode($response, true);
        $order_id = $res['id'];
        
        $LOGPROCESS = storage_path('logs/kbank_create_order.log');
        $this->GEN_Logs(date("Y-m-d H:i:s")."|Response|$response",$LOGPROCESS,'l' );
        $data = array(
            "pickup" => $pickup,
            "kbankOrderId" => $order_id,
        );
        return view('payment_qr',$data);

    }
    
    public function paymentCompleted(Request $request)
    {
        $chargeId = $request->input('chargeId');
        $token = $request->input('token');
        $paymentMethods = $request->input('paymentMethods');

        /*
        if ($paymentMethods = "card") {
        	
        	//5431289719925031 MASTERCARD
        	//4024007134786226 VISA
        	//3536353014086177 JCB
        	$amount = "1";
        	$reference_order = date("mdHis").substr($_SERVER['REQUEST_TIME'],-3);
        	$jsonCardCreateCharge = '{
				"amount": '.$amount.',
				"currency": "THB",
				"description": "TESTCARDSANDBOX",
				"source_type": "card",
				"mode": "token",
				"token": "'.$token.'",
				"reference_order" : "'.$reference_order.'",
				"ref_1": "ref1",
				"ref_2": "123456"
			}';
			alert($jsonCardCreateCharge);
        	$method = "POST";
	        $url = "https://kpaymentgateway-services.kasikornbank.com/card/v2/charge";
	        $jsonData = $jsonCardCreateCharge;
	        alert($url);
	        $response = callAPI_Kbank($method, $url, $jsonData);
	        $transaction = json_decode($response, true);
            alert($response);
            alert($transaction);
	        $id = $transaction['id'];
	        $transaction_state = $transaction['transaction_state'];
	        $status = $transaction['status'];
	        $redirect_url = $transaction['redirect_url'];
	        if ($transaction_state == 'Pre-Authorized' && $status == 'success') {
	        	return redirect($redirect_url);
	        	exit();
	        }elseif ($transaction_state == 'Authorized' && $status == 'success') {
	        	//alert('Card Charge Success');
	        	//alert($transaction);
	        	$completed = ($transaction['status'] == $transaction['status'] && $transaction['transaction_state'] == $transaction['transaction_state']);
	        	$transaction_state = $transaction['transaction_state'];
	        	$pickupId = $transaction['reference_order'];
	        }else{
	        	//alert('Card Charge Fail');
	        	//alert($transaction);
	        	$completed = ($transaction['status'] == $transaction['status'] && $transaction['transaction_state'] == $transaction['transaction_state']);
	        	$transaction_state = $transaction['transaction_state'];
	        	$pickupId = $transaction['reference_order'];
	        }
        }else{
        */
        	$method = "GET";
	        $url = "https://kpaymentgateway-services.kasikornbank.com/qr/v2/qr/".$chargeId;
	        $jsonData = "";
	        
	        $response = callAPI_Kbank($method, $url, $jsonData);
	        $transaction = json_decode($response, true);
	        //alert($transaction);
	        
	        $LOGPROCESS = storage_path('logs/kbank_payment_completed.log');
	        $this->GEN_Logs(date("Y-m-d H:i:s")."|$chargeId|$response",$LOGPROCESS,'l' );
	        
	        $completed = ($transaction['status'] == "success" && $transaction['transaction_state'] == "Authorized");
	        $transaction_state = $transaction['transaction_state'];
	        $pickupId = $transaction['reference_order'];
	        
       // }
       
        $data = array(
            "completed" => $completed,
            "transaction_state" => $transaction_state,
            "pickupId" => $pickupId,
        );
        return view('payment_completed',$data);
        
    }

    public function paymentCompleted2(Request $request)
    {
        alert($request->all());
        $req = json_encode($request->all());
        $LOGPROCESS = storage_path('logs/kbank_payment_status_qr.log');
        $str = 'Start Payment Status QR';
        $this->GEN_Logs(date("Y-m-d H:i:s")."|$str|",$LOGPROCESS,'l' );
        $this->GEN_Logs(date("Y-m-d H:i:s")."|Request|$req",$LOGPROCESS,'l' );
        $str = 'End Payment Status QR';
        $this->GEN_Logs(date("Y-m-d H:i:s")."|$str|",$LOGPROCESS,'l' );
    }

    /*
    public function paymentStatusQr(Request $request)
    {
        $req = json_encode($request->all());
        $LOGPROCESS = storage_path('logs/kbank_payment_status_qr.log');
        $str = 'Start Payment Status QR';
        $this->GEN_Logs(date("Y-m-d H:i:s")."|$str|",$LOGPROCESS,'l' );
        $this->GEN_Logs(date("Y-m-d H:i:s")."|Request|$req",$LOGPROCESS,'l' );
        $str = 'End Payment Status QR';
        $this->GEN_Logs(date("Y-m-d H:i:s")."|$str|",$LOGPROCESS,'l' );
    }*/

    public function paymentStatusCard(Request $request)
    {
        $req = json_encode($request->all());
        $LOGPROCESS = storage_path('logs/kbank_payment_status_card.log');
        $str = 'Start Payment Status Card';
        $this->GEN_Logs(date("Y-m-d H:i:s")."|$str|",$LOGPROCESS,'l' );
        $this->GEN_Logs(date("Y-m-d H:i:s")."|Request|$req",$LOGPROCESS,'l' );
        $str = 'End Payment Status Card';
        $this->GEN_Logs(date("Y-m-d H:i:s")."|$str|",$LOGPROCESS,'l' );
    }

    public function GEN_Logs($Detail, $File_name = '', $Wtype = '', $Chmode = ''){

        if($File_name == ''){
            //$File_name = "/home/web/library/logs/DEBUG-LOG-" . date("Y-m-d") . ".TXT";
            $File_name = "/opt/bitnami/frameworks/laravel/storage/logs/DEBUG-LOG-" . date("Y-m-d") . ".TXT";
        }
        if($Wtype == ''){
            $Wtype = "a";
        }
        
        if ($Wtype == 'l'){
            $Wtype = "a";
        }else{
            if($Chmode == '' and !eregi("w", $Wtype)){
                $Detail = "(" . date("Y-m-d H:i:s") . ") - " . $Detail; 
            }
        }
        
        $fp = @fopen($File_name, $Wtype);
        @fwrite($fp, $Detail . "\r\n");
        @fclose($fp);

        if($Chmode != ''){
            $command = "chmod $Chmode $File_name";
            exec($command, $status);
        }
    }

    public function inquiry($payment_methods,$id)
    {

        $charge_id = "chrg_test_20236c47ec496595a4a458b60bfeffbbe40de";
        $order_id = "order_test_202365d973df4531a419999e8929f59da40cc";

        if (!empty($payment_methods) && !empty($id)) {
        	if ($payment_methods=='qr') {
        		$method = "GET";
	            //$url = "https://kpaymentgateway-services.kasikornbank.com/qr/v2/qr/".$charge_id;
	            //$url = "https://kpaymentgateway-services.kasikornbank.com/qr/v2/qr/".$id;
	            $url = $this->kbank_url.$id;
	            $jsonData = "";
	            alert($url);
	            $response = callAPI_Kbank($method, $url, $jsonData);
	            $res = json_decode($response, true);
	            alert($res);
        	}elseif($payment_methods=='card'){
        		$url = "https://kpaymentgateway-services.kasikornbank.com/card/v2/charge/".$id;
        		$jsonData = "";
	            alert($url);
	            $response = callAPI_Kbank($method, $url, $jsonData);
	            $res = json_decode($response, true);
	            alert($res);
        	}else{
	            alert('Invalid Payment Methods is not QR or Card!');
	        }
        }else{
            alert('Payment Methods or Charge ID or Order ID is null!');
        }
    }

    public function prepareQrTest($code,Request $request)
    {
        $customerId = 4815;
        $amount = number_format($code,2);
        $req = $code;
        $shipment_id = "151234567";
        $description = "Test Payment QR";

        $shipment_id = '15'.date("mdHis").substr($_SERVER['REQUEST_TIME'],-3);
        $jsonCreateOrderId = '{
            "amount": '.$amount.',
            "currency": "THB",
            "description": "'.$description.'",
            "source_type": "qr",
            "reference_order": "'.$shipment_id.'"
        }';
        $method = "POST";
        $url = "https://kpaymentgateway-services.kasikornbank.com/qr/v2/order";
        $jsonData = $jsonCreateOrderId;
        $response = callAPI_Kbank($method, $url, $jsonData);
        $res = json_decode($response, true);
        $order_id = $res['id'];
        //$payment_methods = 'qr';
        $payment_methods = 'card';
        //$payment_methods = 'redirect';

        $LOGPROCESS = storage_path('logs/kbank_create_order.log');
        $this->GEN_Logs(date("Y-m-d H:i:s")."|Response|$response",$LOGPROCESS,'l' );
        $data = array(
            "amount" => $amount,
            "order_id" => $order_id,
            "shipment_id" => $shipment_id,
            "customerId" => $customerId,
            "payment_methods" => $payment_methods,
            "description" => $description,
        );
        return view('payment_qr1',$data);
    }

    public function prepareUnionPay($code,Request $request)
    {
        $amount = number_format($code,2);
        $reference_order = date("mdHis").substr($_SERVER['REQUEST_TIME'],-3);
        //"source_type": "card",
        //"source_type": "card",
        $jsonCardCreateCharge = '{
            "amount": '.$amount.',
            "currency": "THB",
            "description": "TESTUNIONPAY",
            "source_type": "unionpay",
            "mode": "",
            "token": "",
            "reference_order" : "'.$reference_order.'",
            "ref_1": "ref1",
            "ref_2": "123456"
        }';
        alert($jsonCardCreateCharge);
        $method = "POST";
        $url = "https://kpaymentgateway-services.kasikornbank.com/card/v2/charge";
        $jsonData = $jsonCardCreateCharge;
        alert($url);//die();
        $response = callAPI_Kbank($method, $url, $jsonData);
        $transaction = json_decode($response, true);
        $LOGPROCESS = storage_path('logs/kbank_payment_unionpay.log');
        $this->GEN_Logs(date("Y-m-d H:i:s")."|Response|$response",$LOGPROCESS,'l' );

        alert($response);
        alert($transaction);

        $id = $transaction['id'];
        $transaction_state = $transaction['transaction_state'];
        $status = $transaction['status'];
        $redirect_url = $transaction['redirect_url'];
        $brand = $transaction['source']['brand'];
        
        return redirect($redirect_url);
        exit();
    }
    

    public function void($charge_id)
    {
        /*
        curl -X GET https://kpaymentgateway-services.kasikornbank.com/qr/v2/qr/chrg_prod_12345678/void \
        -H "x-api-key : skey_prod_41Bbw6At8dJjVyKV3ZaXghhLpRro5oAtR"
        */
        if (!empty($charge_id)) {
            $method = "GET";
            //$url = "https://kpaymentgateway-services.kasikornbank.com/qr/v2/qr/".$charge_id."/void";
            $url = $this->kbank_url.$charge_id."/void";
            $jsonData = "";
            alert($url);
            $response = callAPI_Kbank($method, $url, $jsonData);
            $res = json_decode($response, true);
            alert($res);
        }else{
            alert('Charge ID is null!');
        }
        
    }

    public function cancel($qr_id)
    {
        /*
        curl -X GET https://kpaymentgateway-services.kasikornbank.com/qr/v2/qr/qr_prod_12345678/cancel \
        -H "x-api-key : skey_prod_41Bbw6At8dJjVyKV3ZaXghhLpRro5oAtR"
        */
        if (!empty($charge_id)) {
            $method = "GET";
            //$url = "https://kpaymentgateway-services.kasikornbank.com/qr/v2/qr/".$qr_id."/cancel";
            $url = $this->kbank_url.$qr_id."/cancel";
            $jsonData = "";
            alert($url);
            $response = callAPI_Kbank($method, $url, $jsonData);
            $res = json_decode($response, true);
            alert($res);
        }else{
            alert('QR ID is null!');
        }
    }
    
}