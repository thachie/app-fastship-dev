<?php

namespace App\Http\Controllers;

use App\Lib\Fastship\FS_Pickup;
use Illuminate\Http\Request;
use App\Lib\Fastship\Fastship;
use App\Lib\Fastship\FS_CreditBalance;
use App\Lib\Fastship\FS_Customer;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        //check customer login
        if (session('customer.id') != null){
            $customerId = session('customer.id');
        }else{
            return redirect('/login')->with('msg','คุณยังไม่ได้เข้าระบบ กรุณาเข้าสู่ระบบเพื่อใช้งาน');
        }

        $sentPickups = array();

        //prepare request data
        $searchDetails = array(
            'Status' => "Sent",
        );

        Fastship::getToken($customerId);

        //sent pickups
        $resDetails = FS_Pickup::search($searchDetails);
        if($resDetails === false){
            $sentPickups = array();
        }else{
            $sentPickups = sizeof($resDetails);
        }
        
        //customer
        $customer = FS_Customer::get($customerId);
        
        if($customer['Group'] == "Standard"){
            $groupTextCurrent = "";
            $groupTextNext = "ใช้งานให้ครบ 10,000 บาท เพื่อรับส่วนลด 3%";
        }else if($customer['Group'] == "Iron"){
            $groupTextCurrent = "ได้รับส่วนลดค่าส่ง 3% ทุกการส่ง";
            $groupTextNext = "ใช้งานให้ครบ 20,000 บาท เพื่อรับส่วนลด 5%";
        }else if($customer['Group'] == "Bronze"){
            $groupTextCurrent = "ได้รับส่วนลดค่าส่ง 5% ทุกการส่ง";
            $groupTextNext = "ใช้งานให้ครบ 50,000 บาท เพื่อรับส่วนลด 7%";
        }else if($customer['Group'] == "Silver"){
            $groupTextCurrent = "ได้รับส่วนลดค่าส่ง 7% ทุกการส่ง";
            $groupTextNext = "ใช้งานให้ครบ 100,000 บาท เพื่อรับส่วนลด 10%";
        }else if($customer['Group'] == "Gold"){
            $groupTextCurrent = "ได้รับส่วนลดค่าส่ง 10% ทุกการส่ง";
            $groupTextNext = "ใช้งานให้ครบ 200,000 บาท เพื่อรับส่วนลด 15%";
        }else if($customer['Group'] == "Titanium"){
            $groupTextCurrent = "ได้รับส่วนลดค่าส่ง 15% ทุกการส่ง";
            $groupTextNext = "";
        }else{
            $groupTextCurrent = "";
            $groupTextNext = "";
        }
        
        //balance
        $balance = FS_CreditBalance::get();
        
        $data = array(
            "sentPickups" => $sentPickups,
            "customer_data" => $customer,
            "groupText" => array(
                "current" => $groupTextCurrent,
                "next" => $groupTextNext,
            ),
            "balance" => $balance,
        );

        return view('index',$data);
    }
}
