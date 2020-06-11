<?php
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;

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
Route::get('/login', function () { return view('login'); });
Route::post('customer/login', 'Customer\CustomerController@login');
Route::post('customer/register', 'Customer\CustomerController@register');
Route::match(['get', 'post'],'joinus/{refercode?}',  'Customer\CustomerController@prepareLoginWithCode');
Route::get('register/{refercode?}', 'Customer\CustomerController@prepareLoginWithCode');
Route::get('register_email/{refercode?}',  'Customer\CustomerController@prepareRegisterWithCode');
Route::get('register_line/{refercode?}',  'Customer\CustomerController@prepareRegisterLine');
Route::get('register_complete', function () {  return view('register_complete'); });
Route::get('a/{refercode}',   'Customer\CustomerController@prepareLoginWithCode');

//customer & password
Route::get('forget_password', function () {  return view('forget_password'); });
Route::post('customer/forget_password', 'Customer\CustomerController@resetPassword');
Route::get('regenpass/{id?}','Customer\CustomerController@regenPass');

//special login
Route::get('fastship_facebook',  'Customer\CustomerController@prepareRegisterFacebook');
Route::get('fastship_payoneer',  'Customer\CustomerController@prepareRegisterPayoneer');

//callback send email
Route::get('email_tracking/{token1?}/{token2?}', 'Shipment\ShipmentController@sendTrackingEmail');
Route::get('email_paymentnotify/{token1?}/{token2?}', 'Credit\CreditBalanceController@sendPaymentNotifyEmail');

//standalone track
Route::get('track_st/{trackID?}', 'Tools\ToolsController@prepareStandaloneTrack');
Route::get('track.fs/{trackID?}', 'Tools\ToolsController@prepareStandaloneTrack');

//translate
Route::get('locale/{locale?}', 'LanguageController@setLocale');

//LIFF LINE API
Route::match(['get', 'post'],'liff/calculate', 'Liff\LiffController@calculate');
Route::post('liff/ajax/get_rate', 'Liff\LiffController@ajaxGetRate');
Route::post('liff/ajax/check_email', 'Liff\LiffController@ajaxCheckEmailExisted');
Route::post('liff/ajax/get_postal', 'Liff\LiffController@ajaxGetPostals');
// Route::get('liff/create_shipment', function () {  return view('liff/redirect_create_shipment'); });
// Route::post('liff/create_shipment', 'Liff\LiffController@createShipment');
// Route::post('liff/create_shipment_step2', 'Liff\LiffController@createShipmentStep2');
// Route::post('liff/create_shipment_step3', 'Liff\LiffController@createShipmentStep3');
// Route::post('liff/create_shipment_step4', 'Liff\LiffController@createShipmentStep4');
// Route::post('liff/create_shipment_step5', 'Liff\LiffController@createShipmentStep5');
// Route::post('liff/action/create_shipment', 'Liff\LiffController@doCreateShipment');
// Route::get('liff/create_shipment_completed', function () {  return view('liff/redirect_create_shipment_completed'); });
Route::get('liff/connect', function () {  return view('liff/redirect_connect'); });
Route::post('liff/connect', 'Liff\LiffController@connectLine');
Route::get('liff/connect_success', function () {  return view('liff/redirect_connect_completed'); });
Route::get('liff/signup', 'Liff\LiffController@signup');
Route::get('liff/login', 'Liff\LiffController@login');
Route::post('liff/action/signup', 'Liff\LiffController@doSignup');
Route::post('liff/action/login', 'Liff\LiffController@doLogin');
Route::post('liff/states_query', 'Liff\LiffController@ajaxStates');
Route::post('liff/get_hscodes', 'Liff\LiffController@ajaxHsCodes');
Route::post('liff/webhook', 'Liff\WebhookController@webhook');
Route::get('liff/push', 'Liff\WebhookController@pushMessage');
// Route::get('liff/create_pickup', function () {  return view('liff/redirect_create_pickup'); });
// Route::post('liff/create_pickup', 'Liff\LiffController@createPickup');
// Route::post('liff/create_pickup_step2', 'Liff\LiffController@createPickupStep2');
// Route::post('liff/create_pickup_step3', 'Liff\LiffController@createPickupStep3');
// Route::post('liff/create_pickup_step4', 'Liff\LiffController@createPickupStep4');
// Route::post('liff/action/create_pickup', 'Liff\LiffController@doCreatePickup');
// Route::get('liff/create_pickup_completed', function () {  return view('liff/redirect_create_pickup_completed'); });
Route::get('liff/tracking', function () {  return view('liff/redirect_tracking'); });
Route::post('liff/tracking', 'Liff\LiffController@tracking');
Route::match(['get', 'post'],'liff/tracking_result', 'Liff\LiffController@trackingResult');
//Route::get('liff/topup', function () {  return view('liff/redirect_topup'); });
//Route::post('liff/topup', 'Liff\LiffController@topup');
//Route::post('liff/topup_qr', 'Liff\LiffController@topupQr');
//Route::post('liff/topup_creditcard', 'Liff\LiffController@topupCreditCard');
//Route::get('liff/add_creditcard', function () {  return view('liff/add_creditcard'); });
//Route::post('liff/action/add_creditcard', 'Liff\LiffController@doAddCreditcard');
Route::get('liff/loginline', 'Customer\CustomerController@loginLine');
Route::post('liff/action/login2', 'Liff\LiffController@doLogin2');
Route::get('liff/create_case', function () {  return view('liff/redirect_create_case'); });
Route::post('liff/create_case', 'Liff\LiffController@createCase');
Route::post('liff/action/create_case', 'Liff\LiffController@doCreateCase');
Route::get('liff/create_case_completed', function () {  return view('liff/redirect_create_case_completed'); });

