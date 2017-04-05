<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\User;
use Facebook\Exceptions\FacebookResponseException;
use Facebook\Exceptions\FacebookSDKException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use League\Flysystem\Exception;
use SammyK\LaravelFacebookSdk\LaravelFacebookSdk;


class FacebookController extends Controller
{
    public function callback(LaravelFacebookSdk $fb){

        Session::put('FBRLH_state', $_GET['state']);

        try {
            $token = $fb->getAccessTokenFromRedirect();
        } catch (FacebookResponseException $e) {
            echo 'Graph returned an error: ' . $e->getMessage();
        } catch (FacebookSDKException $e) {
            echo 'Facebook SDK returned an error: ' . $e->getMessage();
        }

        // Access token will be null if the user denied the request
        // or if someone just hit this URL outside of the OAuth flow.
        if (!$token) {
            // Get the redirect helper
            $helper = $fb->getRedirectLoginHelper();

            if (!$helper->getError()) {
                abort(403, 'Unauthorized action.');
            }

            // User denied the request
            dd(
                $helper->getError(),
                $helper->getErrorCode(),
                $helper->getErrorReason(),
                $helper->getErrorDescription()
            );
        }

        if (!$token->isLongLived()) {
            // OAuth 2.0 client handler
            $oauth_client = $fb->getOAuth2Client();

            // Extend the access token.
            try {
                $token = $oauth_client->getLongLivedAccessToken($token);
            } catch (FacebookResponseException $e) {
                echo 'Graph returned an error: ' . $e->getMessage();
            } catch (FacebookSDKException $e) {
                echo 'Facebook SDK returned an error: ' . $e->getMessage();
            }
        }

        $fb->setDefaultAccessToken($token);

        Session::put('fb_user_access_token', (string)$token);

        $user = $this->getUserInfo($fb);

        if($user){

            Auth::login($user);

            $referrer = Session::get('from_url', '/');
            $referrer = '/library';

            return redirect($referrer)->with('message', 'Successfully logged in with Facebook');
        }else{

            return redirect('login')->withErrors(['email' => trans('login.mail_exist')]);
        }
    }

    public function getUserInfo(LaravelFacebookSdk $fb)
    {
        try {
            $response = $fb->get('/me?fields=id,first_name,last_name,email');
        } catch (FacebookResponseException $e) {
            echo 'Graph returned an error: ' . $e->getMessage();
        } catch (FacebookSDKException $e) {
            echo 'Facebook SDK returned an error: ' . $e->getMessage();
        }

        // Convert the response to a `Facebook/GraphNodes/GraphUser` collection
        $facebook_user = $response->getGraphUser();

        // Create the user if it does not exist or update the existing entry.
        // This will only work if you've added the SyncableGraphNodeTrait to your User model.

        try {

            $u = User::where('email', $facebook_user->getEmail())->first();

            if(count($u)){
                $u->facebook_user_id = $facebook_user->getId();
                $u->save();
            }

            $user = User::createOrUpdateGraphNode($facebook_user);

            $response = $fb->get('/me?fields=gender,age_range');
            if($response){
                $facebookUser = $response->getGraphUser();

                if($facebookUser){
                    if($facebookUser->getGender() == 'male') $user->gender = 'man';
                    if($facebookUser->getGender() == 'female') $user->gender = 'woman';

                    $response2 = $fb->get('/me/picture?width=200');

                    var_dump($response2);

                    if(isset($response2->getHeaders()['Location'])){
                        $image = $response2->getHeaders()['Location'];
                        $info = pathinfo(explode('?', $image)[0]);
                        $filePath = 'avatars/' . md5($image) . '.' . $info['extension'];
                        if(Storage::disk('public')->put($filePath, file_get_contents($image))){
                            $image = $filePath;
                        }
                        $user->image = $image;
                    }
                    $user->save();
                }
            }

            return $user;
        }catch(\Exception $e){
            return null;
        }
    }


    public function getFriends(LaravelFacebookSdk $fb)
    {
        try {
            $response = $fb->get('/me/friends');
        } catch (FacebookResponseException $e) {
            echo 'Graph returned an error: ' . $e->getMessage();
        } catch (FacebookSDKException $e) {
            echo 'Facebook SDK returned an error: ' . $e->getMessage();
        }

        // Convert the response to a `Facebook/GraphNodes/GraphUser` collection
        $facebook_user = $response->getGraphEdge();

        return $facebook_user;
    }

    /**
     * Share link in user's feed
     *
     * @param LaravelFacebookSdk $fb
     * @param string $link
     * @param string $message
     *
     * @return bool
     */
    public function postLink(LaravelFacebookSdk $fb, $link, $message = '')
    {
        $linkData = [
            'link'    => $link,
            'message' => $message,
        ];

        try {
            // Returns a `Facebook\FacebookResponse` object
            $response = $fb->post('/me/feed', $linkData);
        } catch (FacebookResponseException $e) {
            echo 'Graph returned an error: ' . $e->getMessage();
        } catch (FacebookSDKException $e) {
            echo 'Facebook SDK returned an error: ' . $e->getMessage();
        }

        $graphNode = $response->getGraphNode();

        $result = 'Posted with id: ' . $graphNode['id'];

        return true;
    }

    /**
     * Don't work
     * @param LaravelFacebookSdk $fb
     * @param string $userID
     * @param string $message
     * @param string $token
     *
     * @return bool
     */
    public function sendMessage(LaravelFacebookSdk $fb, string $userID, string $message, string $token = '') : bool
    {
        $params = [
            'recipient' => [
                'id' => $userID,
            ],
            'message'   => [
                'text' => $message,
            ]
        ];

//        try {
//            $r = (new \GuzzleHttp\Client())->request('POST', 'https://graph.facebook.com/v2.8/me/messages?access_token=' . $token, ['json' => json_encode($params)]);
//        } catch (\Exception $e) {
//            echo $e->getMessage(); exit();
//        }

        try {
            $response = $fb->post('/me/messages', $params);
        } catch (FacebookResponseException $e) {
            echo 'Graph returned an error: ' . $e->getMessage();
        } catch (FacebookSDKException $e) {
            echo 'Facebook SDK returned an error: ' . $e->getMessage();
        }

        return true;
    }
}
