<?php
/**
 * Developer: Andrew Karpich
 * Date: 01.03.2017 17:14
 */

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\InvitedUsers;
use App\Models\Payment as PaymentDB;
use App\Payments\Card;
use App\Payments\Cardinity;
use App\Payments\Plan;
use App\User;
use Carbon\Carbon;
use Cardinity\Method\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Inacho\CreditCard;
use Session;

class SubscriptionController extends Controller {


    public function index(Cardinity $cardinity, $invite = false){

        $detailsUrl = $invite ? '/abonne/invite/details/' : '/abonne/details/';

        return view('site.subscription.abonne', [
            'plans'       => $cardinity->getAllPlans(),
            'details_url' => $detailsUrl,
        ]);
    }

    public function inviteDetails(Cardinity $cardinity, $id){

        return $this->details($cardinity, $id, true);
    }

    public function details(Cardinity $cardinity, $id, $invite = false)
    {
        $user = Auth::user();

        if ($user->is_email_valid == 0) {
            Session::flash('flash_message', 'Confirm your email');
            Session::flash('flash_message_type', 'error');

            return redirect('/abonne');
        }

        if($user->country()->get()[0]->iso == ''){
            Session::flash('flash_message', 'Renseignez votre pays');
            Session::flash('flash_message_type', 'error');

            return redirect('membre?redirect_to=/abonne/details/' . $id);
        }

        try {
            $currentPlan = $cardinity->getPlan((int)$id);

            return view('site.subscription.details', [
                'years'  => $this->getAvailableYears(),
                'months' => $this->getAvailableMonths(),
                'id'     => $id,
                'invite' => $invite,
            ]);

        } catch(\Exception $e){
            return redirect()->back();
        }
    }

    public function invite(){
        return view('site.subscription.invite', []);
    }

    public function saveInviter(Request $request, Cardinity $cardinity){

        Session::set('inviter_data', $request->all());

        return $this->index($cardinity, true);
    }

    public function payment(){
        return view('site.subscription.payment', []);
    }

    public function renewal(){
        return view('site.subscription.renewal', []);
    }

    public function activateAuto(){
        $user = Auth::user();

        $user->is_subscription_renewable = true;

        $user->save();

        return redirect('/abonne/subscription');
    }

    public function deleteAuto(){

        $user = Auth::user();

        $user->is_subscription_renewable = false;

        $user->save();

        return redirect('/abonne/subscription');
    }

    public function subscription(){

        $user = Auth::user();

        if(!$user->isSubscriber()) return redirect('/abonne');

        return view('site.subscription.subscription', [
            'payments' => $user->payments()->get(),
            'gifts'    => $user->gifts()->get(),
        ]);
    }

