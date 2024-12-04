<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;



Route::get('/', function () {
    return ['Laravel' => app()->version()];
});


// For Auth endpoints
require __DIR__.'/auth.php';


Route::get('/mail', function (Request $request){
    $send = Mail::raw('This is a test email, from: ' . url(), function ($message) {
        $message->to('abdulsalamamtech@gmail.com')->subject('Test Email');
    });
    return $send? "done": "fail";
});
