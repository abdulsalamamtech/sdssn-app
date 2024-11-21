<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StoreProjectRequest;
use App\Http\Requests\Api\UpdateProjectRequest;
use App\Models\Api\Project;
use App\Models\Assets;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $project = Project::with(['user', 'comments.user', 'banner'])->get();

        if (!$project) {
            return $this->sendError([], 'unable to load projects', 500);
        }

        return $this->sendSuccess($project, 'successful', 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProjectRequest $request)
    {
        $data = $request->validated();
        $user = $request->user();

        // $upload =  $this->uploadImage($request, 'banner');
        $upload = $this->uploadToImageKit($request,'banner');

        // Add assets
        $banner = Assets::create($upload);
        $data['banner_id'] = $banner->id;
        $data['user_id'] = $user->id;

        // Add project
        $project = Project::create($data);
        $project->load(['user', 'comments.user', 'banner']);


        if (!$project) {
            return $this->sendError([], 'unable to update project', 500);
        }

        return $this->sendSuccess($project, 'project created', 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Project $project)
    {
        $project->views++;
        $project->save();
        $project->load(['user', 'comments.user', 'banner']);

        if (!$project) {
            return $this->sendError([], 'unable to load project', 500);
        }

        return $this->sendSuccess($project, 'successful', 200);

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProjectRequest $request, Project $project)
    {
        $data = $request->validated();
        $user = $request->user();

        if ($user->id != $project->user_id) {
            return $this->sendError([], 'you are unauthorize', 401);
        }

        if($request->banner){

            // Update the code to delete the previously uploaded banner
            $upload = $this->uploadToImageKit($request,'banner');

            // Add assets
            $banner = Assets::create($upload);
            $data['banner_id'] = $banner->id;

        }

        $project->update($data);
        $project->load(['user','comments.user', 'banner']);

        if (!$project) {
            return $this->sendError([], 'unable to update project', 500);
        }

        return $this->sendSuccess($project, 'project updated', 200);

    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Project $project)
    {

        $user = request()->user();

        if ($user->id != $project->user_id) {
            return $this->sendError([], 'you are unauthorize', 401);
        }

        $project->delete();

        return $this->sendSuccess($project, 'project deleted', 200);

    }


    // show user project
    public function personal(Request $request)
    {

        $user = $request->user();

        $project = Project::where('user_id', $user->id)->with(['user', 'comments.user', 'banner'])->get();

        if (!$project) {
            return $this->sendError([], 'unable to load personal projects', 500);
        }

        return $this->sendSuccess($project, 'successful', 200);
    }


    // Like project
    public function like(Request $request, Project $project)
    {

        $project->likes++;
        $project->save();
        $project->load(['user', 'comments.user', 'banner']);

        if (!$project) {
            return $this->sendError([], 'unable to load project', 500);
        }

        return $this->sendSuccess($project, 'successful', 200);

    }


    // Share project
    public function share(Project $project)
    {

        $project->shares++;
        $project->save();

        $project->load(['user', 'comments.user', 'banner']);

        if (!$project) {
            return $this->sendError([], 'unable to load project', 500);
        }

        return $this->sendSuccess($project, 'successful', 200);

    }



}
