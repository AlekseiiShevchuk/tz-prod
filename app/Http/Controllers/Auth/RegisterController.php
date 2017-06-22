<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\InvitedUsers;
use App\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;
use Validator;

class RegisterController extends Controller
{

    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    protected $redirectTo = '/library';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');

        //        $this->redirectTo = Session::get('from_url', '/');
    }

    public function register(Request $request)
    {
        $validator = $this->validator($request->all());

        if ($validator->fails()) {
            return redirect('login')->withErrors([
                'password' => Lang::get('auth.password'),
            ]);
        }

        event(new Registered($user = $this->create($request->all())));

        $this->guard()->login($user, $request->has('remember'));

        if ($user) {
            $invitedUser = InvitedUsers::where('email', $user->email)->first();
            if ($invitedUser) {
                $payment = $invitedUser->payment();
                $payment->user_id = $user->id;
                $payment->save();

                $invitedUser->delete();

                $user->start_subscribe_date = $payment->start_access_date;

            }

            $user->email_token = str_random(40);
            $user->save();

            \Mail::send('site.emails.reg',
                ['url' => \URL::to('email/verif/' . $user->id . '/' . $user->email_token), 'email' => $user->email],
                function ($message) use ($user) {
                    $message->to($user->email);
                    $message->subject(trans('login.success_reg_subject'));
                });
        }

        return $this->registered($request, $user) ?: redirect($this->redirectPath());
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array $data
     *
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:6',
            'country_id' => 'required|exists:countries,id',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array $data
     *
     * @return User
     */
    protected function create(array $data)
    {
        return User::create([
            'country_id' => $data['country_id'],
            'email' => $data['email'],
            'password' => $data['password'],
            'partner_aid' => $_COOKIE['aid'] ?? 0,
        ]);
    }
}
