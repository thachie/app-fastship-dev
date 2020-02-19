<?php

namespace App\Providers;

use App\Lib\Fastship\FS_Address;
use App\Lib\Fastship\Fastship;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\DB;
use App\Lib\TrafficTracker\TrafficTracker;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {

        //share country data
        //Fastship::getToken();
        //$countries = FS_Address::get_countries();
    	$countryQuery = DB::table('country')->orderBy("CNTRY_NAME")->get();
    	foreach ($countryQuery as $val) {
            $countries[$val->CNTRY_CODE] = $val->CNTRY_NAME;
        }
        //$countries = 'THA';
    	view()->share('countries', $countries);
    	
    	//share payment method data
    	$paymentMethod = array(
    		"Bank_Transfer" => "โอนผ่านธนาคาร",
    		"Credit_Card" => "จ่ายผ่านบัตรเครดิต",
    	    "Invoice" => "วางบิล",
    	    "Cash" => "เงินสด",
    	);
    	view()->share('paymentMethod', $paymentMethod);

    	//share pickup type data
    	$pickupType = array(
    		"Drop_AtFastship" => "ส่งที่ Fastship",
    		"Drop_AtSkybox" => "ส่งผ่าน  Skybox",
    		"Drop_AtThaiPost" => "ส่งที่ไปรษณีย์ไทย",
    		"Drop_AtEEDU" => "ส่งที่สถาบัน eEDU",
    		"Drop_AtBox24" => "ส่งผ่าน Box24",
    		"Drop_AtChiangmai" => "ส่งที่สาขาเชียงใหม่",
    		
    		"Pickup_AtHome" => "ให้ไปรับที่บ้าน",
    		"Pickup_BySkootar" => "รับโดย Skootar",
    		"Pickup_ByLalamove" => "รับโดย Lalamove",
    		"Pickup_ByFlash" => "รับโดย Flash",
    		"Pickup_BySpeedy" => "รับโดย Speedy",
    		"Pickup_ByKerry" => "รับโดย Kerry",
    		"Pickup_ByOther" => "รับโดยวิธีอื่นๆ",
    	    "Pickup_ByAirPortels" => "รับโดย Air Portels",
    	    "Pickup_ByPolite" => "รับโดย Polite",
    	);
    	view()->share('pickupType', $pickupType);
    	
    	view()->composer('*', function($view){
    	    $view_name = str_replace('.', '-', $view->getName());
    	    view()->share('view_name', $view_name);
    	});
    	    	
    	$trafficTracker = new TrafficTracker(
	        'localhost',
	        'root',
	        '59lAdf91XTaU',
	        'fastship_app',
	        'ttcpc',
    	    60
    	);

    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
    	//$this->app['url']->forceScheme("https");
    	
    }
}
