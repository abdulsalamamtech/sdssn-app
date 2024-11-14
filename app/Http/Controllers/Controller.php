<?php

namespace App\Http\Controllers;

use App\Traits\ApiResponseTrait;
use App\Traits\UploadFileTrait;

abstract class Controller
{
    use ApiResponseTrait, UploadFileTrait;
}
