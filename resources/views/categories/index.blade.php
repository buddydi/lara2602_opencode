<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>分类管理</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 1200px; margin: 0 auto; padding: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 12px; text-align: left; }
        th { background-color: #f4f4f4; }
        .btn { padding: 8px 16px; text-decoration: none; border-radius: 4px; display: inline-block; }
        .btn-primary { background-color: #007bff; color: white; }
        .btn-success { background-color: #28a745; color: white; }
        .btn-danger { background-color: #dc3545; color: white; border: none; cursor: pointer; }
        .btn-secondary { background-color: #6c757d; color: white; }
        .alert { padding: 10px; background-color: #d4edda; border: 1px solid #c3e6cb; margin-bottom: 20px; }
        .level-1 { padding-left: 20px; }
        .level-2 { padding-left: 40px; }
        .level-3 { padding-left: 60px; }
    </style>
</head>
<body>
    <h1>分类管理</h1>
    
    @if (session('success'))
        <div class="alert">{{ session('success') }}</div>
    @endif
    
    <a href="{{ route('categories.create') }}" class="btn btn-primary">新建分类</a>
    
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>名称</th>
                <th>Slug</th>
                <th>父分类</th>
                <th>排序</th>
                <th>操作</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($categories as $category)
                <tr>
                    <td>{{ $category->id }}</td>
                    <td>{{ $category->name }}</td>
                    <td>{{ $category->slug }}</td>
                    <td>{{ $category->parent ? $category->parent->name : '-' }}</td>
                    <td>{{ $category->order }}</td>
                    <td>
                        <a href="{{ route('categories.show', $category) }}" class="btn btn-secondary">查看</a>
                        <a href="{{ route('categories.edit', $category) }}" class="btn btn-primary">编辑</a>
                        <form action="{{ route('categories.destroy', $category) }}" method="POST" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" onclick="return confirm('确定删除？')">删除</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="text-align: center;">暂无分类</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    
    {{ $categories->links() }}
</body>
</html>
