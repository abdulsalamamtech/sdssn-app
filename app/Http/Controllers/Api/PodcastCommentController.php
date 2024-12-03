<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StoreCommentRequest;
use App\Http\Requests\Api\StorePodcastCommentRequest;
use App\Http\Requests\Api\UpdateCommentRequest;
use App\Http\Requests\Api\UpdatePodcastCommentRequest;
use App\Models\Api\Podcast;
use App\Models\Api\PodcastComment;
use Illuminate\Http\Request;

class PodcastCommentController extends Controller
{
      /**
     * Display a listing of the resource.
     */
    public function index(Podcast $podcast)
    {
        $podcast_comments = $podcast->podcastComments;
        $podcast_comments->load(['user']);

        if (!$podcast_comments) {
            return $this->sendError([], 'unable to load podcast comments', 500);
        }

        return $this->sendSuccess($podcast_comments, 'successful', 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store( StoreCommentRequest $request, Podcast $podcast)
    {
        $user = $request->user();
        $data = $request->validated();
        $data['user_id'] = $user->id;
        $data['podcast_id'] = $podcast->id;

        $podcast_comment = $podcast->podcastComments()->create($data);
        $podcast_comment->load(['user']);

        if (!$podcast_comment) {
            return $this->sendError([], 'unable to create comment', 500);
        }

        return $this->sendSuccess($podcast_comment, 'podcast comment created', 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Podcast $podcast, PodcastComment $comment)
    {


        $podcast_comment = PodcastComment::where('podcast_id', $podcast->id)->find($comment->id);

        if (!$podcast_comment) {
            return $this->sendError([], 'podcast comment not found', 404);
        }

        $podcast_comment->load(['user']);


        return $this->sendSuccess($podcast_comment, 'successful', 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update( UpdateCommentRequest $request, Podcast $Podcast, PodcastComment $comment)
    {



        $user = $request->user();
        $data = $request->validated();

        $comment->where('podcast_id', $Podcast->id)
                ->where('user_id', $user->id);

        $comment->content = $data['content'];
        $comment->save();

        $comment->load(['user']);

        if (!$comment) {
            return $this->sendError([], 'unable to update podcast comment', 500);
        }

        return $this->sendSuccess($comment, 'podcast comment updated', 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
