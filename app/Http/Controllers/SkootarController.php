<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
//use Illuminate\Contracts\Routing\ResponseFactory;
use App\Http\Controllers\Controller;
use App\Lib\Fastship\Fastship;
use App\Lib\Fastship\FS_Shipment;
use App\Lib\Fastship\FS_Pickup;
use Session;
use DB;

//https://docs.google.com/spreadsheets/d/1QtR-nbTx2fYEWOpPHvHfbid9DeA5UmeOcE2kJJiTOvk/edit#gid=823660479

class SkootarController extends Controller
{
    private $userName;
    private $apiKey;
    private $channel;

    public function __construct()
    {
        date_default_timezone_set("Asia/Bangkok");
        if($_SERVER['REMOTE_ADDR'] == "localhost"){
            include(app_path() . '\Lib\inc.functions.php');
        }else{
            include(app_path() . '/Lib/inc.functions.php');
        }

        /*
        For Dev
        userName: skootar@fastship.co
        apiKey: 161F17FDA50A51E72700EC30DB38E00F
        channel: fastship*/
        $this->userName = 'skootar@fastship.co';
        $this->apiKey = '161F17FDA50A51E72700EC30DB38E00F';
        $this->channel = 'fastship';
    }

    public function index()
    {
        return "Hello SKOOTAR!!!";
    }

