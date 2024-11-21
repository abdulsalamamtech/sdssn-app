<?php

use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\CertificateController;
use App\Http\Controllers\Api\CommentController;
use App\Http\Controllers\Api\PodcastCommentController;
use App\Http\Controllers\Api\ProjectController;
use App\Http\Controllers\Api\UserProfile;
use App\Http\Controllers\Api\UserSocial;
use App\Http\Controllers\Api\PodcastController;
use App\Utils\ImageKit;
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





// Artisan routes for local development and testing
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


// Link storage to public directory
Route::get('/link-storage', function () {

    Artisan::call('storage:link');

    return response()->json(['message' => 'Storage linked'], 201);
});





// User profile and social media information
Route::group(['prefix' => 'profile','middleware' => ['auth:sanctum','verified']], function() {
    // User Profile
    Route::get('/', [UserProfile::class, 'show']);
    Route::put('/', [UserProfile::class, 'update']);

    // User Socials
    Route::get('/socials', [UserSocial::class, 'show']);
    Route::put('/socials', [UserSocial::class, 'update']);

    // User Projects
    Route::get('/projects', [ProjectController::class, 'personal']);

});


// Certificate
Route::apiResource('/certificates', CertificateController::class)
    ->middleware(['auth:sanctum']);



// Like project
Route::put('projects/{project}/likes', [ProjectController::class, 'like']);
// Share project
Route::put('projects/{project}/shares', [ProjectController::class, 'share']);




// Project routes
Route::apiResource('projects', ProjectController::class)
        ->middleware(['auth:sanctum', 'verified'])
        ->only(['store', 'update', 'destroy']);

// Project routes
Route::apiResource('projects', ProjectController::class)
    ->only(['index', 'show']);


// Project comments
Route::apiResource('projects.comments', CommentController::class)
    ->middleware(['auth:sanctum'])
    ->only(['store', 'update', 'destroy']);
// Project comments
Route::apiResource('projects.comments', CommentController::class)
    ->only(['index', 'show']);





// Route::group(['middleware' => ['auth:sanctum','verified']], function() {
//     // User Profile
//     Route::post('/projects', [ProjectController::class, 'store']);
//     Route::patch('/projects/{project}', [ProjectController::class, 'up']);
//     Route::put('/projects/{project}', [ProjectController::class, 'update']);

//     Route::put('/now/{project}/now', [ProjectController::class, 'now']);


// });

// Route::apiResource('projects', ProjectController::class)
//         ->middleware(['auth:sanctum', 'verified'])
//         ->only(['store', 'update']);


// Route::resource('photos', PhotoController::class)->only([
//     'index', 'show'
// ]);

// Route::resource('photos', PhotoController::class)->except([
//     'create', 'store', 'update', 'destroy'
// ]);







    Route::apiResource('podcasts', PodcastController::class);


// Podcast routes
// Route::apiResource('podcasts', PodcastController::class)
//     ->only(['index', 'show']);
// Route::apiResource('podcasts', PodcastController::class)
//         ->middleware(['auth:sanctum'])
//         ->only(['store', 'update', 'delete']);

// Podcast comments
Route::apiResource('podcasts.comments', PodcastCommentController::class)
    ->only(['index', 'show']);
// Podcast comments
Route::apiResource('podcasts.comments', PodcastCommentController::class)
    ->middleware(['auth:sanctum'])
    ->only(['store', 'update']);




Route::get('info', function (Request $request){
    $user = $request->user();
    return $user ?? 'no message available';
})->middleware(['auth:sanctum']);




// Route::group(['prefix' => 'projects','middleware' => ['auth:sanctum','verified']], function() {
//     // Projects route
//     Route::apiResource('/', ProjectController::class);
// });

// Route::apiResource('projects.comments', CommentController::class);
// Route::get('/', [ProjectController::class, 'index']);
// Route::get('/{project}', [ProjectController::class,'show']);
// Route::post('/', [ProjectController::class, 'store']);
// Route::put('/{project}', [ProjectController::class, 'update']);
// Route::delete('/{project}', [ProjectController::class, 'destroy']);


Route::post('/upload', function (Request $request) {


    // $data = json_decode($request->getContent(), true);
    // return [$data, $request->getContent(), $request->all()["file"], "success"];

    $file = $request->file('image');

    // $con = $file->getContent();
    // $con = base64_encode($con);
    // return $con;


    // return $file;
    $upload = new ImageKit();
    $res =  $upload->uploadFile($file, 'images');
    // $res =  $upload->upload($file, 'images');

    // uploadToImageKit($request, $fileName = 'image')
    return $res;

    return response()->json([
        'method' => $request->method(),
        'all' => $request->all(),
        'content' => $request->getContent(),
        'data' => $data
    ]);

});
