@extends('admin_layout')

@section('title', '文章管理')

@section('content')
    <h1>文章管理</h1>
    
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
                        <a href="{{ route('posts.show', $post) }}" class="btn btn-secondary" style="padding: 5px 10px; font-size: 12px;">查看</a>
                        <a href="{{ route('posts.edit', $post) }}" class="btn btn-primary" style="padding: 5px 10px; font-size: 12px;">编辑</a>
                        <form action="{{ route('posts.destroy', $post) }}" method="POST" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" style="padding: 5px 10px; font-size: 12px;" onclick="return confirm('确定删除？')">删除</button>
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
@endsection
