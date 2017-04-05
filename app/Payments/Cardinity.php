<?php
/**
 * Developer: Andrew Karpich
 * Date: 01.03.2017 16:59
 */

namespace App\Payments;

use App\User;
use Cardinity\Client;
use Cardinity\Method\MethodInterface;
use Cardinity\Method\ResultObjectInterface;
use Config;
use Cardinity\Method\Payment;
use Illuminate\Support\Facades\Auth;

class Cardinity {

    /**
     * Model for plans. Price in euro cents
     *
     * @var array
     */
    static protected $plansModel = [
        [ 'id' => 1, 'price' => 999, 'countMonth' => 1 ],
        [ 'id' => 2, 'price' => 2397, 'countMonth' => 3 ],
        [ 'id' => 3, 'price' => 3594, 'countMonth' => 6 ],
        [ 'id' => 4, 'price' => 5988, 'countMonth' => 12 ],
    ];

    public static function getCurrency(){
        return 'EUR';
    }

    /**
     * @var Plan[] $plans
     */
    private $plans = [];

    private $test = false;
    private $d3pass = null;

    /**
     * @var Client
     */
    protected $client;

    public function __construct(){
        $this->client = Client::create([
            'consumerKey'    => Config::get('app.cardinity_key'),
            'consumerSecret' => Config::get('app.cardinity_secret'),
        ]);

        foreach(self::$plansModel as $planModel){
            $plan = new Plan();

            foreach($planModel as $property => $value){
                $plan->{$property} = $value;
            }

            $this->plans[ $plan->id ] = $plan;
        }
    }

    /**
     * Call the given method.
     * @param MethodInterface $method
     * @return ResultObjectInterface|array
     */
    public function call(MethodInterface $method){
        return $this->client->call($method);
    }

    /**
     * @param Card   $card
     * @param Plan   $plan
     * @param string $orderId
     * @param string $description
     * @return \Cardinity\Method\Payment\Payment
     */
    public function createCardPayment(Card $card, Plan $plan, $orderId = null, $description = ''){

        /** @var User $user */
        $user = Auth::user();

        $paymentParams = $this->getPaymentParams($user, $plan, [
            'holder'    => $card->holder,
            'pan'       => $card->pan,
            'cvc'       => $card->cvc,
            'exp_month' => $card->exp_month,
            'exp_year'  => $card->exp_year,
        ], Payment\Create::CARD, $orderId, $description);

        $method = new Payment\Create($paymentParams);

        /** @var \Cardinity\Method\Payment\Payment $payment */
        return $this->call($method);
    }

    /**
     * @param User   $user
     * @param Plan   $plan
     * @param array  $paymentInstrument
     * @param string $paymentMethod
     * @param null   $orderId
     * @param string $description
     * @return array
     */
    public function getPaymentParams(User $user, Plan $plan, array $paymentInstrument, $paymentMethod = Payment\Create::CARD, $orderId = null, $description = ''){

        if($orderId == null) $orderId = str_random(8);

        if($this->test){
            if(is_bool($this->d3pass)){
                if($this->d3pass) $description = '3d-pass';
                else $description = '3d-fail';
            }
        }

        $country = $user->country()->get()[0];

        return [
            'amount'             => $plan->price / 100,
            'currency'           => self::getCurrency(),
            'settle'             => false,
            'description'        => $description,
            'order_id'           => $orderId,
            'country'            => $country->iso,
            'payment_method'     => $paymentMethod,
            'payment_instrument' => $paymentInstrument,
        ];

    }

    public function set3DPassTestCase($success){
        $this->test = true;
        $this->d3pass = $success;
    }

    public function getPlan($id){
        return $this->plans[ $id ];
    }

    public function getAllPlans(){
        return $this->plans;
    }

    /**
     * @param \App\Models\Payment $payment
     * @param null                $orderId
     * @param string              $description
     * @return array|ResultObjectInterface
     */
    public function renewSubscribe(\App\Models\Payment $payment, $orderId = null, $description = ''){

        $plan = $payment->plan();
        $user = $payment->user();

        $paymentParams = $this->getPaymentParams($user, $plan, [
            'payment_id' => $payment->cardinity_id,
        ], Payment\Create::RECURRING, $orderId, $description);

        $method = new Payment\Create($paymentParams);

        /** @var \Cardinity\Method\Payment\Payment $payment */
        return $this->call($method);

    }
}

class Plan {

    public $id;

    /**
     * Price in euro cents
     * @var int
     */
    public $price;

    public $countMonth;

    public function getFormatEuroPrice(){
        return self::getFormatString($this->price / 100);
    }

    public function getFormatEuroPricePerMonth(){
        return self::getFormatString(($this->price / 100) / $this->countMonth);
    }

    public static function getFormatString($price, $utf = false){
        return $price . ($utf ? ' €' : ' &euro;');
    }

}

class Card {

    public $holder;
    public $pan;
    public $cvc;
    public $exp_month;
    public $exp_year;

}