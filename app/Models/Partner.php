<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Partner extends Model
{
    protected $fillable = [
        'user_id',
        'banner_id',
        'name',
        'description',
        'deleted_by',
        'deleted_at',
    ];


    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }

    public function banner()
    {
        return $this->belongsTo(Assets::class, 'banner_id');
    }
}
