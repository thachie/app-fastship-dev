<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;
use App\Lib\Peak\PeakManager;
use App\Lib\Fastship\Fastship;
use App\Lib\Fastship\FS_Shipment;

class PeakController extends Controller
{
    public function __construct()
    {
        date_default_timezone_set("Asia/Bangkok");
        
        if($_SERVER['REMOTE_ADDR'] == "localhost" || $_SERVER['REMOTE_ADDR'] == "127.0.0.1"){
            include(app_path() . '\Lib\Peak\peak.functions.php');
        }else{
            include(app_path() . '/Lib/Peak/peak.functions.php');
        }
    }

    public function index()
    {
        $clientToken = PeakManager::authentication();
        alert($clientToken);    
        alert(9999);    
    }

    public function createContact()
    {
        if(session('customer.id') != null){
            $customerId = session('customer.id');
        }else{
            return redirect('/')->with('msg','คุณยังไม่ได้เข้าระบบ กรุณาเข้าสู่ระบบเพื่อใช้งาน');
        }

        $getContact = DB::table('peak_contacts')->where("cus_id",$customerId)->first();
        //$getContact = DB::table('peak_contacts')->where("cus_id",$customerId)->count();
        $getContact = null;
        if($getContact == null || count($getContact) == 0){
            $customerObj = DB::table('customer')->where("CUST_ID",$customerId)->where("IS_ACTIVE",1)->first();
            $customer_data = array();
            $customer_data['fullName'] = $customerObj->CUST_FIRSTNAME.' '.$customerObj->CUST_LASTNAME;
            $customer_data['firstname'] = $customerObj->CUST_FIRSTNAME;
            $customer_data['lastname'] = $customerObj->CUST_LASTNAME;
            $customer_data['taxID'] = $customerObj->CUST_TAXID;
            $customer_data['phonenumber'] = $customerObj->CUST_TEL;
            $customer_data['email'] = $customerObj->CUST_EMAIL;
            $customer_data['company'] = $customerObj->CUST_COMPANY;
            $customer_data['address1'] = $customerObj->CUST_ADDR1;
            $customer_data['address2'] = $customerObj->CUST_ADDR2;
            $customer_data['city'] = $customerObj->CUST_CITY;
            $customer_data['state'] = $customerObj->CUST_STATE;
            $customer_data['postcode'] = $customerObj->CUST_POSTCODE;
            $customer_data['country'] = $customerObj->CNTRY_CODE;
            //alert($customer_data);
            //die();
            $clientToken = PeakManager::authentication();
            $contacts[] = array(
                //'name' => $customer_data['fullName'],
                'name' => "TestAPI Contacts ".substr($_SERVER['REQUEST_TIME'],-2).generateRandomString(2),
                'type' => 4,
                'taxNumber' => $customer_data['taxID'],
                'branchCode' => "",
                'address' => $customer_data['address1'],
                'subDistrict' => $customer_data['address2'],
                'district' => $customer_data['city'],
                'province' => $customer_data['state'],
                'country' => $customer_data['country'],
                'postCode' => $customer_data['postcode'],
                'callCenterNumber' => "",
                'email' => $customer_data['email'],
                'contactFirstName' => $customer_data['firstname'],
                'contactLastName' => $customer_data['lastname'],
                'contactPhoneNumber' => $customer_data['phonenumber'],
                'contactEmail' => $customer_data['email']
            );
            
            /*
             $contacts[] = array(
                'name' => "TestAPI Contacts ".substr($_SERVER['REQUEST_TIME'],-2).generateRandomString(2),
                'type' => 4,
                'taxNumber' => "",
                'branchCode' => "",
                'address' => $customer_data['address1'],
                'subDistrict' => $customer_data['address2'],
                'district' => $customer_data['city'],
                'province' => $customer_data['state'],
                'country' => $customer_data['country'],
                'postCode' => $customer_data['postcode'],
                'callCenterNumber' => "",
                'faxNumber' => "",
                'email' => $customer_data['email'],
                'website' => "",
                'contactFirstName' => $customer_data['firstname'],
                'contactLastName' => $customer_data['lastname'],
                'contactNickName' => "",
                'contactPosition' => "",
                'contactPhoneNumber' => $customer_data['phonenumber'],
                'contactEmail' => $customer_data['email']
            );
            */
            $params['clientToken'] = $clientToken;
            $params['contacts'] = $contacts;
            $params['url'] = 'http://peakengineapidev.azurewebsites.net/api/v1/contacts';
            //alert($params);die();
            //$url = 'http://peakengineapidev.azurewebsites.net/api/v1/contacts';
            $res = PeakManager::createContacts($params);
            alert($res);
            $data = $res->PeakContacts->contacts;
            $resCode = $data[0]->resCode;
            $resDesc = $data[0]->resDesc;
            $contact_id = $data[0]->id;
            alert($data[0]->id);//die();
            if($resCode == 200){
                echo 'resCode =>'.$resCode.', resDesc => '.$resDesc;
                $contact_id = $data[0]->id;
                $insert = DB::table('peak_contacts')->insert([
                    'contact_id' => $contact_id,
                    'cus_id' => $customerId,
                    'create_date'=> date("Y-m-d H:i:s"),
                ]);
                if($insert){
                    alert('Insert Success');
                    $update_cus = DB::table('customer')
                    ->where('CUST_ID', $customerId)
                    ->update(
                        [
                            'PEAK_CONTACT_ID' => $contact_id,
                            'UPDATE_DATETIME' =>  date('Y-m-d H:i:s')
                        ]
                    );
                    
                    if($update_cus){
                        echo 'Update Success';
                        //return redirect('/myaccount')->with('msg','ระบบได้ทำการอัปเดทข้อมูล เรียบร้อยแล้ว')->with('msg-type','success');
                    }else{
                        echo 'Update Fail';
                        //return redirect('/myaccount')->with('msg','อัปเดทข้อมูลไม่สำเร็จ กรุณาลองใหม่อีกครั้ง');
                    }  
                }else{
                    alert('Insert Fail');
                }

            }else{
                echo 'resCode =>'.$resCode.', resDesc => '.$resDesc;
            }
            
        }else{
            echo 111;
        }
        
    }

    
    public function store(Request $request)
    {
        //
    }

    
    public function invoice($id=null)
    {
        if(session('customer.id') != null){
            $customerId = session('customer.id');
        }else{
            return redirect('/')->with('msg','คุณยังไม่ได้เข้าระบบ กรุณาเข้าสู่ระบบเพื่อใช้งาน');
        }
        //$customerObj = DB::table('customer')->where("CUST_ID",$customerId)->where("IS_ACTIVE",1)->first();
        $customerObj = DB::table('customer')->select('PEAK_CONTACT_ID')->where("CUST_ID",$customerId)->where("IS_ACTIVE",1)->first();
        $contactId = $customerObj->PEAK_CONTACT_ID;
        $clientToken = PeakManager::authentication();
        //alert($clientToken);
        $today = date('Ymd');
        $tomorrow = date('Ymd',strtotime($today . "+1 days"));
        //alert($today);alert($tomorrow);die();
        $invoice['issuedDate'] = $today;
        $invoice['dueDate'] = $today;
        $invoice['contactId'] = $contactId;
        $invoice['tags'][] = "";

        $products['productId'] = "";
        $products['accountCode'] = "410201";
        $products['description'] = "Test xxx";
        $products['quantity'] = 1;
        $products['price'] = "200.00";
        $products['discount'] = "0";
        $products['vatType'] = 1;
        $invoice['products'][] = $products;

        $paidPayments['paymentDate'] = $tomorrow;
        $paidPayments['withHoldingTaxAmount'] = "0";
        $invoice['paidPayments'] = $paidPayments;

        $payments['paymentMethodId'] = "0";
        $payments['amount'] = "200.00";
        $payments['note'] = "Test 9999";
        $invoice['paidPayments']['payments'][] = $payments;
        $invoices[] = $invoice;

        //Json
        /*$jsonInvoices = '
            {
                "PeakInvoices":{
                    "invoices":[
                        {
                            "issuedDate":"'.$today.'",
                            "dueDate":"'.$today.'",
                            "contactId":"'.$contactId.'",
                            "accountCode":"410201",
                            "tags":[
                                ""
                            ],
                            "products":[
                                {
                                    "productId":"",
                                    "quantity":1,
                                    "price":"200.00",
                                    "discount":"0",
                                    "vatType":1
                                }
                            ],
                            "paidPayments":{
                                "paymentDate":"'.$tomorrow.'",
                                "withHoldingTaxAmount":"0",
                                "payments":[
                                    {
                                        "paymentMethodId":"",
                                        "amount":200.00,
                                        "note":"Test 1"
                                    }
                                ]
                            }
                        }
                    ]
                }
            }';*/

        $params['clientToken'] = $clientToken;
        $params['invoices'] = $invoices;
        //$params['encodedInvoices'] = $jsonInvoices;
        $params['url'] = 'http://peakengineapidev.azurewebsites.net/api/v1/invoices';
        //alert($params);
        $res = PeakManager::curlInvoice($params);
        alert('Invoice Response');
         $data = $res->PeakInvoices->invoices;
            $resCode = $data[0]->resCode;
            $resDesc = $data[0]->resDesc;
            $status = $data[0]->status;
            if($resCode == 200){
                echo 'resCode =>'.$resCode.', resDesc => '.$resDesc.', status => '.$status;
                $code = $data[0]->code;
                $id = $data[0]->id;
                alert('Code = '. $code);
                alert('ID = '. $id);
                $json = 'invoices?id='.$id.'&code='.$code.'&page=1';
                $url = 'http://peakengineapidev.azurewebsites.net/api/v1/';
                /*$ResponseX = callAPI('PUT', $url, $json, $clientToken);
                $resX = json_decode($ResponseX, true);
                alert($ResponseX);
                alert($resX);*/
            }else{
                echo 'resCode =>'.$resCode.', resDesc => '.$resDesc.', status => '.$status;
            }

            //http://peakengineapidev.azurewebsites.net/api/v1/invoices/api/v1/invoices?id=1c9ef01b-2bc0-47d6-8ee8-7d14b2065558&code=IV-20180600006&page=1
    }
    
    public function getInvoice($id=null)
    {
        $clientToken = PeakManager::authentication();//alert($clientToken);die();
        $code = "IV-20180600001";
        $id = "1c779d55-4ede-4fbf-ae2b-08c35843d46c";
        $json = 'invoices?id='.$id.'&code='.$code.'&page=1';
        $url = 'http://peakengineapidev.azurewebsites.net/api/v1/';
        $params['url'] = $url;
        $params['data'] = $json;
        $params['method'] = 'PUT';
        $params['clientToken'] = $clientToken;
        $ResponseX = PeakManager::callAPI($params);
        //$ResponseX = PeakManager::callAPI('PUT', $url, $json, $clientToken);
        //$resX = json_decode($ResponseX, true);
        alert($ResponseX);
        //alert($resX);
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
