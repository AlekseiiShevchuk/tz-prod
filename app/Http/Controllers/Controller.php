<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\View;

class Controller extends BaseController {

    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function __construct(){

        try {
            $fbLoginUrl = app('SammyK\LaravelFacebookSdk\LaravelFacebookSdk')->getLoginUrl([ 'email', 'publish_actions', 'user_friends', 'pages_messaging' ]);
        }catch( \Exception $exception){
            $fbLoginUrl = null;
        }

        View::share('fb_login_url', $fbLoginUrl);
        View::share('google_login_url', route('googlecallback'));
        View::share('site_url', 'http://step11.turbulencezero.com');

    }


}