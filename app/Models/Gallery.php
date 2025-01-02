<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
{
    protected $fillable = [
        'user_id',
        'banner_id',
        'slug',
        'title',
        'description',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function banner()
    {
        return $this->belongsTo(Assets::class, 'banner_id');
    }
}
