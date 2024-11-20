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
        // $upload =  $this->uploadImage($request, 'banner');
        $upload = $this->uploadToImageKit($request,'banner');
        $user = $request->user();

        // Add assets
        $banner = Assets::create($upload);
        $data['banner_id'] = $banner->id;
        $data['user_id'] = $user->id ?? 1;

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

        // return [$request->getContent(), $request->all(), $project];

        $data = $request->all();

        // if($request->banner){
        //     // delete old banner
        //     $banner = Assets::find($project->banner_id);
        //     $banner->delete();
        //     // upload new banner
        //     $upload =  $this->uploadImage($request);
        //     $data['banner_id'] = $upload['id'];
        // }

        if($request->banner){

            // Upload the banner
            // $upload =  $this->uploadImage($request, 'banner');
            // $user = $request->user();


            // // Add assets
            // $banner = Assets::create($upload);
            // $data['banner_id'] = $banner->id;
            // $data['user_id'] = $user->id;

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

        return [$request->all(), $request->getContent(), $project];
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
