<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;
use Excel;
use Illuminate\Support\Facades\Input;
use App\Lib\Fastship\Fastship;
use App\Lib\Fastship\FS_Shipment;

class UploadController extends Controller
{
    public function __construct()
    {
        date_default_timezone_set("Asia/Bangkok");
        
        if($_SERVER['REMOTE_ADDR'] == "localhost" || $_SERVER['REMOTE_ADDR'] == "127.0.0.1"){
            include(app_path() . '\Lib\inc.functions.php');
            //include(app_path() . '\Lib\omise.functions.php');
        }else{
            include(app_path() . '/Lib/inc.functions.php');
            //include(app_path() . '/Lib/omise.functions.php');

            //https://laravel-excel.maatwebsite.nl/docs/3.0/getting-started/installation
            //https://www.youtube.com/watch?v=AeAh_7dAsQs
            //https://www.youtube.com/watch?v=6P_nqOX38CE
        }
    }

    public function index()
    {
        //
    }

    
    public function create()
    {
        //
    }

    
    public function importFile(Request $request)
    {
        if(session('customer.id') != null){
            $customerId = session('customer.id');
        }else{
            return redirect('/')->with('msg','คุณยังไม่ได้เข้าระบบ กรุณาเข้าสู่ระบบเพื่อใช้งาน');
        }
        //alert($customerId);
        //alert($request->all());
        if(!isset($request->upload)){
            //return redirect()->back()->withInput($request->all);
            return redirect()->back()->with('status', 'Choose file please! ');
        }else{
            //get api token
            Fastship::getToken($customerId);

            $data = array();
            $customerObj = DB::table('customer')->where("CUST_ID",$customerId)->where("IS_ACTIVE",1)->first();

            if($customerObj == null){ 
                //return redirect('create_shipment')->with('msg','Customer id is null');
                echo 'Customer id is null';
            }else{
                
                /*$data['Sender_Firstname'] = $customerObj->CUST_FIRSTNAME;
                $data['Sender_Lastname'] = $customerObj->CUST_LASTNAME;
                $data['Sender_PhoneNumber'] = $customerObj->CUST_TEL;
                $data['Sender_Email'] = $customerObj->CUST_EMAIL;
                $data['Sender_Company'] = $customerObj->CUST_COMPANY;
                $data['Sender_AddressLine1'] = $customerObj->CUST_ADDR1;
                $data['Sender_AddressLine2'] = $customerObj->CUST_ADDR2;
                $data['Sender_City'] = $customerObj->CUST_CITY;
                $data['Sender_State'] = $customerObj->CUST_STATE;
                $data['Sender_Postcode'] = $customerObj->CUST_POSTCODE;
                $data['Sender_Country'] = $customerObj->CNTRY_CODE;*/
                $Sender_Firstname = $customerObj->CUST_FIRSTNAME;
                $Sender_Lastname = $customerObj->CUST_LASTNAME;
                $Sender_PhoneNumber = $customerObj->CUST_TEL;
                $Sender_Email = $customerObj->CUST_EMAIL;
                $Sender_Company = $customerObj->CUST_COMPANY;
                $Sender_AddressLine1 = $customerObj->CUST_ADDR1;
                $Sender_AddressLine2 = $customerObj->CUST_ADDR2;
                $Sender_City = $customerObj->CUST_CITY;
                $Sender_State = $customerObj->CUST_STATE;
                $Sender_Postcode = $customerObj->CUST_POSTCODE;
                $Sender_Country = $customerObj->CNTRY_CODE;
                //alert($data);
                //die();
                //file upload
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
                /*$results = Excel::load($target_dir.$file_name, function($reader)
                {
                    $reader->all();
                })->get()->toArray();
                //})->get();*/

                /*Excel::filter('chunk')->load($path)->chunk(200, function ($results)
                {
                foreach ($results as $row)
                {
                    $group = new Group();
                    $group->name = $row->codigo;
                    $group->save();
                    }
                });*/
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
                //alert($data_import);//die();
                $i=1;
                //get api token
                //Fastship::getToken($customerId);
                foreach ($data_import as $v) {
                    //alert($i);
                    //Receiver
                    $data['Receiver_Firstname'] = 'Arya';//$v['receiver_name'];
                    $data['Receiver_Lastname'] = 'Stark';//$v['receiver_name'];
                    $data['Receiver_PhoneNumber'] = $v['receiver_tel'];
                    $data['Receiver_Email'] = $v['receiver_email'];
                    $data['Receiver_Company'] = '';//$v['receiver_name'];
                    $data['Receiver_AddressLine1'] = $v['receiver_addr1'];
                    $data['Receiver_AddressLine2'] = $v['receiver_addr2'];
                    $data['Receiver_City'] = $v['receiver_city'];
                    $data['Receiver_State'] = $v['receiver_state'];
                    $data['Receiver_Postcode'] = $v['receiver_postcode'];
                    $data['Receiver_Country'] = $v['receiver_country_code'];
                    $data['TermOfTrade'] = 'DDU';//strtoupper($v['receiver_name']);
                    $data['ShippingAgent'] = $v['shippingagent'];
                    $data['Weight'] = $v['weight'];
                    $data['Width'] = $v['package_width'];
                    $data['Height'] = $v['package_height'];
                    $data['Length'] = $v['package_length'];

                    $category = explode(';',$v['producttype'],-1);
                    $amount = explode(';',$v['productvalue'],-1);
                    $qty = explode(';',$v['productqty'],-1);
                                        
                    foreach ($category as $key => $cat) {
                        $Declarations[$key] = array(
                            'DeclareType' => $cat,
                            'DeclareQty' => $qty[$key],
                            'DeclareValue' => $amount[$key],
                        );
                    }
                    //alert($Declarations);die();
                    $data['Reference'] = '';
                    $data['Remark'] = $v['order_note'];
                    //alert($data);die();

                    //get api token
                    //Fastship::getToken($customerId);
                    
                    //prepare request data
                    $createDetails = array(
                        'ShipmentDetail' => array(
                            'ShippingAgent' => $data['ShippingAgent'],
                            'Weight' => $data['Weight'],
                            'Width' => $data['Width'],
                            'Height' => $data['Height'],
                            'Length' => $data['Length'],
                            'Declarations' => $Declarations,
                            'TermOfTrade' => $data['TermOfTrade'],
                        ),
                        'SenderDetail' => array(
                            'Firstname' => $Sender_Firstname,
                            'Lastname' => $Sender_Lastname,
                            'PhoneNumber' => $Sender_PhoneNumber,
                            'Email' => $Sender_Email,
                            'Company' => $Sender_Company,
                            'AddressLine1' => $Sender_AddressLine1,
                            'AddressLine2' => $Sender_AddressLine2,
                            'City' => $Sender_City,
                            'State' => $Sender_State,
                            'Postcode' => $Sender_Postcode,
                            'Country' => $Sender_Country,
                        ),
                        'ReceiverDetail' => array(
                            'Firstname' => $data['Receiver_Firstname'],
                            'Lastname' => $data['Receiver_Lastname'],
                            'PhoneNumber' => $data['Receiver_PhoneNumber'],
                            'Email' => $data['Receiver_Email'],
                            'Company' => $data['Receiver_Company'],
                            'AddressLine1' => $data['Receiver_AddressLine1'],
                            'AddressLine2' => $data['Receiver_AddressLine2'],
                            'City' => $data['Receiver_City'],
                            'State' => $data['Receiver_State'],
                            'Postcode' => $data['Receiver_Postcode'],
                            'Country' => $data['Receiver_Country'],
                        ),
                        'Reference' => $data['Reference'],
                        'Remark' => $data['Remark'],
                    );
                    //alert($createDetails);
                    //call api
                    $response = FS_Shipment::create($createDetails);
                    alert($response);
                    if($response === false){
                        //alert($i.'. Fail= '.date("Y-m-d H:i:s"));
                        echo 'Fail= '.date("Y-m-d H:i:s").'<br>';
                    }else{
                        //alert($i.'. Success= '.date("Y-m-d H:i:s"));
                        echo 'Success= '.date("Y-m-d H:i:s").'<br>';
                    }

                    //Test Insert to database
                    //usleep(10);
                    /*$insert = DB::table('order')->insert([
                        'ORDER_SENDER_FIRSTNAME' => $Sender_Firstname,
                        'ORDER_SENDER_LASTNAME' => $Sender_Lastname,
                        'ORDER_SENDER_EMAIL' => $Sender_Email,
                        'ORDER_SENDER_TEL' => $Sender_PhoneNumber,
                        'ORDER_SENDER_COMPANY' => $Sender_Company,
                        'ORDER_SENDER_ADDR1' => $Sender_AddressLine1,
                        'ORDER_SENDER_ADDR2' => $Sender_AddressLine2,
                        'ORDER_SENDER_CITY' => $Sender_City,
                        'ORDER_SENDER_STATE' => $Sender_State,
                        'ORDER_SENDER_POSTCODE' => $Sender_Postcode,
                        'ORDER_SENDER_CNTRY' => $Sender_Country,
                        'ORDER_CUSTNAME' => $data['Receiver_Firstname'],
                        'ORDER_FIRSTNAME' => $data['Receiver_Firstname'],
                        'ORDER_LASTNAME' => $data['Receiver_Lastname'],
                        'ORDER_EMAIL' => $data['Receiver_Email'],
                        'ORDER_TEL' => $data['Receiver_PhoneNumber'],
                        'ORDER_ADDR1' => $data['Receiver_AddressLine1'],
                        'ORDER_ADDR2' => $data['Receiver_AddressLine2'],
                        'ORDER_CITY' => $data['Receiver_City'],
                        'ORDER_STATE' => $data['Receiver_State'],
                        'ORDER_POSTCODE' => $data['Receiver_Postcode'],
                        'CNTRY_CODE' => $data['Receiver_Country'],
                        'ORDER_SUBTOTAL' => 1.00,
                        'ORDER_WIDTH' => $data['Width'],
                        'ORDER_HEIGHT' => $data['Height'],
                        'ORDER_LENGTH' => $data['Length'],
                        'ORDER_ACTWEIGHT' => 0.00,
                        'ORDER_WEIGHT' => $data['Weight'],
                        'ORDER_TRACKING' => '',
                        'ORDER_TRACKSTATUS' => '',
                        'ORDER_TRACKHISTORY' => '',
                        'ORDER_SHIPPINGAGENT' => $data['ShippingAgent'],
                        'ORDER_BPSHIPPINGAGENT' => $data['ShippingAgent'],
                        'ORDER_SHIPPINGRATE' => 0.00,
                        'ORDER_PRODUCTTYPE' => $v['producttype'],
                        'ORDER_PRODUCTQTY' => $v['productqty'],
                        'ORDER_PRODUCTVALUE' => $v['productvalue'],
                        'ORDER_TERM' => $data['TermOfTrade'],
                        'ORDER_INSURANCE' => 0.00,
                        'ORDER_NOTE' => $data['Receiver_Firstname'],
                        'ORDER_REFNOTE' => $data['Receiver_Firstname'],
                        'ORDER_REFACCOUNT' => '',
                        'ORDER_TRANSID' => date("YmdHis"),
                        'ORDER_STATUS' => 0,
                        'IS_CLOUDORDER' => 0,
                        'CREATE_DATETIME'=> date("Y-m-d H:i:s"),
                    ]);
                    //usleep(10);
                    if($insert){
                        alert($i.'. Success= '.date("Y-m-d H:i:s"));
                    }else{
                        alert($i.'. Fail= '.date("Y-m-d H:i:s"));
                    }*/
                    //alert($i.'. Tset = '.date("Y-m-d H:i:s"));
                    $i++;
                    
                }
            }
        }
    }

