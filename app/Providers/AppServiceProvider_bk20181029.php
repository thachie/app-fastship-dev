<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use DB;
use Session;

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
    	$countryQuery = DB::table('country')->orderBy("CNTRY_NAME")->get();
    	foreach ($countryQuery as $val) {
            $countries[$val->CNTRY_CODE] = $val->CNTRY_NAME;
        }
    	view()->share('countries', $countries);
    	
    	//share payment method data
    	$paymentMethod = array(
    		"Bank_Transfer" => "โอนผ่านธนาคาร",
    		"Credit_Card" => "จ่ายผ่านบัตรเครดิต",
    	);
    	view()->share('paymentMethod', $paymentMethod);
    	
    	//share pickup type data
    	$pickupType = array(
    		"Drop_AtFastship" => "ส่งที่ Fastship",
    		"Drop_AtSkybox" => "ส่งผ่าน  Skybox",
    		"Drop_AtEEDU" => "ส่งที่สถาบัน eEDU",
    		"Drop_AtBox24" => "ส่งผ่าน Box24",
    		"Drop_AtChiangmai" => "ส่งที่สาขาเชียงใหม่",
    		"Pickup_AtHome" => "ให้ไปรับที่บ้าน",
    	    "Pickup_AtKerry" => "ให้ไปรับที่บ้านโดย Kerry",
    	    "Drop_AtThaiPost" => "ส่งที่ไปรษณีย์ไทย",
    	);
    	view()->share('pickupType', $pickupType);
    	    	 
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //$this->app['url']->forceScheme("https");
        $this->app['url']->forceScheme("http");
    }
}
