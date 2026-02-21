@extends('admin_layout')

@section('title', '权限管理')

@section('content')
    <h1>权限管理</h1>
    
    <a href="{{ route('permissions.create') }}" class="btn btn-primary">新建权限</a>
    
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>权限名称</th>
                <th>Guard</th>
                <th>创建时间</th>
                <th>操作</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($permissions as $permission)
                <tr>
                    <td>{{ $permission->id }}</td>
                    <td>{{ $permission->name }}</td>
                    <td>{{ $permission->guard_name }}</td>
                    <td>{{ $permission->created_at }}</td>
                    <td>
                        <a href="{{ route('permissions.edit', $permission) }}" class="btn btn-primary" style="padding: 5px 10px; font-size: 12px;">编辑</a>
                        <form action="{{ route('permissions.destroy', $permission) }}" method="POST" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" style="padding: 5px 10px; font-size: 12px;" onclick="return confirm('确定删除？')">删除</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" style="text-align: center;">暂无权限</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    
    {{ $permissions->links() }}
@endsection
