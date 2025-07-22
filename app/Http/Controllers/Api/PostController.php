<?php

namespace App\Http\Controllers\Api;

use App\Models\Post;
use Illuminate\Http\Request;
use App\Traits\ApiResponseTrait;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StorePostRequest;
use App\Http\Resources\PostResource;

class PostController extends Controller
{

    use ApiResponseTrait;

    /**
     * Display a listing of the resource.
     */
    public function index(request $request)
    {
        //
        $query = Post::with('user');
        if ($request->has('category')) {
            $query->where('category', $request->category);}

             if ($request->has('author')) {
        $query->where('user_id', $request->author);
    }

    if ($request->has('from')) {
        $query->whereDate('created_at', '>=', $request->from);
    }

    if ($request->has('to')) {
        $query->whereDate('created_at', '<=', $request->to);
    }


        $posts = $query->latest()->paginate(10);
        return $this->success(PostResource::collection($posts), 'Posts fetched successfully', 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePostRequest $request)
    {
        //
        $post = Post::create([
            'title' => $request->title,
            'content'  => $request->content,
            'category' => $request->category,
            'user_id' => Auth::user()->id,
        ]);

        return $this->success(new PostResource($post), 'Post created successfully', 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //

         $post = Post::with('user')->find($id);

    if (!$post) {
        return $this->error('Post not found', 404);
    }

    return $this->success(
        new PostResource($post),'Post fetched successfully',200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
