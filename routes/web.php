<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/*
get: การดึงข้อมูลมาแสดง
post: เอาข้อมูลไปบันทึกในฐานข้อมูล
put path: การเอาข้อมูล ไปอัพเดตในฐานข้อมูล
delete : การลบข้อมูลในฐานข้อมูล
*/

//https://www.teeneeweb.com/laravel5-2-%E0%B8%A7%E0%B8%B4%E0%B8%98%E0%B8%B5%E0%B9%83%E0%B8%8A%E0%B9%89-laravel-%E0%B9%80%E0%B8%9A%E0%B8%B7%E0%B9%89%E0%B8%AD%E0%B8%87%E0%B8%95%E0%B9%89%E0%B8%99/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/tae', function () {
	return "I'm Tae";
});

Route::get('test', 'Auth\LoginController@test');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

//Connect DB
Route::get('check-connect',function(){
if(DB::connection()->getDatabaseName())
{
	return "Yes! successfully connected to the DB: " . DB::connection()->getDatabaseName();
}else{
	return 'Connection False !!';
}
 
});

/*Route::get('/', function () {
    return view('greeting', ['name' => 'James']);
});
*/
Route::get('/contact', 'ContactController@show');
Route::post('/contact',  'ContactController@mailToAdmin');

//เรียกจาก Route โดยตรงไม่ต้องผ่าน controller
Route::get('test-route',function(){ return View::make('test'); });

//เรียกใช้งาน โดย Route เรียกผ่าน Method controller อีกที
Route::get('test-method','TestController@getIndex');


/*
// Offline Login Page
Route::controller('admin/login','Admins\LoginController');
 
// Start Online Page
Route::group(['prefix'=>'admin','middleware'=>'auth','namespace'=>'Admins'],function(){
    Route::controller('index','BlankController');
    Route::controller('user','UserController');
});
*/

//Route::get('getIndex','Admins\LoginController@getIndex');

/*
Route::get('/cal/{num1?}/{num2?}',function($num1=0,$num2=0){
	echo $num1.'+'.$num2.' = '.($num1+$num2);
})->where('num1','[0-9]+')->where('num2','[0-9]+');
*/


//Developer Modude
Route::get('dev/index/','Developer\DeveloperController@index');

Route::get('getUser/{id?}/{name?}','Developer\DeveloperController@getUser')->where('id','[0-9]+')->where('name','[a-zA-zก-ฮ]+');

//Model
Route::get('/profile', function () {
	$profile = App\Employee::get();
	foreach ($profile as $value) {
		var_dump($value);
	}
    return $profile;
});

Route::get('dev/list', 'Developer\EmployeeController@getList');
Route::get('dev/emp', 'Developer\EmployeeController@getIndex');
//Route::get('emp', 'EmployeeController@getIndex');
Route::get('dev/api_form', 'Developer\EmployeeController@apiForm');



Route::get('dev/userList', 'Developer\EmployeeController@userList');
Route::post('dev/post', 'Developer\EmployeeController@apiFormSubmit');
Route::get('dev/register',function()
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
			'style' => $aCss,
			'script' => $aSript
		);
		return View::make('dev.register',$data); 
	});
Route::post('dev/chkRegister', 'Developer\EmployeeController@register');
Route::get('dev/updateUser/{id}', 'Developer\EmployeeController@updatedUser');
Route::post('dev/editUser', 'Developer\EmployeeController@editUser');
Route::get('dev/deleteUser/{id}', 'Developer\EmployeeController@deleteUser');