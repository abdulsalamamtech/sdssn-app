<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\GalleryRequest;
use App\Models\Assets;
use App\Models\Gallery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class GalleryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $galleries = Gallery::with(['banner'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        if (!$galleries) {
            return $this->sendError([], 'unable to load galleries', 500);
        }

        return $this->sendSuccess($galleries, 'successful', 200);        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(GalleryRequest $request)
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

        $slug_fund = Gallery::where('slug', $slug)->first();
        // $data['slug'] = $slug;
        // if($slug_fund){
        //     $data['slug'] = $slug.'-'.rand(100,999);
        // }
        ($slug_fund)
        ?$data['slug'] = $slug.'-'.rand(100,999)
        :$data['slug'] = $slug;


        // Add project
        $project = Gallery::create($data);
        $project->load(['user', 'banner']);


        if (!$project) {
            return $this->sendError([], 'unable to update gallery', 500);
        }

        return $this->sendSuccess($project, 'gallery created', 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Gallery $gallery)
    {
        $gallery->load([ 'banner']);

        if (!$gallery) {
            return $this->sendError([], 'unable to load gallery', 500);
        }

        return $this->sendSuccess($gallery, 'successful', 200);    
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(GalleryRequest  $request, Gallery $gallery)
    {
        $data = $request->validated();
        $user = $request->user();

        try {
            DB::beginTransaction();

            if($gallery->title != $data['title']){
                // Generate slug
                $title = $data['title'];
                $slug = Str::slug($title);

                $slug_fund = Gallery::where('slug', $slug)->first();
                // if($slug_fund){
                //     $data['slug'] = $slug.'-'.rand(100,999);
                // }else{
                //     $data['slug'] = $slug;
                // }
                ($slug_fund)
                ?$data['slug'] = $slug.'-'.rand(100,999)
                :$data['slug'] = $slug;
            }


            if ($user->id != $gallery->user_id) {
                return $this->sendError([], 'you are unauthorize', 401);
            }

            if($request->banner){

                // Delete the previously uploaded banner
                // Update the code to delete the previously uploaded banner
                $upload = $this->uploadToImageKit($request,'banner');

                // Add assets
                $banner = Assets::create($upload);
                $data['banner_id'] = $banner->id;

                // Delete previously uploaded file
                $fileId = $gallery->banner->file_id;
                $previousFile = $this->deleteImageKitFile($fileId);
                Assets::where('file_id', $fileId)->delete();

            }

            $gallery->update($data);
            $gallery->load(['user', 'banner']);

            if (!$gallery) {
                return $this->sendError([], 'unable to update gallery', 500);
            }

            return $this->sendSuccess($gallery, 'gallery updated', 200);

            DB::commit();
        } catch (\Exception $e) {
            // Handle transaction failure
            DB::rollBack();
        }

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Gallery $gallery)
    {
        //
    }
}
