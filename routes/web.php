<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

Route::group([ 'middleware' => 'trailing_slashes' ], function(){

    Auth::routes();

    Route::any('logout', 'Auth\LoginController@logout')->name('logout');

    //Route::get('/', [ 'as' => 'home', function () {
    //    return view('site.index');
    //}]);

    Route::group([ 'middleware' => [ 'locale', 'ajax' ], 'namespace' => 'Site' ], function(){

        Route::get('login', 'AuthController@showForm');
        Route::post('login', 'AuthController@loginOrRegistration');

        Route::get('email/verif/{id}/{code}', 'AuthController@emailValidation');

        Route::get('/', 'IndexController@info');
        Route::get('meditation', 'IndexController@meditation');
//        Route::get('info', 'IndexController@info');

        Route::get('contacts', 'IndexController@contacts');
        Route::get('faq', 'IndexController@faq');
        Route::get('presse', 'IndexController@presse');
        Route::post('contacts/send', 'IndexController@sendMail');
        Route::get('forum', 'IndexController@forum');
        //    Route::get('privee', 'IndexController@privee');
        //    Route::get('conditions', 'IndexController@conditions');
        //    Route::get('bio', 'IndexController@bio');

        Route::group([ 'middleware' => [ 'client' ] ], function(){

            Route::get('library', 'IndexController@library');
            Route::get('membre', 'IndexController@membre');
            Route::post('membre', 'IndexController@saveMembre');

            Route::get('abonne', 'SubscriptionController@index');
            Route::get('abonne/details/{id}', 'SubscriptionController@details');
            Route::get('abonne/invite/details/{id}', 'SubscriptionController@inviteDetails');
            Route::get('abonne/invite', 'SubscriptionController@invite');
            Route::post('abonne/invite/save', 'SubscriptionController@saveInviter');
            Route::get('abonne/payment', 'SubscriptionController@payment');
            Route::get('abonne/renewal', 'SubscriptionController@renewal');
            Route::get('abonne/renewal/delete', 'SubscriptionController@deleteAuto');
            Route::get('abonne/renewal/activate', 'SubscriptionController@activateAuto');
            Route::get('abonne/subscription', 'SubscriptionController@subscription');
            Route::post('abonne/save/{id}', 'SubscriptionController@save');
            Route::any('abonne/processAuthorization', 'SubscriptionController@processAuthorization');
        });
    });

    Route::group([ 'prefix' => 'ap', 'middleware' => 'admin', 'namespace' => 'Ap' ], function(){
        Route::get('/', 'HomeController@index');

        Route::put('/users/activate/{id}', 'UsersController@activate');
        Route::get('/users/findByEmail', 'UsersController@findByEmail');
        Route::match([ 'get', 'post' ], '/users/sendEmail', 'UsersController@sendEmail');

        Route::resource('/users', 'UsersController');

        Route::put('/categories/activate/{id}', 'AudioCategoriesController@activate');
        Route::resource('/categories', 'AudioCategoriesController');
        Route::post("/categories/updateOrder", "AudioCategoriesController@updateOrder");

        Route::get("category/{category}/groups", "AudioCategoriesController@groups");
        Route::get("group/{group}/sounds", "AudioGroupsController@sounds");

        Route::get("category/{category}/groups", "AudioCategoriesController@groups");
        Route::get("group/{group}/sounds", "AudioGroupsController@sounds");

        Route::post("category/{category}/updateOrderGroup", "AudioGroupsController@updateOrder");
        Route::post("group/{group}/updateOrderSounds", "SoundController@updateOrder");

        Route::put('/groups/activate/{id}', 'AudioGroupsController@activate');
        Route::resource('/groups', 'AudioGroupsController');

        Route::put('/sounds/activate/{id}', 'SoundController@activate');
        Route::resource('/sounds', 'SoundController');

        Route::get('/report/generate', 'PartnerReportingController@generate');
        Route::resource('/report', 'PartnerReportingController');

    });

    //TODO: etot govnokod nujno fixit. zakazchik vbivaaet v verhnem registre prfix
    Route::group([ 'prefix' => 'AP', 'middleware' => 'admin', 'namespace' => 'Ap' ], function(){
        Route::get('/', 'HomeController@index');

        Route::put('/users/activate/{id}', 'UsersController@activate');
        Route::get('/users/findByEmail', 'UsersController@findByEmail');
        Route::match([ 'get', 'post' ], '/users/sendEmail', 'UsersController@sendEmail');

        Route::resource('/users', 'UsersController');

        Route::put('/categories/activate/{id}', 'AudioCategoriesController@activate');
        Route::resource('/categories', 'AudioCategoriesController');
        Route::post("/categories/updateOrder", "AudioCategoriesController@updateOrder");

        Route::get("category/{category}/groups", "AudioCategoriesController@groups");
        Route::get("group/{group}/sounds", "AudioGroupsController@sounds");

        Route::get("category/{category}/groups", "AudioCategoriesController@groups");
        Route::get("group/{group}/sounds", "AudioGroupsController@sounds");

        Route::post("category/{category}/updateOrderGroup", "AudioGroupsController@updateOrder");
        Route::post("group/{group}/updateOrderSounds", "SoundController@updateOrder");

        Route::put('/groups/activate/{id}', 'AudioGroupsController@activate');
        Route::resource('/groups', 'AudioGroupsController');

        Route::put('/sounds/activate/{id}', 'SoundController@activate');
        Route::resource('/sounds', 'SoundController');

        Route::get('/report/generate', 'PartnerReportingController@generate');
        Route::resource('/report', 'PartnerReportingController');

    });

    Route::get('lang/{locale}', function($locale){

        Session::put('locale', $locale);

        return redirect('/');
    });

    //Route::get('/facebook/login', function(){
    //
    //        $fb = app('SammyK\LaravelFacebookSdk\LaravelFacebookSdk');
    //        // Send an array of permissions to request
    //        $login_url = $fb->getLoginUrl([ 'email', 'publish_actions', 'user_friends', 'pages_messaging' ]);
    //        // Obviously you'd do this in blade :)
    //        echo '<a href="' . $login_url . '">Login with Facebook</a>';
    //});

    Route::get('/facebook/callback', 'Auth\FacebookController@callback');
    Route::get('/google/callback', 'Auth\GoogleController@callback')->name('googlecallback');
});