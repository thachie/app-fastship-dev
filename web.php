<?php
use Illuminate\Http\Request;
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

//login & register
Route::get('login', function () {  return view('login'); });
Route::post('customer/login', 'Customer\CustomerController@login');
Route::post('customer/register', 'Customer\CustomerController@register');
Route::get('forget_password', function () {  return view('forget_password'); });
Route::post('customer/forget_password', 'Customer\CustomerController@resetPassword');
Route::get('joinus', function () {  return view('register'); });
Route::get('joinus_en', function () {  return view('register_en'); });
Route::get('joinus/{refercode}', function ($refercode) {  return view('register')->with("ref",$refercode); });
Route::get('register/DWSHIP', function () { return view('register')->with("ref","!RFdTSElQ"); });
Route::get('register/PYSHIP', function () { return view('register')->with("ref","!UFlTSElQ"); });
Route::get('register/VVSHIP', function () { return view('register')->with("ref","!VlZTSElQ"); });
Route::get('register/KOSHIP', function () { return view('register')->with("ref","!S09TSElQ"); });
Route::get('register/TKSHIP', function () { return view('register')->with("ref","!VEtTSElQ"); });
Route::get('register/NNSHIP', function () { return view('register')->with("ref","!Tk5TSElQ"); });

Route::get('register_complete', function () {  return view('register_complete'); });

Route::get('join_{type}', function ($type) {  return view('register_type')->with("type",$type); });

Route::get('dtacfastship', function () {  return view('login'); });
Route::get('shopeefastship', function () {  return view('login'); });

//callback send email
Route::get('email_tracking/{token1?}/{token2?}', 'Shipment\ShipmentController@sendTrackingEmail');
Route::get('email_paymentnotify/{token1?}/{token2?}', 'Credit\CreditBalanceController@sendPaymentNotifyEmail');

//check login session
Route::group(['middleware' => 'loginsession'], function () {
	
	Route::get('/', function () {  return view('index'); });
	
	//customer
	Route::get('customer/logout', 'Customer\CustomerController@logout');
	Route::get('myaccount', 'Customer\CustomerController@prepareMyAccount');
	Route::get('edit_customer', 'Customer\CustomerController@prepareEditCustomer');
	Route::post('customer/edit', 'Customer\CustomerController@update');
	Route::get('change_password', function () {  return view('change_password'); });
	Route::post('customer/change_password', 'Customer\CustomerController@changePassword');
	Route::post('customer/add-channel', 'Customer\CustomerController@addChannel');

	//shipment
	Route::get('calculate_shipment_rate', 'Shipment\ShipmentController@prepareCalculateShipmentRate');
	Route::match(['get', 'post'], 'create_shipment', 'Shipment\ShipmentController@prepareCreateShipment');
	Route::post('shipment/get_rate', 'Shipment\ShipmentController@getRate');
	Route::post('shipment/create', 'Shipment\ShipmentController@createShipment');
	Route::post('shipment/cancel', 'Shipment\ShipmentController@cancelShipment');
	Route::match(['get', 'post'], 'create_shipment_data','Shipment\ShipmentController@createShipmentData');
	Route::get('shipment_detail/{id?}', 'Shipment\ShipmentController@prepareShipmentDetail');
	Route::get('shipment_channel', 'Shipment\ShipmentController@prepareShipmentChannel');
	Route::get('tracking/{trackID?}', 'Shipment\ShipmentController@tracking');
	Route::get('import_shipment', function(){ return view('import_shipment_upload'); });
	Route::post('shipment/upload', 'Shipment\ShipmentController@prepareShipmentImport');
	Route::post('shipment/import', 'Shipment\ShipmentController@importShipment');
	Route::post('shipment/get_fba_address', 'Shipment\ShipmentController@getFbaAddress');
	
	
	//pickup
	Route::get('create_pickup', 'Pickup\PickupController@prepareCreatePickup');
	Route::post('pickup/create', 'Pickup\PickupController@createPickup');
	Route::get('pickup_detail/{id?}', 'Pickup\PickupController@preparePickupDetail');
	Route::get('pickup_detail_print/{id?}', 'Pickup\PickupController@preparePickupDetailPrint');
	Route::match(['get', 'post'],'pickup_list/{page?}', 'Pickup\PickupController@preparePickupList');
	Route::post('pickup/cancel', 'Pickup\PickupController@cancelPickup');
	Route::get('pickup_invoice_print/{id?}', 'Pickup\PickupController@preparePickupInvoicePrint');
	Route::get('thaipost/label/{id?}', 'Pickup\PickupController@getThaiPostLabel');
	Route::post('pickup/get_coupon', 'Pickup\PickupController@getCoupon');
	
	
	//credit
	Route::get('credit','Credit\CreditBalanceController@index');
	Route::get('add_credit','Credit\CreditBalanceController@prepareCredit');
	Route::post('credit/create', 'Credit\CreditBalanceController@saveCredit');
	Route::get('add_credit_banktransfer', function () {  return view('add_credit_banktransfer'); });
	Route::get('add_credit_creditcard', function () {  return view('add_credit_creditcard'); });
	Route::post('credit/add_creditcard', 'Credit\CreditBalanceController@omiseAddCreditCard');
	Route::get('credit/credit_charge', 'Credit\CreditBalanceController@omiseChargeAction');
	Route::get('credit/test_omise','Credit\CreditBalanceController@testOmise'); //test
	Route::get('credit/getBalance/{customerId?}','Credit\CreditBalanceController@getBalance'); //test
	Route::get('credit/insertToCreditBalance/{ccID?}/{customerId?}','Credit\CreditBalanceController@insertToCreditBalance'); //test
	Route::post('credit/delete_creditcard', 'Credit\CreditBalanceController@deleteCreditCard');
	Route::get('payment_submission', 'Credit\CreditBalanceController@preparePaymentSubmission');

	//tools
	Route::get('track/{trackID?}', 'Tools\ToolsController@prepareTrack');
	Route::get('deminimis/{country?}', 'Tools\ToolsController@prepareDeMinimis');
	
	Route::get('promotion', 'Customer\CustomerController@preparePromotion');
	Route::get('channel_list', 'Customer\CustomerController@prepareChannelList');

	//error
	//return \Response::view('404',array(),500);
	Route::get('/error_page', function () {
		return view('404'); 
	});
	
});

//Test
Route::get('testSendRegisterEmail', 'TestController@testSendRegisterEmail');
Route::get('testSendResetPasswordEmail', 'TestController@testSendResetPasswordEmail');
Route::get('testSendNewOrderEmail', 'TestController@testSendNewOrderEmail');
Route::get('testSendTrackingEmail', 'TestController@testSendTrackingEmail');
Route::get('testSendPaymentEmail', 'TestController@testSendPaymentEmail');

Route::get('testEbay', 'Shipment\EbayController@importFromEbay');
Route::get('testEbay2', 'Shipment\EbayController@getUserToken');

//Clear Cache facade value:
Route::get('/clear-all', function() {
	Artisan::call('cache:clear');
	//Artisan::call('route:cache');
	Artisan::call('view:clear');
	//Artisan::call('config:cache');
	return '<h1>All Cache cleared</h1>';
});