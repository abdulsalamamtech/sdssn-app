<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StorePodcastRequest;
use App\Http\Requests\Api\UpdatePodcastRequest;
use App\Models\Api\Podcast;
use App\Models\Assets;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PodcastController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $podcast = Podcast::with(['user', 'podcastComments.user', 'banner'])->get();

        if (!$podcast) {
            return $this->sendError([], 'unable to load podcast', 500);
        }

        return $this->sendSuccess($podcast, 'successful', 200);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePodcastRequest $request)
    {
        $data = $request->validated();
        $user = $request->user();

        // $upload =  $this->uploadImage($request, 'banner');
        $upload = $this->uploadToImageKit($request,'banner');

        // Add assets
        $banner = Assets::create($upload);
        $data['banner_id'] = $banner->id;
        $data['user_id'] = $user->id;

        // Generate slug
        $title = $data['title'];
        $slug = Str::slug($title);

        $slug_fund = Podcast::where('slug', $slug)->first();
        // $data['slug'] = $slug;
        // if($slug_fund){
        //     $data['slug'] = $slug.'-'.rand(100,999);
        // }
        ($slug_fund)
        ?$data['slug'] = $slug.'-'.rand(100,999)
        :$data['slug'] = $slug;


        // Add project
        $project = Podcast::create($data);
        $project->load(['user', 'podcastComments.user', 'banner']);


        if (!$project) {
            return $this->sendError([], 'unable to update podcast', 500);
        }

        return $this->sendSuccess($project, 'podcast created', 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Podcast $podcast)
    {
        $podcast->views++;
        $podcast->save();
        $podcast->load(['user', 'podcastComments.user', 'banner']);

        if (!$podcast) {
            return $this->sendError([], 'unable to load podcast', 500);
        }

        return $this->sendSuccess($podcast, 'successful', 200);

    }


    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePodcastRequest $request, Podcast $podcast)
    {
        $data = $request->validated();
        $user = $request->user();

        try {
            DB::beginTransaction();

            if($podcast->title != $data['title']){
                // Generate slug
                $title = $data['title'];
                $slug = Str::slug($title);

                $slug_fund = Podcast::where('slug', $slug)->first();
                // if($slug_fund){
                //     $data['slug'] = $slug.'-'.rand(100,999);
                // }else{
                //     $data['slug'] = $slug;
                // }
                ($slug_fund)
                ?$data['slug'] = $slug.'-'.rand(100,999)
                :$data['slug'] = $slug;
            }


            if ($user->id != $podcast->user_id) {
                return $this->sendError([], 'you are unauthorize', 401);
            }

            if($request->banner){

                // Delete the previously uploaded banner
                // Update the code to delete the previously uploaded banner
                $upload = $this->uploadToImageKit($request,'banner');

                // Add assets
                $banner = Assets::create($upload);
                $data['banner_id'] = $banner->id;

            }

            $podcast->update($data);
            $podcast->load(['user','podcastComments.user', 'banner']);

            if (!$podcast) {
                return $this->sendError([], 'unable to update podcast', 500);
            }

            return $this->sendSuccess($podcast, 'podcast updated', 200);

            DB::commit();
        } catch (\Exception $e) {
            // Handle transaction failure
            DB::rollBack();
        }

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Podcast $podcast)
    {
        //
    }

    // show user project
    public function personal(Request $request)
    {

        $user = $request->user();

        $project = Podcast::where('user_id', $user->id)->with(['user', 'podcastComments.user', 'banner'])->get();

        if (!$project) {
            return $this->sendError([], 'unable to load personal podcasts', 500);
        }

        return $this->sendSuccess($project, 'successful', 200);
    }

    // Like podcast
    public function like(Request $request, Podcast $podcast)
    {

        $podcast->likes++;
        $podcast->save();
        $podcast->load(['user', 'podcastComments.user', 'banner']);

        if (!$podcast) {
            return $this->sendError([], 'unable to load podcast', 500);
        }

        return $this->sendSuccess($podcast, 'successful', 200);

    }


    // Share project
    public function share(Podcast $podcast)
    {

        $podcast->shares++;
        $podcast->save();

        $podcast->load(['user', 'podcastComments.user', 'banner']);

        if (!$podcast) {
            return $this->sendError([], 'unable to load podcast', 500);
        }

        return $this->sendSuccess($podcast, 'successful', 200);

    }

}
