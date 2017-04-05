<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Sound extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'title', 'audio_groups_id', 'url', 'duration', 'is_free', "order"
    ];

    protected $dates = ['deleted_at'];

    public function group(){
        return $this->belongsTo(AudioGroup::class, 'audio_groups_id')->withTrashed();
    }

    public function getDuration(){
        if(!$this->duration){
            $this->duration = self::estimateDuration(public_path(mb_substr($this->url, 1)));
            if($this->duration) $this->save();
        }

        $duration = $this->duration;

        if($duration){
            $minutes = (int)($duration / 60);
            $seconds = $duration - ($minutes * 60);
            $duration = ( $minutes < 10 ? '0' . $minutes : $minutes ) . ':' . ( $seconds < 10 ? '0' . $seconds : $seconds );
        }

        return $duration;
    }

    public static function estimateDuration($path){

        $duration = null;

        if(file_exists($path)){
            require_once(base_path('vendor/php_mp3_duration/php_mp3_duration/mp3file.class.php'));
            $mp3file = new \MP3File($path);
            $duration = $mp3file->getDurationEstimate();
            if(!$duration){
                $duration = $mp3file->getDuration();
            }
        }

        return $duration;
    }

    public function isAccess(){
        $user = Auth::user();
        return $user && (($this->is_free && $user->is_email_valid ) || $user->isSubscriber());
    }
}
