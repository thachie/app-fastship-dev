<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Employee as Employee;// กำหนดชื่อ ของ Model จากที่อยู่ของ Model ที่เราเรียกใช้งาน


class EmployeeController extends Controller
{
	public function getIndex(){
		header('content-type:text/html; charset=utf-8');
		//$employees = DB::table('employees')->get();
		//$employees = App\Employee::get(); //Model
		$employees = Employee::get();
		return $employees ? 'Model Profile Connect Yes!' : 'Error! Model Profile Connect False!!!';
		//return 'Model Profile Connect Yes!';
	}
}
