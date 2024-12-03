<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return ['Laravel' => app()->version()];
});


// For Auth endpoints
require __DIR__.'/auth.php';


