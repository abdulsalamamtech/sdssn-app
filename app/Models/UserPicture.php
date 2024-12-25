<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserPicture extends Model
{
    protected $fillable = [
        'user_id',
        'asset_id',
    ];


    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function asset()
    {
        return $this->belongsTo(Assets::class, 'asset_id');
    }
}
