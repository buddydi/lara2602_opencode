<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function store(Request $request, Post $post)
    {
        $validated = $request->validate([
            'content' => 'required|min:2|max:1000',
            'parent_id' => 'nullable|exists:comments,id',
        ]);

        $comment = new Comment($validated);
        $comment->post_id = $post->id;
        $comment->user_id = Auth::id();
        $comment->is_approved = true;
        $comment->save();

        return redirect()->route('posts.show', $post)->with('success', '评论已发布');
    }

    public function destroy(Post $post, Comment $comment)
    {
        if ($comment->post_id !== $post->id) {
            abort(404);
        }

        $comment->delete();

        return redirect()->route('posts.show', $post)->with('success', '评论已删除');
    }
}
