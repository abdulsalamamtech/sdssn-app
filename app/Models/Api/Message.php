<?php

namespace App\Models\Api;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $fillable = [
        'full_name',
        'email',
        'phone_number',
        'message'
    ];
}
