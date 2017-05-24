<?php
/**
 * Developer: Andrew Karpich and Aleksey Shevchuk shevchuka@gmail.com
 * Date: 01.02.2017 14:16
 */

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\AudioCategory;
use App\Models\Country;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;

class IndexController extends Controller
{


    public function index()
    {

        response()->otherViewData = [
            //'blue_style' => true
        ];

        return view('site.index');
    }

    public function meditation()
    {
        return view('site.meditation');
    }

    public function faq()
    {
        return view('site.faq');
    }

    public function presse()
    {
        return view('site.presse');
    }

    public function info()
    {
        return view('site.info');
    }

    function library(Request $request)
    {

        response()->otherViewData = [
            'title' => 'Library',
        ];

        return view('site.library', [
            'categories' => AudioCategory::orderBy('order', 'asc')->get(),
            'active_category' => (int)$request->offsetGet('category'),
        ]);
    }

    function membre()
    {

        $user = Auth::user();

        if (!$user) {
            throw new \Exception('User not found');
        }

        return view('site.membre', [
            'item' => $user,
        ]);
    }

    function saveMembre(Request $request)
    {
        $data = $request->all();

        if ((int)$data['year'] && (int)$data['month'] != 0) {
            $date = new \DateTime();
            $date->setDate((int)$data['year'], (int)$data['month'], (int)$data['date']);
            $request->offsetSet('birthday', $date->format('Y-m-d'));
        }

        if (!isset($data['subscribe_news'])) {
            $request->offsetSet('subscribe_news', 0);
        }

        $userController = app('App\Http\Controllers\Ap\UsersController');

        $updateResponse = $userController->update($request, Auth::user()->id);

        return $request->offsetExists('redirect_to') ? redirect($request->offsetGet('redirect_to')) : $updateResponse;
    }

    function abonne()
    {
        return view('site.abonne');
    }

    function contacts()
    {
        return view('site.contact', [
            'item' => Auth::user(),
        ]);
    }

    function sendMail(Request $request)
    {

        $to = 'info@turbulencezero.com';

        if (Auth::check()) {
            $this->validate($request, [
                'email' => 'required|email',
                'message' => 'required',
            ], [], [
            ]);
        } else {
            $this->validate($request, [
                'email' => 'required|email',
                'g-recaptcha-response' => 'required|recaptcha',
                'message' => 'required',
            ], [], [
                'g-recaptcha-response' => 'captcha',
            ]);
        }

        $data = $request->all();

        if ($request->offsetExists('country_id')) {
            $data['country'] = Country::findOrFail((int)$request->offsetGet('country_id'));
        }

        try {
            Mail::send('site.emails.contacts', ['data' => $data], function ($message) use ($to) {
                $message->to($to);
//                $message->to('shevchuka@gmail.com');
                $message->subject('Contact depuis le formulaire du site');
            });

            Session::flash('flash_message', trans('contacts.sended'));

        } catch (\Exception $e) {

            Session::flash('flash_message', trans('contacts.not_sended'));
            Session::flash('flash_message_type', 'error');

        }

        return redirect()->back()->withInput();
    }

    function forum()
    {
        return view('site.develop');
    }

    function privee()
    {
        return view('site.privee');
    }

    function conditions()
    {
        return view('site.conditions');
    }

    function bio()
    {
        return view('site.bio');
    }

    function tanks()
    {
        return view('site.tanks');
    }
}