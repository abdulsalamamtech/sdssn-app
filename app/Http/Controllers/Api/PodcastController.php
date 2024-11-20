<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StorePodcastRequest;
use App\Models\Api\Podcast;
use App\Models\Assets;
use Illuminate\Http\Request;

class PodcastController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $project = Podcast::with(['user', 'comments.user', 'banner'])->get();

        if (!$project) {
            return $this->sendError([], 'unable to load projects', 500);
        }

        return $this->sendSuccess($project, 'successful', 200);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePodcastRequest $request)
    {
        $data = $request->validated();
        $upload =  $this->uploadImage($request, 'banner');
        $user = $request->user();

        // Add assets
        $banner = Assets::create($upload);
        $data['banner_id'] = $banner->id;
        $data['user_id'] = $user->id ?? 1;

        // Add project
        $project = Podcast::create($data);
        $project->load(['user', 'comments.user', 'banner']);


        if (!$project) {
            return $this->sendError([], 'unable to update project', 500);
        }

        return $this->sendSuccess($project, 'project created', 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Podcast $podcast)
    {
        //
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Podcast $podcast)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Podcast $podcast)
    {
        //
    }
}
