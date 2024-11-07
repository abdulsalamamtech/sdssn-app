<?php

namespace App\Models\Api;

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
}
