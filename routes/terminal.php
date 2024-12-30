<?php

use App\Models\Assets;
use App\Models\User;
use App\Utils\ImageKit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;
use Symfony\Component\Process\Process;









// DEPLOYMENT ROUTES
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
    if ($pass && $deploy == 'new' && app()->config('app.env') == 'local') {

        // Run artisan commands here...
        /** 
         * Don't run this on production
         * Artisan::call('migrate:fresh');
        */
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

// Assign role to user
Route::get('/assign-role', function (Request $request) {

    // return $request->email;
    $user = User::where('email', $request->email)->first();

    // return $user;

    if(!$user){
        return response()->json(['message' => 'User not found'], 201);
    }

    if(!$request->role){
        return response()->json(['message' => 'enter a role'], 201);
    }

    if(!in_array($request->role, ['user', 'moderator', 'admin', 'super-admin'])){
        return response()->json(['message' => 'Invalid role'], 201);
    }

    $user->role = $request->role;
    $user->save();

    $message = $request->role . ' role assign to ' . $request->email;
    return response()->json([
        'status' => true,
        'message' => $message
    ], 201);
    
})->middleware(['auth:sanctum', 'role:admin']);



// TEST ROUTES
Route::get('/files', function (Request $request) {

    $data = Assets::latest()->paginate(50);

    return response()->json([
        'method' => $request->method(),
        'all' => $request->all(),
        'content' => $request->getContent(),
        'data' => $data
    ]);

});
Route::get('/files/upload', function (Request $request) {


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
Route::get('/files/delete', function (Request $request) {

    $fileId = $request->file_id ?? '';
    if (!$fileId) {
        $result['success'] = false;
        $result['message'] = "File ID is missing";
    }


    $result['message'] = "Processing file: " . $fileId;
    $removeFile = new ImageKit();
    $res =  $removeFile->deleteFile($fileId);

    // check and test this expression
    // if ($res && $res['success'] == true) {
    //     return;
    // }else{
    //     return $res;
    // }

    return response()->json([
        'method' => $request->method(),
        'all' => $request->all(),
        'content' => $request->getContent(),
        'data' => [$result, $res]
    ]);

});


Route::get('info', function (Request $request){
    $user = $request->user();
    return $user ?? 'no message';
})->middleware(['auth:sanctum']);



// use Symfony\Component\Process\Process;

Route::get('/run-terminal', function () {


    // $process = new Process(['ls', '-la']);

    $commands = ['ls', '..'];
    $process = new Process($commands);


    $process->run();

    if ($process->isSuccessful()) {
        return nl2br($process->getOutput());
    }

    return 'Error: ' . nl2br($process->getErrorOutput());
});


Route::get('/run-sequential-commands', function () {
    $commands = [
        ['ls', '..'],
        // ['../storage/', ' sudo chmod -R 775'],
        ['php', '../artisan', 'config:clear'],
        ['php', '../artisan', 'cache:clear'],
        ['php', '../artisan', 'migrate']
    ];

    $output = '';

    foreach ($commands as $command) {
        $process = new Process($command);
        $process->run();

        if ($process->isSuccessful()) {
            $output .= nl2br($process->getOutput()) . "\n";
        } else {
            return 'Error: ' . nl2br($process->getErrorOutput());
        }
    }

    return $output;
});


Route::get('/debug-path', function () {
    return base_path();
});



Route::get('/run-command', function () {
    $process = new Process(['php', '../artisan', 'config:clear']);
    $process->run();

    if ($process->isSuccessful()) {
        return nl2br($process->getOutput());
    }

    return 'Error: ' . nl2br($process->getErrorOutput());
});
