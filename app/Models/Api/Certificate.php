<?php

namespace App\Models\Api;

use App\Models\Assets;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Certificate extends Model
{
    protected $fillable = [
        'added_by',
        'belong_to',
        'asset_id',
        'course',
        'description',
    ];


    public function user()
    {
        return $this->belongsTo(User::class, 'belongs_to');
    }

    public function addedBy()
    {
        return $this->belongsTo(User::class, 'added_by');
    }

    // Certificate Image
    public function certificate()
    {
        return $this->belongsTo(Assets::class, 'asset_id');
    }
}
