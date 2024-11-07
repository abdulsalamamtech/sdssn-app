<?php

namespace App\Models\Api;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Social extends Model
{
    protected $fillable = [
        'user_id',
        'github',
        'linkedin',
        'twitter',
        'facebook',
        'instagram',
    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
