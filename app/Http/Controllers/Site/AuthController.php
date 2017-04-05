<?php
/**
 * Developer: Andrew Karpich
 * Date: 02.02.2017 17:24
 */

namespace App\Http\Controllers\Site;


use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\MessageBag;
use Illuminate\Support\ViewErrorBag;

class AuthController extends Controller
{

    public function showForm()
    {
        return view('site.login');
    }

    public function loginOrRegistration(Request $request)
    {
        /**
         * @var Validator $validator
         */

        $this->validate($request, [
            'login'    => 'required|max:255',
            'password' => 'required|min:6',
        ]);

        $field = 'nickname';

        if (filter_var($request->input('login'), FILTER_VALIDATE_EMAIL)) {
            $field = 'email';

            $loginMethod = 'loginOrRegistrationByEmail';
        } else {
            $loginMethod = 'loginByNickname';
        }

        $request->merge([$field => $request->input('login')]);

        return $this->$loginMethod($request);
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

                    return $registerResponse;
                }
            }
        }

        return $loginResponse;
    }

    public function loginByNickname(Request $request)
    {
        $loginResponse = Auth::attempt($request->only('nickname', 'password'), $request->has('remember'));

        if ($loginResponse) {
            return redirect('/library');
        }

        if (User::whereNickname($request->get('nickname'))->count() > 0) {
            $msg = trans('login.wrong_password');
        } else {
            $msg = trans('login.wrong_nickname');
        }

        return redirect('/login')->withErrors([
            'login' => $msg,
        ]);
    }

    public function emailValidation(Request $request, $id, $code){

        $user = User::where('id', (int) $id)->first();

        if(trim($code) != '' && $user && $user->email_token == $code ){

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