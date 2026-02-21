<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $post->title }}</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 800px; margin: 0 auto; padding: 20px; }
        .meta { color: #666; font-size: 14px; margin-bottom: 20px; }
        .content { line-height: 1.8; white-space: pre-wrap; }
        .btn { padding: 10px 20px; text-decoration: none; border-radius: 4px; display: inline-block; }
        .btn-primary { background-color: #007bff; color: white; border: none; cursor: pointer; }
        .btn-danger { background-color: #dc3545; color: white; border: none; cursor: pointer; }
        .btn-secondary { background-color: #6c757d; color: white; }
        .actions { margin-top: 20px; }
        .comment-form { margin-top: 40px; padding: 20px; background: #f8f9fa; border-radius: 8px; }
        .comment-form textarea { width: 100%; height: 100px; padding: 10px; border: 1px solid #ddd; border-radius: 4px; }
        .comment-list { margin-top: 40px; }
        .comment { padding: 15px; border-bottom: 1px solid #eee; }
        .comment-header { display: flex; justify-content: space-between; margin-bottom: 8px; }
        .comment-author { font-weight: bold; }
        .comment-date { color: #999; font-size: 12px; }
        .comment-content { line-height: 1.6; }
        .reply { margin-left: 30px; padding: 10px; background: #f8f9fa; border-radius: 4px; margin-top: 10px; }
        .error { color: #dc3545; font-size: 14px; }
        .alert { padding: 10px; background-color: #d4edda; border: 1px solid #c3e6cb; margin-bottom: 20px; }
        .login-hint { color: #666; margin-top: 20px; }
    </style>
</head>
<body>
    <h1>{{ $post->title }}</h1>
    
    @if($post->cover_image)
        <div style="margin-bottom: 20px;">
            <img src="{{ asset('storage/' . $post->cover_image) }}" alt="封面图" style="max-width: 100%; max-height: 400px;">
        </div>
    @endif
    
    <div class="meta">
        作者：{{ $post->user->name }} | 
        状态：{{ $post->status }} | 
        发布时间：{{ $post->published_at ? $post->published_at->format('Y-m-d H:i') : '-' }}
    </div>
    
    <div class="content">
{{ $post->content }}
    </div>
    
    <div class="actions">
        <a href="{{ route('posts.edit', $post) }}" class="btn btn-primary">编辑</a>
        <form action="{{ route('posts.destroy', $post) }}" method="POST" style="display: inline;">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger" onclick="return confirm('确定删除？')">删除</button>
        </form>
        <a href="{{ route('posts.index') }}" class="btn btn-secondary">返回列表</a>
    </div>

    <!-- 评论表单 -->
    <div class="comment-form">
        <h3>发表评论</h3>
        
        @if (session('success'))
            <div class="alert">{{ session('success') }}</div>
        @endif
        
        @auth
            <form action="{{ route('posts.comments.store', $post) }}" method="POST">
                @csrf
                <textarea name="content" placeholder="写下你的评论...">{{ old('content') }}</textarea>
                @error('content')
                    <div class="error">{{ $message }}</div>
                @enderror
                <button type="submit" class="btn btn-primary" style="margin-top: 10px;">提交评论</button>
            </form>
        @else
            <p class="login-hint"><a href="{{ route('login') }}">登录</a>后发表评论</p>
        @endauth
    </div>

    <!-- 评论列表 -->
    <div class="comment-list">
        <h3>评论 ({{ $post->comments->count() }})</h3>
        
        @forelse($post->comments as $comment)
            <div class="comment">
                <div class="comment-header">
                    <span class="comment-author">{{ $comment->user->name }}</span>
                    <span class="comment-date">{{ $comment->created_at->format('Y-m-d H:i') }}</span>
                </div>
                <div class="comment-content">{{ $comment->content }}</div>
                
                @auth
                    @if(auth()->id() === $comment->user_id)
                        <form action="{{ route('posts.comments.destroy', [$post, $comment]) }}" method="POST" style="margin-top: 10px;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" style="padding: 5px 10px; font-size: 12px;" onclick="return confirm('确定删除？')">删除</button>
                        </form>
                    @endif
                @endauth
            </div>
        @empty
            <p style="color: #999;">暂无评论，快来抢沙发吧！</p>
        @endforelse
    </div>
</body>
</html>
