<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StoreProjectRequest;
use App\Http\Requests\Api\UpdateProjectRequest;
use App\Models\Api\Project;
use App\Models\Assets;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $projects = Project::whereNotNull('approved_by')
            ->where('status', 'public')
            ->with(['user', 'comments.user', 'banner'])
            ->get();

            // return $projects;

        if (!$projects) {
            return $this->sendError([], 'unable to load projects', 500);
        }

        return $this->sendSuccess($projects, 'successful', 200);
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

        // ['public', 'private', 'draft']
        if($data['status'] == 'public'){
            $data['approved_by'] = $user->id;
        }

        // Generate slug
        $title = $data['title'];
        $slug = Str::slug($title);

        $slug_fund = Project::where('slug', $slug)->first();
        ($slug_fund)
        ?$data['slug'] = $slug.'-'.rand(100,999)
        :$data['slug'] = $slug;


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

        // Admin can also edit post
        if ($user->id != $project->user_id || $user->role != 'admin') {
            return $this->sendError([], 'you are unauthorize', 401);
        }

        // Project title as slug
        if($project->title != $data['title']){
            // Generate slug
            $title = $data['title'];
            $slug = Str::slug($title);

            $slug_fund = Project::where('slug', $slug)->first();
            // $data['slug'] = $slug;
            // if($slug_fund){
            //     $data['slug'] = $slug.'-'.rand(100,999);
            // }
            ($slug_fund)
            ?$data['slug'] = $slug.'-'.rand(100,999)
            :$data['slug'] = $slug;
        }


        // If the banner is updated
        if($request->banner){

            // Delete the previously uploaded banner
            // Update the code to delete the previously uploaded banner
            $upload = $this->uploadToImageKit($request,'banner');

            // Add assets
            $banner = Assets::create($upload);
            $data['banner_id'] = $banner->id;

            // Delete previously uploaded file
            $fileId = $project->banner->assets->fileId;
            $previousFile = $this->deleteImageKitFile($fileId);
            Assets::where('file_id', $fileId)->delete();

        }

        // ['public', 'private', 'draft']
        if($data['status'] == 'public'){
            $data['approved_by'] = $user->id;
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

        if ($user->id != $project->user_id || $user->role != 'admin') {
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
            return $this->sendError([], 'unable to like project', 500);
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


     // Approve project
    public function approve(Request $request, Project $project)
    {
        $user = $request->user();

        // ['public', 'private', 'draft']
        if($project->status == 'public'){
            $project->approved_by = $user->id;
        }
        $project->save();


        if (!$project) {
            return $this->sendError([], 'unable to load project', 500);
        }

        return $this->sendSuccess($project, 'project approved successfully', 200);

    }

    // User approved public project
    public function approved(Request $request)
    {

        $user = $request->user();

        $project = Project::where('approved_by', $user->id)->with(['user', 'comments.user', 'banner'])->get();

        if (!$project) {
            return $this->sendError([], 'unable to load projects', 500);
        }

        return $this->sendSuccess($project, 'successful', 200);
    }

    // Force delete
    public function forceDelete(Request $request, $project)
    {
        $user = $request->user();

        if ($user->role != 'admin') {
            return $this->sendError([], 'you are unauthorize', 401);
        }

        Project::where('id', $project)->forceDelete();

        return $this->sendSuccess($project, 'project deleted from trash', 200);
    }

    // Restore deleted project
    public function restore(Request $request, $project)
    {
        $user = $request->user();

        if ($user->role != 'admin') {
            return $this->sendError([], 'you are unauthorize', 401);
        }

        Project::where('id', $project)->restore();

        return $this->sendSuccess($project, 'project restored successfully', 200);
    }

    // Get trashed project
    public function trash(Request $request)
    {

        $user = $request->user();

        if ($user->role != 'admin') {
            return $this->sendError([], 'you are unauthorize', 401);
        }

        $project = Project::onlyTrashed()->with(['user', 'comments.user', 'banner'])->get();

        if (!$project) {
            return $this->sendError([], 'unable to load trashed projects', 500);
        }

        return $this->sendSuccess($project, 'successful', 200);
    }

    public function search(Request $request)
    {
        $query = $request->input('query');

        if(!$query){
            return $this->sendError([], 'invalid search query', 400);
        }

        // return $query;
        $podcasts = Project::whereNotNull('approved_by')
            ->where('status', 'public')
            ->whereAny([
                'title',
                'slug',
                'category',
                'description',
                'tags',
                'created_at',
            ], 'like', '%' . $query . '%')
            ->with(['user', 'comments.user', 'banner'])
            ->latest()
            ->limit(20)
            ->get();

        if (!$podcasts) {
            return $this->sendError([], 'unable to load podcast', 500);
        }

        return $this->sendSuccess($podcasts, 'successful', 200);

    }


    public function allProjects()
    {
        $projects = Project::with(['user', 'comments.user', 'banner'])
            ->latest()->paginate();

            // return $projects;

        if (!$projects) {
            return $this->sendError([], 'unable to load projects', 500);
        }

        return $this->sendSuccess($projects, 'successful', 200);
    }


}
