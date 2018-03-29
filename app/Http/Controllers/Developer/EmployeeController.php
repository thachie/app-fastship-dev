<?php

namespace App\Http\Controllers\Developer;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Employee as Employee;// กำหนดชื่อ ของ Model จากที่อยู่ของ Model ที่เราเรียกใช้งาน
use DB;
class EmployeeController extends Controller
{
	public function __construct()
	{
	    $this->dateTime = date_default_timezone_set("Asia/Bangkok");
	}
	
	public function getList(){
		$employees = Employee::get();
		$employees = DB::table('omise_customer')->where('CUST_ID', '40')->where('OMISE_CARDTYPE', 'credit')->get();
		//$this->alert($employees);die();
		foreach ($employees as $value) {
			$this->alert($value);
			$employ[] = $value;
		}
		$data = array(
			'title' => 'Develop Test Laravel',
			'content' => 'Hello Connect'
		);
		$data['employees'] = $employ;
		return view('dev.index',$data);
	}


	public function getIndex(){
		header('content-type:text/html; charset=utf-8');
		//$employees = DB::table('employees')->get();
		//$employees = App\Employee::get(); //Model
		$employees = Employee::get();

		//get() คือการ query ออกมาแบบหลาย record ส่วน first() คือการ query ออกมาเพียง 1 record ที่ต้องการ
		//$employees ? 'Model Profile Connect Yes!' : 'Error! Model Profile Connect False!!!';
		//OR
		/*if($employees){
			return 'Model Profile Connect Yes!';
		}else{
			return 'Error! Model Profile Connect False!!!';
		}*/

		//$employees = Profile::where('id','1')->get();
 
		//$employees = Employee::where('id','1')->first();
		$id =1;
		//$employees = DB::select('select * from employees where id = ?', [$id]);
		$employees = DB::table('omise_customer')->where('CUST_ID', '40')->where('OMISE_CARDTYPE', 'credit')->get();
		//$this->alert($employees);die();
		foreach ($employees as $value) {
			$this->alert($value);
		}
		
		//return $employees;

	}

	

	public function apiForm()
	{
		$aCss = array(
			'css/bootstrap.min.css'
		);
		$aSript = array(
			'js/jquery-3.3.1.js',
			'js/bootstrap.min.js',
			'js/custom.js'
		);

		$data = array(
			'title' => 'DHL API ZZZ',
			'content' => 'Hello Connect',
			'style' => $aCss,
			'script' => $aSript
		);
		return view('dev.api_form',$data);
	}

	public function apiFormSubmit(Request $data)
	{
		//echo $data->input("shipTo_CompanyName");
		$this->alert($data->input("shipTo_CompanyName"));
	}

	public function userList()
	{
		//$users = DB::table('users')->select('name', 'email as user_email')->get();
		$users = DB::select('select * from users');
		//$users2 = DB::table('omise_customer')->get();
		foreach ($users as $value) {
			
			$user_row[] = $value;
		}
		//$this->alert($user_row[1]->OMISE_ID);
		/*foreach ($user_row as $v) {
			
			$this->alert($v->OMISE_ID);
		}*/
		//$data = array();
		//$data['user_row'] = $user_row;
		//$this->alert($users);
		//$this->alert($user_row);
		$aCss = array(
			'css/bootstrap.min.css'
		);
		$aSript = array(
			'js/jquery-3.3.1.js',
			'js/bootstrap.min.js',
			'js/custom.js'
		);

		$data = array(
			'user_row' => $users,
			'style' => $aCss,
			'script' => $aSript
		);
		return view('dev.userList',$data);
	}

	
	public function register(Request $data)
	{
		//echo $data->input("shipTo_CompanyName");
		//$this->alert($data->input("name"));
		//$this->alert($data->input("email"));
		//$this->alert($data->input("password"));
		//$this->alert($data->input("_token"));

		if (! $data->input('email') or ! $data->input('password') )   
	    {
	         //return $this->respondUnprocessableEntity('Parameters failed validation for a lesson.');
	         return 'Parameters failed validation for a lesson.';
	    }
		
		/*$this->validate($data, [
	        'name' => 'required',
	        'email' => 'required',
	        'password' => 'required|max:6'
	    ]);*/
		
		$insert = DB::table('users')->insert(
		    [
		    	'name' => $data->input("name"),
		    	'email' => $data->input("email"),
		    	'password' => $data->input("password"),
		    	'remember_token' => $data->input("_token"),
		    	'created_at' => date('Y-m-d H:i:s'),
		    	'updated_at' => date('Y-m-d H:i:s')
			]
		);

		if($insert){
			return 'Success';
		}else{
			return 'Fail';
		}
	}

	public function updatedUser($id)
	{
		//$this->alert($id);
		$user = DB::select('select * from users where id = ?', [$id]);
		//$user = DB::table('users')->where('id', '=', $id)->get();

		//$this->alert($user);
		//$this->alert($user[0]->id);
		foreach ($user as $val) {
			
			$user_row = $val;
		}
		//$this->alert($user_row);
		$aCss = array(
			'css/bootstrap.min.css'
		);
		$aSript = array(
			'js/jquery-3.3.1.js',
			'js/bootstrap.min.js',
			'js/custom.js'
		);

		$data = array(
			'user' => $user_row,
			'style' => $aCss,
			'script' => $aSript
		);
		return view('dev.update',$data);
		
	}

	public function editUser(Request $data)
	{
		
		if (! $data->input('id') or ! $data->input('name') or ! $data->input('email'))   
	    {
	         //return $this->respondUnprocessableEntity('Parameters failed validation for a lesson.');
	         return 'Parameters failed validation for a lesson.';
	    }else{
	    	echo "OK";

	    	if ( empty($data->input('password')) ){
	    		$password = $data->input('password_old');
	    	}elseif($data->input('password') <> $data->input('password_old')){
	    		$password = $data->input('password');
	    	}else{
	    		$password = $data->input('password_old');
	    	}

	    	$update = DB::table('users')
						->where('id', $data->input('id'))
						->update(
							[
								'name' =>  $data->input('name'),
								'email' =>  $data->input('email'),
						    	'password' =>  $password,
								'updated_at' =>  date('Y-m-d H:i:s')
							]
						);
			if($update){
				return redirect('dev/userList')->with('status','Update Success');
			}else{
				return redirect('dev/userList')->with('status','Update Fail');
			}
	    }
	}

	public function update(Request $request, $id)
	{
	    $this->validate($request, [
	        'menuName' => 'required',
	        'menuLink' => 'required'
	    ]);

	    //create new menu
	    
	    $menus = Menu::find($id);
	    $menus->name = $request->input('menuName');
	    $menus->link = $request->input('menuLink');
	    $menus->save();
	    
	    return redirect('/menu')->with('success', 'Menu updated');
	}

	public function deleteUser($id)
	{
		if ( !empty($id) ){
	    		$delete = DB::table('users')->where('id', $id)->delete();
	    		if($delete){
					return redirect('dev/userList')->with('status','Delete Success');
				}else{
					return redirect('dev/userList')->with('status','Delete Fail');
				}
    	}else{
    		return redirect('dev/userList')->with('status','ID id null');
    	}
	}




	public function store(Request $request)
	{

	    if (! $request->input('title') or ! $request->input('body') )   
	    {
	         return $this->respondUnprocessableEntity('Parameters failed validation for a lesson.');
	    }

	    Lesson::create($request->all());

	    return $this->respondCreated('Lesson successfully created.');

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
