<?php

namespace App\Http\Controllers;
use App;
use DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Lib\Fastship\Fastship;
use App\Lib\Fastship\FS_Shipment;
use App\Lib\Fastship\FS_Pickup;
use Mail;
use Session;
use App\Mail\Mailfastship;
use App\dataTable;
//use PDF;
use Barryvdh\DomPDF\Facade as PDF;
use Jcf\Geocode\Geocode;

class mailController extends Controller
{
	public $customerId;
    public function __construct()
    {

        if($_SERVER['REMOTE_ADDR'] == "localhost"){
            include(app_path() . '\Lib\inc.functions.php');
        }else{
            include(app_path() . '/Lib/inc.functions.php');
        }

        

    }

    public function send()
    {	
    	if (session('customer.id') != null){
			$customerId = session('customer.id');
		}else{
			return redirect('/')->with('msg','คุณยังไม่ได้เข้าระบบ กรุณาเข้าสู่ระบบเพื่อใช้งาน');
		}

		$validateCustomer = DB::table('customer')
							->select("CUST_FIRSTNAME","CUST_LASTNAME","CUST_EMAIL")
							->where('CUST_ID', $customerId)
							->where("IS_ACTIVE",1)
							->first();
		//alert($validateCustomer);
		$toName = $validateCustomer->CUST_FIRSTNAME.' '.$validateCustomer->CUST_LASTNAME;
		$eMail = $validateCustomer->CUST_EMAIL;
		//alert($toName);alert($eMail);
		//die();
		//alert($customerId);
    	Fastship::getToken($customerId);
        //get pickup by pickup_id
        $pickupId = 265563;
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
        //alert($data);die();
        Mail::send('mail',$data,function($message) use ($data){
        	$message->to($data['email']);
        	$message->from('info@fastship.co', 'FastShip');
        	$message->subject('ใบรับพัสดุจาก FastShip หมายเลข '. $data['pickupId'] ." ถูกสร้างแล้ว");
        });
        return redirect('/myaccount')->with('msg','ระบบได้ทำการส่งอีเมลล์ เรียบร้อยแล้ว')->with('msg-type','success');
    }


    public function pdfAttachment()
    {   //alert(public_path('fonts/THSarabunNew.ttf'));die();
        if (session('customer.id') != null){
            $customerId = session('customer.id');
        }else{
            return redirect('/')->with('msg','คุณยังไม่ได้เข้าระบบ กรุณาเข้าสู่ระบบเพื่อใช้งาน');
        }

        $validateCustomer = DB::table('customer')
                            ->select("CUST_FIRSTNAME","CUST_LASTNAME","CUST_EMAIL")
                            ->where('CUST_ID', $customerId)
                            ->where("IS_ACTIVE",1)
                            ->first();
        //alert($validateCustomer->CUST_EMAIL);
        $toName = $validateCustomer->CUST_FIRSTNAME.' '.$validateCustomer->CUST_LASTNAME;
        $eMail = $validateCustomer->CUST_EMAIL;
        //alert($toName);alert($eMail);
        //die();
        $customerId = 1365;
        Fastship::getToken($customerId);
        //get pickup by pickup_id
        $pickupId = 262468;
        $shipment_data = array();
        $response = FS_Pickup::get($pickupId);
        foreach ($response['ShipmentDetail']['ShipmentIds'] as $shipmentId) {
            $shipment_data[] = FS_Shipment::get($shipmentId);
        }
        //alert($response);alert($shipment_data);die();
        //$response = array();
        //$shipment_data = array();
        $data = array(
            'pickupId' => $pickupId,
            'name' => $toName,
            'email' => $eMail,
            'pickupData' => $response,
            'shipmentData' => $shipment_data,
        );
        //alert($data);die();
        $pdf = App::make('dompdf');
        //$pdf = PDF::loadView('mail', $data)->setPaper('a4');
        //$pdf = PDF::loadView('genfilepdf', $data)->setPaper('a4');
        //$pdf = PDF::setOptions(['dpi' => 150, 'defaultFont' => 'sans-serif']);
        $pdf = PDF::loadView('genpdf', $data)->setPaper('a4');
        //return $pdf->stream('document.pdf');die();
        
        //Mail::send('genpdf',$data,function($message) use ($data,$pdf){
        //Mail::send('testsendmail', $data, function($message) use ($data,$pdf){
        Mail::send('mail', $data, function($message) use ($data,$pdf){
            $message->to($data['email']);
            $message->from('info@fastship.co', 'FastShip');
            $message->subject('Test attach file');
            $message->attachData($pdf->output(),'ใบรับพัสดุจากFastShip.pdf');
            //$message->attachData($pdf->output(),'customer.pdf');
        });
        //return redirect('/myaccount')->with('msg','ระบบได้ทำการส่งอีเมลล์ เรียบร้อยแล้ว')->with('msg-type','success');
        return 'success';
    }

