<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\UpdateUserProfileRequest;
use App\Models\Assets;
use App\Models\User;
use App\Models\UserPicture;
use Illuminate\Auth\Events\Validated;
use Illuminate\Http\Request;

class UserProfile extends Controller
{

    /**
     * Display the specified resource.
     */
    public function show(Request $request)
    {
        $user = $request->user();
        $user->load(['picture', 'picture.asset', 'social', 'projects', 'projects.user', 'projects.banner', 'certificates']);

        if(!$user){
            return $this->sendError([], 'unable to load user profile', 500);
        }

        return $this->sendSuccess($user, 'successful', 200);


    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserProfileRequest $request)
    {

        // return "update profile";
        $data = $request->validated();
        $user = $request->user();
        $user->update($data);
      
        if(!$user){
            return $this->sendError([], 'unable to update profile', 500);
        }

        return $this->sendSuccess($user, 'profile updated', 201);
    }


    
    public function profile(User $user){

        // $user->load(['picture', 'picture.asset', 'social', 'projects', 'certificates']);
        $user->load(['picture', 'picture.asset', 'social', 'projects', 'projects.user', 'projects.banner', 'certificates']);

    
        if (!$user) {
            return $this->sendError([], 'unable to load profile', 500);
        }
    
        return $this->sendSuccess($user, 'successful', 200);

    }



    public function updatePicture(Request $request){

        $user = $request->user();

        $request->validate([
            'picture' => ['required', 'image', 'max:2048'],
        ]);

        // $upload =  $this->uploadImage($request, 'banner');
        $upload = $this->uploadToImageKit($request,'picture');

        // Add assets
        $picture = Assets::create($upload);

        $user_picture = UserPicture::updateOrCreate(
            ['user_id' => $user->id], ['user_id' => $user->id, 'asset_id' => $picture->id]
        );

        // Delete previously uploaded file
        $fileId = $user?->picture?->asset?->fileId;
        if($fileId){
            $previousFile = $this->deleteImageKitFile($fileId);
            Assets::where('file_id', $fileId)->delete();
        }

        $user->load(['picture', 'picture.asset']);

        if(!$user_picture){
            return $this->sendError([], 'unable to load user picture', 500);
        }

        return $this->sendSuccess($user, 'successful', 200);

    }

}
