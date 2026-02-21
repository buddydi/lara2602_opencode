@extends('admin_layout')

@section('title', '用户管理')

@section('content')
    <h1>用户管理</h1>
    
    <a href="{{ route('users.create') }}" class="btn btn-primary">创建用户</a>
    
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>用户名</th>
                <th>邮箱</th>
                <th>角色</th>
                <th>操作</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($users as $user)
                <tr>
                    <td>{{ $user->id }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>
                        @forelse($user->roles as $role)
                            <span class="badge">{{ $role->name }}</span>
                        @empty
                            <span>-</span>
                        @endforelse
                    </td>
                    <td>
                        <a href="{{ route('users.edit', $user) }}" class="btn btn-primary" style="padding: 5px 10px; font-size: 12px;">编辑</a>
                        @if($user->id !== 1)
                            <form action="{{ route('users.destroy', $user) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" style="padding: 5px 10px; font-size: 12px;" onclick="return confirm('确定删除？')">删除</button>
                            </form>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" style="text-align: center;">暂无用户</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    
    {{ $users->links() }}
@endsection
