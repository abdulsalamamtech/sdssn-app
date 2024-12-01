<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StoreCommentRequest;
use App\Http\Requests\Api\UpdateCommentRequest;
use App\Models\Api\Comment;
use App\Models\Api\Project;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Project $project)
    {
        $comments = $project->comments;
        if (!$comments) {
            return $this->sendError([], 'unable to load comments', 404);
        }

        $comments->load(['user']);


        return $this->sendSuccess($comments, 'successful', 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store( StoreCommentRequest $request, Project $project)
    {
        $user = $request->user();
        $data = $request->validated();
        $data['user_id'] = $user->id;
        $data['project_id'] = $project->id;

        $comment = $project->comments()->create($data);
        $comment->load(['user']);

        if (!$comment) {
            return $this->sendError([], 'unable to create comment', 500);
        }


        return $this->sendSuccess($comment, 'comment created', 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Project $project, Comment $comment)
    {
        $comment = Comment::where('project_id', $project->id)->find($comment->id);

        if (!$comment) {
            return $this->sendError([], 'comment not found', 404);
        }

        $comment->load(['user']);


        return $this->sendSuccess($comment, 'successful', 201);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update( UpdateCommentRequest $request, Project $project, Comment $comment)
    {

        $user = $request->user();
        $data = $request->validated();

        $comment->where('project_id', $project->id)
                ->where('user_id', $user->id);
        $comment->content = $data['content'];
        $comment->save();

        $comment->load(['user']);

        if (!$comment) {
            return $this->sendError([], 'unable to update comment', 500);
        }

        return $this->sendSuccess($comment, 'comment updated', 201);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
