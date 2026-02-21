@extends('admin_layout')

@section('title', '分类管理')

@section('content')
    <h1>分类管理</h1>
    
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
                        <a href="{{ route('categories.show', $category) }}" class="btn btn-secondary" style="padding: 5px 10px; font-size: 12px;">查看</a>
                        <a href="{{ route('categories.edit', $category) }}" class="btn btn-primary" style="padding: 5px 10px; font-size: 12px;">编辑</a>
                        <form action="{{ route('categories.destroy', $category) }}" method="POST" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" style="padding: 5px 10px; font-size: 12px;" onclick="return confirm('确定删除？')">删除</button>
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
@endsection