//under construction
Route::get('upgrading', function () {  return view('underconstruction'); });

//check login session
Route::group(['middleware' => 'loginsession'], function () {
	
    //Route::get('/', function () {  return view('index'); });
    //Route::get('/testHome', function () {  return view('index1'); });
    
    Route::get('/', 'HomeController@index');

	//customer
	Route::get('customer/logout', 'Customer\CustomerController@logout');
	Route::get('myaccount', 'Customer\CustomerController@prepareMyAccount');
	Route::get('account_overview', 'Customer\CustomerController@prepareAccountOverview');
	Route::get('edit_customer', 'Customer\CustomerController@prepareEditCustomer');
	Route::post('customer/edit', 'Customer\CustomerController@update');
	Route::get('change_password', function () {  return view('change_password'); });
	Route::post('customer/change_password', 'Customer\CustomerController@changePassword');
	Route::post('customer/remove-channel', 'Customer\CustomerController@removeChannel');
	Route::get('liff/connectline', 'Customer\CustomerController@connectLine');
	//Add new creditcard
	Route::match(['get', 'post'], 'new_creditcard/{pickupId?}','Customer\CustomerController@prepareAddNewCreditCard');

	//shipment
	Route::get('calculate_shipment_rate', 'Shipment\ShipmentController@prepareCalculateShipmentRate');
	Route::match(['get', 'post'], 'create_shipment', 'Shipment\ShipmentController@prepareCreateShipment');
	Route::post('shipment/get_rate', 'Shipment\ShipmentController@getRate');
	Route::post('shipment/create', 'Shipment\ShipmentController@createShipment');
	Route::post('shipment/cancel', 'Shipment\ShipmentController@cancelShipment');
	Route::match(['get', 'post'], 'create_shipment_data','Shipment\ShipmentController@createShipmentData');
	Route::get('shipment_detail/{id?}', 'Shipment\ShipmentController@prepareShipmentDetail');
	//Route::get('shipment_channel', 'Shipment\ShipmentController@prepareShipmentChannel');
	Route::match(['get', 'post'],'shipment_list/{page?}', 'Shipment\ShipmentController@prepareShipmentList');
	Route::get('tracking/{trackID?}', 'Shipment\ShipmentController@tracking');
	Route::get('import_shipment', function(){ return view('import_shipment_upload'); });
	Route::post('shipment/upload', 'Shipment\ShipmentController@prepareShipmentImport');
	Route::post('shipment/import', 'Shipment\ShipmentController@importShipment');
	Route::post('shipment/cancel_ebay', 'Shipment\ShipmentController@ebayCancelOrder');
	Route::post('shipment/get_fba_address', 'Shipment\ShipmentController@getFbaAddress');
	Route::post('shipment/get_fba_addresses', 'Shipment\ShipmentController@getFbaAddresses');
	Route::get('import_sook', 'Shipment\ShipmentController@prepareShipmentThaitradeImport');
	Route::post('shipment/cancel_sook', 'Shipment\ShipmentController@sookCancelOrder');
	Route::post('shipment/validate_address', 'Shipment\ShipmentController@validateAddress');
	
	//Shipment Advance Actions
	Route::post('shipment/export', 'Shipment\ShipmentController@exportShipment');
	Route::get('shipment/clone', 'Shipment\ShipmentController@cloneShipment');

	//eBay Feed APIs
	Route::post('shipment/import_ebay', 'Shipment\EbayController@createShipmentEbay');
	Route::match(['GET', 'POST'], 'shipment/ebay/create_token', 'Shipment\EbayController@eBayCreateToken');
	Route::match(['GET', 'POST'], 'shipment/create_ebay/{command?}/{filter_type?}', 'Shipment\EbayController@prepareCreateShipmentEbay');
	Route::match(['GET', 'POST'], 'shipment/ebay/{id?}', 'Shipment\EbayController@prepareCreateShipmentEbayDetail');
	Route::post('shipment/ebay-delete', 'Shipment\EbayController@deleteeBayOrder');
	Route::match(['get', 'post'], 'shipment/ebay_accept', 'Shipment\EbayController@acceptToken');
	Route::post('shipment/add_ebay_channel', 'Shipment\EbayController@addChannel');
	Route::match(['get', 'post'], 'shipment/ebay-inprogress', 'Shipment\EbayController@updateInProgress');
	Route::match(['get', 'post'], 'shipment/ebay-uptracking', 'Shipment\EbayController@updateTrackingEbay');
	Route::match(['GET', 'POST'], 'shipment/ebay-test/{id?}', 'Shipment\EbayController@testprepareCreateShipmentEbayDetail');

	//eBay Feed APIs Environment Developer Options
	Route::match(['GET', 'POST'], 'dev/shipment/create_ebay/{command?}/{filter_type?}', 'Shipment\EbayController@devprepareCreateShipmentEbay');
	Route::match(['GET', 'POST'], 'dev/shipment/ebay/{id?}', 'Shipment\EbayController@devprepareCreateShipmentEbayDetail');
	Route::match(['get', 'post'], 'dev/shipment/ebay-uptracking', 'Shipment\EbayController@devupdateTrackingEbay');

	//FBA
	Route::get('create_fba/{country}', 'Shipment\ShipmentController@prepareCreateShipmentFBA');
	Route::post('shipment/create_fba', 'Shipment\ShipmentController@createShipmentFBA');

	//quatation
	Route::get('quotations', 'Shipment\ShipmentController@prepareQuotations');
	
	//pickup
	Route::get('create_pickup', 'Pickup\PickupController@prepareCreatePickup');
	Route::post('pickup/create', 'Pickup\PickupController@createPickup');
	Route::match(['get', 'post'],'pickup_detail/{id?}', 'Pickup\PickupController@preparePickupDetail');
	Route::get('pickup_detail_print/{id?}', 'Pickup\PickupController@preparePickupDetailPrint');
	Route::match(['get', 'post'],'pickup_list/{page?}', 'Pickup\PickupController@preparePickupList');
	Route::post('pickup/cancel', 'Pickup\PickupController@cancelPickup');
	Route::get('pickup_invoice_print/{id?}', 'Pickup\PickupController@preparePickupInvoicePrint');
	Route::get('thaipost/label/{id?}', 'Pickup\PickupController@getThaiPostLabel');
	Route::post('pickup/get_coupon', 'Pickup\PickupController@getCoupon');
	Route::post('pickup/get_time', 'Pickup\PickupController@getPickupTime');
	Route::post('pickup/get_date', 'Pickup\PickupController@getPickupDate');
	Route::post('pickup/get_remark', 'Pickup\PickupController@getPickupRemark');

	//Feature Prepaid System
	Route::get('pickup_detail_payment/{id?}', 'Pickup\PickupController@preparePickupDetailPayment');
	
	//credit card
	Route::post('credit/add_creditcard', 'Credit\CreditBalanceController@omiseAddCreditCard');
	Route::post('credit/delete_creditcard', 'Credit\CreditBalanceController@deleteCreditCard');
	Route::get('credit/omise_auto_charge/{pickupId}/{card}', 'Credit\CreditBalanceController@omiseAutoChargeAction');
	//Route::post('credit/add_new_creditcard', 'Credit\CreditBalanceController@omiseAddNewCreditCard');
	
	//store credit
	Route::get('customer_balance', 'Credit\CreditBalanceController@index');
	Route::post('credit/withdraw', 'Credit\CreditBalanceController@withdraw');
	Route::post('credit/update_refund', 'Credit\CreditBalanceController@updateRefund');
	
	//payment submission
	//Route::get('payment_submission', 'Credit\CreditBalanceController@preparePaymentSubmission');
	//Route::get('payment_submission/{amount}', 'Credit\CreditBalanceController@preparePaymentSubmission');

	//tools
	Route::get('track/{trackID?}', 'Tools\ToolsController@prepareStandaloneTrack');
	Route::get('deminimis/{country?}', 'Tools\ToolsController@prepareDeMinimis');
	Route::get('tariff_rates/{q?}', 'Shipment\TaxDutyController@prepareTariffRates');
	Route::post('tariff/get_hscodes', 'Shipment\TaxDutyController@hscodes');
	Route::match(['get', 'post'], 'tariff/get_cost', 'Shipment\TaxDutyController@getLandedCost');
	
	//marketplace channel
	Route::get('channel_list', 'Customer\CustomerController@prepareChannelList');
	//Route::get('channel_list2', 'Customer\CustomerController@prepareChannelList2');
	Route::get('add_channel', 'Customer\CustomerController@prepareAddChannel');
	Route::get('add_channel_ebay/{site}', 'Customer\CustomerController@prepareAddChannelEbay');

	//case
	Route::get('case_list', 'Customer\CustomerController@prepareCaseList');
	Route::get('case/{id?}', 'Customer\CustomerController@prepareCaseDetail');
	//Route::get('add_case', 'Customer\CustomerController@prepareAddCase');
	Route::get('add_case/{id?}', 'Customer\CustomerController@prepareAddCase');
	Route::post('case/create', 'Customer\CustomerController@createCase');
	Route::post('case/createreply', 'Customer\CustomerController@createCaseReply');
	Route::post('case/get_ref', 'Customer\CustomerController@getCaseReferences');
	
});

