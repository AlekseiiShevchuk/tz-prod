<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AudioGroup extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'title', 'audio_categories_id', "order"
    ];

    protected $dates = ['deleted_at'];

    public function category()
    {
        return $this->belongsTo(AudioCategory::class, 'audio_categories_id');
    }

    public function sounds()
    {
        return $this->hasMany(Sound::class, 'audio_groups_id', 'id')->orderBy('order', 'asc');
    }
}
