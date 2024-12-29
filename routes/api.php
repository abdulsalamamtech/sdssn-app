<?php

use App\Http\Controllers\Api\AdminController;
use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\CertificateController;
use App\Http\Controllers\Api\CommentController;
use App\Http\Controllers\Api\PodcastCommentController;
use App\Http\Controllers\Api\PodcastController;
use App\Http\Controllers\Api\ProjectController;
use App\Http\Controllers\Api\UserProfile;
use App\Http\Controllers\Api\UserSocial;
use App\Models\Api\Certificate;
use App\Models\User;
use App\Utils\ImageKit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;






Route::get('/', function () {
    return ['Laravel API' => app()->version()];
});


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


// Get available roles
Route::get('/available-roles', function (Request $request) {
    $roles = ['user', 'moderator', 'admin', 'super-admin'];

    $message = 'Available roles retrieved successfully';

    return response()->json(['success' => true,'message' => $message, 'data' => $roles], 200);

});


// Get the authenticated user
Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {

    $message = "Authenticated";
    $statusCode = 200;
    $user =  $request->user();
    return response()->json([
        'success' => false,
        'message' => $message,
        'data' => $user
    ], $statusCode);

});





// AUTHENTICATION ROUTES
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





// PROFILE ROUTES
// User profile, social media, projects, podcasts, certificates, information
Route::group(['prefix' => 'profile','middleware' => ['auth:sanctum','verified']], function() {
    // User Profile
    Route::get('/', [UserProfile::class, 'show']);
    Route::put('/', [UserProfile::class, 'update']);

    // User Socials
    Route::get('/socials', [UserSocial::class, 'show']);
    Route::put('/socials', [UserSocial::class, 'update']);

    // User Projects
    Route::get('/projects', [ProjectController::class, 'personal']);

    // User Podcasts [admin only]
    Route::get('/podcasts', [PodcastController::class, 'personal']);

    // User certificates
    Route::get('/certificates', [PodcastController::class, 'personal']);

    // User certificates approved [admin only]
    Route::get('/certificates/approved', [PodcastController::class, 'approved']);

    // For user profile picture
    // {"picture": "image profile"}
    Route::put('/picture', [UserProfile::class, 'updatePicture']);
    Route::post('/picture/update', [UserProfile::class, 'updatePicture']);





});


// User profile information
Route::get('/profile/{user:name}', [UserProfile::class, 'profile']);



// AUTH ROUTES RESOURCES
Route::group(['middleware' => ['auth:sanctum','verified']], function() {

    // PROJECTS
    // Project routes
    Route::apiResource('projects', ProjectController::class)
        ->only(['store', 'destroy']);
    // Update Project
    Route::post('projects/{project}/update', [ProjectController::class, 'update']);
    Route::put('projects/{project}', [ProjectController::class, 'update']);
    // Project comments
    Route::apiResource('projects.comments', CommentController::class)
        ->only(['store', 'update', 'destroy']);
    
    // Like project
    Route::put('projects/{project}/likes', [ProjectController::class, 'like']);
    // Share project
    Route::put('projects/{project}/shares', [ProjectController::class, 'share']);

    // Project like and share using POST
    Route::post('projects/{project}/likes', [ProjectController::class, 'like']);
    Route::post('projects/{project}/shares', [ProjectController::class, 'share']);


    // PODCAST
    // Project routes
    Route::apiResource('podcasts', PodcastController::class)
        ->only(['store', 'destroy']);
    // Update podcast
    Route::post('podcasts/{podcast}/update', [PodcastController::class, 'update']);
    Route::put('podcasts/{podcast}', [PodcastController::class, 'update']);
    // Podcast comments
    Route::apiResource('podcasts.comments', PodcastCommentController::class)
        ->only(['store', 'update', 'destroy']);
    
    // Like podcast
    Route::put('podcasts/{podcast}/likes', [PodcastController::class, 'like']);
    // Share podcast
    Route::put('podcasts/{podcast}/shares', [PodcastController::class, 'share']);
    
    
    // Podcast like and share using POST
    Route::post('podcasts/{podcast}/likes', [PodcastController::class, 'like']);
    Route::post('podcasts/{podcast}/shares', [PodcastController::class, 'share']);

});






// ADMIN ROUTES
Route::group(['prefix' => 'admin','middleware' => ['auth:sanctum','verified', 'admin']], function() {
    // Admin dashboard route
    Route::apiResource('/', AdminController::class);
    // Users routes
    Route::get('/users', [AdminController::class, 'users']);
    // Get all projects
    Route::get('/projects/all/', [ProjectController::class, 'allProjects']);
    // Approve project
    Route::put('/projects/{project}/approve', [ProjectController::class, 'approve']);
    // Approved projects
    Route::get('/projects/approved', [ProjectController::class, 'approved']);
    // Force delete project
    Route::delete('/projects/{project}/delete-from-trash', [ProjectController::class, 'forceDelete']);
    // Get deleted trash projects
    Route::get('/projects/trash', [ProjectController::class, 'trash']);
    // Podcast routes [admin]
    Route::apiResource('/podcasts', PodcastController::class);
    // Certificate routes [admin]
    Route::apiResource('/certificates', CertificateController::class);
    // Membership status routes [admin]
    Route::get('/memberships', [AdminController::class, 'memberships']);
});



// LOCATIONS ROUTES
// Get user locations
Route::get('/locations', [AdminController::class, 'locations']);

// Get minimal resource
Route::get('/resources', [AdminController::class, 'resources']);
// Get application statistics
Route::get('/statistics', [AdminController::class, 'index']);








// GENERAL PUBLIC ROUTES
// Projects routes
Route::apiResource('projects', ProjectController::class)
->only(['index', 'show']);
// Projects comments
Route::apiResource('projects.comments', CommentController::class)
->only(['index', 'show']);

// Search for projects
Route::get('/projects/search/query', [ProjectController::class, 'search']);

Route::get('/projects/title/{project:slug}', [ProjectController::class, 'show']);


// GENERAL PUBLIC ROUTES
// Podcasts routes
Route::apiResource('podcasts', PodcastController::class)
    ->only(['index', 'show']);
// Podcasts comments
Route::apiResource('podcasts.comments', PodcastCommentController::class)
    ->only(['index', 'show']);

// Search for podcast
Route::get('/podcasts/search/query', [PodcastController::class, 'search']);

Route::get('/podcasts/title/{podcast:slug}', [PodcastController::class, 'show']);
Route::get('/podcasts/category/video', [PodcastController::class, 'video']);
Route::get('/podcasts/category/audio', [PodcastController::class, 'audio']);


// For terminal, artisan and special commands
require __DIR__.'/terminal.php';


