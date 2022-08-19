<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use App\Http\Resources\Product\CategoryResource;
use Validator, View;
use App\Model\Currency;
use App\Model\Setting\ShopSetting;
use App\Model\Setting\SeoSetting;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {

    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);
        // validator extending

        Validator::extend('image64', function ($attribute, $value, $parameters, $validator) {
            $type = explode('/', explode(':', substr($value, 0, strpos($value, ';')))[1])[1];
            if (in_array($type, $parameters)) {
                return true;
            }
            return false;
        });

        Validator::replacer('image64', function ($message, $attribute, $rule, $parameters) {
            return str_replace(':values', join(",", $parameters), $message);
        });

        // sharing  data in all view

        // $current_currency = Currency::where('currency_status',1)->first();
        $shop_info = ShopSetting::orderBy('id', 'desc')->first();
        $seo_info = SeoSetting::orderBy('id', 'desc')->first();

        return View::share([
            // 'current_currency' => $current_currency,
            'shop_info' => $shop_info,
            'seo_info' => $seo_info]);

    }
}
