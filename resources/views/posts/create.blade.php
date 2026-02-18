<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>新建文章</title>
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
    <h1>新建文章</h1>
    
    <form action="{{ route('posts.store') }}" method="POST">
        @csrf
        
        <div class="form-group">
            <label for="title">标题</label>
            <input type="text" name="title" id="title" value="{{ old('title') }}">
            @error('title')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>
        
        <div class="form-group">
            <label for="content">内容</label>
            <textarea name="content" id="content">{{ old('content') }}</textarea>
            @error('content')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>
        
        <div class="form-group">
            <label for="status">状态</label>
            <select name="status" id="status">
                <option value="draft">草稿</option>
                <option value="published">已发布</option>
                <option value="archived">归档</option>
            </select>
            @error('status')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>
        
        <button type="submit" class="btn btn-primary">创建</button>
        <a href="{{ route('posts.index') }}" class="btn btn-secondary">返回</a>
    </form>
</body>
</html>
