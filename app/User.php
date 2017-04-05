<?php

namespace App;

use App\Models\Country;
use App\Models\Payment;
use App\Models\Role;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Config;
use SammyK\LaravelFacebookSdk\SyncableGraphNodeTrait;
use App\Http\Controllers\Auth\ResetPassword as ResetPasswordNotification;

class User extends Authenticatable
{
    use Notifiable, Role, SoftDeletes, SyncableGraphNodeTrait;

    const DEFAULT_ROLE    = 'client';
    const DEFAULT_PERCENT = 10.00;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'percent',
        'aid',
        'partner_aid',
        'surname',
        'country_id',
        'nickname',
        'birthday',
        'gender',
        'city',
        'zip',
        'address1',
        'address2',
        'phone_country_code',
        'phone',
        'image',
        'company_title',
        'name_2',
        'surname_2',
        'email_2',
        'vat',
        'phone_country_code_2',
        'phone_2',
        'is_permanent_subscribe_access',
        'subscribe_news',
        'is_subscription_renewable',
        'start_subscribe_date',
        'email_token',
        'is_email_valid',
        'subscribe_access_to',
        'manually'
    ];

    protected $dates = ['deleted_at', 'subscribe_access_to'];

    protected static $graph_node_field_aliases = [
        'id'           => 'facebook_user_id',
        'first_name'   => 'name',
        'last_name'    => 'surname',
        'access_token' => 'access_token'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'access_token'
    ];

    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = bcrypt($value);
    }

    public function getAffiliateUrlAttribute()
    {
        return Config::get('app.url') . '?aid=' . $this->aid;
    }

    public function country()
    {
        return $this->hasOne(Country::class, 'id', 'country_id');
    }

    public function partner(){
        return $this->partner_aid ? $this->hasOne(User::class, 'aid', 'partner_aid')->first() : false;
    }

    public function payments(){
        return $this->hasMany(Payment::class, 'user_id', 'id')->orderBy('created_at', 'asc');
    }

    public function gifts(){
        return $this->hasMany(Payment::class, 'payer_id', 'id')->whereNotIn('user_id', [$this->id])->orderBy('created_at', 'asc');
    }

    public function getLastPayment(){
        $payments = $this->payments()->get();

        if(count($payments) > 0){
            return $payments[ count($payments) - 1 ];
        }

        return null;
    }

    public function getBirthDayDate(){

        $date = new \stdClass();
        $date->month = 0;
        $date->year = 0;
        $date->day = 0;

        $birthday = new \DateTime($this->birthday);

        if($birthday){
            $date->month = $birthday->format('m');
            $date->year = $birthday->format('Y');
            $date->day = $birthday->format('d');
        }

        return $date;
    }

    public function getImage(){
        return $this->image && file_exists(public_path('storage/' . $this->image)) ? '/storage/' . $this->image : null;
    }

    public function isSubscriber(){
        return $this->is_permanent_subscribe_access || $this->isAdmin() || ( $this->getLastPayment() && $this->getLastPayment()->end_access_date->timestamp > Carbon::create()->timestamp ) || ($this->subscribe_access_to && $this->subscribe_access_to->timestamp > Carbon::create()->timestamp) ;
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPasswordNotification($token));
    }

    /**
     * Scope a query to only include partners users.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePartners($query)
    {
        return $query->where('role', 'partner');
    }

    /**
     * Scope a query to only include clients users.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeClients($query)
    {
        return $query->where('role', 'client');
    }
}