    public function save(Request $request, Cardinity $cardinity, $id){

        $this->validate($request, [
            'holder'    => 'required|max:32',
            'pan'       => 'required',
            'cvc'       => 'required',
            'exp_month' => 'required',
            'exp_year'  => 'required',
        ]);


        $card = CreditCard::validCreditCard($request->offsetGet('pan'));

        if(!$card['valid']){
            return redirect()->back()->withErrors([
                'pan' => 'Le numéro de carte n\'est pas valide',
            ]);
        }

        $validCvc = CreditCard::validCvc($request->offsetGet('cvc'), $card['type']);
        if(!$validCvc){
            return redirect()->back()->withErrors([
                'cvc' => 'CVC est pas valide',
            ]);
        }

        $validDate = CreditCard::validDate((int)$request->offsetGet('exp_year'), (int)$request->offsetGet('exp_month'));
        if(!$validDate){
            return redirect()->back()->withErrors([
                'exp_month' => 'La Date n\'est pas valide',
            ]);
        }

        $card = new Card();
        $card->holder = $request->offsetGet('holder');
        $card->pan = $request->offsetGet('pan');
        $card->cvc = $request->offsetGet('cvc');
        $card->exp_month = (int)$request->offsetGet('exp_month');
        $card->exp_year = (int)$request->offsetGet('exp_year');


        $invite = $request->offsetGet('invite') == '1';

        try {

            //            $cardinity->set3DPassTestCase(true);

            $currentPlan = $cardinity->getPlan((int)$id);

            $payment = $cardinity->createCardPayment($card, $currentPlan);

            if($payment->isPending()){
                Session::set('cardinity_payment', $payment->serialize());
                Session::set('cardinity_plan', $currentPlan->id);

                return view('site.subscription.secure', [
                    'auth'        => $payment->getAuthorizationInformation(),
                    'callbackUrl' => url('abonne/processAuthorization?_token=' . csrf_token() . '&invite=' . $invite),
                    'identifier'  => $payment->getOrderId(),
                ]);
            }

            if($payment->isApproved()){
                Session::flash('flash_message', 'Succès');

                self::successPayment($currentPlan, $payment, Auth::user(), $invite);

                return redirect('abonne/subscription');
            }

        } catch(\Cardinity\Exception\Declined $exception){
            Session::flash('flash_message', $exception->getErrors()[0]);
            Session::flash('flash_message_type', 'error');

            return redirect()->back();
        } catch(\Cardinity\Exception\InvalidAttributeValue $exception){
            Session::flash('flash_message', $exception->getMessage());
            Session::flash('flash_message_type', 'error');

            return redirect()->back();
        } catch(\Cardinity\Exception\ValidationFailed $exception){
            Session::flash('flash_message', $exception->getErrors()[0]['message']);
            Session::flash('flash_message_type', 'error');

            return redirect()->back();
        } catch(\ErrorException $exception){
            Session::flash('flash_message', 'Plan type d\'erreur');
            Session::flash('flash_message_type', 'error');

            return redirect()->back();
        }
    }

    public function processAuthorization(Request $request, Cardinity $cardinity){

        $message = null;
        $identifier = $request->offsetGet('MD');
        $pares = $request->offsetGet('PaRes');

        $invite = $request->offsetGet('invite') == "1";

        $currentPlan = $cardinity->getPlan((int)Session::get('cardinity_plan'));

        $payment = new Payment\Payment();

        $payment->unserialize(Session::get('cardinity_payment'));
        if($payment->getOrderId() != $identifier || $pares != $payment->getDescription()){
            Session::flash('flash_message', 'Invalide de rappel de données');
            Session::flash('flash_message_type', 'error');

            return redirect('abonne/details/' . $currentPlan->id);
        }

        try {
            if($payment->isPending()){
                $method = new Payment\Finalize(
                    $payment->getId(),
                    $pares
                );
                /** @var \Cardinity\Method\Payment\Payment $payment */
                $payment = $cardinity->call($method);
            }

            if($payment->isApproved()){
                Session::flash('flash_message', 'Success');

                self::successPayment($currentPlan, $payment, Auth::user(), $invite);

                return redirect('abonne/subscription');
            }
        } catch(\Cardinity\Exception\Runtime $e){
            Session::flash('flash_message', '3D secure error');
            Session::flash('flash_message_type', 'error');

            return redirect('abonne/details/' . $currentPlan->id);
        }

        Session::flash('flash_message', 'Réponse inattendue pendant la finalisation de paiement');

        return redirect('abonne/details/' . $currentPlan->id);
    }

