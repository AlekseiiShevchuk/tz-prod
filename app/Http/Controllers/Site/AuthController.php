<?php
/**
 * Developer: Andrew Karpich and Aleksey Shevchuk shevchuka@gmail.com
 * Date: 02.02.2017 17:24
 */

namespace App\Http\Controllers\Site;


use App\Http\Controllers\Controller;
use App\Services\GeoIPService;
use App\User;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\MessageBag;
use Illuminate\Support\ViewErrorBag;

class AuthController extends Controller
{

    public function showForm()
    {
        $response = response()->view('site.login');

        if (empty(Cookie::get('is_old_visitor'))) {
            $response->withCookie(cookie('is_old_visitor', 'yes', 10000000));
        }

        if (empty(Cookie::get('country_id'))) {
            $response->withCookie(cookie('country_id', GeoIPService::getCountryIdForCurrentVisitor(), 10000000));
        }

        return $response;
    }

    public function loginOrRegistration(Request $request)
    {
        /**
         * @var Validator $validator
         */

        $this->validate($request, [
            'login' => 'required|max:255|email',
            'password' => 'required|min:6',
        ]);

        $request->merge(['email' => $request->input('login')]);

        return $this->loginOrRegistrationByEmail($request);
    }

    public function loginOrRegistrationByEmail(Request $request)
    {
        // If email and password - try login

        $loginController = app('App\Http\Controllers\Auth\LoginController');

        $loginResponse = $loginController->login($request);
        /**
         * @var \Illuminate\Session\Store $session
         */
        $session = $loginResponse->getSession();
        /**
         * @var ViewErrorBag $errorBag
         */
        $errorBag = $session->get('errors');
        if ($errorBag) {
            /**
             * @var MessageBag $errors
             */
            $errors = $errorBag->getBag('default');
            if ($errors) {

                $messages = $errors->getMessages();

                if (isset($messages['email'])) {

                    // Credentials do not match records

                    $registerController = app('App\Http\Controllers\Auth\RegisterController');

                    $registerResponse = $registerController->register($request);

                    return $registerResponse
                        ->withCookie(cookie('country_id', $request->get('country_id'), 10000000));
                }
            }
        }

        return $loginResponse
            ->withCookie(cookie('country_id', $request->get('country_id'), 10000000));
    }

    public function emailValidation(Request $request, $id, $code)
    {

        $user = User::where('id', (int)$id)->first();

        if (trim($code) != '' && $user && $user->email_token == $code) {

            $user->is_email_valid = 1;

            $user->email_token = '';

            $user->deleted_at = null;

            $user->save();

            Auth::guard()->login($user, false);

            return redirect('/library');
        }

        return redirect('/');
    }

}