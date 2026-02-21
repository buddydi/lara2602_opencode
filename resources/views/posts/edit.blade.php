<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>编辑文章</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 800px; margin: 0 auto; padding: 20px; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input[type="text"], textarea, select { width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px; }
        textarea { height: 200px; }
        .btn { padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer; }
        .btn-primary { background-color: #007bff; color: white; }
        .btn-secondary { background-color: #6c757d; color: white; }
        .error { color: #dc3545; font-size: 14px; }
    </style>
</head>
<body>
    <h1>编辑文章</h1>
    
    <form action="{{ route('posts.update', $post) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
        <div class="form-group">
            <label for="title">标题</label>
            <input type="text" name="title" id="title" value="{{ old('title', $post->title) }}">
            @error('title')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>
        
        <div class="form-group">
            <label for="cover_image">封面图片</label>
            @if($post->cover_image)
                <div style="margin-bottom: 10px;">
                    <img src="{{ asset('storage/' . $post->cover_image) }}" alt="封面图" style="max-width: 200px; max-height: 200px;">
                </div>
            @endif
            <input type="file" name="cover_image" id="cover_image" accept="image/*">
            @error('cover_image')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>
        
        <div class="form-group">
            <label for="content">内容</label>
            <textarea name="content" id="content">{{ old('content', $post->content) }}</textarea>
            @error('content')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>
        
        <div class="form-group">
            <label for="status">状态</label>
            <select name="status" id="status">
                <option value="draft" {{ $post->status === 'draft' ? 'selected' : '' }}>草稿</option>
                <option value="published" {{ $post->status === 'published' ? 'selected' : '' }}>已发布</option>
                <option value="archived" {{ $post->status === 'archived' ? 'selected' : '' }}>归档</option>
            </select>
            @error('status')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>
        
        <button type="submit" class="btn btn-primary">更新</button>
        <a href="{{ route('posts.index') }}" class="btn btn-secondary">返回</a>
    </form>
</body>
</html>
