<?php

namespace App\Http\Controllers\Liff;

use App\Models\Country as Country;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Http\Request;
use App\Lib\Encryption;
use App\Http\Controllers\Controller;
use App\Lib\Fastship\FS_Error;
use App\Lib\Fastship\FS_Pickup;
use App\Lib\Fastship\Fastship;
use App\Lib\Fastship\FS_Shipment;
use App\Lib\Fastship\FS_Address;
use App\Lib\Fastship\FS_Customer;
use App\Lib\Fastship\FS_CreditBalance;
use App\Lib\Fastship\FS_CreditCard;
//use LINE\LINEBot;
//use LINE\LINEBot\HTTPClient\CurlHTTPClient;

class LiffController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    

    public function ajaxGetRate(Request $request)
    {

        //check parameter
        if ( (!empty($request->input('weight')) && $request->input('weight') > 0) && !empty($request->input('country'))){
            $weight = $request->input('weight');
            $country = $request->input('country');
        }else{
            exit();
        }

        if( !empty($request->input('width')) ){
            $width = $request->input('width');
        }else{
            $width = 0;
        }
        
        if( !empty($request->input('height')) ){
            $height = $request->input('height');
        }else{
            $height = 0;
        }
        
        if( !empty($request->input('length')) ){
            $length = $request->input('length');
        }else{
            $length = 0;
        }

        //get api token
        $fixCustId = 1; //for show FBA
        Fastship::getToken($fixCustId);
        
        //prepare request data
        $rateDetails = array(
            'Weight' => $weight,
            'Width' => $width,
            'Height' => $height,
            'Length' => $length,
            'Country' => $country,
        );
        
        //call api
        try{
            $rates = FS_Shipment::get_shipping_rates($rateDetails);
            echo json_encode($rates);
        }catch (Exception $e){
            echo false;
        }

        exit();
    }
    
    public function ajaxCheckEmailExisted(Request $request)
    {
        
        //check parameter
        if (!empty($request->input('email'))){
            $email = $request->input('email');
        }else{
            exit();
        }

        //get api token
        Fastship::getToken();

        //call api
        try{
            $check = FS_Customer::checkEmail($email);
            echo json_encode($check);
        }catch (Exception $e){
            echo "error";
        }
        
        exit();
    }
    
    public function ajaxStates(Request $request){
        
        //validate
        $this->validate($request, [
            'country' => 'required',
        ]);
        
        $country = strtoupper(trim($request->input('country')));
        $query = strtolower($request->get('term'));
        
        //get api token
        Fastship::getToken();
        
        
        $states = FS_Address::get_states_query($country,$query);

        $data = array();
        
        if(sizeof($states)>0){
            foreach($states as $state){
                $data[]=array(
                    "country" => $country,
                    "code"=> $state['stateCode'],
                    "name" => $state['stateName']
                );
            }
        }
        
        return $data;
            
            
    }
    
    public function ajaxGetPostals(Request $request){
        
        //validate
        $this->validate($request, [
            'state' => 'required',
        ]);
        
        $state = strtoupper(trim($request->input('state')));
        //$query = strtolower($request->get('term'));

        $postals = DB::table('thaipost_postal')->where('post_state',$state)->select('post_name as name','post_code as code')->get();
        
        $data = array();
        
        if(sizeof($postals)>0){
            foreach($postals as $postal){
                $data[$postal->code] = $postal->code . " - " . $postal->name;
//                 $data[]=array(
//                     "state" => $state,
//                     "code"=> $postal['code'],
//                     "name" => $postal['code'] . " - " . $postal['name']
//                 );
            }
        }
        
        //echo json_encode($data);
        return $data;
        
        
    }
    
    public function ajaxHsCodes(Request $request){
                    
        $query = strtoupper($request->get('term'));
        $category = "";
        
        if(strlen($query) < 3) return ;
        
        $hsQuery = DB::table("hscodes")
        ->select(
            DB::raw("MATCH (hs_desc) AGAINST ('".$query."') as relevance "),
            "hscodes.hs_code as code",
            "hscodes.hs_desc as desc"
            )->where('hscodes.hs_leveldepth',6);
            
            if($query != ""){
                $hsQuery->whereRaw(" (UPPER(hscodes.hs_desc) LIKE '%" . $query . "%' OR MATCH (hs_desc) AGAINST ('".$query."' IN NATURAL LANGUAGE MODE) ) ");
                //$hsQuery->whereRaw("UPPER(hscodes.hs_desc) LIKE '%" . $query . "%'");
                //$hsQuery->whereRaw("MATCH (hs_desc) AGAINST ('".$query."' IN NATURAL LANGUAGE MODE)");
            }
            if($category != ""){
                $hsQuery->whereRaw("hs_code LIKE '".$category."%'");
            }
            
            //$codes = $hsQuery->orderBy('hscodes.hs_code')->get();
            $codes = $hsQuery->orderBy('relevance','desc')->orderBy('hscodes.hs_code','asc')->get();
            
            $data = array();
            
            if(sizeof($codes)>0){
                foreach($codes as $code){
                    $data[]=array(
                        "code"=> $code->code,
                        "desc" => $code->desc
                    );
                }
            }else{
                $data[]=array(
                    "code"=> "",
                    "desc" => "ไม่พบผลลัพธ์",
                );
            }
            
            return $data;
            
            
    }
    
    public function signup(Request $request)
    {

        $return = $request->input('return');

        $data = array(
            "return" => $return,
        );
        return view('liff/signup',$data);
        
    }
    public function login(Request $request)
    {
        
        $return = $request->input('return');
        
        if(!isset($return)){
            $return = "/liff/connect_success";
        }
        $data = array(
            "return" => $return,
        );
        return view('liff/login',$data);
        
    }
    public function doSignup(Request $request)
    {

        //parameters 
        $lineUserId = $request->input('line_user_id');
        $lineId = $request->input('line_id');
        $firstname = $request->input('firstname');
        $lastname = $request->input('lastname');
        $email = $request->input('email');
        $telephone = $request->input('telephone');
        
        $return = $request->input('return');
        
        //default create shipment
        if(session('liff.weight') != null){
            //print_r(session('liff.weight'));
        }
        
        //get api token
        Fastship::getToken();
        
        //existed user
        try{
            $existed = FS_Customer::checkEmail($email);
        }catch (Exception $e){

        }
        if($existed == 1){
            
            return back()->with('msg','อีเมล์นี้ ' . $email . ' ถูกใช้งานแล้ว');
            
        }else{
            
            //create customer
            $params = array(
                "Firstname" => $firstname,
                "Lastname" => $lastname,
                "Email" => $email,
                "PhoneNumber" => $telephone,
                "LineUserId" => $lineUserId,
                "LineId" => $lineId,
                "Group" => "Standard",
            );
            $create = FS_Customer::create_line($params);
            
            return redirect($return);

        }

    }
    public function doLogin(Request $request)
    {
        
        //parameters
        $lineUserId = $request->input('line_user_id');
        $lineId = $request->input('line_id');
        $email = $request->input('email');
        $password = $request->input('password');
        
        if($request->has('return')){
            $return = $request->input('return');
        }else{
            return back()->with('msg','ไม่พบข้อมูล');
        }
        
        
        //default create shipment
        if(session('liff.weight') != null){
            print_r(session('liff.weight'));
        }
        
        //get api token
        Fastship::getToken();
        
        //existed user
        try{
            $existed = FS_Customer::checkEmail(strtolower($email));
        }catch (Exception $e){
            return back()->with('msg','ไม่พบผู้ใช้งาน กรุณาตรวจสอบอีเมล์หรือรหัสผ่าน');
        }
        if($existed == 1){

            //convert password to Encrypt
            //$converter = new Encryption;
            //$encryptPassword = $converter->encode($password);
            
            //update customer
            $params = array(
                "Email" => $email,
                "Password" => $password,
            );
            $login = FS_Customer::login($params);
            
            if($login > 0){

                //get new api token
                Fastship::getToken($login);
                
                $customer = FS_Customer::get($login);

                $params = array(
                    "Firstname" => $customer['Firstname'],
                    "Lastname" => $customer['Lastname'],
                    "Email" => $customer['Email'],
                    "PhoneNumber" => $customer['PhoneNumber'],
                    "LineUserId" => $lineUserId,
                    "LineId" => $lineId,
                );
                $update = FS_Customer::update($params);
                
            }else{
                
                echo "invalid password";
                //exit();
                return redirect("liff/login")->with("msg","invalid password");
            }
            
            return redirect($return);
            
        }else{
            
            return back()->with('msg','อีเมล์หรือรหัสผ่านไม่ถูกต้อง');
            
        }

    }
    
    public function doLogin2(Request $request)
    {
        
        //parameters
        $lineUserId = $request->input('line_user_id');
        $lineId = $request->input('line_id');
        $email = $request->input('email');
        $password = $request->input('password');
        $return = $request->input('return');
        
        //default create shipment
        if(session('liff.weight') != null){
            print_r(session('liff.weight'));
        }

        //get api token
        Fastship::getToken();
        
        //existed user
        try{
            $existed = FS_Customer::checkEmail($email);
        }catch (Exception $e){
            return back()->with('msg','ไม่พบผู้ใช้งาน');
        }

        if($existed == 1){
            
            //convert password to Encrypt
            //$converter = new Encryption;
            //$encryptPassword = $converter->encode($password);
            
            echo "existed";
            
            //update customer
            $params = array(
                "Email" => $email,
                "Password" => $password,
            );
            $login = FS_Customer::login($params);
            
            print_r($login);
            
            if($login > 0){
                
                //get new api token
                Fastship::getToken($login);
                
                $customer = FS_Customer::get($login);
                
                $params = array(
                    "Firstname" => $customer['Firstname'],
                    "Lastname" => $customer['Lastname'],
                    "Email" => $customer['Email'],
                    "PhoneNumber" => $customer['PhoneNumber'],
                    "LineUserId" => $lineUserId,
                    "LineId" => $lineId,
                );
                $update = FS_Customer::update($params);
                
                print_r($update);
                echo "ttt";
                
            }else{
                
                echo "invalid password";
                //exit();
                return redirect("liff/login")->with("msg","invalid password");
            }
            
            return redirect($return);
            
        }else{
            
            return back()->with('msg','ไม่พบผู้ใช้งาน');
            
        }
        
    }
    
    public function calculate(Request $request)
    {
        
        Fastship::getToken();
        $countries = FS_Address::get_countries();
        
        //alert($ShipmentDetail);
        $data = array(
            'countries' => $countries,
        );
        
        return view('liff/calculate',$data);
        
    }
    
    /*
     * connect line
     */
    public function connectLine(Request $request)
    {

        if($request->has("line_user_id") && $request->input("line_user_id") != ""){
            
            Fastship::getToken();
            
            $lineUserId = $request->input("line_user_id");
            
            try{
                $existed = FS_Customer::checkLineUserId($lineUserId);
            }catch (Exception $e){
                echo "error";
            }

            if($existed == -1){
                $data = array(
                    "return" => "/liff/connect_success",
                );
                return view('liff/connect',$data);
            }
        }

        $data = array(
            "return" => "/liff/connect_success",
        );
        return view('liff/redirect_connect_completed',$data);
        
        
    }
    
    /*
     * create shipment - weight & country step
     */
    public function createShipment(Request $request)
    {
        //print_r($request->all());
        //print_r($request->session());
 
        Fastship::getToken();
        
        if($request->has("line_user_id") && $request->input("line_user_id") != ""){
            
            $lineUserId = $request->input("line_user_id");

            try{
                $existed = FS_Customer::checkLineUserId($lineUserId);
            }catch (Exception $e){
                echo "error"; 
            }
            
            //save calculate value
            if($request->has('weight')){
                $request->session()->put('liff.weight', $request->input('weight'));
            }
            if($request->has('width')){
                $request->session()->put('liff.width', $request->input('width'));
            }
            if($request->has('height')){
                $request->session()->put('liff.height', $request->input('height'));
            }
            if($request->has('length')){
                $request->session()->put('liff.length', $request->input('length'));
            }
            if($request->has('country')){
                $request->session()->put('liff.country', $request->input('country'));
            }
            if($request->has('agent')){
                $request->session()->put('liff.agent', $request->input('agent'));
            }
            
            if($existed == -1){

                $data = array(
                    "return" => "/liff/create_shipment",
                );
                return view('liff/connect',$data);
                
            }else{
                
                //login
                Fastship::getToken($existed);
                
                //get customer
                $customerObj = FS_Customer::get($existed);
                
                //save to session
                $request->session()->put('customer.id', $existed);
                $request->session()->put('customer.name', $customerObj['Firstname']);
                
                
            }

        }

        $countries = FS_Address::get_countries();
        
        //alert($ShipmentDetail);
        $data = array(
            'countries' => $countries,
        );
        return view('liff/create_shipment_step1',$data);
    }
    
    /*
     * create shipment - agent step
     */
    public function createShipmentStep2(Request $request)
    {

        $customerId = session('customer.id');
        
        Fastship::getToken($customerId);

        //save calculate value
        if($request->has('weight')){
            $weight = $request->input('weight');
            $request->session()->put('liff.weight', $request->input('weight'));
        }
        if($request->has('width')){
            $width = $request->input('width');
            $request->session()->put('liff.width', $request->input('width'));
        }else{
            $width = 0;
            $request->session()->forget('liff.width');
        }
        if($request->has('height')){
            $height = $request->input('height');
            $request->session()->put('liff.height', $request->input('height'));
        }else{
            $height = 0;
            $request->session()->forget('liff.height');
        }
        if($request->has('length')){
            $length = $request->input('length');
            $request->session()->put('liff.length', $request->input('length'));
        }else{
            $length = 0;
            $request->session()->forget('liff.length');
        }
        if($request->has('country')){
            $country = $request->input('country');
            $request->session()->put('liff.country', $request->input('country'));
        }
        
        $rateDetails = array(
            'Weight' => $weight,
            'Width' => $width,
            'Height' => $height,
            'Length' => $length,
            'Country' => $country,
        );
        
        //call api
        try{
            $rateObjs = FS_Shipment::get_shipping_rates($rateDetails);
            
        }catch (Exception $e){
            echo $e->getMessage();
            exit();
        }
        
        if(is_array($rateObjs) && sizeof($rateObjs) > 0){
            $rates = array();
            foreach($rateObjs as $rateObj){
                
                $rateDetail = DB::table('agent')->select('agent_name as name','agent_desc as desc','agent_type as type')->where('agent_code',$rateObj['Name'])->first();
                $rates[] = array(
                    "code" => $rateObj['Name'],
                    "name" => $rateDetail->name,
                    "desc" => $rateDetail->desc,
                    "type" => $rateDetail->type,
                    "minTime" => $rateObj['DeliveryMinTime'],
                    "maxTime" => $rateObj['DeliveryMaxTime'],
                    "rate" => $rateObj['AccountRate'],
                );
            }
        }else{

            $rates = null;
            //exit();
        }
        
        //get country
        $country2iso = DB::table('country')->select('cntry_name as name','cntry_code2iso as code')->where('cntry_code',$country)->first();
        
        $data = array(
            'country' => $country2iso,
            'rates' => $rates,
        );
        return view('liff/create_shipment_step2',$data);
    }  
    
    /*
     * create shipment - declaration step
     */
    public function createShipmentStep3(Request $request)
    {
        
        $customerId = session('customer.id');
        $country = session('liff.country');

        if(session('liff.declare_category') != null){
            $categories = session('liff.declare_category');
            $amounts = session('liff.declare_amount');
            $values = session('liff.declare_value');
        }
        
        Fastship::getToken($customerId);
        
        //save calculate value
        if($request->has('agent')){
            $agent = $request->input('agent');
            $request->session()->put('liff.agent', $request->input('agent'));
        }
        if($request->has('rate')){
            $rate = $request->input('rate');
            $request->session()->put('liff.rate', $request->input('rate'));
        }

        //get country
        $country2iso = DB::table('country')->select('cntry_name as name','cntry_code2iso as code')->where('cntry_code',$country)->first();
        
        //get agent
        $agentDetail = DB::table('agent')->select('agent_name as name','agent_desc as desc','agent_type as type')->where('agent_code',$agent)->first();
        
        //get declares
        $declares = array();
        if(session('liff.declare_category') != null){
            foreach($categories as $key=>$category){
                $declares[] = array(
                    "type" => $categories[$key],
                    "qty" => $amounts[$key],
                    "value" => $values[$key],
                );
            }
        }
        
        $data = array(
            'country' => $country2iso,
            'agent' => $agentDetail,
            'declares' => $declares,
        );
        return view('liff/create_shipment_step3',$data);
    }
    
    /*
     * create shipment - recevier step
     */
    public function createShipmentStep4(Request $request)
    {

        $customerId = session('customer.id');
        $country = session('liff.country');
        $agent = session('liff.agent');
        
        Fastship::getToken($customerId);
        
        //save calculate value
        if($request->has('category')){
            $categories = $request->input('category');
            $request->session()->put('liff.declare_category', $request->input('category'));
        }
        if($request->has('amount')){
            $amounts = $request->input('amount');
            $request->session()->put('liff.declare_amount', $request->input('amount'));
        }
        if($request->has('value')){
            $values = $request->input('value');
            $request->session()->put('liff.declare_value', $request->input('value'));
        }
        
        //get country
        $country2iso = DB::table('country')->select('cntry_name as name','cntry_code2iso as code')->where('cntry_code',$country)->first();
        
        //get agent
        $agentDetail = DB::table('agent')->select('agent_name as name','agent_desc as desc','agent_type as type')->where('agent_code',$agent)->first();
        
        //get declares
        $declares = array();
        foreach($categories as $key=>$category){
            $declares[] = array(
                "type" => $categories[$key],
                "qty" => $amounts[$key],
                "value" => $values[$key],
            );
        }
        
        $data = array(
            'country' => $country2iso,
            'agent' => $agentDetail,
            'declares' => $declares,
        );
        return view('liff/create_shipment_step4',$data);
    }
    
    /*
     * create shipment - confirm step
     */
    public function createShipmentStep5(Request $request)
    {
        
        $customerId = session('customer.id');
        $country = session('liff.country');
        $agent = session('liff.agent');
        $categories = session('liff.declare_category');
        $amounts = session('liff.declare_amount');
        $values = session('liff.declare_value');
        
        Fastship::getToken($customerId);
        
        //save calculate value
        if($request->has('firstname')){
            $firstname = $request->input('firstname');
            $request->session()->put('liff.firstname', $request->input('firstname'));
        }
        if($request->has('lastname')){
            $lastname = $request->input('lastname');
            $request->session()->put('liff.lastname', $request->input('lastname'));
        }
        if($request->has('email')){
            $email = $request->input('email');
            $request->session()->put('liff.email', $request->input('email'));
        }
        if($request->has('phonenumber')){
            $phonenumber = $request->input('phonenumber');
            $request->session()->put('liff.phonenumber', $request->input('phonenumber'));
        }
        if($request->has('company')){
            $company = $request->input('company');
            $request->session()->put('liff.company', $request->input('company'));
        }
        if($request->has('address1')){
            $address1 = $request->input('address1');
            $request->session()->put('liff.address1', $request->input('address1'));
        }
        if($request->has('address2')){
            $address2 = $request->input('address2');
            $request->session()->put('liff.address2', $request->input('address2'));
        }
        if($request->has('state')){
            $state = $request->input('state');
            $request->session()->put('liff.state', $request->input('state'));
        }
        if($request->has('city')){
            $city = $request->input('city');
            $request->session()->put('liff.city', $request->input('city'));
        }
        if($request->has('postcode')){
            $postcode = $request->input('postcode');
            $request->session()->put('liff.postcode', $request->input('postcode'));
        }
        if($request->has('note')){
            $note = $request->input('note');
            $request->session()->put('liff.note', $request->input('note'));
        }
        
        //get country
        $country2iso = DB::table('country')->select('cntry_name as name','cntry_code2iso as code')->where('cntry_code',$country)->first();
        
        //get agent
        $agentDetail = DB::table('agent')->select('agent_name as name','agent_desc as desc','agent_type as type')->where('agent_code',$agent)->first();
        
        //get declares
        $declares = array();
        foreach($categories as $key=>$category){
            $declares[] = array(
                "type" => $categories[$key],
                "qty" => $amounts[$key],
                "value" => $values[$key],
            );
        }
        
        
        $data = array(
            'country' => $country2iso,
            'agent' => $agentDetail,
            'declares' => $declares,
        );
        return view('liff/create_shipment_step5',$data);
    }
    
    public function doCreateShipment(Request $request)
    {

        //check customer login
        if (session('customer.id') != null){
            $customerId = session('customer.id');
        }else{
            return redirect('/')->with('msg','คุณยังไม่ได้เข้าระบบ กรุณาเข้าสู่ระบบเพื่อใช้งาน');
        }

        $data = array();
        
        //get api token
        Fastship::getToken($customerId);
        
        //get sender
        $customerObj = FS_Customer::get($customerId);

        if($customerObj == null){
            return back()->with('msg','ไม่พบผู้ใช้งานในระบบ');
        }else{
            
            $agent = session('liff.agent');
            
            $data['Sender_Firstname'] = $customerObj['Firstname'];
            $data['Sender_Lastname'] = $customerObj['Lastname'];
            $data['Sender_PhoneNumber'] = $customerObj['PhoneNumber'];
            $data['Sender_Email'] = $customerObj['Email'];
            $data['Sender_Company'] = $customerObj['Company'];
            $data['Sender_AddressLine1'] = $customerObj['AddressLine1'];
            $data['Sender_AddressLine2'] = $customerObj['AddressLine2'];
            $data['Sender_City'] = $customerObj['City'];
            $data['Sender_State'] = $customerObj['State'];
            $data['Sender_Postcode'] = $customerObj['Postcode'];
            $data['Sender_Country'] = $customerObj['Country'];

            //Receiver
            $data['Receiver_Firstname'] = session('liff.firstname');
            $data['Receiver_Lastname'] = session('liff.lastname');
            $data['Receiver_PhoneNumber'] = session('liff.phonenumber');
            $data['Receiver_Email'] = session('liff.email');
            $data['Receiver_Company'] = session('liff.company');
            $data['Receiver_AddressLine1'] = session('liff.address1');
            $data['Receiver_AddressLine2'] = session('liff.address2');
            $data['Receiver_City'] = session('liff.city');
            $data['Receiver_State'] = session('liff.state');
            $data['Receiver_Postcode'] = session('liff.postcode');
            $data['ShippingAgent'] = $agent;
            
            if($agent == "FS_FBA" || $agent == "FS_FBA_JP" || $agent == "Ecom_PD"){
                $data['TermOfTrade'] = "DDP";
            }else{
                $data['TermOfTrade'] = "DDU";
            }
            
            $data['Weight'] = session('liff.weight');
            $data['Receiver_Country'] = session('liff.country');

            if( session('liff.width') != null ){
                $data['Width'] = session('liff.width');
            }else{
                $data['Width'] = 0;
            }
            
            if( session('liff.height') != null ){
                $data['Height'] = session('liff.height');
            }else{
                $data['Height'] = 0;
            }
            
            if( session('liff.length') != null ){
                $data['Length'] = session('liff.length');
            }else{
                $data['Length'] = 0;
            }

            $category = session('liff.declare_category');
            $amount = session('liff.declare_amount');
            $value = session('liff.declare_value');

            $Remark = session('liff.note');
            $Reference = "";
            
            
            if(sizeof($category) == 1 && $category[0] == ""){
                return redirect()->back()->with('msg','กรุณาระบุประเภทพัสดุ');
            }
            
            foreach ($category as $key => $cat) {
                $Declarations[$key] = array(
                    'DeclareType' => $cat,
                    'DeclareQty' => $amount[$key],
                    'DeclareValue' => $value[$key],
                );
            }

            $source = "Line";

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
                'Source' => $source,
            );

            $response = FS_Shipment::create($createDetails);

            //remove save session
            //$request->session()->forget('liff.*');
            $request->session()->flush();
            
            if($response === false){
                
                return redirect()->back()->with('msg','ข้อมูลไม่ครบถ้วน รบกวนตรวจสอบข้อมูลอีกครั้ง');
                
            }else{

                return redirect('liff/create_shipment_completed')->with('msg-type','shipment-success');
                
            }
        }
    }
    
    
    /*
     * create pickup - shipment list & coupon
     */
    public function createPickup(Request $request)
    {

        Fastship::getToken();
        
        if($request->has("line_user_id") && $request->input("line_user_id") != ""){
            
            $lineUserId = $request->input("line_user_id");
            
            try{
                $existed = FS_Customer::checkLineUserId($lineUserId);
            }catch (Exception $e){
                echo "error";
            }

            if($existed == -1){
                
                $data = array(
                    "return" => "/liff/create_pickup_step1",
                );
                return view('liff/connect',$data);
                
            }else{
                
                //login
                Fastship::getToken($existed);
                
                //get customer
                $customerObj = FS_Customer::get($existed);
                
                //save to session
                $request->session()->put('customer.id', $existed);
                $request->session()->put('customer.name', $customerObj['Firstname']);
                
                
            }
            
        }
        
        $customerId = session('customer.id');
        Fastship::getToken($customerId);
        
        //get shipment in cart
        $searchDetails = array(
            "Status" => 'Pending',
        );
        $response = FS_Shipment::search($searchDetails);
        
        $shipments = array();
        $totalRate = 0;
        if(sizeof($response) > 0 && is_array($response)){
            foreach ($response as $shipmentId) {
                $shipment = FS_Shipment::get($shipmentId);
                $shipments[] = $shipment;
                $totalRate+= $shipment['ShipmentDetail']['ShippingRate'];
            }
        }

        $data = array(
            'shipments' => $shipments,
            'totalRate' => $totalRate,
        );
        return view('liff/create_pickup_step1',$data);
    }
    
    /*
     * create pickup step 2 - select pickup agent
     */
    public function createPickupStep2(Request $request)
    {
        
        $customerId = session('customer.id');
        
        Fastship::getToken($customerId);

        //save calculate value
        if($request->has('coupon')){
            $coupon = $request->input('coupon');
            $request->session()->put('liff.coupon', $request->input('coupon'));
        }
        
        //calculate discount
        $discount = 0;
        
        //get shipment in cart
        $searchDetails = array(
            "Status" => 'Pending',
        );
        $response = FS_Shipment::search($searchDetails);
        $shipments = array();
        $totalRate = 0;
        if(sizeof($response) > 0 && is_array($response)){
            foreach ($response as $shipmentId) {
                $shipment = FS_Shipment::get($shipmentId);
                $shipments[] = $shipment;
                $totalRate+= $shipment['ShipmentDetail']['ShippingRate'];
            }
            
            //list format according to number of shipment
            $numShipment = sizeof($response);
            if($numShipment == 1){
                $listFormat = "single";
            }else if($numShipment % 3 == 0){
                $listFormat = "divide-3";
            }else if($numShipment == 2 || $numShipment == 4){
                $listFormat = "divide-4";
            }else if($numShipment % 4 == 0){
                $listFormat = "divide-4";
            }else{
                $listFormat = "divide-3";
            }
        }

        $total = $totalRate - $discount;
        
        
        $data = array(
            'shipments' => $shipments,
            'total' => $total,
            'shipmentListFormat' => $listFormat,
        );
        return view('liff/create_pickup_step2',$data);
    }
    
    /*
     * create pickup step 3 - show pickup type required data
     */
    public function createPickupStep3(Request $request)
    {
        
        $customerId = session('customer.id');
        
        Fastship::getToken($customerId);
        
        //save calculate value
        if($request->has('type')){
            $type = $request->input('type');
            $request->session()->put('liff.type', $request->input('type'));
        }

        //check type
        if($type == "Pickup_AtHome"){
            
            $customer = FS_Customer::get($customerId);
            
            $latitude = (session('liff.latitude') != null) ? session('liff.latitude'):$customer['Latitude'];
            $longitude = (session('liff.longitude') != null) ? session('liff.longitude'):$customer['Longitude'];
            $pickdate = (session('liff.pickdate') != null) ? session('liff.pickdate'):"";
            $picktime = (session('liff.picktime') != null) ? session('liff.picktime'):"";
            $firstname = (session('liff.firstname') != null) ? session('liff.firstname'):$customer['Firstname'];
            $telephone = (session('liff.telephone') != null) ? session('liff.telephone'):$customer['PhoneNumber'];
            $address1 = (session('liff.address1') != null) ? session('liff.address1'):$customer['AddressLine1'];
            $address2 = (session('liff.address2') != null) ? session('liff.address2'):$customer['AddressLine2'];
            $city = (session('liff.city') != null) ? session('liff.city'):$customer['City'];
            $state = (session('liff.state') != null) ? session('liff.state'):$customer['State'];
            $postcode = (session('liff.postcode') != null) ? session('liff.postcode'):$customer['Postcode'];
            $memo = (session('liff.memo') != null) ? session('liff.memo'):"";

            $states = DB::table('thaipost_postal')->groupBy('post_state')->pluck('post_state')->toArray();
            
            $data = array(
                "states" => $states,
                "default" => array(
                    "latitude" => $latitude,
                    "longitude" => $longitude,
                    "pick_date" => $pickdate,
                    "pick_time" => $picktime,
                    "firstname" => $firstname,
                    "telephone" => $telephone,
                    "address1" => $address1,
                    "address2" => $address2,
                    "city" => $city,
                    "state" => $state,
                    "postcode" => $postcode,
                    "memo" => $memo,
                ),
            );
            
            return view('liff/create_pickup_step3_pickupathome',$data);
            
        }else if($type == "Drop_AtThaipost"){
            
            $customer = FS_Customer::get($customerId);
            
            $states = DB::table('thaipost_postal')->groupBy('post_state')->pluck('post_state')->toArray();

            $state = (session('liff.state') != null) ? session('liff.state'):$customer['State'];
            $postal = (session('liff.postal') != null) ? session('liff.postal'):$customer['Postcode'];
            $memo = (session('liff.memo') != null) ? session('liff.memo'):"";
            
            $data = array(
                "states" => $states,
                "default" => array(
                    "state" => $state,
                    "postal" => $postal,
                    "memo" => $memo,
                ),
            );
            
            return view('liff/create_pickup_step3_dropatthaipost',$data);
            
        }else{
            
            //get shipment in cart
            $searchDetails = array(
                "Status" => 'Pending',
            );
            $response = FS_Shipment::search($searchDetails);
            $shipments = array();
            $totalRate = 0;
            if(sizeof($response) > 0 && is_array($response)){
                foreach ($response as $shipmentId) {
                    $shipment = FS_Shipment::get($shipmentId);
                    $shipments[] = $shipment;
                    $totalRate+= $shipment['ShipmentDetail']['ShippingRate'];
                }
                
                //list format according to number of shipment
                $numShipment = sizeof($response);
                if($numShipment == 1){
                    $listFormat = "single";
                }else if($numShipment % 3 == 0){
                    $listFormat = "divide-3";
                }else if($numShipment == 2 || $numShipment == 4){
                    $listFormat = "divide-4";
                }else if($numShipment % 4 == 0){
                    $listFormat = "divide-4";
                }else{
                    $listFormat = "divide-3";
                }
            }
            
            $total = $totalRate - $discount;
            
            $data = array(
                'shipments' => $shipments,
                'total' => $total,
                'shipmentListFormat' => $listFormat,
            );
            
            return view('liff/create_pickup_step4',$data);
            //return view('liff/create_pickup_step3_dropatfastship',$data);
        }

    }
    
    /*
     * create pickup step 4 - show pickup type required data
     */
    public function createPickupStep4(Request $request)
    {
        
        $customerId = session('customer.id');
        
        Fastship::getToken($customerId);
        
        //get type from session 
        $type = session('liff.type');
        $pickupDates = array();

        if($type == "Pickup_AtHome"){
            
            //save param value
            $defData = array();
            if($request->has('pick_date')){
                $defData['pickDate'] = $request->input('pick_date');
                $request->session()->put('liff.pickdate', $request->input('pick_date'));
            }
            if($request->has('pick_time')){
                $defData['pickTime'] = $request->input('pick_time');
                $request->session()->put('liff.picktime', $request->input('pick_time'));
            }
            if($request->has('latitude')){
                $defData['latitude'] = $request->input('latitude');
                $request->session()->put('liff.latitude', $request->input('latitude'));
            }
            if($request->has('longitude')){
                $defData['longitude'] = $request->input('longitude');
                $request->session()->put('liff.longitude', $request->input('longitude'));
            }
            if($request->has('firstname')){
                $defData['firstname'] = $request->input('firstname');
                $request->session()->put('liff.firstname', $request->input('firstname'));
            }
            if($request->has('telephone')){
                $defData['telephone'] = $request->input('telephone');
                $request->session()->put('liff.telephone', $request->input('telephone'));
            }
            if($request->has('address1')){
                $defData['address1'] = $request->input('address1');
                $request->session()->put('liff.address1', $request->input('address1'));
            }
            if($request->has('address2')){
                $defData['address2'] = $request->input('address2');
                $request->session()->put('liff.address2', $request->input('address2'));
            }
            if($request->has('city')){
                $defData['city'] = $request->input('city');
                $request->session()->put('liff.city', $request->input('city'));
            }
            if($request->has('state')){
                $defData['state'] = $request->input('state');
                $request->session()->put('liff.state', $request->input('state'));
            }
            if($request->has('postcode')){
                $defData['postcode'] = $request->input('postcode');
                $request->session()->put('liff.postcode', $request->input('postcode'));
            }
            if($request->has('memo')){
                $defData['memo'] = $request->input('memo');
                $request->session()->put('liff.memo', $request->input('memo'));
            }
            
            $pickupTimes = array(
                "slot0" => "9.00 - 15.00 น.",
                "slot1" => "9.00 - 12.00 น.",
                "slot2" => "12.00 - 15.00 น.",
            );
            
        }else if($type == "Drop_AtThaipost"){
            
            //save param value
            $defData = array();
            if($request->has('state')){
                $defData['state'] = $request->input('state');
                $request->session()->put('liff.state', $request->input('state'));
            }
            if($request->has('postal')){
                $defData['postal'] = $request->input('postal');
                $request->session()->put('liff.postal', $request->input('postal'));
            }
            if($request->has('memo')){
                $defData['memo'] = $request->input('memo');
                $request->session()->put('liff.memo', $request->input('memo'));
            }
            
            $postalName = DB::table('thaipost_postal')->where('post_state',$defData['state'])->where('post_code',$defData['postal'])->value('post_name');
            $defData['post_name'] = $postalName;
            
        }else if($type == "Drop_AtFastship"){
            
        }else{
            
        }
        
        //get shipment in cart
        $searchDetails = array(
            "Status" => 'Pending',
        );
        $response = FS_Shipment::search($searchDetails);
        $shipments = array();
        $totalRate = 0;
        if(sizeof($response) > 0 && is_array($response)){
            foreach ($response as $shipmentId) {
                $shipment = FS_Shipment::get($shipmentId);
                $shipments[] = $shipment;
                $totalRate+= $shipment['ShipmentDetail']['ShippingRate'];
            }
            
            //list format according to number of shipment
            $numShipment = sizeof($response);
            if($numShipment == 1){
                $listFormat = "single";
            }else if($numShipment % 3 == 0){
                $listFormat = "divide-3";
            }else if($numShipment == 2 || $numShipment == 4){
                $listFormat = "divide-4";
            }else if($numShipment % 4 == 0){
                $listFormat = "divide-4";
            }else{
                $listFormat = "divide-3";
            }
        }
        
        $total = $totalRate - $discount;
        
        $data = array(
            'shipments' => $shipments,
            'total' => $total,
            'pickupTimes' => $pickupTimes,
            'shipmentListFormat' => $listFormat,
            'data' => $defData,
        );
        
        return view('liff/create_pickup_step4',$data);

    }
    
    public function doCreatePickup(Request $request)
    {
        
        //check customer login
        if (session('customer.id') != null){
            $customerId = session('customer.id');
        }else{
            return redirect('/')->with('msg','คุณยังไม่ได้เข้าระบบ กรุณาเข้าสู่ระบบเพื่อใช้งาน');
        }
        
        $data = array();
        
        //get api token
        Fastship::getToken($customerId);
        
        //get sender
        $customerObj = FS_Customer::get($customerId);
        
        if($customerObj == null){
            return back()->with('msg','ไม่พบผู้ใช้งานในระบบ');
        }else{
            
            //get type from session
            $type = session('liff.type');

            //////////////////

            //default sender info
            $Firstname = $customerObj['Firstname'];
            $Lastname  = $customerObj['Lastname'];
            $PhoneNumber = $customerObj['PhoneNumber'];
            $Email = $customerObj['Email'];
            $Company = $customerObj['Company'];
            $TaxId = $customerObj['TaxId'];
            $Address1 = $customerObj['AddressLine1'];
            $Address2 = $customerObj['AddressLine2'];
            $City = $customerObj['City'];
            $State = $customerObj['State'];
            $Postcode = $customerObj['Postcode'];
            $Country = $customerObj['Country'];
            $Latitude = $customerObj['Latitude'];
            $Longitude = $customerObj['Longitude'];
            $PickupDate = "";
            
            
            //type condition
            if($type == 'Drop_AtThaiPost'){
                
                //check param
                if( session('liff.state') == null || session('liff.postcode') == null ){
                    return back()->with('msg','กรุณากรอกที่อยู่ให้ครบถ้วน');
                }
                
                //update assign
                $State = session('liff.state');
                $Postcode = session('liff.postcode');
            }
            
            if($type == 'Pickup_AtHome'){
                
                //check param
                if( session('liff.pickdate') != null ){
                    $PickupDate = session('liff.pickdate');
                    $PickupTime = session('liff.picktime');
                    if($PickupTime == "slot0"){
                        $PickupDateTime = $PickupDate . " 00:00:00";
                    }else if($PickupTime == "slot1"){
                        $PickupDateTime = $PickupDate . " 09:00:00";
                    }else if($PickupTime == "slot2"){
                        $PickupDateTime = $PickupDate . " 13:00:00";
                    }
                }else{
                    return back()->with('msg','กรุณาเลือกวันที่ให้เข้าไปรับพัสดุ');
                }
                if( session('liff.firstname') == null || session('liff.telephone') == null || session('liff.address1') == null ||
                    session('liff.city') == null || session('liff.state') == null || session('liff.postcode') == null ){
                        return back()->with('msg','กรุณากรอกที่อยู่ให้ครบถ้วน');
                }
                
                //update assign
                $Firstname = session('liff.firstname');
                $Lastname  = "";
                $PhoneNumber = session('liff.telephone');
                $Address1 = session('liff.address1');
                $Address2 = session('liff.address2');
                $City = session('liff.city');
                $State = session('liff.state');
                $Postcode = session('liff.postcode');
                $Country = "THA";
                $Latitude = session('liff.latitude');
                $Longitude = session('liff.longitude');
                
            }            

            //get shipments
            $searchDetails = array(
                "Status" => 'Pending',
            );
            $ShipmentIds = FS_Shipment::search($searchDetails);
            $Weight = 0;
            $ShippingRate = 0;
            foreach ($ShipmentIds as $key => $ShipId) {
                $ShipmentData = FS_Shipment::get($ShipId);
                $Weight += $ShipmentData['ShipmentDetail']['Weight'];
                $ShippingRate += $ShipmentData['ShipmentDetail']['ShippingRate'];
            }

            $PaymentMethod = "Bank_Transfer";
            //$data['PaymentMethod'] = $request->input('payment_method');
            
            $firstTime = array();
            $searchPickup = array(
                "NoStatuses" => "Cancelled", //4
            );
            $searchPickupResult = FS_Pickup::search($searchPickup);
            if(is_array($searchPickupResult)){
                $firstTime = array_merge($firstTime,$searchPickupResult);
            }
            
            $args = array(
                "refercode" => $customerObj['ReferCode'],
                "isFirstTime" => (sizeof($firstTime)==0),
                "totalRate" => $ShippingRate,
                "customerId" => $customerId,
            );
            //$Discount = $this->calculateDiscount($args);
            $Discount = 0;
            $CouponCode = strtoupper(session('liff.coupon'));
            $Memo = session('liff.memo');

            ///////////////////
            
            //prepare request data
            $createDetails = array(
                'ShipmentDetail' => array(
                    'ShipmentIds' => $ShipmentIds,
                    'TotalShippingRate' => $ShippingRate,
                    'Weight' => $Weight,
                ),
                'PickupAddress' => array(
                    'Firstname' => $Firstname,
                    'Lastname' => $Lastname,
                    'PhoneNumber' => $PhoneNumber,
                    'Email' => $Email,
                    'Company' => $Company,
                    'TaxId' => $TaxId,
                    'AddressLine1' => $Address1,
                    'AddressLine2' => $Address2,
                    'City' => $City,
                    'State' => $State,
                    'Postcode' => $Postcode,
                    'Latitude' => $Latitude,
                    'Longitude' => $Longitude,
                ),
                'PaymentMethod' => $PaymentMethod,
                'PickupType' => $type,
                'Coupon' => $CouponCode,
                'Discount' => $Discount,
                'ScheduleDatetime' => $PickupDateTime,
                'Remark' => $Memo,
            );
            
            //create pickup
            $response = FS_Pickup::create($createDetails);

            //remove save session
            $request->session()->flush();
            
            if($response === false){
                return back()->with('msg','ข้อมูลไม่ครบถ้วน รบกวนตรวจสอบข้อมูลอีกครั้ง');
                
            }else{
                return redirect('liff/create_pickup_completed')->with('msg-type','pickup-success');
            }
        }
    }
    
    /*
     * tracking
     */
    public function tracking(Request $request)
    {
        
        Fastship::getToken();
        
        //check line connect
        if($request->has("line_user_id") && $request->input("line_user_id") != ""){
            
            $lineUserId = $request->input("line_user_id");
            
            try{
                $existed = FS_Customer::checkLineUserId($lineUserId);
            }catch (Exception $e){
                echo "error";
            }
            
            if($existed == -1){
                
                $data = array(
                    "return" => "/liff/tracking",
                );
                return view('liff/connect',$data);
                
            }else{
                
                //login
                Fastship::getToken($existed);
                
                //get customer
                $customerObj = FS_Customer::get($existed);
                
                //save to session
                $request->session()->put('customer.id', $existed);
                $request->session()->put('customer.name', $customerObj['Firstname']);
                
                
            }
            
        }

        $customerId = session('customer.id');
        Fastship::getToken($customerId);
        
        //get shipment in cart
        $searchDetails = array(
            //"Status" => "Sent",
            "NoStatuses" => array("Pending","Created","ReadyToShip","Cancelled")
        );
        $response = FS_Shipment::search($searchDetails);
        
        $shipments = array();
        if(sizeof($response) > 0 && is_array($response)){
            foreach ($response as $shipmentId) {
                $shipment = FS_Shipment::get($shipmentId);
                $shipments[] = $shipment;
            }
        }
        
        //prepare data
        $data = array(
            'shipments' => $shipments,
        );
        return view('liff/tracking',$data);
    }
    
    /*
     * tracking result
     */
    public function trackingResult(Request $request)
    {
        //tracking number
        if($request->has("tracking")){
            $tracking = $request->input("tracking");
        }else{
            $data = array(
                'tracking' => $tracking,
                'trackingResult' =>  array(),
                'trackingStatus' => array(),
            );
            return view('liff/tracking_result',$data)->with('msg','ไม่พบหมายเลข Tracking '.$tracking.' หรือพัสดุกำลังรอการจัดส่ง');
        }
        
        $customerId = session('customer.id');
        Fastship::getToken();

        //static variable
        $trackingStatus = array(
            "pre_transit" => "Pre-Transit",
            "in_transit" => "In-Transit",
            "out_for_delivery" => "Out for Delivery",
            "available_for_pickup" => "Available for Pickup",
            "delivered" => "Delivered",
            "return_to_sender" => "Return to Sender",
            "failure" => "Failure",
            "26" => "Order Processed",
            "1001" => "Pre-Transit",
            "1002" => "In-Transit",
            "1003" => "Out for Delivery",
            "1004" => "Delivered",
            "1005" => "Return to Sender",
            "1006" => "On-hold",
            "1007" => "Unknown",
            "1000" => "Processing",
        );
        
        try{
            
            //call api
            $tracking_data = FS_Shipment::track($tracking);

            if(is_array($tracking_data) && !empty($tracking_data['Events'])){
                
                $data = array(
                    'tracking' => $tracking,
                    'trackingResult' => $tracking_data,
                    'trackingStatus' => $trackingStatus,
                );
                return view('liff/tracking_result',$data);
                
            }else{
                
                $tracking_data = FS_Shipment::trackid($tracking);
                
                if(isset($tracking_data) && is_array($tracking_data) && !empty($tracking_data['Events'])){

                    $data = array(
                        'tracking' => $tracking,
                        'trackingResult' => $tracking_data,
                        'trackingStatus' => $trackingStatus,
                    );
                    return view('liff/tracking_result',$data);
                }else{
                    
                    $data = array(
                        'tracking' => $tracking,
                        'trackingResult' =>  array(),
                        'trackingStatus' => array(),
                    );
                    return view('liff/tracking_result',$data)->with('msg','ไม่พบหมายเลข Tracking '.$tracking.' ระบบปรับปรุงข้อมูลภายใน 24 ชม หลังส่งออก กรุณาตรวจสอบใหม่อีกครั้งภายหลัง');
                }
                
            }
            
        }catch(Exception $e){

            $data = array(
                'tracking' => $tracking,
                'trackingResult' =>  array(),
                'trackingStatus' => array(),
            );
            return view('liff/tracking_result',$data)->with('msg','ไม่พบหมายเลข Tracking '.$tracking.' หรือพัสดุกำลังรอการจัดส่ง');
        }
        
    }
    
    /*
     * topup
     */
    public function topup(Request $request)
    {
        
        Fastship::getToken();
        
        //check line connect
        if($request->has("line_user_id") && $request->input("line_user_id") != ""){
            
            $lineUserId = $request->input("line_user_id");
            
            try{
                $existed = FS_Customer::checkLineUserId($lineUserId);
            }catch (Exception $e){
                echo "error";
            }
            
            if($existed == -1){
                
                $data = array(
                    "return" => "/liff/topup",
                );
                return view('liff/connect',$data);
                
            }else{
                
                //login
                Fastship::getToken($existed);
                
                //get customer
                $customerObj = FS_Customer::get($existed);
                
                //save to session
                $request->session()->put('customer.id', $existed);
                $request->session()->put('customer.name', $customerObj['Firstname']);
                
                
            }
            
        }
        
        $customerId = session('customer.id');
        Fastship::getToken($customerId);
        
        $creditBalance = FS_CreditBalance::get();
        $unpaid = FS_CreditBalance::getUnpaid();
        $creditCards = FS_CreditCard::get_credit_cards();
        
        //prepare data
        $data = array(
            'creditBalance' => $creditBalance['Balance'],
            'unpaid'=> $unpaid['Unpaid'],
            'creditCards' => $creditCards,
        );
        return view('liff/topup',$data);
    }
    
    /*
     * topup by QR
     */
    public function topupQr(Request $request)
    {
        
        if(!$request->has('amount') || !$request->input('amount')){
            return back()->with('msg','กรุณาระบุจำนวนเงิน');
        }
        
        $customerId = session('customer.id');
        
        Fastship::getToken($customerId);
        
        //tracking number
        if($request->has("amount")){
            $amount = $request->input("amount");
        }
        
        $data = array(
            'amount' => $amount,
        );
        return view('liff/topup_qr',$data);
    }
    
    /*
     * topup by Creditcard
     */
    public function topupCreditCard(Request $request)
    {
        
        if(!$request->has('amount') || !$request->input('amount')){
            return back()->with('msg','กรุณาระบุจำนวนเงิน');
        }
        
        $customerId = session('customer.id');
        
        Fastship::getToken($customerId);
        $creditCards = FS_CreditCard::get_credit_cards();
        
        //tracking number
        if($request->has("amount")){
            $amount = $request->input("amount");
        }
        
        $data = array(
            'amount' => $amount,
            'creditCards' => $creditCards,
        );
        return view('liff/topup_creditcard',$data);
    }
    
    public function doAddCreditcard(Request $request)
    {
        
        //check customer login
        if (session('customer.id') != null){
            $customerId = session('customer.id');
        }else{
            return redirect('/')->with('msg','คุณยังไม่ได้เข้าระบบ กรุณาเข้าสู่ระบบเพื่อใช้งาน');
        }

        $this->validate($request, [
            'command' => 'required',
            'omise_token' => 'required',
            'card_number' => 'required',
            'cvv_number' => 'required',
            'holder_name' => 'required',
            'expiration_month' => 'required',
            'expiration_year' => 'required',
        ]);
        
        $customerId = session('customer.id');
        
        Fastship::getToken($customerId);
        
        $command = $request->input('command');
        $token = $request->input('omise_token');
        $number = $request->input('card_number');
        $cvv = $request->input('cvv_number');
        if($command == 'collect-card'){
            
            //get sender
            $customerObj = FS_Customer::get($customerId);

            if(empty($customerObj)){
                return redirect('/liff/add_creditcard')->with('msg','ไม่มีข้อมูลในระบบ');
            }else{
 
                $customerName = $customerObj['Firstname'];
                $customerLastname = $customerObj['Lastname'];
                $customerEmail = $customerObj['Email'];

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
                echo $JSON;exit();
                $url = 'https://app.fastship.co/Omise/AddcardAction.php';
                
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
                                return back()->with('msg','ทำรายการไม่สมบูรณ์ กรุณาทำรายการใหม่อีกครั้ง');
                            }
                            
                            //echo 'Success';
                            return redirect('/liff/add_creditcard')->with('msg','ทำรายการเพิ่มบัตรเรียบร้อยแล้ว')->with('msg-type','success');
                        }else{
                            //echo 'Fail';
                            return back()->with('msg','ทำรายการไม่ถูกต้อง กรุณาทำรายการใหม่อีกครั้ง2');
                        }
                    }else{
                        //echo 'Fail';
                        return back()->with('msg','ทำรายการไม่ถูกต้อง กรุณาทำรายการใหม่อีกครั้ง3');
                    }
                }else{
                    //echo 'Fail';
                    return back()->with('msg','ทำรายการไม่ถูกต้อง กรุณาทำรายการใหม่อีกครั้ง');
                }
            }
        }else{
            //echo 'Fail';
            return back()->with('msg','ทำรายการไม่ถูกต้อง กรุณาทำรายการใหม่อีกครั้ง');
        }
    }
}
