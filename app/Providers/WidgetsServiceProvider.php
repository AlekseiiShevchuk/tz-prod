<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class WidgetsServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        \View::composer('widgets.InputSelectAudioCategories', '\App\Widgets\InputSelectAudioCategories');
        \View::composer('widgets.InputSelectAudioGroups', '\App\Widgets\InputSelectAudioGroups');
        \View::composer('widgets.InputSelectCountries', '\App\Widgets\InputSelectCountries');
    }
}
