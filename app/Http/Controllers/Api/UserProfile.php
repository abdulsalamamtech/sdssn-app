<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\UpdateUserProfileRequest;
use App\Models\Assets;
use App\Models\User;
use Illuminate\Auth\Events\Validated;
use Illuminate\Http\Request;

class UserProfile extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request)
    {
        $user = $request->user();
        $user->load(['social']);

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
        $user->load(['social']);

        $user_profile = $user;

        if(!$user_profile){
            return $this->sendError([], 'unable to update profile', 500);
        }

        return $this->sendSuccess($user_profile, 'profile updated', 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
    
    public function profile(User $user){

        $user->load(['social', 'projects', 'picture', 'certificates']);
    
        if (!$user) {
            return $this->sendError([], 'unable to load profile', 500);
        }
    
        return $this->sendSuccess($user, 'successful', 200);

    }


    public function storePicture(Request $request){

        $user = $request->user();

        $data = $request->validate([
            'picture' => ['required', 'image', 'max:2048'],
        ]);

        // $upload =  $this->uploadImage($request, 'banner');
        $upload = $this->uploadToImageKit($request,'picture');

        // Add assets
        $picture = Assets::create($upload);
        $user->update(['asset_id' => $picture]);

        $user->load(['picture']);

        if(!$user){
            return $this->sendError([], 'unable to store user picture', 500);
        }

        return $this->sendSuccess($user, 'successful', 200);

    }


    public function updatePicture(Request $request){

        $user = $request->user();

        $data = $request->validate([
            'picture' => ['required', 'image', 'max:2048'],
        ]);

        // $upload =  $this->uploadImage($request, 'banner');
        $upload = $this->uploadToImageKit($request,'picture');

        // Add assets
        $picture = Assets::create($upload);
        $user->update(['asset_id' => $picture]);

        $user->load(['picture']);

        if(!$user){
            return $this->sendError([], 'unable to load user picture', 500);
        }

        return $this->sendSuccess($user, 'successful', 200);

    }

}
