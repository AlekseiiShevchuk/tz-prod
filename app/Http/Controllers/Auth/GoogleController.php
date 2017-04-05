<?php
/**
 * Developer: Andrew Karpich
 * Date: 10.02.2017 15:24
 */

namespace App\Http\Controllers\Auth;


use App\Http\Controllers\Controller;
use App\User;
use Google_Client;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;

class GoogleController extends Controller {

    use RegistersUsers;

    function callback(Request $request){
        $google_redirect_url = route('googlecallback');

        $gClient = new \Google_Client();
        $gClient->setApplicationName('Turbulence Zero');
        $gClient->setAuthConfig(json_decode(file_get_contents(base_path('google_api_conf.json')), true));

        $gClient->setScopes(array(
            'https://www.googleapis.com/auth/plus.me',
            'https://www.googleapis.com/auth/userinfo.email',
            'https://www.googleapis.com/auth/userinfo.profile',
        ));

        $gClient->setRedirectUri($google_redirect_url);

        $google_oauthV2 = new \Google_Service_Oauth2($gClient);

        if($request->get('code')){
            $gClient->authenticate($request->get('code'));
            $request->session()->put('token', $gClient->getAccessToken());
        }

        if($request->session()->get('token')){
            $gClient->setAccessToken($request->session()->get('token'));
        }

        if($gClient->getAccessToken()){

            $gUser = $google_oauthV2->userinfo->get();

            $surname = $gUser['familyName'];
            $name = $gUser['givenName'];
            $googleId = $gUser['id'];
            $image = $gUser['picture'];
            $email = $gUser['email'];
            $verifiedEmail = $gUser['verifiedEmail'];

            if($verifiedEmail){

                if($image){
                    $info = pathinfo($image);
                    $filePath = 'avatars/' . md5($image) . '.' . $info['extension'];
                    if(Storage::disk('public')->put($filePath, file_get_contents($image))){
                        $image = $filePath;
                    }
                }

                $user = User::where('email', $email)->first();

                if(!$user) {
                    $user = User::create([
                        'name'     => $name,
                        'surname'  => $surname,
                        'email'    => $email,
                        'aid'      => $_COOKIE['aid'] ?? 0,
                        'image'    => $image,
                    ]);
                }

                Auth::login($user);

//                $referrer = Session::get('from_url', '/');
                $referrer = '/library';

                return redirect($referrer)->with('message', 'Successfully logged in with Google');
            }

        } else {

            $authUrl = $gClient->createAuthUrl();

            return redirect()->to($authUrl);
        }
    }

}