//etsy Feed APIs
Route::post('shipment/add_etsy_channel', 'Shipment\EtsyController@addChannel');
Route::match(['GET', 'POST'], 'shipment/create_etsy/{command?}', 'Shipment\EtsyController@prepareCreateShipmentEtsy');
Route::match(['GET', 'POST'], 'shipment/create_etsytest', function(){ return view('etsy_create_completed_test'); });
Route::match(['GET', 'POST'], 'shipment/etsy/{id?}', 'Shipment\EtsyController@prepareCreateShipmentEtsyDetail');
Route::post('shipment/etsy-delete', 'Shipment\EtsyController@deleteEtsyOrder');

//Test
Route::get('testSendRegisterEmail', 'TestController@testSendRegisterEmail');
Route::get('testSendResetPasswordEmail', 'TestController@testSendResetPasswordEmail');
Route::get('testSendNewOrderEmail', 'TestController@testSendNewOrderEmail');
Route::get('testSendTrackingEmail', 'TestController@testSendTrackingEmail');
Route::get('testSendPaymentEmail', 'TestController@testSendPaymentEmail');

Route::get('testEmail', 'TestController@testEmail');
Route::get('testTracker', 'TestController@testTrafficTracker');

Route::get('testEbay', 'Shipment\EbayController@importFromEbay');
Route::get('testZoho', 'TestController@testZoho');
Route::get('testThaitrade', 'TestController@testThaitrade');
Route::get('testKbank', 'TestController@testKbankQRPayment');
Route::get('testAny', 'TestController@testAny');
Route::get('testThaipostBarcode', 'TestController@testThaipostBarcode');

//address
//Route::get('test_address', 'Shipment\AddressController@index');
Route::post('address/states','Shipment\AddressController@getStates');
Route::post('address/cities','Shipment\AddressController@getCities');
Route::post('address/postcodes','Shipment\AddressController@getPostcodes');

Route::post('shipment/declarations','Shipment\ShipmentController@getDeclarations');

//kbank payment
Route::post('kbank/payment_completed','Payment\PaymentController@paymentCompleted');
Route::post('kbank/payment_status/card','Payment\PaymentController@paymentStatusCard');
Route::get('kbank/qr/{code1}/{code2}','Payment\PaymentController@prepareQr');
Route::get('kbank/inquiry/{charge_id?}','Payment\PaymentController@inquiryQR');
Route::get('kbank/void/{charge_id?}','Payment\PaymentController@void');
Route::get('kbank/cancel/{qr_id?}','Payment\PaymentController@cancel');

//Clear Cache facade value:
Route::get('/clear-all', function() {
	Artisan::call('cache:clear');
	Artisan::call('route:cache');
	Artisan::call('view:clear');
	Artisan::call('config:cache');
	return '<h1>All Cache cleared</h1>';
});