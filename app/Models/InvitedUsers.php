<?php
/**
 * Developer: Andrew Karpich
 * Date: 15.03.2017 12:17
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class InvitedUsers extends Model{

    protected $fillable = [
        'payment_id', 'email'
    ];

    protected $dates = ['created_at', 'updated_at'];

    public function payment(){
        return $this->belongsTo(Payment::class)->first();
    }
}