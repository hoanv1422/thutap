<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PostController extends Controller
{
    
    public function index()
    {
        return Post::with('user')->latest()->get();
    }
    
    public function store(Request $request)
{
    try {
        $request->validate([
            'title' => 'required|max:255',
            'content' => 'required'
        ]);

        return Post::create([
            'user_id' => auth()->id(),
            'title' => $request->title,
            'content' => $request->content
        ]);
    } catch (\Exception $e) {
        return response()->json(['message' => 'Lỗi tạo bài viết'], 500);
    }
}
public function show($id)
{
    try {
        $post = Post::with('user')->findOrFail($id); 
        return response()->json($post); 
    } catch (\Exception $e) {
        return response()->json(['message' => 'Bài viết không tồn tại'], 404);
    }
}
    
    public function update(Request $request, Post $post)
    {
        $this->authorize('update', $post);
    
        $request->validate([
            'title' => 'required|max:255',
            'content' => 'required'
        ]);
    
        $post->update($request->all());
        return $post;
    }
    
    public function destroy(Post $post)
    {
        $this->authorize('delete', $post);
        $post->delete();
        return response()->noContent();
    }
}
