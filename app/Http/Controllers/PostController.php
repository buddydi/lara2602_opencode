<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::with('user')->latest()->paginate(10);
        return view('posts.index', compact('posts'));
    }

    public function create()
    {
        return view('posts.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|max:255',
            'content' => 'required',
            'cover_image' => 'nullable|image|max:2048',
            'status' => 'required|in:draft,published,archived',
        ]);

        $validated['slug'] = Str::slug($validated['title']);
        $validated['user_id'] = auth()->id();

        if ($request->hasFile('cover_image')) {
            $validated['cover_image'] = $this->uploadImage($request->file('cover_image'));
        }

        if ($validated['status'] === 'published') {
            $validated['published_at'] = now();
        }

        Post::create($validated);

        return redirect()->route('posts.index')->with('success', '文章创建成功');
    }

    public function show(Post $post)
    {
        return view('posts.show', compact('post'));
    }

    public function edit(Post $post)
    {
        return view('posts.edit', compact('post'));
    }

    public function update(Request $request, Post $post)
    {
        $validated = $request->validate([
            'title' => 'required|max:255',
            'content' => 'required',
            'cover_image' => 'nullable|image|max:2048',
            'status' => 'required|in:draft,published,archived',
        ]);

        $validated['slug'] = Str::slug($validated['title']);

        if ($request->hasFile('cover_image')) {
            if ($post->cover_image) {
                Storage::delete($post->cover_image);
            }
            $validated['cover_image'] = $this->uploadImage($request->file('cover_image'));
        }

        if ($validated['status'] === 'published' && !$post->published_at) {
            $validated['published_at'] = now();
        }

        $post->update($validated);

        return redirect()->route('posts.index')->with('success', '文章更新成功');
    }

    public function destroy(Post $post)
    {
        if ($post->cover_image) {
            Storage::delete($post->cover_image);
        }
        $post->delete();
        return redirect()->route('posts.index')->with('success', '文章删除成功');
    }

    protected function uploadImage($image)
    {
        $path = $image->store('covers', 'public');
        return $path;
    }
}
