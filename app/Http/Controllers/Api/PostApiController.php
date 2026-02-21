<?php

namespace App\Http\Controllers\Api;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class PostApiController extends BaseApiController
{
    public function index(Request $request)
    {
        $posts = Post::with('user')
            ->when($request->status, function ($query) use ($request) {
                $query->where('status', $request->status);
            })
            ->when($request->category_id, function ($query) use ($request) {
                $query->where('category_id', $request->category_id);
            })
            ->when($request->keyword, function ($query) use ($request) {
                $query->where('title', 'like', "%{$request->keyword}%");
            })
            ->latest();

        $perPage = $request->per_page ?? 15;
        $paginated = $posts->paginate($perPage);

        return $this->success([
            'data' => $paginated->items(),
            'meta' => [
                'current_page' => $paginated->currentPage(),
                'per_page' => $paginated->perPage(),
                'total' => $paginated->total(),
                'last_page' => $paginated->lastPage(),
            ],
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|max:255',
            'content' => 'required',
            'category_id' => 'nullable|exists:categories,id',
            'cover_image' => 'nullable|image|max:2048',
            'status' => 'required|in:draft,published,archived',
        ]);

        $validated['slug'] = Str::slug($validated['title']);
        $validated['user_id'] = $request->user()->id;

        if ($request->hasFile('cover_image')) {
            $validated['cover_image'] = $this->uploadImage($request->file('cover_image'));
        }

        if ($validated['status'] === 'published') {
            $validated['published_at'] = now();
        }

        $post = Post::create($validated);

        return $this->success($post->load('user'), '文章创建成功', 201);
    }

    public function show(Post $post)
    {
        return $this->success($post->load(['user', 'comments', 'category']));
    }

    public function update(Request $request, Post $post)
    {
        $validated = $request->validate([
            'title' => 'sometimes|max:255',
            'content' => 'sometimes',
            'category_id' => 'nullable|exists:categories,id',
            'cover_image' => 'nullable|image|max:2048',
            'status' => 'sometimes|in:draft,published,archived',
        ]);

        if (isset($validated['title'])) {
            $validated['slug'] = Str::slug($validated['title']);
        }

        if ($request->hasFile('cover_image')) {
            if ($post->cover_image) {
                Storage::disk('public')->delete($post->cover_image);
            }
            $validated['cover_image'] = $this->uploadImage($request->file('cover_image'));
        }

        if (isset($validated['status']) && $validated['status'] === 'published' && !$post->published_at) {
            $validated['published_at'] = now();
        }

        $post->update($validated);

        return $this->success($post->load('user'), '文章更新成功');
    }

    public function destroy(Post $post)
    {
        if ($post->cover_image) {
            Storage::disk('public')->delete($post->cover_image);
        }
        $post->delete();

        return $this->success(null, '文章删除成功');
    }

    public function myPosts(Request $request)
    {
        $posts = $request->user()->posts()
            ->when($request->status, function ($query) use ($request) {
                $query->where('status', $request->status);
            })
            ->latest();

        $perPage = $request->per_page ?? 15;
        $paginated = $posts->paginate($perPage);

        return $this->success([
            'data' => $paginated->items(),
            'meta' => [
                'current_page' => $paginated->currentPage(),
                'per_page' => $paginated->perPage(),
                'total' => $paginated->total(),
                'last_page' => $paginated->lastPage(),
            ],
        ]);
    }

    protected function uploadImage($image)
    {
        return $image->store('covers', 'public');
    }
}
