<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $category->name }}</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 800px; margin: 0 auto; padding: 20px; }
        .meta { color: #666; font-size: 14px; margin-bottom: 20px; }
        .content { line-height: 1.8; }
        .btn { padding: 10px 20px; text-decoration: none; border-radius: 4px; display: inline-block; }
        .btn-primary { background-color: #007bff; color: white; }
        .btn-danger { background-color: #dc3545; color: white; border: none; cursor: pointer; }
        .btn-secondary { background-color: #6c757d; color: white; }
    </style>
</head>
<body>
    <h1>{{ $category->name }}</h1>
    
    <div class="meta">
        父分类：{{ $category->parent ? $category->parent->name : '顶级分类' }} | 
        排序：{{ $category->order }} | 
        Slug：{{ $category->slug }}
    </div>
    
    <div class="content">
        <p>{{ $category->description ?: '暂无描述' }}</p>
    </div>
    
    <div style="margin-top: 30px;">
        <a href="{{ route('categories.edit', $category) }}" class="btn btn-primary">编辑</a>
        <form action="{{ route('categories.destroy', $category) }}" method="POST" style="display: inline;">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger" onclick="return confirm('确定删除？')">删除</button>
        </form>
        <a href="{{ route('categories.index') }}" class="btn btn-secondary">返回列表</a>
    </div>
</body>
</html>
