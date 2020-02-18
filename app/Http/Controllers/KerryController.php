<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
//use App\Lib\Fastship\Fastship;
//use App\Lib\Fastship\FS_Shipment;

class KerryController extends Controller
{
    public function __construct()
    {
        date_default_timezone_set("Asia/Bangkok");
        if($_SERVER['REMOTE_ADDR'] == "localhost" || $_SERVER['REMOTE_ADDR'] == "127.0.0.1"){
            include(app_path() . '\Lib\inc.functions.php');
        }else{
            include(app_path() . '/Lib/inc.functions.php');
        }
    }

    public function index()
    {
        echo 1234;
    }
	
	public function shipmentInfo()
    {
        //TEST000000001 – TEST999999999
        $gencode = generateRandomString(9);
		$con_no = "TEST".$gencode;
		$s_name = "Fastship Co.,Ltd";
        $s_address = "1/269 ซอยแจ้งวัฒนะ 14";
        $s_village = "";
        $s_soi = "";
        $s_road = "";
		$s_subdistrict = "แขวงทุ่งสองห้อง";
		$s_district = "หลักสี่";
		$s_province = "กรุงเทพมหานคร";
        $s_zipcode = "10210";
        $s_mobile1 = "0819999999";
		$s_mobile2 = "";
		$s_telephone = "020803999";
		$s_email = "tae@tuff.co.th";
		$postcodeDrop = "10210";
        $s_contactperson = "Potae";
        $r_name = "ANUSAK JITCHOT";
        $r_address = "999 ม.3";
        $r_village = "";
        $r_soi = "";
        $r_road = "";
        $r_subdistrict = "บางเลน";
        $r_district = "บางใหญ่";
        $r_province = "นนทบุรี";
        $r_zipcode = "11140";
        $r_mobile1 = "0858884698";
        $r_mobile2 = "";
        $r_telephone = "";
        $r_email = "tae@tuff.co.th";
        $r_contactperson = "ANUSAK JITCHOT";
        $special_note = "เก็บเงินปลายทาง COD";
        $service_code = "ND";
        $cod_amount = 2500;
        $cod_type = "CASH";
        $tot_pkg = 2;
        $declare_value = 0;
        $ref_no = "REF-".date("YmdHis");
        $action_code = "A";

		$json = '
		{
            "req": {
                "shipment": {
                    "con_no": "'.$con_no.'",
                    "s_name": "'.$s_name.'",
                    "s_address": "'.$s_address.'",
                    "s_village": "'.$s_village.'",
                    "s_soi": "'.$s_soi.'",
                    "s_road": "'.$s_road.'",
                    "s_subdistrict": "'.$s_subdistrict.'",
                    "s_district": "'.$s_district.'",
                    "s_province": "'.$s_province.'",
                    "s_zipcode": "'.$s_zipcode.'",
                    "s_mobile1": "'.$s_mobile1.'",
                    "s_mobile2": "'.$s_mobile2.'",
                    "s_telephone": "'.$s_telephone.'",
                    "s_email": "'.$s_email.'",
                    "s_contactperson": "'.$s_contactperson.'",
                    "r_name": "'.$r_name.'",
                    "r_address": "'.$r_address.'",
                    "r_village": "'.$r_village.'",
                    "r_soi": "'.$r_soi.'",
                    "r_road": "'.$r_road.'",
                    "r_subdistrict": "'.$r_subdistrict.'",
                    "r_district": "'.$r_district.'",
                    "r_province": "'.$r_province.'",
                    "r_zipcode": "'.$r_zipcode.'",
                    "r_mobile1": "'.$r_mobile1.'",
                    "r_mobile2": "'.$r_mobile2.'",
                    "r_telephone": "'.$r_telephone.'",
                    "r_email": "'.$r_email.'",
                    "r_contactperson": "'.$r_contactperson.'",
                    "special_note": "'.$special_note.'",
                    "service_code": "'.$service_code.'",
                    "cod_amount": '.$cod_amount.',
                    "cod_type": "'.$cod_type.'",
                    "tot_pkg": '.$tot_pkg.',
                    "declare_value": '.$declare_value.',
                    "ref_no": "'.$ref_no.'",
                    "action_code": "'.$action_code.'"
                }
            }
        }';
        
        alert($json);
        //die();
        $url = "http://58.137.103.187:8081/EDIWebAPIs/SmartEDI/Shipment_Info"; //Test
        $Response = callAPI_Kerry('POST', $url, $json);
        $res = json_decode($Response, true);
        alert($res);
    }

    public function shipmentStatus()
    {
        $gencode = generateRandomString(9);
        $con_no = "TEST".$gencode;
        $status_code = "010";
        $status_desc = "Shipment picked-up";
        $status_date = date("Y-m-d H:i:s");
        $update_date = date("Y-m-d H:i:s", strtotime("+1 hours"));
        $ref_no = "REF-".date("YmdHis");
        $location = "Bangkok";
        /*$json ='
        {
            "req": {
                "status":
                {
                    "con_no": "'.$con_no.'",
                    "status_code": "'.$status_code.'",
                    "status_desc": "'.$status_desc.'",
                    "status_date": "'.$status_date.'",
                    "update_date": "'.$update_date.'",
                    "ref_no": "'.$ref_no.'",
                    "location": "'.$location.'"
                }
            }
        }';*/
        $json='
        {
            "req": {
                "status":
                {
                    "con_no": "KERY00000000001",
                    "status_code": "010",
                    "status_desc": "Shipment picked-up",
                    "status_date": "2014-06-09 15:00:00",
                    "update_date": "2014-06-09 15:07:35",
                    "ref_no": "REF-3359000187",
                    "location": "Bangkok"
                }
            }
        }';
        alert($json);
        $url = "http://58.137.103.187:8081/EDIWebAPIs/shipment_status"; //Test
        $Response = callAPI_Kerry('POST', $url, $json);
        $res = json_decode($Response, true);
        alert($res);
    }
    
}
