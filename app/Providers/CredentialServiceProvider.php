<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Model\Setting\SocialCreadential;

class CredentialServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $social_provider = SocialCreadential::where('status', '1')->get();

        $provider_array = [];

        foreach ($social_provider as $value) {

            $provider_array[$value->provider] = [
                'client_id' => $value->app_id,
                'client_secret' => $value->app_secret,
                'redirect' => url('/') . '/' . 'login/' . $value->provider . '/callback'
            ];

        }

        $this->app['config']['services'] = $provider_array;
    }
}
