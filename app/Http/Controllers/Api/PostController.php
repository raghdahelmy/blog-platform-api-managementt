<?php

namespace App\Http\Controllers\Api;

use App\Models\Post;
use Illuminate\Http\Request;
use App\Traits\ApiResponseTrait;
use App\Http\Controllers\Controller;
use App\Http\Resources\PostResource;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use Illuminate\Support\Facades\Cache;


class PostController extends Controller
{


    use ApiResponseTrait;

    /**
     * Display a listing of the resource.
     */
    public function index(request $request)
    {
        //
        $page = $request->get('page', 1);
        $search   = $request->get('search', '');
        $category = $request->get('category', '');
        $author   = $request->get('author', '');
        $from     = $request->get('from', '');
        $to       = $request->get('to', '');

        $cacheKey = "posts_page_{$page}_search_{$search}_category_{$category}_author_{$author}_from_{$from}_to_{$to}";
        $posts = Cache::remember($cacheKey, 60, function () use ($request) {

            $query = Post::with('user');
            if ($request->has('category')) {
                $query->where('category', $request->category);
            }

            if ($request->has('author')) {
                $query->where('user_id', $request->author);
            }

            if ($request->has('from')) {
                $query->whereDate('created_at', '>=', $request->from);
            }

            if ($request->has('to')) {
                $query->whereDate('created_at', '<=', $request->to);
            }

            if ($request->has('search')) {
                $query->where('title', 'like', '%' . $request->search . '%');
            }


            // $posts = $query->latest()->paginate(10);
            return $query->latest()->paginate(10);
        });
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
            new PostResource($post),
            'Post fetched successfully',
            200
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePostRequest $request, $id)
    {
        //
        $post = Post::find($id);

        if (!$post) {
            return $this->error('Post not found', 404);
        }

        /** @var \App\Models\User $user */

        $user = Auth::user();
        if (
            $user->hasRole('admin') ||
            ($user->hasRole('author') && $user->id === $post->user_id)
        ) {
            $post->update([
                'title'    => $request->title,
                'content'  => $request->content,
                'category' => $request->category,
            ]);

            return $this->success(
                new PostResource($post),
                'Post updated successfully',
                200
            );
        }

        return $this->error('You are not allowed to update this post', 403);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //

        $post = Post::find($id);

        if (!$post) {
            return $this->error('Post not found', 404);
        }


        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (
            $user->hasRole('admin') ||
            ($user->hasRole('author') && $user->id === $post->user_id)
        ) {
            $post->delete();

            return $this->success(null, 'Post deleted successfully', 200);
        }

        return $this->error('You are not allowed to delete this post', 403);
    }
}
