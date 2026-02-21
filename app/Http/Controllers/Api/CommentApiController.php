<?php

namespace App\Http\Controllers\Api;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;

class CommentApiController extends BaseApiController
{
    public function index(Request $request)
    {
        $comments = Comment::with(['user', 'parent', 'replies'])
            ->when($request->post_id, function ($query) use ($request) {
                $query->where('post_id', $request->post_id);
            })
            ->when($request->is_approved !== null, function ($query) use ($request) {
                $query->where('is_approved', $request->boolean('is_approved'));
            })
            ->whereNull('parent_id')
            ->latest();

        $perPage = $request->per_page ?? 15;
        $paginated = $comments->paginate($perPage);

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
            'post_id' => 'required|exists:posts,id',
            'content' => 'required',
            'parent_id' => 'nullable|exists:comments,id',
        ]);

        $post = Post::findOrFail($validated['post_id']);

        if ($post->comments()->where('user_id', $request->user()->id)->exists()) {
            return $this->error('您已经评论过这篇文章', 422);
        }

        $validated['user_id'] = $request->user()->id;
        $validated['is_approved'] = true;

        $comment = Comment::create($validated);

        return $this->success($comment->load(['user', 'parent']), '评论创建成功', 201);
    }

    public function show(Comment $comment)
    {
        return $this->success($comment->load(['user', 'parent', 'replies.user', 'post']));
    }

    public function update(Request $request, Comment $comment)
    {
        if ($comment->user_id !== $request->user()->id) {
            return $this->error('无权限修改此评论', 403);
        }

        $validated = $request->validate([
            'content' => 'required',
        ]);

        $comment->update($validated);

        return $this->success($comment->load('user'), '评论更新成功');
    }

    public function destroy(Request $request, Comment $comment)
    {
        if ($comment->user_id !== $request->user()->id) {
            return $this->error('无权限删除此评论', 403);
        }

        $comment->delete();

        return $this->success(null, '评论删除成功');
    }

    public function myComments(Request $request)
    {
        $comments = $request->user()->comments()
            ->with(['post'])
            ->latest();

        $perPage = $request->per_page ?? 15;
        $paginated = $comments->paginate($perPage);

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

    public function approve(Request $request, Comment $comment)
    {
        $comment->update(['is_approved' => true]);
        return $this->success($comment, '评论已审核通过');
    }

    public function reject(Request $request, Comment $comment)
    {
        $comment->update(['is_approved' => false]);
        return $this->success($comment, '评论已拒绝');
    }
}
