<?php

namespace App\Http\Controllers;
use DB;
use Illuminate\Http\Request;
use App\Lib\Fastship\Fastship;
use App\Lib\Fastship\FS_Shipment;
use Excel;
use Illuminate\Support\Facades\Input;

class ThaiPostController extends Controller
{
    public function __construct()
    {
        date_default_timezone_set("Asia/Bangkok");
        
        if($_SERVER['REMOTE_ADDR'] == "localhost" || $_SERVER['REMOTE_ADDR'] == "127.0.0.1"){
            include(app_path() . '\Lib\thaipost.functions.php');
            //include(app_path() . '\Lib\omise.functions.php');
        }else{
            include(app_path() . '/Lib/thaipost.functions.php');
            //include(app_path() . '/Lib/omise.functions.php');
        }
    }

    public function index()
    {
        echo 1234;
    }
	
	public function createMerchant()
    {
        //$merchantId = "FSTEST999'";
		$merchantId = "FASTSHIP88";
		$merchantName = "Fastship Co.,Ltd";
		$merchantAddress = "1/269 ซอยแจ้งวัฒนะ 14";
		$merchantDistinct = "แขวงทุ่งสองห้อง";
		$merchantAmphur = "หลักสี่";
		$merchantProvince = "กรุงเทพมหานคร";
		$merchantPostcode = "10210";
		$merchantPhoneNumber = "020803999";
		$merchantEmail = "tae@tuff.co.th";
		$postcodeDrop = "10210";

		$json = '
		{
			"merchantId": "'.$merchantId.'",
			"merchantName": "'.$merchantName.'",
			"merchantAddress": "'.$merchantAddress.'",
			"merchantDistinct": "'.$merchantDistinct.'",
			"merchantAmphur": "'.$merchantAmphur.'",
			"merchantProvince": "'.$merchantProvince.'",
			"merchantPostcode": "'.$merchantPostcode.'",
			"merchantPhoneNumber": "'.$merchantPhoneNumber.'",
			"merchantEmail": "'.$merchantEmail.'",
			"postcodeDrop": "'.$postcodeDrop.'"
		}';
        
        //alert($json);
        $url = "https://r_dservice.thailandpost.com/webservice/addMerchant"; //Test
        $Response = callAPI_thaiPost('PUT', $url, $json);
        $res = json_decode($Response, true);
        alert($res);
    }

    
    public function createOrder()
    {
        if(session('customer.id') != null){
            $customerId = session('customer.id');
        }else{
            return redirect('/')->with('msg','คุณยังไม่ได้เข้าระบบ กรุณาเข้าสู่ระบบเพื่อใช้งาน');
        }
        //Format :  PREYYYYMMDD-XXXX
        // Prefix : CLC
        $date_D = date("Y-m-d");
        $date_T = date("H:i");
        $f_time = date("H:i", strtotime("+2 hours"));
        $manifestNo = 'CLC'.date("Ymd").'-'.generateRandomString(4);//substr($_SERVER['REQUEST_TIME'], -4);
        $invNo = date("Ymd").'-'.generateRandomString(4);//substr($_SERVER['REQUEST_TIME'], -4);
        $orderId = date("YmdHi").generateRandomString(3); //substr($_SERVER['REQUEST_TIME'], -3);//"309155597"; //shipmentID
        $invNo = $invNo; //"309155597-8629"; //pickupID
       
        $getBarcode = DB::table('thaipost_barcode')->where("is_active",0)->first();
        $barcode_id = $getBarcode->barcode_id;
        $barcode = $getBarcode->barcode;
        alert($getBarcode);
        $update_active = DB::table('thaipost_barcode')
            ->where('barcode_id', $barcode_id)
            ->update(
            [
                'cus_id' => $customerId,
                'is_active' => 1,
                'ref_order_id' => $orderId,
                'update_date' =>  date('Y-m-d H:i:s')
            ]
        );
        alert($barcode);
        //die();
        $barcode = $barcode; //"EY660437537TH";
        $shipperName = "อนุศักดิ์ จิตโชติ";
        $shipperAddress = "999 ม.3 บางเลน";
        $shipperDistrict = "บางใหญ่ ";
        $shipperProvince = "นนทบุรี";
        $shipperZipcode = "11140";
        $shipperEmail = "tae@tuff.co.th";
        $shipperMobile = "085-8884698";
        $cusName = "Tuff Company";
        $cusAdd = "1/269 ซอยแจ้งวัฒนะ 14";
        $cusAmp = "หลักสี่";
        $cusProv = "กรุงเทพฯ";
        $cusZipcode = "10210";
        $cusTel = "020803999";
        $productPrice = "1000";
        $productInbox = "-";
        $productWeight = "400";
        $orderType = "D";
        $manifestNo = $manifestNo;
        //$merchantId = "FSTEST999'";
        $merchantId = "FASTSHIP88";
        $merchantZipcode = "10120";
        $storeLocationNo = "";
        $insurance = "";
        $insuranceRatePrice = "";
        //alert($barcode);
        //die();
        $json = '
        {
            "orderId": "'.$orderId.'",
            "invNo": "'.$invNo.'",
            "barcode": "'.$barcode.'",
            "shipperName": "'.$shipperName.'",
            "shipperAddress": "'.$shipperAddress.'",
            "shipperDistrict": "'.$shipperDistrict.'",
            "shipperProvince": "'.$shipperProvince.'",
            "shipperZipcode": "'.$shipperZipcode.'",
            "shipperEmail": "'.$shipperEmail.'",
            "shipperMobile": "'.$shipperMobile.'",
            "cusName": "'.$cusName.'",
            "cusAdd": "'.$cusAdd.'",
            "cusAmp": "'.$cusAmp.'",
            "cusProv": "'.$cusProv.'",
            "cusZipcode": "'.$cusZipcode.'",
            "cusTel": "'.$cusTel.'",
            "productPrice": "'.$productPrice.'",
            "productInbox": "'.$productInbox.'",
            "productWeight": "'.$productWeight.'",
            "orderType": "'.$orderType.'",
            "manifestNo": "'.$manifestNo.'",
            "merchantId": "'.$merchantId.'",
            "merchantZipcode": "'.$merchantZipcode.'",
            "storeLocationNo": "'.$storeLocationNo.'",
            "insurance": "'.$insurance.'",
            "insuranceRatePrice": "'.$insuranceRatePrice.'"
        }';
        alert($json);
        //$json = json_encode($json);
        $url = "https://r_dservice.thailandpost.com/webservice/addItem"; //Test
        //$Response = thaipost($url,$json);
        $Response = callAPI_thaiPost('POST', $url, $json);
        //$Response = test($url, $json);
        $res = json_decode($Response, true);
        alert($res);
        alert($res[0]['errorCode']);
        $errorCode = $res[0]['errorCode'];
        $errorDetail = $res[0]['errorDetail'];
        if($errorCode == '000'){
            $is_active = 1;
        }else{
            $is_active = 2;
        }

        $update = DB::table('thaipost_barcode')
        ->where('barcode_id', $barcode_id)
        ->update(
                    [
                        'is_active' => $is_active,
                        'status' => $errorDetail,
                        'update_date' =>  date('Y-m-d H:i:s')
                    ]
                );
        if($update){
            echo 'Success';
            //return redirect('/myaccount')->with('msg','ระบบได้ทำการอัปเดทข้อมูล เรียบร้อยแล้ว')->with('msg-type','success');
        }else{
            echo 'Fail';
            //return redirect('/myaccount')->with('msg','อัปเดทข้อมูลไม่สำเร็จ กรุณาลองใหม่อีกครั้ง');
        }
    }

    
    public function createOrders($count=null)
    {
        
        if(session('customer.id') != null){
            $customerId = session('customer.id');
        }else{
            return redirect('/')->with('msg','คุณยังไม่ได้เข้าระบบ กรุณาเข้าสู่ระบบเพื่อใช้งาน');
        }
        $count = $count; //Count Total shipment
        if($count>0){
            for ($i=0; $i < $count; $i++) {
                $add_array[] = $i;
            }
            $json = '[';
            $i = 0;
            foreach ($add_array as $v) {
                $date_D = date("Y-m-d");
                $date_T = date("H:i");
                $f_time = date("H:i", strtotime("+2 hours"));
                $manifestNo = 'CLC'.date("Ymd").'-'.generateRandomString(4);//substr($_SERVER['REQUEST_TIME'], -4);
                $invNo = date("Ymd").'-'.generateRandomString(4);//substr($_SERVER['REQUEST_TIME'], -4);
                $orderId = date("YmdHi").generateRandomString(3); //substr($_SERVER['REQUEST_TIME'], -3);//"309155597"; //shipmentID
                $invNo = $invNo; //"309155597-8629"; //pickupID

                $getBarcode = DB::table('thaipost_barcode')->where("is_active",0)->first();
                $barcode_id = $getBarcode->barcode_id;
                $barcode = $getBarcode->barcode;
                //alert($getBarcode);
                $update_id[]['barcode_id'] = $barcode_id;
                $update_active = DB::table('thaipost_barcode')
                    ->where('barcode_id', $barcode_id)
                    ->update(
                    [
                        'cus_id' => $customerId,
                        'is_active' => 1,
                        'ref_order_id' => $orderId,
                        'update_date' =>  date('Y-m-d H:i:s')
                    ]
                );
                
                $barcode = $barcode; //"EY660437537TH";
                $shipperName = "อนุศักดิ์ จิตโชติ";
                $shipperAddress = "999 ม.3 บางเลน";
                $shipperDistrict = "บางใหญ่ ";
                $shipperProvince = "นนทบุรี";
                $shipperZipcode = "11140";
                $shipperEmail = "tae@tuff.co.th";
                $shipperMobile = "085-8884698";
                $cusName = "Tuff Company";
                $cusAdd = "1/269 ซอยแจ้งวัฒนะ 14";
                $cusAmp = "หลักสี่";
                $cusProv = "กรุงเทพฯ";
                $cusZipcode = "10210";
                $cusTel = "020803999";
                $productPrice = "1000";
                $productInbox = "-";
                $productWeight = "400";
                $orderType = "D";
                $manifestNo = $manifestNo;
                //$merchantId = "FSTEST999'";
                $merchantId = "FASTSHIP88";
                $merchantZipcode = "10120";
                $storeLocationNo = "";
                $insurance = "";
                $insuranceRatePrice = "";
                $json .= '
                {
                    "orderId": "'.$orderId.'",
                    "invNo": "'.$invNo.'",
                    "barcode": "'.$barcode.'",
                    "shipperName": "'.$shipperName.'",
                    "shipperAddress": "'.$shipperAddress.'",
                    "shipperDistrict": "'.$shipperDistrict.'",
                    "shipperProvince": "'.$shipperProvince.'",
                    "shipperZipcode": "'.$shipperZipcode.'",
                    "shipperEmail": "'.$shipperEmail.'",
                    "shipperMobile": "'.$shipperMobile.'",
                    "cusName": "'.$cusName.'",
                    "cusAdd": "'.$cusAdd.'",
                    "cusAmp": "'.$cusAmp.'",
                    "cusProv": "'.$cusProv.'",
                    "cusZipcode": "'.$cusZipcode.'",
                    "cusTel": "'.$cusTel.'",
                    "productPrice": "'.$productPrice.'",
                    "productInbox": "'.$productInbox.'",
                    "productWeight": "'.$productWeight.'",
                    "orderType": "'.$orderType.'",
                    "manifestNo": "'.$manifestNo.'",
                    "merchantId": "'.$merchantId.'",
                    "merchantZipcode": "'.$merchantZipcode.'",
                    "storeLocationNo": "'.$storeLocationNo.'",
                    "insurance": "'.$insurance.'",
                    "insuranceRatePrice": "'.$insuranceRatePrice.'"
                }';
                if($i < $count-1){$json .= ',';}
                $i++;
            }
            $json .= '
            ]';

            alert($json);

            $url = 'https://r_dservice.thailandpost.com/webservice/addItems';
            $Response = callAPI_thaiPost('POST', $url, $json);
            $res = json_decode($Response, true);
            alert($res);

            /*$errorCode = $res[0]['errorCode'];
            $errorDetail = $res[0]['errorDetail'];
            if($errorCode == '000'){
                $is_active = 1;
            }else{
                $is_active = 2;
            }*/
            if(!empty($res[0]['errorCode'])){
                $errorCode = $res[0]['errorCode'];
                $errorDetail = $res[0]['errorDetail'];
            }else{
                $errorCode = $res[0]['messagesError'][0]['errorCode'];
                $errorDetail = $res[0]['messagesError'][0]['errorDetail'];
            }
            if($errorCode == '000'){
                $is_active = 1;
            }else{
                $is_active = 2;
            }

            foreach ($update_id as $val) {
                $bar_id = $val['barcode_id'];
                $update_status = DB::table('thaipost_barcode')
                ->where('barcode_id', $bar_id)
                ->update(
                    [
                        'is_active' => $is_active,
                        'status' => $errorDetail,
                        'update_date' =>  date('Y-m-d H:i:s')
                    ]
                );
            }

            if($update_status){
                echo 'Success';
                //return redirect('/myaccount')->with('msg','ระบบได้ทำการอัปเดทข้อมูล เรียบร้อยแล้ว')->with('msg-type','success');
            }else{
                echo 'Fail';
                //return redirect('/myaccount')->with('msg','อัปเดทข้อมูลไม่สำเร็จ กรุณาลองใหม่อีกครั้ง');
            }  
        }else{
            echo 'Count == 0';
        }
    }
 
    public function getOrderByBarcode($barcode=null)
    {
        $url = "https://r_dservice.thailandpost.com/webservice/getOrderByBarcode?barcode=".$barcode; //Test
        //alert($url);die();
        $Response = callAPI_thaiPost('GET', $url, false);
        $res = json_decode($Response, true);
        alert($res);
    }

    public function getOrderByBarcodes($barcode=null)
    {
        $exp = explode(",",$barcode);
        //alert($exp);
        $barcodes = '';
        foreach ($exp as $value) {
            $barcodes .= $value.',';
        }
        
        $json = '
        {
            "barcodes": "'.$barcodes.'"
        }';
        // "barcodes": "'.$barcode.'"
        //"barcodes": "EY660437537TH,EY660437538TH,EY660437539TH"
        alert($json);
        $url = "https://r_dservice.thailandpost.com/webservice/getOrderByBarcodes"; //Test
        $Response = callAPI_thaiPost('POST', $url, $json);
        $res = json_decode($Response, true);
        alert($res);
    }

    public function getRatePriceByWeight($type=null, $weight=null)
    {
        //type = "E" (บริการส่งสินค้า  EMS) <= 2000 g.(20Kg.)
        //type = "R" (บริการส่งสินค้า  ลงทะเบียน) <= 200 g.(2Kg.)
        $type = strtoupper ($type);
        if(empty($type)){
            $type = "R";
        }
        $json = '
        {
            "type": "'.$type.'",
            "weight": "'.$weight.'"
        }';
        alert($json);
        $url = "https://r_dservice.thailandpost.com/webservice/getRatePriceByWeight"; //Test
        $Response = callAPI_thaiPost('POST', $url, $json);
        $res = json_decode($Response, true);
        alert($res);
    }

  
    public function prePrint($barcode=null)
    {
        $path = public_path('images\logo-1.png');
        if($_SERVER['REMOTE_ADDR'] == "localhost" || $_SERVER['REMOTE_ADDR'] == "127.0.0.1"){
            //$path = public_path('images\logo-1.png');
            $path = 'https://app.fastship.co/images/logo-1.png';
        }else{
            //$path = public_path('images/logo-1.png');
            $path = 'https://app.fastship.co/images/logo-1.png';
        }
        
        $urlLogo = $path;//'/opt/bitnami/frameworks/laravel/public/images/xlogo-1.png';
        //$urlLogo = urlencode($path);//'/opt/bitnami/frameworks/laravel/public/images/xlogo-1.png';
        $url = "https://r_dservice.thailandpost.com/webservice/LabelPDF?barcode=".$barcode."&urlLogo=".$urlLogo;
        //$url = "https://r_dservice.thailandpost.com/webservice/LabelPDF?barcode=".$barcode."&urlLogo=".$urlLogo;
        
        $Response = callAPI_thaiPost('GET', $url, false);
        //alert($Response);die();
        //Label
        $file_pdf = $Response; //$array['LabelImage']['OutputImage'];
        //$data = base64_decode($file_pdf);
        header('Content-Type: application/pdf');
        echo $file_pdf;
        //alert('XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX');
        //$res = json_decode($Response, true);
        //alert($res);
    }

    
    public function getAllOrderDelivered($date=null)
    {
        //Report สถานะ และรายละเอียดการจัดส่งสินค้าของไปรษณีย์ ที่สินค้าถึงผู้รับเรียบร้อยแล้ว
        //Format :  DD/MM/YYYY
        //Example : "10/06/2016"
        $date = date("d/m/Y");

        $url = 'https://r_dservice.thailandpost.com/webservice/getAllOrderDelivered?date='.$date;
        alert($url);
        $Response = callAPI_thaiPost('GET', $url, false);
        $res = json_decode($Response, true);
        alert($res);
    }

    public function getAllOrderReceived($date=null)
    {
        //Report สถานะ และรายละเอียดการจัดส่งสินค้าของไปรษณีย์ ที่รับของเข้าระบบเรียบร้อยแล้ว
        //Format :  DD/MM/YYYY
        //Example : "10/06/2016"
        $date = date("d/m/Y");

        $url = 'https://r_dservice.thailandpost.com/webservice/getAllOrderReceived?date='.$date;
        alert($url);
        $Response = callAPI_thaiPost('GET', $url, false);
        $res = json_decode($Response, true);
        alert($res);
    }

    public function importBarcode(Request $request)
    {
        if (session('customer.id') != null){
            $customerId = session('customer.id');
        }else{
            return redirect('/')->with('msg','คุณยังไม่ได้เข้าระบบ กรุณาเข้าสู่ระบบเพื่อใช้งาน');
        }
        //alert($request->all());die();
        if(!isset($request->upload)){
            //return redirect()->back()->withInput($request->all);
            return redirect()->back()->with('status', 'Choose file please! ');
        }else{

            $file = Input::file('upload');
            $file_name = $file->getClientOriginalName();
            if($_SERVER['REMOTE_ADDR'] == "localhost" || $_SERVER['REMOTE_ADDR'] == "127.0.0.1"){
                $target_dir = storage_path("app\\public\\files_upload\\");
                //$target_dir = public_path("slip_upload");
            }else{
                $target_dir = storage_path("app/public/files_upload/");
                //$target_dir = public_path("slip_upload/");

            }
            //echo $target_dir;
            $file->move($target_dir,$file_name);
            $data = array();
            Excel::load($target_dir.$file_name, function ($reader) {
                $reader->each(function($sheet) {    
                    foreach ($sheet->toArray() as $row) {
                        //alert($row);
                        //Insert to database
                        //$this->data[] = $this->res;
                        $this->data[] = $row;
                    }
                });
            });
            $data_import = $this->data;
            //alert($data_import);die();
            $i=1;
            foreach ($data_import as $val) {
                //alert($val);
                //Test Insert to database
                //usleep(10);
                $insert = DB::table('thaipost_barcode')->insert([
                    'barcode' => $val,
                    'date'=> date("Y-m-d H:i:s"),
                ]);
                //usleep(10);
                if($insert){
                    alert($i.'. Success= ' .$val. 'Time' .date("Y-m-d H:i:s"));
                }else{
                    alert($i.'. Fail= ' .$val. 'Time'.date("Y-m-d H:i:s"));
                }
                //alert($i.'. Tset = '.date("Y-m-d H:i:s"));
                $i++;
            }
        }

        /*
            for ($i=2; $i <= 1000; $i++) {
            $update = DB::table('thaipost_barcode')
                ->where('barcode_id', $i)
                ->update(
                    [
                        'update_date' =>  '0000-00-00 00:00:00'
                    ]
                );
            if($update){
                    alert($i.'. Success= ' .$i. 'Time' .date("Y-m-d H:i:s"));
                }else{
                    alert($i.'. Fail= ' .$i. 'Time'.date("Y-m-d H:i:s"));
                }           
        }
            die();
        */
    }

    public function updateDB($id=null)
    {
        if (session('customer.id') != null){
            $customerId = session('customer.id');
        }else{
            return redirect('/')->with('msg','คุณยังไม่ได้เข้าระบบ กรุณาเข้าสู่ระบบเพื่อใช้งาน');
        }
        alert($id);
        if(!empty($id)){
            for ($i=2; $i <= $id; $i++) {
                $update = DB::table('thaipost_barcode')
                    ->where('barcode_id', $i)
                    ->update(
                        [
                            'is_active' =>  0
                        ]
                    );
                if($update){
                        alert($i.'. Success= ' .$i. 'Time' .date("Y-m-d H:i:s"));
                    }else{
                        alert($i.'. Fail= ' .$i. 'Time'.date("Y-m-d H:i:s"));
                    }           
            }
        }else{
            echo 'ID is null';
        }
    }


    //BK Code
    public function createOrders_BK($count=null)
    {
        
        if(session('customer.id') != null){
            $customerId = session('customer.id');
        }else{
            return redirect('/')->with('msg','คุณยังไม่ได้เข้าระบบ กรุณาเข้าสู่ระบบเพื่อใช้งาน');
        }
        $count = $count; //Count Total shipment
        for ($i=0; $i < $count; $i++) {
            $xx[] = $i;
        }
        alert($xx);
        $a = 0;
        $json = '';
        foreach ($xx as $v) {
            alert($v);
            if($a < $count-1){$json .= ',';}

            $a++;
        }
        die();
        $json = '[';
        for ($i=0; $i < $count; $i++) {
            //Data
            $date_D = date("Y-m-d");
            $date_T = date("H:i");
            $f_time = date("H:i", strtotime("+2 hours"));
            $manifestNo = 'CLC'.date("Ymd").'-'.generateRandomString(4);//substr($_SERVER['REQUEST_TIME'], -4);
            $invNo = date("Ymd").'-'.generateRandomString(4);//substr($_SERVER['REQUEST_TIME'], -4);
            $orderId = date("YmdHi").generateRandomString(3); //substr($_SERVER['REQUEST_TIME'], -3);//"309155597"; //shipmentID
            $invNo = $invNo; //"309155597-8629"; //pickupID

            $getBarcode = DB::table('thaipost_barcode')->where("is_active",0)->first();
            $barcode_id = $getBarcode->barcode_id;
            $barcode = $getBarcode->barcode;

            /*$update_active = DB::table('thaipost_barcode')
                ->where('barcode_id', $barcode_id)
                ->update(
                [
                    'cus_id' => $customerId,
                    'is_active' => 1,
                    'ref_order_id' => $orderId,
                    'update_date' =>  date('Y-m-d H:i:s')
                ]
            );*/
            
            $barcode = $barcode; //"EY660437537TH";
            $shipperName = "อนุศักดิ์ จิตโชติ";
            $shipperAddress = "999 ม.3 บางเลน";
            $shipperDistrict = "บางใหญ่ ";
            $shipperProvince = "นนทบุรี";
            $shipperZipcode = "11140";
            $shipperEmail = "tae@tuff.co.th";
            $shipperMobile = "085-8884698";
            $cusName = "Tuff Company";
            $cusAdd = "1/269 ซอยแจ้งวัฒนะ 14";
            $cusAmp = "หลักสี่";
            $cusProv = "กรุงเทพฯ";
            $cusZipcode = "10210";
            $cusTel = "020803999";
            $productPrice = "1000";
            $productInbox = "-";
            $productWeight = "400";
            $orderType = "D";
            $manifestNo = $manifestNo;
            //$merchantId = "FSTEST999'";
            $merchantId = "FASTSHIP88";
            $merchantZipcode = "10120";
            $storeLocationNo = "";
            $insurance = "";
            $insuranceRatePrice = "";
            $json .= '
            {
                "orderId": "'.$orderId.'",
                "invNo": "'.$invNo.'",
                "barcode": "'.$barcode.'",
                "shipperName": "'.$shipperName.'",
                "shipperAddress": "'.$shipperAddress.'",
                "shipperDistrict": "'.$shipperDistrict.'",
                "shipperProvince": "'.$shipperProvince.'",
                "shipperZipcode": "'.$shipperZipcode.'",
                "shipperEmail": "'.$shipperEmail.'",
                "shipperMobile": "'.$shipperMobile.'",
                "cusName": "'.$cusName.'",
                "cusAdd": "'.$cusAdd.'",
                "cusAmp": "'.$cusAmp.'",
                "cusProv": "'.$cusProv.'",
                "cusZipcode": "'.$cusZipcode.'",
                "cusTel": "'.$cusTel.'",
                "productPrice": "'.$productPrice.'",
                "productInbox": "'.$productInbox.'",
                "productWeight": "'.$productWeight.'",
                "orderType": "'.$orderType.'",
                "manifestNo": "'.$manifestNo.'",
                "merchantId": "'.$merchantId.'",
                "merchantZipcode": "'.$merchantZipcode.'",
                "storeLocationNo": "'.$storeLocationNo.'",
                "insurance": "'.$insurance.'",
                "insuranceRatePrice": "'.$insuranceRatePrice.'"
            }';
            //$json .= $i;
            if($i < $count-1){$json .= ',';}
            //$json .= $i;
            //$json .= ',';
            //usleep(100);                                                                                
        }
        $json .= '
        ]';

        alert($json);
        die();
        $url = 'https://r_dservice.thailandpost.com/webservice/addItems';
        $Response = callAPI_thaiPost('POST', $url, $json);
        $res = json_decode($Response, true);
        alert($Response);
        alert($res);
        die();
        alert($res[0]['errorCode']);
        $errorCode = $res[0]['errorCode'];
        $errorDetail = $res[0]['errorDetail'];
        if($errorCode == '000'){
            $is_active = 1;
        }else{
            $is_active = 2;
        }

        $update_status = DB::table('thaipost_barcode')
        ->where('barcode_id', $barcode_id)
        ->update(
            [
                'is_active' => $is_active,
                'status' => $errorDetail,
                'update_date' =>  date('Y-m-d H:i:s')
            ]
        );
        
        if($update_status){
            echo 'Success';
            //return redirect('/myaccount')->with('msg','ระบบได้ทำการอัปเดทข้อมูล เรียบร้อยแล้ว')->with('msg-type','success');
        }else{
            echo 'Fail';
            //return redirect('/myaccount')->with('msg','อัปเดทข้อมูลไม่สำเร็จ กรุณาลองใหม่อีกครั้ง');
        }  
        
        
    }

    public function createOrdersMore($count=null)
    {
        
        if(session('customer.id') != null){
            $customerId = session('customer.id');
        }else{
            return redirect('/')->with('msg','คุณยังไม่ได้เข้าระบบ กรุณาเข้าสู่ระบบเพื่อใช้งาน');
        }
        $count = $count; //Count Total shipment
        
        $json = '[
            {
                "orderId": "201806011624729",
                "invNo": "20180601-0109",
                "barcode": "EY031200090TH",
                "shipperName": "อนุศักดิ์ จิตโชติ",
                "shipperAddress": "999 ม.3 บางเลน",
                "shipperDistrict": "บางใหญ่ ",
                "shipperProvince": "นนทบุรี",
                "shipperZipcode": "11140",
                "shipperEmail": "tae@tuff.co.th",
                "shipperMobile": "085-8884698",
                "cusName": "Tuff Company",
                "cusAdd": "1/269 ซอยแจ้งวัฒนะ 14",
                "cusAmp": "หลักสี่",
                "cusProv": "กรุงเทพฯ",
                "cusZipcode": "10210",
                "cusTel": "020803999",
                "productPrice": "1000",
                "productInbox": "-",
                "productWeight": "400",
                "orderType": "D",
                "manifestNo": "CLC20180601-8112",
                "merchantId": "FASTSHIP88",
                "merchantZipcode": "10120",
                "storeLocationNo": "",
                "insurance": "",
                "insuranceRatePrice": ""
            },
            {
                "orderId": "201806011624729",
                "invNo": "20180601-0109",
                "barcode": "EY031200109TH",
                "shipperName": "อนุศักดิ์ จิตโชติ",
                "shipperAddress": "999 ม.3 บางเลน",
                "shipperDistrict": "บางใหญ่ ",
                "shipperProvince": "นนทบุรี",
                "shipperZipcode": "11140",
                "shipperEmail": "tae@tuff.co.th",
                "shipperMobile": "085-8884698",
                "cusName": "Tuff Company",
                "cusAdd": "1/269 ซอยแจ้งวัฒนะ 14",
                "cusAmp": "หลักสี่",
                "cusProv": "กรุงเทพฯ",
                "cusZipcode": "10210",
                "cusTel": "020803999",
                "productPrice": "1000",
                "productInbox": "-",
                "productWeight": "400",
                "orderType": "D",
                "manifestNo": "CLC20180601-8112",
                "merchantId": "FASTSHIP88",
                "merchantZipcode": "10120",
                "storeLocationNo": "",
                "insurance": "",
                "insuranceRatePrice": ""
            }
        ]';

        alert($json);
        //die();
        $url = 'https://r_dservice.thailandpost.com/webservice/addItems';
        $Response = callAPI_thaiPost('POST', $url, $json);
        $res = json_decode($Response, true);
        alert($Response);
        alert($res);
        die();


        alert($res[0]['errorCode']);
        $errorCode = $res[0]['errorCode'];
        $errorDetail = $res[0]['errorDetail'];
        if($errorCode == '000'){
            $is_active = 1;
        }else{
            $is_active = 2;
        }

        $update_status = DB::table('thaipost_barcode')
        ->where('barcode_id', $barcode_id)
        ->update(
            [
                'is_active' => $is_active,
                'status' => $errorDetail,
                'update_date' =>  date('Y-m-d H:i:s')
            ]
        );
        
        if($update_status){
            echo 'Success';
            //return redirect('/myaccount')->with('msg','ระบบได้ทำการอัปเดทข้อมูล เรียบร้อยแล้ว')->with('msg-type','success');
        }else{
            echo 'Fail';
            //return redirect('/myaccount')->with('msg','อัปเดทข้อมูลไม่สำเร็จ กรุณาลองใหม่อีกครั้ง');
        }  
        
        
    }

    public function createOrder2()
    {
        //Format :  PREYYYYMMDD-XXXX
        // Prefix : CLC
        $date_D = date("Y-m-d");
        $date_T = date("H:i");
        $f_time = date("H:i", strtotime("+2 hours"));
        $manifestNo = 'CLC'.date("Ymd").'-'.substr($_SERVER['REQUEST_TIME'], -4);
        $invNo = date("Ymd").'-'.substr($_SERVER['REQUEST_TIME'], -4);
        $gen_barcode = generateRandomString(7);
        $barcode = 'EY660437539TH';//'EY66'.$gen_barcode.'TH';
        $orderId = date("YmdHi").substr($_SERVER['REQUEST_TIME'], -3);//"309155597";
        $invNo = $invNo; //"309155597-8629";
        $barcode = $barcode; //"EY660437537TH";
        $shipperName = "อนุศักดิ์ จิตโชติ";
        $shipperAddress = "999 ม.3 บางเลน";
        $shipperDistrict = "บางใหญ่ ";
        $shipperProvince = "นนทบุรี";
        $shipperZipcode = "11140";
        $shipperEmail = "tae@tuff.co.th";
        $shipperMobile = "085-8884698";
        $cusName = "Tuff Company";
        $cusAdd = "1/269 ซอยแจ้งวัฒนะ 14";
        $cusAmp = "หลักสี่";
        $cusProv = "กรุงเทพฯ";
        $cusZipcode = "10210";
        $cusTel = "020803999";
        $productPrice = "1000";
        $productInbox = "-";
        $productWeight = "400";
        $orderType = "D";
        $manifestNo = $manifestNo;
        //$merchantId = "FSTEST999'";
        $merchantId = "FASTSHIP88";
        $merchantZipcode = "10120";
        $storeLocationNo = "";
        $insurance = "Y";
        $insuranceRatePrice = "500";
        //alert($barcode);
        //die();
        $json = '
        {
            "orderId": "'.$orderId.'",
            "invNo": "'.$invNo.'",
            "barcode": "'.$barcode.'",
            "shipperName": "'.$shipperName.'",
            "shipperAddress": "'.$shipperAddress.'",
            "shipperDistrict": "'.$shipperDistrict.'",
            "shipperProvince": "'.$shipperProvince.'",
            "shipperZipcode": "'.$shipperZipcode.'",
            "shipperEmail": "'.$shipperEmail.'",
            "shipperMobile": "'.$shipperMobile.'",
            "cusName": "'.$cusName.'",
            "cusAdd": "'.$cusAdd.'",
            "cusAmp": "'.$cusAmp.'",
            "cusProv": "'.$cusProv.'",
            "cusZipcode": "'.$cusZipcode.'",
            "cusTel": "'.$cusTel.'",
            "productPrice": "'.$productPrice.'",
            "productInbox": "'.$productInbox.'",
            "productWeight": "'.$productWeight.'",
            "orderType": "'.$orderType.'",
            "manifestNo": "'.$manifestNo.'",
            "merchantId": "'.$merchantId.'",
            "merchantZipcode": "'.$merchantZipcode.'",
            "storeLocationNo": "'.$storeLocationNo.'",
            "insurance": "'.$insurance.'",
            "insuranceRatePrice": "'.$insuranceRatePrice.'"
        }';
        alert($json);
        //$json = json_encode($json);
        $url = "https://r_dservice.thailandpost.com/webservice/addItem"; //Test
        //$Response = thaipost($url,$json);
        $Response = callAPI_thaiPost('POST', $url, $json);
        //$Response = test($url, $json);
        $res = json_decode($Response, true);
        alert($res);
    }

}
