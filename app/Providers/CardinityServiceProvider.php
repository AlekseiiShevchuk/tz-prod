<?php
/**
 * Developer: Andrew Karpich
 * Date: 01.03.2017 18:09
 */

namespace App\Providers;


use App\Payments\Cardinity;
use Illuminate\Support\ServiceProvider;

class CardinityServiceProvider extends ServiceProvider{

    public function register()
    {
        // Main Service
        $this->app->bind('App\Payments\Cardinity', function ($app) {
            return new Cardinity();
        });
    }

}