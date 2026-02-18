<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>文章列表</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 1200px; margin: 0 auto; padding: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 12px; text-align: left; }
        th { background-color: #f4f4f4; }
        .btn { padding: 8px 16px; text-decoration: none; border-radius: 4px; margin-right: 5px; }
        .btn-primary { background-color: #007bff; color: white; }
        .btn-success { background-color: #28a745; color: white; }
        .btn-danger { background-color: #dc3545; color: white; }
        .btn-secondary { background-color: #6c757d; color: white; }
        .alert { padding: 10px; background-color: #d4edda; border: 1px solid #c3e6cb; margin-bottom: 20px; }
    </style>
</head>
<body>
    <h1>文章管理</h1>
    
    @if (session('success'))
        <div class="alert">{{ session('success') }}</div>
    @endif
    
    <a href="{{ route('posts.create') }}" class="btn btn-primary">新建文章</a>
    
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>标题</th>
                <th>作者</th>
                <th>状态</th>
                <th>发布时间</th>
                <th>操作</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($posts as $post)
                <tr>
                    <td>{{ $post->id }}</td>
                    <td>{{ $post->title }}</td>
                    <td>{{ $post->user->name }}</td>
                    <td>
                        @if ($post->status === 'published')
                            <span>已发布</span>
                        @elseif ($post->status === 'draft')
                            <span>草稿</span>
                        @else
                            <span>归档</span>
                        @endif
                    </td>
                    <td>{{ $post->published_at ? $post->published_at->format('Y-m-d H:i') : '-' }}</td>
                    <td>
                        <a href="{{ route('posts.show', $post) }}" class="btn btn-secondary">查看</a>
                        <a href="{{ route('posts.edit', $post) }}" class="btn btn-primary">编辑</a>
                        <form action="{{ route('posts.destroy', $post) }}" method="POST" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" onclick="return confirm('确定删除？')">删除</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="text-align: center;">暂无文章</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    
    {{ $posts->links() }}
</body>
</html>