    public function create($pickupId=null)
    {
        if(session('customer.id') != null){
            $customerId = session('customer.id');
        }else{
            return redirect('/')->with('msg','คุณยังไม่ได้เข้าระบบ กรุณาเข้าสู่ระบบเพื่อใช้งาน');
        }
        if(empty($pickupId)){
            return 'pickupId is null';
        }else{
            $pickupId = '265595';
            $PICK_ID = $pickupId;
            //$customerId = '4815';
            Fastship::getToken($customerId);
            $response = FS_Pickup::get($pickupId);
           
            //set parameter
            $date_D = date("Y-m-d");
            $date_T = date("H:i");
            $f_time = date("H:i", strtotime("+2 hours"));
            $userName = $this->userName;
            $apiKey = $this->apiKey;
            $channel = $this->channel;
            $customerMobile = "020803999";
            $customerEmail = "cs@fastship.co";
            $jobDate = $date_D; //"2015-12-21";
            $startTime = $date_T; //"13:45";
            $finishTime = $f_time; //"15:45";
            $callbackUrl = "";
            //$callbackUrl = "http://localhost:8000/skootar/create";

            /* Job type. Choose only one choice to assign value
            1 is Document คือเอกสาร
            2 is Parcel goods คือพัสดุภัณฑ์
            3 is Food คืออาหาร*/

            /* Extra optional for delivery
            1 is Delivery Document, Collect cheque, Deliver invoice
            2 is Deposit cheque after collecting
            3 is Return trip
            4 is Collect cash on delivery and return immediatly
            6 is Send post */
            // "option":"1, 2, 4, 6",
            //Payment solution is 3 choice is "invoice", "cash", "creditcard"

            $data = $response['PickupAddress'];
            $fullName = $data['Firstname'].' '.$data['Lastname'];
            $address = $data['AddressLine1'].' '.$data['AddressLine2'].' '.$data['City'].' '.$data['State'].' '.$data['Postcode'];
            $latitude = "13.8628135";//$data['Latitude'];
            $longitude = "100.4361852";//$data['Longitude'];
            $phoneNumber = $data['PhoneNumber'];


            /*$pickup_addressName = "Centro รัตนาธิเบศร์";
            $pickup_address = "88/9999 ม.3 บางเลน เขต บางใหญ่ นนทบุรี ประเทศไทย 11140"; 
            $pickup_lat = "13.8628135";
            $pickup_lng = "100.4361852";
            $pickup_contactName = "Anusak";
            $pickup_contactPhone = "0858888888";
            $pickup_cashFee = "N";
            $pickup_seq = "1";*/
            $pickup_addressName = $data['AddressLine1'];
            $pickup_address = $address; 
            $pickup_lat = $latitude;
            $pickup_lng = $longitude;
            $pickup_contactName = $fullName;
            $pickup_contactPhone = $phoneNumber;
            $pickup_cashFee = "N";
            $pickup_seq = "1";

            $delivery_addressName = "Fastship Co.,Ltd";
            $delivery_address = "1/269 ซอยแจ้งวัฒนะ 14 เขตหลักสี่ กรุงเทพมหานคร 10210";
            $delivery_lat = "13.9020684";
            $delivery_lng = "100.5625023";
            $delivery_contactName = "K.WOOTINUN";
            $delivery_contactPhone = "020803999";
            $delivery_cashFee = "Y";
            $delivery_seq = "2";

            $JSON ='{
                    "userName": "'.$userName.'",
                    "apiKey" : "'.$apiKey.'",
                    "channel": "'.$channel.'",
                    "customerMobile":"'.$customerMobile.'",
                    "customerEmail":"'.$customerEmail.'",
                    "jobDate":"'.$jobDate.'",
                    "startTime":"'.$startTime.'",
                    "finishTime":"'.$finishTime.'",
                    "option":"1", 
                    "jobType":"2", 
                    "totalSize":0,
                    "totalWeight":0,
                    "promoCode":"", 
                    "remark":"Be careful fragile goods",
                    "callbackUrl": "'.$callbackUrl.'",
                    "locationList":
                        [
                            {
                                "addressName":"'.$pickup_addressName.'",
                                "address":"'.$pickup_address.'",
                                "lat":'.$pickup_lat.',
                                "lng":'.$pickup_lng.',
                                "contactName":"'.$pickup_contactName.'",
                                "contactPhone":"'.$pickup_contactPhone.'",
                                "cashFee":"'.$pickup_cashFee.'",
                                "seq":'.$pickup_seq.'
                            },
                            {
                                "addressName":"'.$delivery_addressName.'",
                                "address":"'.$delivery_address.'",
                                "lat":'.$delivery_lat.',
                                "lng":'.$delivery_lng.',
                                "contactName":"'.$delivery_contactName.'",
                                "contactPhone":"'.$delivery_contactPhone.'",
                                "cashFee":"'.$delivery_cashFee.'",
                                "seq":'.$delivery_seq.'
                            }
                        ],
                    "paymentType":"cash"
            }';
      
            //alert($JSON);//die();
            $url = "https://release.skootar.com/skootar_api_dev/api/create_new_job"; //Test
            //$url = 'https://www.skootar.com/skootar_api_prod/api/create_new_job'; //Production
            $Response = callAPI('POST', $url, $JSON);
            $res = json_decode($Response, true);
            alert($res);
            $CUST_ID = $customerId;
            if($res['responseCode'] == 200){
                $detail = $res['jobDetail'];
                $insert = DB::table('skootar')->insert([
                    'PICK_ID' => $PICK_ID,
                    'CUST_ID' => $CUST_ID,
                    'CUSTOMER_MOBILE' => $detail['customerMobile'],
                    'CUSTOMER_EMAIL' => $detail['customerEmail'],
                    'MOBILE_NUMBER' => $detail['mobileNumber'],
                    'SKOOTAR_TOKEN' => $detail['skootarToken'],
                    'JOBID' => $detail['jobId'],
                    'JOB_DATE' => $detail['jobDate'],
                    'JOB_STATUS' => $detail['jobStatus'],
                    'JOB_STATUS_EN' => $detail['jobStatusEn'],
                    'JOB_STATUS_TH' => $detail['jobStatusTh'],
                    'JOB_DESC' => $detail['jobDesc'],
                    'FREE_DESC' => $detail['feeDesc'],
                    'PROMOTION_CODE' => $detail['promoCode'],
                    'START_TIME' => $detail['startTime'],
                    'FINISH_TIME' => $detail['finishTime'],
                    'JOB_TIME' => $detail['jobTime'],
                    'HAVE_RETURN' => $detail['haveReturn'],
                    'JOB_TYPE' => $detail['jobType'],
                    'OPTION' => $detail['option'],
                    'TOTAL_DISTANCE' => $detail['totalDistance'],
                    'TOTAL_WEIGHT' => $detail['totalWeight'],
                    'TOTAL_SIZE' => $detail['totalSize'],
                    'REMARK' => $detail['remark'],
                    'OTHER_FREE' => $detail['otherFee'],
                    'FREE_CREDIT' => $detail['freeCredit'],
                    'TRACING_URL' => $detail['trackingUrl'],
                    'DEVICE_ID' => $detail['deviceId'],
                    'FIRST_NAME' => $detail['firstName'],
                    'LAST_NAME' => $detail['lastName'],
                    'PHONE_NO' => $detail['phoneNo'],
                    'USER_TYPE' => $detail['userType'],
                    'NORMAL_PRICE' => $detail['normalPrice'],
                    'NET_PRICE' => $detail['netPrice'],
                    'DISCOUNT' => $detail['discount'],
                    'SKOOTARID' => $detail['skootarId'],
                    'SKOOTAR_NAME' => $detail['skootarName'],
                    'SKOOTAR_PHONE' => $detail['skootarPhone'],
                    'SKOOTAR_IMAGE_URL' => $detail['skootarImageUrl'],
                    'SKOOTAR_RATING' => $detail['skootarRating'],
                    'RAITING' => $detail['raiting'],
                    'RATING_COMMENT' => $detail['ratingComment'],
                    'PAYMENT_TYPE' => $detail['paymentType'],
                    'LINE_PAY_ID' => $detail['linePayId'],
                    'SIGNATURE_URL' => $detail['signatureUrl'],
                    'INTERNAL_NOTE' => $detail['internalNote'],
                    'ADDRESSID_SEQ1' => $detail['locationList'][0]['addressId'],
                    'ADDRESSID_SEQ2' => $detail['locationList'][1]['addressId'],
                    'CREATE_DATE' => date('Y-m-d H:i:s'),
                    'UPDATE_DATE' => date('Y-m-d H:i:s')
                ]);

                if($insert){
                    echo 'Success';
                    //return redirect('/')->with('msg','คุณทำการ Register เรียบร้อยแล้ว')->with('msg-type','success');
                }else{
                    echo 'Fail';
                    //return redirect('/')->with('msg','Register ไม่สำเร็จ');
                }

            }else{
                alert($res['responseCode']);
                alert($res['responseDesc']);
            }
        }
    }

    public function cancel($jobId=null)
    {
        if(session('customer.id') != null){
            $customerId = session('customer.id');
        }else{
            return redirect('/')->with('msg','คุณยังไม่ได้เข้าระบบ กรุณาเข้าสู่ระบบเพื่อใช้งาน');
        }

        if(empty($jobId)){
            //$jobId = 'J180400485';
            return 'jobId is null';
        }else{
            $userName = $this->userName;
            $apiKey = $this->apiKey;
            $channel = $this->channel;
            $jobId = $jobId; //"J160400003"

            $JSON = '{
                "userName": "'.$userName.'",
                "apiKey" : "'.$apiKey.'",
                "channel": "'.$channel.'",
                "jobId": "'.$jobId.'",
                "cancelReason": "test cancel"
            }'; 

            alert($JSON);//die();
            $url = "https://release.skootar.com/skootar_api_dev/api/cancel_created_job"; //Test
            //$url = 'https://www.skootar.com/skootar_api_prod/api/'; //Production
            $Response = callAPI('POST', $url, $JSON);
            $res = json_decode($Response, true);
            alert($res);
        }
    }

    public function getDriver($id=null)
    {
        
        if (session('customer.id') != null){
            $customerId = session('customer.id');
        }else{
            return redirect('/')->with('msg','คุณยังไม่ได้เข้าระบบ กรุณาเข้าสู่ระบบเพื่อใช้งาน');
        }
        //Role : Get driver locaiton
        $userName = $this->userName;
        $apiKey = $this->apiKey;
        $channel = $this->channel;
        $skootarId = "SK1742";

        $JSON = '{
            "userName": "'.$userName.'",
            "apiKey" : "'.$apiKey.'",
            "channel": "'.$channel.'",
            "skootarId":"'.$skootarId.'"
        }'; 

        alert($JSON);
        $url = "https://release.skootar.com/skootar_api_dev/api/tracking_driver"; //Test
        //$url = 'https://www.skootar.com/skootar_api_prod/api/'; //Production
        $Response = callAPI('POST', $url, $JSON);
        $res = json_decode($Response, true);
        alert($res);
    }

    
    public function estimatePrice($pickupId=null)
    {
        if (session('customer.id') != null){
            $customerId = session('customer.id');
        }else{
            return redirect('/')->with('msg','คุณยังไม่ได้เข้าระบบ กรุณาเข้าสู่ระบบเพื่อใช้งาน');
        }

        if(empty($pickupId)){
            return 'pickupId is null';
        }else{

            $pickupId = '265595';
            $PICK_ID = $pickupId;
            //$customerId = '4815';
            Fastship::getToken($customerId);
            $response = FS_Pickup::get($pickupId);
            //alert($response);
            //die();

            //Role : Estimate pricing
            $userName = $this->userName;
            $apiKey = $this->apiKey;
            $channel = $this->channel;
            $skootarId = "SK1742";
            $option = "1"; //"option": "3", // 3 if has return trip else set this to null
            $promoCode = "skxy2015";

            /*$pickup_addressName = "ราชเทวี";
            $pickup_address = "281/28 บรรทัดทอง เขต ราชเทวี กรุงเทพมหานคร ประเทศไทย 10400"; 
            $pickup_lat = "13.7518789";
            $pickup_lng = "100.5263246";
            $pickup_contactName = "Thanate";
            $pickup_contactPhone = "0851111111";
            $pickup_cashFee = "N";
            $pickup_seq = "1";*/

            //13.8628135,100.4361852
            /*$pickup_addressName = "Centro รัตนาธิเบศร์";
            $pickup_address = "88/9999 ม.3 บางเลน เขต บางใหญ่ นนทบุรี ประเทศไทย 11140"; 
            $pickup_lat = "13.8628135";
            $pickup_lng = "100.4361852";
            $pickup_contactName = "Anusak";
            $pickup_contactPhone = "0858888888";
            $pickup_cashFee = "N";
            $pickup_seq = "1";*/


            $data = $response['PickupAddress'];
            $fullName = $data['Firstname'].' '.$data['Lastname'];
            $address = $data['AddressLine1'].' '.$data['AddressLine2'].' '.$data['City'].' '.$data['State'].' '.$data['Postcode'];
            $latitude = $data['Latitude']; //"13.8628135";//
            $longitude = $data['Longitude'];//"100.4361852";//
            $phoneNumber = $data['PhoneNumber'];

            $pickup_addressName = $data['AddressLine1'];
            $pickup_address = $address; 
            $pickup_lat = $latitude;
            $pickup_lng = $longitude;
            $pickup_contactName = $fullName;
            $pickup_contactPhone = $phoneNumber;
            $pickup_cashFee = "N";
            $pickup_seq = "1";

            $delivery_addressName = "Fastship Co.,Ltd";
            $delivery_address = "1/269 ซอยแจ้งวัฒนะ 14 เขตหลักสี่ กรุงเทพมหานคร 10210";
            $delivery_lat = "13.9015898";
            $delivery_lng = "100.5627994";
            $delivery_contactName = "K.WOOTINUN";
            $delivery_contactPhone = "0819007560";
            $delivery_cashFee = "Y";
            $delivery_seq = "2";

            $JSON ='{
                    "userName": "'.$userName.'",
                    "apiKey" : "'.$apiKey.'",
                    "channel": "'.$channel.'", 
                    "option": "'.$userName.'",
                    "jobType": "1",
                    "promoCode": "'.$promoCode.'",
                    "locationList": [
                        {
                            "addressName":"'.$pickup_addressName.'",
                            "address":"'.$pickup_address.'",
                            "lat":'.$pickup_lat.',
                            "lng":'.$pickup_lng.',
                            "contactName":"'.$pickup_contactName.'",
                            "contactPhone":"'.$pickup_contactPhone.'",
                            "cashFee":"'.$pickup_cashFee.'",
                            "seq":'.$pickup_seq.'
                        },
                        {
                            "addressName":"'.$delivery_addressName.'",
                            "address":"'.$delivery_address.'",
                            "lat":'.$delivery_lat.',
                            "lng":'.$delivery_lng.',
                            "contactName":"'.$delivery_contactName.'",
                            "contactPhone":"'.$delivery_contactPhone.'",
                            "cashFee":"'.$delivery_cashFee.'",
                            "seq":'.$delivery_seq.'
                        }
                    ]
            }'; 

            alert($JSON);
            $url = "https://release.skootar.com/skootar_api_dev/api/get_estimate_price"; //Test
            //$url = 'https://www.skootar.com/skootar_api_prod/api/'; //Production
            $Response = callAPI('POST', $url, $JSON);
            $res = json_decode($Response, true);
            alert($res);
        }
    }

    public function jobDetail($jobId=null)
    {
        if(session('customer.id') != null){
            $customerId = session('customer.id');
        }else{
            return redirect('/')->with('msg','คุณยังไม่ได้เข้าระบบ กรุณาเข้าสู่ระบบเพื่อใช้งาน');
        }

        //Role : Get job detail by job ID
        /*Job status description following the status code is
        0  is Cancel
        1  is New
        2  is Approved
        3  is Mission created
        4  is Mission broadcasted
        5  is Mission accepted
        6  is Going to pickup point
        7  is Going to delivery point
        8  is N/A
        9  is Job completed
        10 is Invoice created*/

        if(empty($jobId)){
            //$jobId = 'J180400485';
            return 'jobId is null';
        }else{
            $userName = $this->userName;
            $apiKey = $this->apiKey;
            $channel = $this->channel;
            $jobId = $jobId;

            $JSON = '{
                "userName": "'.$userName.'",
                "apiKey" : "'.$apiKey.'",
                "channel": "'.$channel.'",
                "jobId":"'.$jobId.'"
            }'; 


            alert($JSON);
            $url = "https://release.skootar.com/skootar_api_dev/api/get_job_detail"; //Test
            //$url = 'https://www.skootar.com/skootar_api_prod/api/'; //Production
            $Response = callAPI('POST', $url, $JSON);
            $res = json_decode($Response, true);
            alert($res);
        }
    }

    public function findDriverByArea(Request $request)
    {
        if (session('customer.id') != null){
            $customerId = session('customer.id');
        }else{
            return redirect('/')->with('msg','คุณยังไม่ได้เข้าระบบ กรุณาเข้าสู่ระบบเพื่อใช้งาน');
        }
        //dd($request->all());
        //Role : Find driver by know center lat, lng and radius (km.)
        $userName = $this->userName;
        $apiKey = $this->apiKey;
        $channel = $this->channel;
        $centerLat = $request->input('centerLat');
        $centerLng = $request->input('centerLng');
        $radiusKm = $request->input('radiusKm');
        if(empty($centerLat) && empty($centerLng) && empty($radiusKm)){
            $centerLat = "13.7435696";
            $centerLng = "100.5624675";
            $radiusKm = "4.0";
        }

        $JSON = '{
            "centerLat": '.$centerLat.',
            "centerLng": '.$centerLng.',
            "radiusKm": '.$radiusKm.',
            "userName": "'.$userName.'",
            "apiKey" : "'.$apiKey.'",
            "channel": "'.$channel.'"
        }'; 

        alert($JSON);
        $url = "https://release.skootar.com/skootar_api_dev/api/find_driver_by_area"; //Test
        //$url = 'https://www.skootar.com/skootar_api_prod/api/'; //Production
        $Response = callAPI('POST', $url, $JSON);
        $res = json_decode($Response, true);
        alert($res);
    }

    public function getCurrentJob()
    {
        if(session('customer.id') != null){
            $customerId = session('customer.id');
        }else{
            return redirect('/')->with('msg','คุณยังไม่ได้เข้าระบบ กรุณาเข้าสู่ระบบเพื่อใช้งาน');
        }

        //Role : List the current active job
        $userName = $this->userName;
        $apiKey = $this->apiKey;
        $channel = $this->channel;
        $customerMobile = "020803999";
        $customerEmail = "cs@fastship.co";

        $JSON = '{
            "userName": "'.$userName.'",
            "apiKey" : "'.$apiKey.'",
            "channel": "'.$channel.'",
            "customerMobile": "'.$customerMobile.'",
            "customerEmail": "'.$customerEmail.'"
        }'; 


        alert($JSON);
        $url = "https://release.skootar.com/skootar_api_dev/api/get_current_job"; //Test
        //$url = "https://23.97.50.35/skootar_api_dev/api/get_current_job"; //Test
        //$url = 'https://www.skootar.com/skootar_api_prod/api/'; //Production
        $Response = callAPI('POST', $url, $JSON);
        $res = json_decode($Response, true);
        alert($res);
    }

    public function getCompletedJob()
    {
        if (session('customer.id') != null){
            $customerId = session('customer.id');
        }else{
            return redirect('/')->with('msg','คุณยังไม่ได้เข้าระบบ กรุณาเข้าสู่ระบบเพื่อใช้งาน');
        }

        //Role : List the completed job
        $userName = $this->userName;
        $apiKey = $this->apiKey;
        $channel = $this->channel;
        $customerMobile = "020803999";
        $customerEmail = "cs@fastship.co";

        $JSON = '{
            "userName": "'.$userName.'",
            "apiKey" : "'.$apiKey.'",
            "channel": "'.$channel.'",
            "customerMobile": "'.$customerMobile.'",
            "customerEmail": "'.$customerEmail.'"
        }'; 

        alert($JSON);
        $url = "https://release.skootar.com/skootar_api_dev/api/get_completed_job"; //Test
        //$url = 'https://www.skootar.com/skootar_api_prod/api/'; //Production
        $Response = callAPI('POST', $url, $JSON);
        $res = json_decode($Response, true);
        alert($res);
    }

    public function getListLocation($id=null)
    {
        if (session('customer.id') != null){
            $customerId = session('customer.id');
        }else{
            return redirect('/')->with('msg','คุณยังไม่ได้เข้าระบบ กรุณาเข้าสู่ระบบเพื่อใช้งาน');
        }

        //Role : List the saved location
        $userName = $this->userName;
        $apiKey = $this->apiKey;
        $channel = $this->channel;
        $customerMobile = "020803999";
        $customerEmail = "cs@fastship.co";
        $keyword = "G";

        $JSON = '{
            "userName": "'.$userName.'",
            "apiKey" : "'.$apiKey.'",
            "channel": "'.$channel.'",
            "keyword": "'.$keyword.'",
            "customerMobile": "'.$customerMobile.'",
            "customerEmail": "'.$customerEmail.'"
        }'; 


        alert($JSON);
        $url = "https://release.skootar.com/skootar_api_dev/api/get_list_location"; //Test
        //$url = 'https://www.skootar.com/skootar_api_prod/api/'; //Production
        $Response = callAPI('POST', $url, $JSON);
        $res = json_decode($Response, true);
        alert($res);
    }

    public function estimateArrivalTime($id=null)
    {
        if (session('customer.id') != null){
            $customerId = session('customer.id');
        }else{
            return redirect('/')->with('msg','คุณยังไม่ได้เข้าระบบ กรุณาเข้าสู่ระบบเพื่อใช้งาน');
        }

        //Role :  Estimate minimum and maximum time that driver arrive to destination
        $userName = $this->userName;
        $apiKey = $this->apiKey;
        $channel = $this->channel;
        $centerLat = "13.8628135";
        $centerLng = "100.4361852";

        $JSON = '{
            "userName": "'.$userName.'",
            "apiKey" : "'.$apiKey.'",
            "channel": "'.$channel.'",
            "centerLat": '.$centerLat.',
            "centerLng": '.$centerLng.'
        }'; 


        alert($JSON);
        $url = "https://release.skootar.com/skootar_api_dev/api/estimate_arrival_time"; //Test
        //$url = 'https://www.skootar.com/skootar_api_prod/api/'; //Production
        $Response = callAPI('POST', $url, $JSON);
        $res = json_decode($Response, true);
        alert($res);
    }

    public function Callback()
    {
        //Callback Push Data
        //$rawData = file_get_contents("php://input");
        /*$json = file_get_contents('php://input');
        $obj = json_decode($json);
        alert($obj);*/
        //$data = 1111;//$request->json()->all();



        echo "OK";
        die();
        //$data = Input::all();
        //$name = $request->input('status');
        //alert($data);die();


        //$content = Request::all();
        //return $content;
        //return "OK";

        /*return Response::json([
            'hello' => 'Success'
        ], 200);*/

        /*return response()->json([
            'status' => 'Success',
            'code' => '200'
        ]);*/
    }

    public function testCallback()
    {
        $JSON = '{
            "jobId":"J1512852586",
            "status":"9",
            "statusDesc":"Job completed"
        }'; 


        //alert($JSON);
        //$url = "https://release.skootar.com/skootar_api_dev/api/test_driver_pickup"; //Test
        $url = 'https://localhost:8000/skootar/callback'; //Production
        //$url = 'https://localhost/skootar/callback.php'; //Production


        //$url =  Route::get('skootar/callback', 'SkootarController@Callback');
        $Response = callAPI('POST', $url, $JSON);
        //$res = json_decode($Response, true);
        alert($Response);
    }

    public function receive(Request $request)
    {   
        $request = Request::instance();
        // Now we can get the content from it
        $content = $request->getContent();
        dd($content);

        
        if ($request->session()->has("access_token")) {
            $access_token = $request->session()->get("access_token");
            echo $access_token;

        } else {
            echo "there is no access token in session";
        }

       //$data = Input::all(); //$data = $request->json()->all(); should also work
       //alert($data);
    }

    public function testDriverAcceptedJob($id=null)
    {
        if(empty($id)){
            $id = 'J180400485';
        }
        $userName = $this->userName;
        $apiKey = $this->apiKey;
        $channel = $this->channel;
        $jobId =  $id;
        $skootarId = "SK0001";

        $JSON = '{
            "userName": "'.$userName.'",
            "apiKey" : "'.$apiKey.'",
            "jobId":"'.$jobId.'",
            "skootarId":"'.$skootarId.'"
        }'; 


        alert($JSON);
        //$url = "https://release.skootar.com/skootar_api_dev/api/test_driver_pickup"; //Test
        $url = 'https://release.skootar.com/skootar_api_dev/api/test_driver_pickup'; //Production
        $Response = callAPI('POST', $url, $JSON);
        $res = json_decode($Response, true);
        alert($Response);
    }

    public function testDriverStartJob($id=null)
    {
        if(empty($id)){
            $id = 'J180400485';
        }
        $userName = $this->userName;
        $apiKey = $this->apiKey;
        $channel = $this->channel;
        $jobId = $id;
        $skootarId = "SK0001";

        $JSON = '{
            "userName": "'.$userName.'",
            "apiKey" : "'.$apiKey.'",
            "jobId":"'.$jobId.'",
            "skootarId":"'.$skootarId.'"
        }'; 


        alert($JSON);
        //$url = "https://release.skootar.com/skootar_api_dev/api/test_driver_pickup"; //Test
        $url = 'https://release.skootar.com/skootar_api_dev/api/test_driver_pickup'; //Production
        $Response = callAPI('POST', $url, $JSON);
        $res = json_decode($Response, true);
        alert($Response);
    }

    public function testDriverPickup($id=null)
    {
        if(empty($id)){
            $id = 'J180400485';
        }
        $userName = $this->userName;
        $apiKey = $this->apiKey;
        $channel = $this->channel;
        $jobId = $id;
        $skootarId = "SK0001";

        $JSON = '{
            "userName": "'.$userName.'",
            "apiKey" : "'.$apiKey.'",
            "jobId":"'.$jobId.'",
            "skootarId":"'.$skootarId.'"
        }'; 


        alert($JSON);
        //$url = "https://release.skootar.com/skootar_api_dev/api/test_driver_pickup"; //Test
        $url = 'https://release.skootar.com/skootar_api_dev/api/test_driver_pickup'; //Production
        $Response = callAPI('POST', $url, $JSON);
        $res = json_decode($Response, true);
        alert($Response);
    }

    public function testDriverCompletedJob($id=null)
    {
        if(empty($id)){
            $id = 'J180400485';
        }
        $userName = $this->userName;
        $apiKey = $this->apiKey;
        $channel = $this->channel;
        $jobId = $id;
        $skootarId = "SK0001";

        $JSON = '{
            "userName": "'.$userName.'",
            "apiKey" : "'.$apiKey.'",
            "jobId":"'.$jobId.'",
            "skootarId":"'.$skootarId.'"
        }'; 

        alert($JSON);
        //$url = "https://release.skootar.com/skootar_api_dev/api/test_driver_completed_job"; //Test
        $url = 'https://release.skootar.com/skootar_api_dev/api/test_driver_completed_job'; //Production
        $Response = callAPI('POST', $url, $JSON);
        $res = json_decode($Response, true);
        alert($Response);
    }
    
    public function testDriverCanceledJob($id=null)
    {
        if(empty($id)){
            $id = 'J180400485';
        }
        $userName = $this->userName;
        $apiKey = $this->apiKey;
        $channel = $this->channel;
        $jobId = $id;
        $skootarId = "SK0001"; //"skootarId" : "SK0001","SK0001","SK0001","SK0001"

        $JSON = '{
            "userName": "'.$userName.'",
            "apiKey" : "'.$apiKey.'",
            "jobId":"'.$jobId.'",
            "skootarId":"'.$skootarId.'"
        }'; 


        alert($JSON);
        //$url = "https://release.skootar.com/skootar_api_dev/api/"; //Test
        $url = 'https://release.skootar.com/skootar_api_dev/api/test_driver_cancel_job'; //Production
        $Response = callAPI('POST', $url, $JSON);
        $res = json_decode($Response, true);
        alert($Response);
    }




    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
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
