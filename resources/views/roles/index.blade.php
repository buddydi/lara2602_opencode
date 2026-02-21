@extends('admin_layout')

@section('title', '角色管理')

@section('content')
    <h1>角色管理</h1>
    
    <a href="{{ route('roles.create') }}" class="btn btn-primary">新建角色</a>
    
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>角色名称</th>
                <th>权限</th>
                <th>操作</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($roles as $role)
                <tr>
                    <td>{{ $role->id }}</td>
                    <td>{{ $role->name }}</td>
                    <td>
                        @forelse($role->permissions as $permission)
                            <span class="badge">{{ $permission->name }}</span>
                        @empty
                            <span>-</span>
                        @endforelse
                    </td>
                    <td>
                        <a href="{{ route('roles.show', $role) }}" class="btn btn-secondary" style="padding: 5px 10px; font-size: 12px;">查看</a>
                        <a href="{{ route('roles.edit', $role) }}" class="btn btn-primary" style="padding: 5px 10px; font-size: 12px;">编辑</a>
                        @if($role->name !== 'admin')
                            <form action="{{ route('roles.destroy', $role) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" style="padding: 5px 10px; font-size: 12px;" onclick="return confirm('确定删除？')">删除</button>
                            </form>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" style="text-align: center;">暂无角色</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    
    {{ $roles->links() }}
@endsection
