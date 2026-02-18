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
        .btn { padding: 10px 20px; text-decoration: none; border-radius: 4px; }
        .btn-primary { background-color: #007bff; color: white; }
        .btn-danger { background-color: #dc3545; color: white; }
        .btn-secondary { background-color: #6c757d; color: white; }
    </style>
</head>
<body>
    <h1>{{ $post->title }}</h1>
    
    <div class="meta">
        作者：{{ $post->user->name }} | 
        状态：{{ $post->status }} | 
        发布时间：{{ $post->published_at ? $post->published_at->format('Y-m-d H:i') : '-' }}
    </div>
    
    <div class="content">
{{ $post->content }}
    </div>
    
    <div style="margin-top: 30px;">
        <a href="{{ route('posts.edit', $post) }}" class="btn btn-primary">编辑</a>
        <form action="{{ route('posts.destroy', $post) }}" method="POST" style="display: inline;">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger" onclick="return confirm('确定删除？')">删除</button>
        </form>
        <a href="{{ route('posts.index') }}" class="btn btn-secondary">返回列表</a>
    </div>
</body>
</html>
