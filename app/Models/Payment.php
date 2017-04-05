<?php
/**
 * Developer: Andrew Karpich
 * Date: 07.03.2017 17:55
 */

namespace App\Models;


use App\Payments\Cardinity;
use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payment extends Model{

    use SoftDeletes;

    protected $fillable = [ 'user_id', 'plan_id', 'price', 'cardinity_id', 'order_id', 'partner_percent', 'payer_id', 'is_renewable' ];

    protected $dates = ['start_access_date', 'end_access_date', 'created_at', 'updated_at'];

    public function user(){
        return $this->belongsTo(User::class)->first();
    }

    public function payer(){
        return $this->belongsTo(User::class)->first();
    }

    /**
     * @return \App\Payments\Plan
     */
    public function plan(){
        return (new Cardinity())->getPlan($this->plan_id);
    }
}