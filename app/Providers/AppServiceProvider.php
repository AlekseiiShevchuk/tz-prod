<?php

namespace App\Providers;

use App\Observers\UserObserver;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        User::observe(UserObserver::class);
        $this->setAffiliateCookie();

        Carbon::setLocale(Config::get('app.locale'));
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    private function setAffiliateCookie()
    {
        $aid = Input::get('aid');

        if (!is_null($aid)) {
            setcookie('aid', $aid, strtotime(Config::get('app.aid_expire')), '/', Config::get('app.domain'));
        }
    }
}
