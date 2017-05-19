<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use Error;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class ShareController extends Controller
{
    public function sendShareEmailAjax(Request $request)
    {
        $email = $request->get('email');
        try {

            Mail::send('site.emails.share', [
                'name' => Auth::user()->name,
            ], function ($message) use ($email) {
                $message->to($email);
                $message->subject('Vous avez reçu une invitation pour Turbulence Zéro!');
            });
        } catch (Error $e) {
            return response()->json($e->getMessage(), 403);
        }
        return response()->json('Success');
    }
}
