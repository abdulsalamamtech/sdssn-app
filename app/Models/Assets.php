<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Assets extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'original_name',
        'name',
        'type',
        'path',
        'file_id',
        'url',
        'size',
        'hosted_at',
        'active',
    ];

    // {
    //     "original_name": "view-page.png",
    //     "name": "banner",
    //     "type": "image/png",
    //     "path": "/sdssn-app/images/images_2025-01-06_13_28_31_mDzvIvz2S.png",
    //     "file_id": "677bda81432c4764166ffd0b",
    //     "url": "https://ik.imagekit.io/laravel10x/sdssn-app/images/images_2025-01-06_13_28_31_mDzvIvz2S.png",
    //     "size": 92757,
    //     "hosted_at": "imagekit"
    // }


}
