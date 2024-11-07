<?php

use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\ProjectController;
use App\Http\Controllers\Api\UserProfile;
use App\Http\Controllers\Api\UserSocial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;




// Registration security questions route
Route::get('/security-questions', function (Request $request) {
    $questions = [
        "What is the name of your first pet?",
        "In what city or town was your first job?",
        "What is your mother's maiden name?",
        "What high school did you attend?",
        "What is the name of the street you grew up on?",
        "What is the name of your favorite childhood teacher?",
        "What is your oldest sibling's middle name?",
        "In what city or town was your mother born?",
        "What is the name of the first company you worked for?",
        "What is your favorite food?",
    ];

    $message = 'Security questions retrieved successfully';

    return response()->json(['success' => true,'message' => $message, 'data' => $questions], 200);

});


// Get the authenticated user
Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});


// API test route
Route::get('/', function (Request $request) {
    return [
        'success' => true,
        'message' => 'api is live',
        'data' => [
            'date' => now()->format('Y-m-d H:i:s'),
            'request' => $request->all(),
            'environment' => config('app.env'),
            'debug' => config('app.debug')
        ],
        'Laravel' => app()->version()
    ];
});


// Authentication routes
// Register route
Route::post('register', [AuthController::class, 'register'])
    ->middleware('guest');
// Login route
Route::post('login', [AuthController::class, 'login'])
    ->middleware('guest');
// Logout route
Route::post('logout', [AuthController::class, 'logout'])
    ->middleware('auth:sanctum');


// Artisan routes
Route::get('/artisan', function (Request $request) {

    // For testing purposes
    $pass = $request->pass;
    $deploy = $request->deploy ?? false;

    // Verifying access
    if (empty($pass) || $pass != 'amtechdigitalnetworks') {
        return ['error' => 'Invalid pass'];
    }

    // For new deployment
    if ($pass && $deploy == 'new') {

        // Run artisan commands here...
        Artisan::call('migrate:fresh');
        Artisan::call('cache:clear');
        Artisan::call('optimize:clear');
        Artisan::call('config:clear');
        // Artisan::call('view:cache');
        // Artisan::call('route:cache');
    }

    // For normal deployment
    Artisan::call('cache:clear');
    Artisan::call('optimize:clear');
    Artisan::call('migrate');
    Artisan::call('storage:link');


    return ['artisan' => 'successfully deployed ' . $deploy];

});








// User profile and social media information
Route::group(['prefix' => 'profile','middleware' => ['auth:sanctum','verified']], function() {
    // User Profile
    Route::get('/', [UserProfile::class, 'show']);
    Route::put('/', [UserProfile::class, 'update']);

    // User Socials
    Route::get('/socials', [UserSocial::class, 'show']);
    Route::put('/socials', [UserSocial::class, 'update']);
});


// Projects routes
Route::group(['prefix' => 'projects','middleware' => ['auth:sanctum','verified']], function() {


    // Projects route
    Route::get('/', [ProjectController::class, 'index']);
    Route::get('/{project}', [ProjectController::class,'show']);
    Route::post('/', [ProjectController::class, 'store']);
    Route::put('/{project}', [ProjectController::class, 'update']);
    Route::delete('/{project}', [ProjectController::class, 'destroy']);

    // Comments route
    // Route::post('/{project}/comments', [CommentController::class,'store']);
    // Route::delete('/{project}/comments/{comment}', [CommentController::class, 'destroy']);

    // User Profile
    // Route::get('/', [UserProfile::class, 'show']);
    // Route::put('/', [UserProfile::class, 'update']);

    // User Socials
    // Route::get('/socials', [UserSocial::class, 'show']);
    // Route::put('/socials', [UserSocial::class, 'update']);
});


Route::get('info', function (Request $request){
    $user = $request->user();
    return $user ?? 'no message available';
})->middleware(['auth:sanctum']);