    public function pdfAttachment_bk()
    {
        //Sending Email with a PDF Attachment
        //https://laracasts.com/discuss/channels/laravel/sending-email-with-a-pdf-attachment
        //Send a PDF document as an attachment via email in Laravel 5.4
        //https://stackoverflow.com/questions/49125123/send-a-pdf-document-as-an-attachment-via-email-in-laravel-5-4
        
        $pickupId = 265563;
        $shipment_data = array();
        $response = array();
        $shipment_data = array();
        $eMail = 'tae@tuff.co.th';
        $data = array(
            'pickupId' => $pickupId,
            'email' => $eMail,
            'pickupData' => $response,
            'shipmentData' => $shipment_data,
        );
        /*alert($data);die();
        Mail::send('mail',$data,function($message) use ($data){
            $message->to($data['email']);
            $message->from('info@fastship.co', 'FastShip');
            $message->subject('ใบรับพัสดุจาก FastShip หมายเลข '. $data['pickupId'] ." ถูกสร้างแล้ว");
        });*/


        //
        $pdf = App::make('dompdf');
        //$pdf = \PDF::loadView('genpdf', $data)->setPaper('a4');
        $pdf = PDF::loadView('genpdf', $data);
        //$pdf = public_path("pdf\decoded.pdf"); 
        //alert($target_dir);die();
        Mail::send('genpdf', $data, function($message) use ($data,$pdf){
            $message->from('info@fastship.co', 'FastShip');
            $message->to($data['email']);
            $message->subject('Thank you message');
            //Attach PDF doc
            $message->attachData($pdf->output(),'customer.pdf');
            //$message->attachData(base64_decode($pdf),'customer.pdf');
        });


        return redirect('/myaccount')->with('msg','ระบบได้ทำการส่งอีเมลล์ เรียบร้อยแล้ว')->with('msg-type','success');
    }








    public function send_bk()
    {	
    	if (session('customer.id') != null){
			$customerId = session('customer.id');
		}else{
			return redirect('/')->with('msg','คุณยังไม่ได้เข้าระบบ กรุณาเข้าสู่ระบบเพื่อใช้งาน');
		}

		$validateCustomer = DB::table('customer')
							->select("CUST_FIRSTNAME","CUST_LASTNAME","CUST_EMAIL")
							->where('CUST_ID', $customerId)
							->where("IS_ACTIVE",1)
							->first();
		alert($validateCustomer);
		$toName = $validateCustomer->CUST_FIRSTNAME.' '.$validateCustomer->CUST_LASTNAME;
		$eMail = $validateCustomer->CUST_EMAIL;
		alert($toName);alert($eMail);
		//die();
		$customerId=65;
    	Fastship::getToken($customerId);
        //get pickup by pickup_id
        $pickupId = 265563;
        $response = FS_Pickup::get($pickupId);
    	$data = array(
        	'to' => $toName,
        	'eMail' => $eMail,
        	'content' => 'For Test',
        	'pickupData' => $response,
        	'from' => 'FastShip Company',
        );


        //alert($data);
        //alert($response);
        //die();
    	/*$customerId = session('customer.id');
    	
    	return view('mail',$data);
    	die();

		Mail::send('emailTemplates.dummy', ['emailBody'=>'<h1>TESTING</h1>'], function($message){
			$message->to($myEmail)->subject('Password reset');
		});
		*/

        /*Mail::send(['text'=>'mail'],['name','Potae'],function($message){
        	$message->to('tae@tuff.co.th', 'Custome FastShip')->subject('FastShip Test Email');
        	$message->from('tae@tuff.co.th', 'FastShip');
        });*/

        /*Mail::send('mail',$data,function($message){
        	$message->to($eMail);
        	$message->from('tae@tuff.co.th', 'FastShip');
        	$message->subject('FastShip Test Email');
        });*/

        Mail::send('email/create_pickup',$data,function($message) use ($data){
        	$message->to($data['eMail']);
        	$message->from('info@fastship.co', 'FastShip');
        	$message->subject('FastShip Test Email');
        });
        return redirect('/myaccount')->with('msg','ระบบได้ทำการส่งอีเมลล์ เรียบร้อยแล้ว')->with('msg-type','success');
    }



