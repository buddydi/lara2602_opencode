<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>编辑分类</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 800px; margin: 0 auto; padding: 20px; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input[type="text"], textarea, select { width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px; }
        .btn { padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer; }
        .btn-primary { background-color: #007bff; color: white; }
        .btn-secondary { background-color: #6c757d; color: white; }
        .error { color: #dc3545; font-size: 14px; }
    </style>
</head>
<body>
    <h1>编辑分类</h1>
    
    <form action="{{ route('categories.update', $category) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="form-group">
            <label for="name">分类名称</label>
            <input type="text" name="name" id="name" value="{{ old('name', $category->name) }}">
            @error('name')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>
        
        <div class="form-group">
            <label for="parent_id">父分类</label>
            <select name="parent_id" id="parent_id">
                <option value="">-- 无（顶级分类） --</option>
                @foreach($categories as $cat)
                    @if($cat->id !== $category->id)
                        <option value="{{ $cat->id }}" {{ $category->parent_id == $cat->id ? 'selected' : '' }}>
                            {{ $cat->name }}
                        </option>
                    @endif
                @endforeach
            </select>
            @error('parent_id')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>
        
        <div class="form-group">
            <label for="order">排序</label>
            <input type="number" name="order" id="order" value="{{ old('order', $category->order) }}">
            @error('order')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>
        
        <div class="form-group">
            <label for="description">描述</label>
            <textarea name="description" id="description">{{ old('description', $category->description) }}</textarea>
            @error('description')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>
        
        <button type="submit" class="btn btn-primary">更新</button>
        <a href="{{ route('categories.index') }}" class="btn btn-secondary">返回</a>
    </form>
</body>
</html>
