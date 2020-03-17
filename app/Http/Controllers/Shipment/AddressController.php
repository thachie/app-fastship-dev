<?php

namespace App\Http\Controllers\Shipment;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Session;
use App\Lib\Fastship\Fastship;
use App\Lib\Fastship\FS_Address;

class AddressController extends Controller
{
    
    public function index()
    {
        if (session('customer.id') != null){
            $customerId = session('customer.id');
        }else{
            return redirect('/')->with('msg','คุณยังไม่ได้เข้าระบบ กรุณาเข้าสู่ระบบเพื่อใช้งาน');
        }
        
        $agent = 'DHL';
        
        Fastship::getToken($customerId);
        
        $countries = FS_Address::get_countries();
        
        $data = array(
            'agent' => $agent,
            'countries' => $countries,
        );
        
        return view('address',$data);
    }
    
    public function getStates(Request $request)
    {
        
        if (session('customer.id') != null){
            $customerId = session('customer.id');
        }else{
            return redirect('/')->with('msg','คุณยังไม่ได้เข้าระบบ กรุณาเข้าสู่ระบบเพื่อใช้งาน');
        }

        Fastship::getToken($customerId);
        
        $states = FS_Address::get_states_smart($request->get("country_id"),$request->get("term"),$request->get("agent"));

        return response()->json(['states'=>$states]);
    }
    
    public function getCities(Request $request)
    {
        
        if (session('customer.id') != null){
            $customerId = session('customer.id');
        }else{
            return redirect('/')->with('msg','คุณยังไม่ได้เข้าระบบ กรุณาเข้าสู่ระบบเพื่อใช้งาน');
        }
        
        Fastship::getToken($customerId);
        
        $cities = FS_Address::get_cities_smart($request->get("country_id"),$request->get("state_id"),$request->get("term"),$request->get("agent"));

        return response()->json(['cities'=>$cities]);
    }
    
    public function getPostcodes(Request $request)
    {
        
        if (session('customer.id') != null){
            $customerId = session('customer.id');
        }else{
            return redirect('/')->with('msg','คุณยังไม่ได้เข้าระบบ กรุณาเข้าสู่ระบบเพื่อใช้งาน');
        }
        
        Fastship::getToken($customerId);
        
        $modifyCity = str_replace(" ","_",$request->get("city_name"));
        $postcodes = FS_Address::get_postcodes($request->get("country_id"),$modifyCity);
        
        return response()->json(['postcodes'=>$postcodes]);
    }
    
    public function getAppPostCode(Request $request)
    {
        $postcode = DB::table("agent_world_city")
        ->select(array("COUNTRY_CODE","STATE_CODE","CITY_NAME","POST_CODE"))
        ->where("AGENT",$request->agent)
        ->where("COUNTRY_CODE",$request->countryCode)
        ->where("CITY_NAME",$request->cityName)
        ->get();
        
        return response()->json(['postcode'=>$postcode]);
    }
    

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
