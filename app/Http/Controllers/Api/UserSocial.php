<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\UpdateUserSocialRequest;
use App\Models\Api\Social;
use Illuminate\Http\Request;

class UserSocial extends Controller
{

    /**
     * Display the specified resource.
     */
    public function show(Request $request)
    {
        $user = $request->user();
        $user_social = $user->social;

        if(!$user_social){
            return $this->sendError([], 'unable to load user social media information', 500);
        }

        return $this->sendSuccess($user_social, 'successful', 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserSocialRequest $request)
    {

        $data = $request->validated();
        $user = $request->user();
        $user_social = $user->social()->updateOrCreate(
            ['user_id' => $user->id], $data
        );

        if(!$user_social){
            return $this->sendError([], 'unable to update', 500);
        }

        return $this->sendSuccess($user_social, 'social media information update');
    }


}
