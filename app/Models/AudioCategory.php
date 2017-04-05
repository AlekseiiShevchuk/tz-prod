<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AudioCategory extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'title', 'order'
    ];

    protected $dates = ['deleted_at'];

    public function groups()
    {
        return $this->hasMany(AudioGroup::class, 'audio_categories_id', 'id')->orderBy('order', 'asc');
    }
}