    public static function successPayment(Plan $plan, Payment\Payment $cardinityPayment, $user, $invite = false){
        $startDate = Carbon::create();

        $lastPayment = $user->getLastPayment();

        if($lastPayment){
            $startDate = $lastPayment->end_access_date;
        } else {
            $user->start_subscribe_date = $startDate->format('Y-m-d');
        }
        $endDate = clone $startDate;
        $endDate->addMonths($plan->countMonth);

        // If invite - send email message and check email
        if($invite){

            $isRenewable = false;

            $inviterData = Session::get('inviter_data');

            if(!isset($inviterData['email-friend'])) return false;

            $email = trim($inviterData['email-friend']);

            $userInvited = User::where('email', $email)->first();

            if($userInvited){

                $user_id = $userInvited->id;
                $lastPayment = $userInvited->getLastPayment();

                if($lastPayment){
                    $startDate = $lastPayment->end_access_date;
                } else {
                    $startDate = Carbon::create();
                }

                $endDate = clone $startDate;
                $endDate->addMonths($plan->countMonth);

                if($userInvited->id == $user->id){
                    $isRenewable = true;
                }

                $userInvited->email_token = str_random(40);
                $userInvited->save();

                Mail::send('site.emails.invite', [
                    'data' => $inviterData,
                    'plan' => $plan,
                    'url'  => \URL::to('email/verif/' . $user_id . '/' . $userInvited->email_token),
                    'end_access_date' => $endDate->format('d.m.Y'),
                ], function($message) use ($email){
                    $message->to($email);
                    $message->subject(trans('subscription.email_subject'));
                });

            } else {

                $password = str_random(10);
                $email_token = str_random(40);
                $startDate = Carbon::create();

                $endDate = clone $startDate;
                $endDate->addMonths($plan->countMonth);

                $newUser = User::create([
                    'name'                 => '',
                    'email'                => $email,
                    'password'             => $password,
                    'partner_aid'          => 0,
                    'email_token'          => $email_token,
                    'is_email_valid'       => 0,
                    'start_subscribe_date' => $startDate->format('Y-m-d'),
                ]);

                if($newUser){
                    $user_id = $newUser->id;

                    Mail::send('site.emails.invite_new', [
                        'data'     => $inviterData,
                        'plan'     => $plan,
                        'url'      => \URL::to('email/verif/' . $user_id . '/' . $email_token),
                        'password' => $password,
                        'end_access_date' => $endDate->format('d.m.Y'),
                    ], function($message) use ($email){
                        $message->to($email);
                        $message->subject(trans('subscription.email_subject'));
                    });
                }
            }

            Mail::send('site.emails.invite_payer', [
                'data' => $inviterData,
                'plan' => $plan,
                'url'  => env('APP_URL'),
                'end_access_date' => $endDate->format('d.m.Y'),
            ], function($message) use ($user, $inviterData){
                $message->to($user->email);
                $message->subject(trans('subscription.email_subject_payer', ['name'=> $inviterData['name-friend']]));
            });

        } else {
            $user_id = $user->id;
            $isRenewable = true;

            \Mail::send('site.emails.buy', [
                'user'            => $user,
                'plan'            => $plan,
                'url'             => \URL::to('/'),
                'end_access_date' => $endDate->format('d.m.Y'),
            ], function($message) use ($user){
                $message->to($user->email);
                $message->subject(trans('subscription.buy_subject'));
            });
        }

        $payment = new PaymentDB;
        $payment->user_id = $user_id;
        $payment->payer_id = $user->id;
        $payment->plan_id = $plan->id;
        $payment->price = $plan->price;
        $payment->cardinity_id = $cardinityPayment->getId();
        $payment->order_id = $cardinityPayment->getOrderId();
        $payment->start_access_date = $startDate->format('Y-m-d');
        $payment->end_access_date = $endDate->format('Y-m-d');
        $payment->is_renewable = $isRenewable;

        if($user->isPartner()){
            $partner = $user;
        } else {
            $partner = $user->partner();
        }

        if($partner && $partner->percent != 0.0){
            $payment->partner_percent = $partner->percent;
            $payment->partner_sum = $payment->price / (100 / $partner->percent);
        }

        if($payment->save()){
            $user->save();

            if($invite && $email && $user_id == 0){
                $invitedUser = new InvitedUsers;
                $invitedUser->email = $email;
                $invitedUser->payment_id = $payment->id;

                $invitedUser->save();
            }

            return true;
        }

        return false;
    }

    private function getAvailableYears(){
        $return = [];
        for($i = date('Y'); $i <= date('Y') + 7; $i++){
            $return[ $i ] = $i;
        }

        return $return;
    }

    private function getAvailableMonths(){
        $return = [];
        for($i = 1; $i <= 12; $i++){
            $return[ $i ] = $i;
        }

        return $return;
    }
}