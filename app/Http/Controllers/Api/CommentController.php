<?php

namespace App\Http\Controllers\Api;

use App\Models\Post;
use App\Models\Comment;
use Illuminate\Http\Request;
use App\Traits\ApiResponseTrait;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreCommentRequest;

class CommentController extends Controller
{
    //
    use ApiResponseTrait;

    public function store(StoreCommentRequest $request, $postId)
    {
        $post = Post::find($postId);

        if (!$post) {
            return $this->error('Post not found', 404);
        }

        $comment = Comment::create([
            'body'     => $request->body,
            'user_id'  => Auth::id(),
            'post_id'  => $post->id,
        ]);

        return $this->success($comment, 'Comment added successfully', 201);
    }

}