    public function sendEmail()
    {   
        if (session('customer.id') != null){
            $customerId = session('customer.id');
        }else{
            return redirect('/')->with('msg','คุณยังไม่ได้เข้าระบบ กรุณาเข้าสู่ระบบเพื่อใช้งาน');
        }
        $toName = 'Potae';
        $email = 'tae@tuff.co.th';
        $response = '';
        $data = array(
            'to' => $toName,
            'eMail' => $email,
            'content' => 'For Test',
            'pickupData' => $response,
            'from' => 'FastShip Company',
        );

        Mail::send('test_mail',$data,function($message) use ($data){
            $message->to($data['eMail']);
            $message->from('info@fastship.co', 'cs@fastship.co');
            //$message->from('info@fastship.co');
            $message->subject('FastShip Test Email');
        });
        //return redirect('/myaccount')->with('msg','ระบบได้ทำการส่งอีเมลล์ เรียบร้อยแล้ว')->with('msg-type','success');
        

        //\Mail::to('tae@tuff.co.th')->send(new Mailfastship());
        //\Mail::to('anusak2527@gmail.com')->send(new Mailfastship());


    }

    public function getLocation(Request $request)
    {
        //$ip = Request::getClientIp();
        $ip = $request->ip();alert($ip);
        //dd($ip);
        //$ip= '183.88.83.12';
        $data = \Location::get($ip);
        alert($data);
    }

    public function testGeocode($data=null)
    {
        //$response = Geocode::make()->address('1 Infinite Loop');
        if (session('customer.id') != null){
            $customerId = session('customer.id');
        }else{
            return redirect('/')->with('msg','คุณยังไม่ได้เข้าระบบ กรุณาเข้าสู่ระบบเพื่อใช้งาน');
        }

        $customer = DB::table('customer')
                            ->select("CUST_FIRSTNAME","CUST_LASTNAME","CUST_ADDR1","CUST_ADDR2","CUST_CITY","CUST_STATE","CUST_POSTCODE","CNTRY_CODE")
                            ->where('CUST_ID', $customerId)
                            ->where("IS_ACTIVE",1)
                            ->first();
        /*alert($customer->CUST_ADDR1);
        alert($customer->CUST_ADDR2);
        alert($customer->CUST_CITY);
        alert($customer->CUST_POSTCODE);
        alert($customer->CNTRY_CODE);*/
        $location = $data;
        //$location = $customer->CUST_ADDR1.','.$customer->CUST_ADDR2.','.$customer->CUST_CITY.','.$customer->CUST_POSTCODE.','.$customer->CNTRY_CODE;
        alert($location);
        $response = Geocode::make()->address($location);
        if ($response) {
            alert($response->latitude());
            alert($response->longitude());
            //alert($response->formattedAddress());
            //alert($response->locationType());
            //echo $response->latitude();
            //echo $response->longitude();
            //echo $response->formattedAddress();
            //echo $response->locationType();
        }
    }



    
}
