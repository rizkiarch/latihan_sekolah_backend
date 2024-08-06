<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Gate;

class PostController extends Controller implements HasMiddleware
{
    public static function middleware()
    {
        return [
            new Middleware('auth:sanctum', except: ['index', 'show'])
        ];
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Post::with("user")->latest()->get();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data =  $request->validate([
            'title' => 'required|max:255',
            'body' => 'required'
        ]);

        $post = $request->user()->posts()->create($data);

        return response()->json([
            'status' => 200,
            'message' => 'Data Berhasil Di Tambahkan',
            'result' => 'ok',
            'post' => $post,
            'user' => $post->user
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        return response()->json([
            'status' => 200,
            'result' => 'ok',
            'post' => $post,
            'user' => $post->user
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $post)
    {
        Gate::authorize('modify', $post);
        $data =  $request->validate([
            'title' => 'required|max:255',
            'body' => 'required'
        ]);

        $post->update($data);

        return response()->json([
            'status' => 200,
            'message' => 'Data Berhasil Di Perbarui',
            'result' => 'ok',
            'post' => $post,
            'user' => $post->user
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        Gate::authorize('modify', $post);
        $post->delete();

        return response()->json([
            'status' => 200,
            'message' => 'Data Berhasil Di hapus',
            'result' => 'ok',
        ]);
    }
}
