<?php

namespace App\Http\Controllers\Developer;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;

class DeveloperController extends Controller
{

    function __construct(){

    }

    public function index(){
    	//return 'Hello Developer';
    	return view('dev.page');
    }

    public function checkData($id=null,$name=null){

    	$data['id'] = $id;
    	$data['name'] = $name;
    	$this->alert($data);
    	//return 'I am  ' . $name;
    }

    
    public function getUser($id=null,$name=null){
    	$data=array();
    	$data['id'] = $id;
    	$data['name'] = $name;
    	$res = $this->getProfile($id);
    	$this->alert($res);
    	//return view('dev.user',$data);
    }

    public function getProfile($id=null){
    	//$q = DB::table('omise_customer')->get();
    	//$q = DB::table('omise_customer')->find('40');
    	//$q = DB::select('select * from omise_customer where CUST_ID = :id', ['id' => 40]);
    	$q = DB::select('select * from omise_customer where CUST_ID = ?', [$id]);
    	//$this->alert($q);
    	return $q;
		//$id = Auth::user()->id;
		//$user = Users::where('id',$id)->first();
		//$this->alert($user);
		//if(!$user) return redirect('admin/user/index');
		//$data = array('id' => $id,'user' => $user);
		//return view('admin.user.form',$data);
	}

    public function alert()
	{
		$arg_list = func_get_args();
		foreach ($arg_list as $k => $v){
			print "<pre>";
			print_r( $v );
			print "</pre>";
		}
	}
}
