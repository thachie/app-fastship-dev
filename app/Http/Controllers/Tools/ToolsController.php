<?php

namespace App\Http\Controllers\Tools;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Lib\Fastship\Fastship;
use App\Lib\Fastship\FS_Shipment;
use App\Lib\TradeGov\TradeGovManager;
use Illuminate\Support\Facades\DB;
use App\Lib\LandedCostIO\LandedCostManager;
use App\Lib\Fastship\FS_Address;

class ToolsController extends Controller
{
    public function __construct()
    {

        if($_SERVER['REMOTE_ADDR'] == "localhost"){
            include(app_path() . '\Lib\inc.functions.php');
        }else{
            include(app_path() . '/Lib/inc.functions.php');
        }
    }
	
	//Prepare for tracking page
	public function prepareTrack($tracking=null)
	{
		
		if (session('customer.id') != null){
			$customerId = session('customer.id');
		}else{
		    $customerId = 69;
			//return redirect('/')->with('msg','คุณยังไม่ได้เข้าระบบ กรุณาเข้าสู่ระบบเพื่อใช้งาน');
		}
		
		if($tracking){
			
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
		    
			//get api token
	        Fastship::getToken($customerId);
	
	        //call api
	        $tracking_data = FS_Shipment::track($tracking);
	        if(!empty($tracking_data['Events'])){
	            $data = array(
	                'paramId' => $tracking,
	                'tracking_data' => $tracking_data,
	            	'trackingStatus' => $trackingStatus,
	            );
	            return view('tools_track',$data);
	        }else{
	            
	            $tracking_data = FS_Shipment::trackid($tracking);
	            if(!empty($tracking_data['Events'])){
	                $data = array(
	                    'paramId' => $tracking,
	                    'tracking_data' => $tracking_data,
	                    'trackingStatus' => $trackingStatus,
	                );
	                return view('tools_track',$data);
	            }else{
	                
    	        	$data = array(
    	        	    'paramId' => $tracking,
    	        		'tracking_data' =>  array(),
    	        		'trackingStatus' => array(),
    	        	);
    	        	return redirect('track.fs')->with('msg','ไม่พบหมายเลข Tracking '.$tracking.' ระบบปรับปรุงข้อมูลภายใน 24 ชม หลังส่งออก กรุณาตรวจสอบใหม่อีกครั้งภายหลัง');
	            }
	        }

		}else{
			
			$data = array(
			    'paramId' => $tracking,
				'tracking_data' =>  array(),
				'trackingStatus' => array(),
			);
			return view('tools_track',$data);
		}
	}
	
	//Prepare for tracking page
	public function prepareStandaloneTrack($tracking=null)
	{
	    
	    $customerId = 69;
	    
	    if($tracking){
	        
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
	        
	        //get api token
	        Fastship::getToken();
	        
	        //call api
	        $tracking_data = FS_Shipment::trackNoApi($tracking);
	        if(!empty($tracking_data)){
	            $data = array(
	                'paramId' => $tracking,
	                'tracking_data' => $tracking_data,
	                'trackingStatus' => $trackingStatus,
	            );
	            return view('standalone_track',$data);
	        }else{
	            
	            $tracking_data = FS_Shipment::trackid($tracking);
	            if(!empty($tracking_data['Events'])){
	                $data = array(
	                    'paramId' => $tracking,
	                    'tracking_data' => $tracking_data,
	                    'trackingStatus' => $trackingStatus,
	                );
	                return view('standalone_track',$data);
	            }else{
	                
	                $data = array(
	                    'paramId' => $tracking,
	                    'tracking_data' =>  array(),
	                    'trackingStatus' => array(),
	                );
	                return redirect('track_st')->with('msg','ไม่พบหมายเลข Tracking '.$tracking.' ระบบปรับปรุงข้อมูลภายใน 24 ชม หลังส่งออก กรุณาตรวจสอบใหม่อีกครั้งภายหลัง');
	            }
	        }
	        
	    }else{

	        $data = array(
	            'paramId' => $tracking,
	            'tracking_data' =>  array(),
	            'trackingStatus' => array(),
	        );
	        return view('standalone_track',$data);
	    }
	}

	//Prepare for tracking page
	public function prepareDeMinimis($country=null)
	{
	
		if (session('customer.id') != null){
			$customerId = session('customer.id');
		}else{
			return redirect('/')->with('msg','คุณยังไม่ได้เข้าระบบ กรุณาเข้าสู่ระบบเพื่อใช้งาน');
		}

		$resource = DB::table('country')->where("IS_ACTIVE",1)->orderBy("CNTRY_NAME")->get();
		$country_row = array();
		foreach ($resource as $val) {
			$country_row[$val->CNTRY_CODE] = $val->CNTRY_NAME;
		}
		
		if($country){

			$data = array();
			$data['country'] = $country_row;
			$data['select_country_code'] = $country;
					
			$resource = DB::table('country')->where("CNTRY_CODE",$country)->first();

			if($resource){
				
				$select_country = $resource->CNTRY_NAME;
				
				$deminimis = TradeGovManager::search($resource->CNTRY_CODE2ISO);
	
				if($deminimis && $deminimis->de_minimis_value){
					$data['select_country'] = $select_country;
					$data['de_minimis_value'] = $deminimis->de_minimis_value;
					$data['de_minimis_currency'] = $deminimis->de_minimis_currency;
					
				}else{
					return redirect('/deminimis/')->with('msg','ไม่พบข้อมูล');
				}
			}else{
				return redirect('/deminimis/')->with('msg','ไม่พบประเทศ ' . $select_country);
			}
				
		}else{
			$data = array(
				'country' => $country_row,
				'select_country' =>  "",
				'select_country_code' =>  "",
				'de_minimis_value' =>  "",
				'de_minimis_currency' =>  "",
			);
		}
			
		return view('tools_deminimis',$data);
			
		
	}
	
	
	
}