    public function createShipment(Request $request)
    {
        //check customer login
        if (session('customer.id') != null){
            $customerId = session('customer.id');
        }else{
            return redirect('/')->with('msg','คุณยังไม่ได้เข้าระบบ กรุณาเข้าสู่ระบบเพื่อใช้งาน');
        }
        
        $data = array();
       
        $customerObj = DB::table('customer')->where("CUST_ID",$customerId)->where("IS_ACTIVE",1)->first();

        if($customerObj == null){ 
            return redirect('create_shipment')->with('msg','Customer id is null');
        }else{
            
            $data['Sender_Firstname'] = $customerObj->CUST_FIRSTNAME;
            $data['Sender_Lastname'] = $customerObj->CUST_LASTNAME;
            $data['Sender_PhoneNumber'] = $customerObj->CUST_TEL;
            $data['Sender_Email'] = $customerObj->CUST_EMAIL;
            $data['Sender_Company'] = $customerObj->CUST_COMPANY;
            $data['Sender_AddressLine1'] = $customerObj->CUST_ADDR1;
            $data['Sender_AddressLine2'] = $customerObj->CUST_ADDR2;
            $data['Sender_City'] = $customerObj->CUST_CITY;
            $data['Sender_State'] = $customerObj->CUST_STATE;
            $data['Sender_Postcode'] = $customerObj->CUST_POSTCODE;
            $data['Sender_Country'] = $customerObj->CNTRY_CODE;

            //Receiver
            $data['Receiver_Firstname'] = $request->input('firstname');
            $data['Receiver_Lastname'] = $request->input('lastname');
            $data['Receiver_PhoneNumber'] = $request->input('phonenumber');
            $data['Receiver_Email'] = $request->input('email');
            $data['Receiver_Company'] = $request->input('company');
            $data['Receiver_AddressLine1'] = $request->input('address1');
            $data['Receiver_AddressLine2'] = $request->input('address2');
            $data['Receiver_City'] = $request->input('city');
            $data['Receiver_State'] = $request->input('state');
            $data['Receiver_Postcode'] = $request->input('postcode');
            //$data['Receiver_Country'] = $request->input('country');
            $data['TermOfTrade'] = strtoupper($request->input('term'));
            $data['ShippingAgent'] = $request->input('agent');
            $data['Weight'] = $request->input('weight');

            if ( (!empty($request->input('weight')) && $request->input('weight') > 0) && !empty($request->input('country'))){
                $data['Weight'] = $request->input('weight');
                $data['Receiver_Country'] = $request->input('country');
            }else{
                return redirect('create_shipment')->with('msg','Weight is null');
            }

            if( !empty($request->input('width')) ){
                $data['Width'] = $request->input('width');
            }else{
                $data['Width'] = 0;
            }

            if( !empty($request->input('height')) ){
                $data['Height'] = $request->input('height');
            }else{
                $data['Height'] = 0;
            }

            if( !empty($request->input('length')) ){
                $data['Length'] = $request->input('length');
            }else{
                $data['Length'] = 0;
            }

            $category = $request->input('category');
            $amount = $request->input('amount');
            $value = $request->input('value');
            $Remark = $request->input('note');
            $Reference = $request->input('orderref');


            foreach ($category as $key => $cat) {
                $Declarations[$key] = array(
                    'DeclareType' => $cat,
                    'DeclareQty' => $amount[$key],
                    'DeclareValue' => $value[$key],
                );
            }

            //get api token
            Fastship::getToken($customerId);

            //prepare request data
            $createDetails = array(
                'ShipmentDetail' => array(
                    'ShippingAgent' => $data['ShippingAgent'],
                    'Weight' => $data['Weight'],
                    'Width' => $data['Width'],
                    'Height' => $data['Height'],
                    'Length' => $data['Length'],
                    'Declarations' => $Declarations,
                    'TermOfTrade' => $data['TermOfTrade'],
                ),
                'SenderDetail' => array(
                    'Firstname' => $data['Sender_Firstname'],
                    'Lastname' => $data['Sender_Lastname'],
                    'PhoneNumber' => $data['Sender_PhoneNumber'],
                    'Email' => $data['Sender_Email'],
                    'Company' => $data['Sender_Company'],
                    'AddressLine1' => $data['Sender_AddressLine1'],
                    'AddressLine2' => $data['Sender_AddressLine2'],
                    'City' => $data['Sender_City'],
                    'State' => $data['Sender_State'],
                    'Postcode' => $data['Sender_Postcode'],
                    'Country' => $data['Sender_Country'],
                ),
                'ReceiverDetail' => array(
                    'Firstname' => $data['Receiver_Firstname'],
                    'Lastname' => $data['Receiver_Lastname'],
                    'PhoneNumber' => $data['Receiver_PhoneNumber'],
                    'Email' => $data['Receiver_Email'],
                    'Company' => $data['Receiver_Company'],
                    'AddressLine1' => $data['Receiver_AddressLine1'],
                    'AddressLine2' => $data['Receiver_AddressLine2'],
                    'City' => $data['Receiver_City'],
                    'State' => $data['Receiver_State'],
                    'Postcode' => $data['Receiver_Postcode'],
                    'Country' => $data['Receiver_Country'],
                ),
                'Reference' => $Reference,
                'Remark' => $Remark,
            );
            //alert($createDetails);
            //call api
            $response = FS_Shipment::create($createDetails);
            //return redirect('create_pickup')->with(['data' => $response]);
            //return redirect('create_pickup')->with('msg',$response);
            //return redirect('create_pickup/'.$response);
            //alert($response);die();
            if($response === false){
                $data_callback['firstname'] = $request->input('firstname');
                $data_callback['lastname'] = $request->input('lastname');
                $data_callback['phonenumber'] = $request->input('phonenumber');
                $data_callback['email'] = $request->input('email');
                $data_callback['company'] = $request->input('company');
                $data_callback['address1'] = $request->input('address1');
                $data_callback['address2'] = $request->input('address2');
                $data_callback['city'] = $request->input('city');
                $data_callback['state'] = $request->input('state');
                $data_callback['postcode'] = $request->input('postcode');
                $data_callback['country'] = $request->input('country');
                $data_callback['term'] = strtoupper($request->input('term'));
                $data_callback['agent'] = $request->input('agent');
                $data_callback['price'] = $request->input('price');
                $data_callback['delivery_time'] = $request->input('delivery_time');
                $data_callback['weight'] = $request->input('weight');
                $data_callback['width'] = $request->input('width');
                $data_callback['height'] = $request->input('height');
                $data_callback['length'] = $request->input('length');
                $data_callback['category'] = $request->input('category');
                $data_callback['amount'] = $request->input('amount');
                $data_callback['value'] = $request->input('value');
                $data_callback['status'] = "Fail";
                //return redirect('calculate_shipment_rate');
                //return redirect('create_shipment_data', array('view' => "true", 'query' => $data))->with(array("status" => "Fail"));
                //return redirect('create_shipment_data', array('data' => $data));
                //return redirect()->route('create_shipment_data', ['data' =>  $data])->with('message', 'State');
                return redirect()->action(
                    'Shipment\ShipmentController@prepareCreateShipment', array('data' => $data_callback)
                );
            }else{
                $status = 'Success';
                //return redirect('create_pickup/'.$status);
                
                $request->session()->put('pending.shipment', session('pending.shipment')+1);
                
                return redirect('create_pickup');
            }
        }
    }

