<?php

namespace App\Http\Controllers\Shipment;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Lib\LandedCostIO\LandedCostManager;

class TaxDutyController extends Controller
{
    /*
	public function prepareTariffRates($q=null)
	{
		 
		if (session('customer.id') != null){
			$customerId = session('customer.id');
		}else{
			return redirect('/')->with('msg','คุณยังไม่ได้เข้าระบบ กรุณาเข้าสู่ระบบเพื่อใช้งาน');
		}
	
		$rates = array();
		if(isset($q) && $q!=""){
			 
			$params = array(
					'description' => "-",
					'name' => $q,
					'category' => "-",
					'sku' => "-",
			);
			// $hs = LandedCostManager::getHS($params);
			$landedCost = LandedCostManager::getLandedCost($params);
			 
			print_r($landedCost);exit();
			 
			if($hs->hsCode == "NO-HS-CODE-FOUND"){
				return back()->with('msg','กรุณากรอกข้อมูลให้ละเอียดหรือถูกต้อง');
			}
			 
			echo $hs->description . "<br />";
			echo $hs->hsCode . "<hr />";
			print_r($hs);
			//

		}
	
		//get parent category
		$categories = DB::table("hscodes")
		->select(
				"hscodes.hs_code as code",
				"hscodes.hs_desc as desc"
				)->where('hscodes.hs_leveldepth',2)->orderBy('hscodes.hs_code')->get();
	
				//get country
				$resource = DB::table('country')->where("IS_ACTIVE",1)->orderBy("CNTRY_NAME")->get();
				$country_row = array();
				foreach ($resource as $val) {
					$country_row[$val->CNTRY_CODE2ISO] = $val->CNTRY_NAME;
				}
	
				$data = array(
						'countries2iso' => $country_row,
						'categories' => $categories,
						'rates' => $rates,
						'declaration' =>  $q,
				);
	
				return view('tools_tariff',$data);
				 
				 
	}
	*/
    
    public function hscodes(Request $request){
        
        $query = strtoupper($request->get('term'));
        $category = trim($request->get('category'));
        
        $query = str_replace("'","",$query);
        
        if(strlen($query) < 3) return ;
        
        
        if(preg_match('/^\d/', $query) === 1){
            
            $hsQuery = DB::table("hscodes")
            ->select(
                "hscodes.hs_code as code",
                "hscodes.hs_desc as desc"
            )->where('hscodes.hs_leveldepth',6);
            
            if($query != ""){
                $hsQuery->whereRaw("hs_code LIKE '".$query."%'");
            }
            if($category != ""){
                $hsQuery->whereRaw("hs_code LIKE '".$category."%'");
            }
            
            $codes = $hsQuery->orderBy('hscodes.hs_code','asc')->get();
                
        }else{
        
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
           
        }
        
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
    
    //Get landed cost
    public function getLandedCost(Request $request)
    {
        //check customer login
        if (session('customer.id') != null){
            $customerId = session('customer.id');
        }else{
            exit();
        }
        
        $carrier = $request->input('carrier');
        $shippingCostTotal = $request->input('shipping');
       // $receiverState = $request->input('state');
        $receiverState = "-";
        $receiverCountry = ($request->has('country'))?$request->input('country'):"US";
       // $receiverPostcode = $request->input('postcode');
        $receiverPostcode = "-";
        
        $category = $request->has('category') ? $request->input('category'):"";
        $productDesc = $request->has('declare') ? $request->input('declare'):"";
        $productHs = $request->has('hs_code') ? $request->input('hs_code'):"";
        $productWeight = $request->has('weight') ? $request->input('weight'):"";
        $productPrice = $request->has('price') ? "".$request->input('price'):"";
        
        $products = array(
            0 => array(
                "sku" => "SC".time(),
                "description" => $productDesc,
                "name" => "-",
                "price" => $productPrice,
                "quantity" => 1,
                "category" => "-",
                "hsCode" => $productHs,
                "weight" => $productWeight/1000,
                "uom" => "kg",
                "countryOfOrigin" => "TH",
                "autoClassify" => "false"
            ),
        );
        
        
        $shippingMethod = "ANY";
        $sourceCurrencyCode = "THB";
        $targetCurrencyCode = "USD";
        $discountTotal = 0;
        $additionalInsuranceTotal = 0;
        $languageCode = "en-us";
        
        $senderFirstname = "John";
        $senderLastname = "Shipper";
        $senderAddress1 = "Chaengwattana 14";
        $senderAddress2 = "";
        $senderCity = "Laksi";
        $senderState = "BKK";
        $senderCountry = "TH";
        $senderPostcode = "10210";
        $senderEmail = "cs@fastship.co";
        $senderNin = "";
        $receiverFirstname = "Fastship";
        $receiverLastname = "Co";
        $receiverAddress1 = "Chaengwattana 14";
        $receiverAddress2 = "";
        $receiverCity = "City";
        
        $receiverEmail = "example@gmail.com";
        $receiverNin = "";
        
        if($request->has('hs_code')){
            
            $params = array(
                'carrier' => $carrier,
                'shippingMethod' => $shippingMethod,
                'shippingCostTotal' => $shippingCostTotal,
                'sourceCurrencyCode' => $sourceCurrencyCode,
                'targetCurrencyCode' => $targetCurrencyCode,
                'discountTotal' => $discountTotal,
                'additionalInsuranceTotal' => $additionalInsuranceTotal,
                'languageCode' => $languageCode,
                'senderFirstname' => $senderFirstname,
                'senderLastname' => $senderLastname,
                'senderAddress1' => $senderAddress1,
                'senderAddress2' => $senderAddress2,
                'senderCity' => $senderCity,
                'senderState' => $senderState,
                'senderCountry' => $senderCountry,
                'senderPostcode' => $senderPostcode,
                'senderEmail' => $senderEmail,
                'senderNin' => $senderNin,
                'receiverFirstname' => $receiverFirstname,
                'receiverLastname' => $receiverLastname,
                'receiverAddress1' => $receiverAddress1,
                'receiverAddress2' => $receiverAddress2,
                'receiverCity' => $receiverCity,
                'receiverState' => $receiverState,
                'receiverCountry' => $receiverCountry,
                'receiverPostcode' => $receiverPostcode,
                'receiverEmail' => $receiverEmail,
                'receiverNin' => $receiverNin,
                'products' => $products,
            );
            
            $resp = LandedCostManager::getLandedCost($params);

        }else{
            $resp = array();
        }
        
        //get parent category
        $categories = DB::table("hscodes")
        ->select(
            "hscodes.hs_code as code",
            "hscodes.hs_desc as desc"
        )->where('hscodes.hs_leveldepth',2)->orderBy('hscodes.hs_code')->get();
            
        //get country
        $resource = DB::table('country')->where("IS_ACTIVE",1)->orderBy("CNTRY_NAME")->get();
        $country_row = array();
        foreach ($resource as $val) {
            $country_row[$val->CNTRY_CODE2ISO] = $val->CNTRY_NAME;
        }
        
        if($customerId==38){
            print_r($resp);
            //exit();
        }
        $data = array(
            'countries2iso' => $country_row,
            'categories' => $categories,
            'result' => $resp,
            'default' =>  array(
                "category" => $category,
                "declare" => $productDesc,
                "hs_code" => $productHs,
                "price" => $productPrice,
                "shipping" => $shippingCostTotal,
                "country" => $receiverCountry,
            ),
        );
        
        return view('tools_tariff',$data);
    }
    
}