    public function importFile_BK(Request $request)
    {
        if(session('customer.id') != null){
            $customerId = session('customer.id');
        }else{
            return redirect('/')->with('msg','คุณยังไม่ได้เข้าระบบ กรุณาเข้าสู่ระบบเพื่อใช้งาน');
        }
        alert($customerId);die();
        //alert($request->all());
        if(!isset($request->upload)){
            //return redirect()->back()->withInput($request->all);
            return redirect()->back()->with('status', 'Choose file please! ');
        }else{

            $data = array();
            $customerObj = DB::table('customer')->where("CUST_ID",$customerId)->where("IS_ACTIVE",1)->first();

            if($customerObj == null){ 
                return redirect('create_shipment')->with('msg','Customer id is null');
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
                /*$results = Excel::load($target_dir.$file_name, function($reader)
                {
                    $reader->all();
                })->get()->toArray();
                //})->get();*/
                $data = array();
                Excel::load($target_dir.$file_name, function ($reader) {
                    $reader->each(function($sheet) {    
                        foreach ($sheet->toArray() as $row) {
                            //Insert to database
                            $this->data[] = $row;
                        }
                    });
                });
                alert($this->data);
                /*foreach ($this->data as $v) {
                    alert($v);
                }*/

                /*
                foreach($results as $row) {
                  alert($row['receiver_name']);
                }
                
                foreach ($results as $key => $value) {
                    //alert($value->task);
                    //alert($value->task);
                    //alert($value->owner);
                    //$value = preg_replace('/\D/', '', $value);
                    //alert($value['heading']);
                    //alert($value);
                    $res[] = $value;
                }
                //$data = array_combine(keys, $value)
                //alert($res);

                $i=0;
                foreach ($res as $v) {
                    alert($v);
                    $i++;
                }
                */
            }
        }
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
