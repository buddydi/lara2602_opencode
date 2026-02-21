@extends('admin_layout')

@section('title', '用户权限管理')

@section('styles')
    <script>
        function toggleAll(groupName, checked) {
            document.querySelectorAll('input[name="permissions[' + groupName + '][]"]').forEach(function(checkbox) {
                checkbox.checked = checked;
            });
        }
        function invertAll(groupName) {
            document.querySelectorAll('input[name="permissions[' + groupName + '][]"]').forEach(function(checkbox) {
                checkbox.checked = !checkbox.checked;
            });
        }
    </script>
@endsection

@section('content')
    <h1>用户权限管理</h1>
    
    <form action="{{ route('users.update', $user) }}" method="POST">
        @csrf
        @method('PUT')
        
        <h3>基本信息</h3>
        <div class="form-group">
            <label for="name">用户名</label>
            <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}">
            @error('name')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>
        
        <div class="form-group">
            <label for="email">邮箱</label>
            <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}">
            @error('email')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>
        
        <div class="form-group">
            <label for="password">新密码（留空则不修改）</label>
            <input type="password" name="password" id="password">
        </div>
        
        <div class="form-group">
            <label class="section-title">分配角色</label>
            <div>
                @forelse($roles as $role)
                    <div class="checkbox-item" style="display: inline-block; margin-right: 15px;">
                        <input type="checkbox" name="roles[]" value="{{ $role->name }}" id="role_{{ $role->id }}" 
                            {{ $user->hasRole($role->name) ? 'checked' : '' }}>
                        <label for="role_{{ $role->id }}">{{ $role->name }}</label>
                    </div>
                @empty
                    <p>暂无角色</p>
                @endforelse
            </div>
        </div>
        
        <div class="form-group">
            <label class="section-title">分配权限</label>
            
            @forelse($permissions as $groupName => $groupPermissions)
                <div class="permission-group">
                    <h4>
                        {{ $groupName }}
                        <span style="float: right;">
                            <button type="button" class="btn btn-outline" style="padding: 3px 8px; font-size: 12px;" onclick="toggleAll('{{ $groupName }}', true)">全选</button>
                            <button type="button" class="btn btn-outline" style="padding: 3px 8px; font-size: 12px;" onclick="invertAll('{{ $groupName }}')">反选</button>
                        </span>
                    </h4>
                    <div class="checkbox-grid">
                        @foreach($groupPermissions as $permission)
                            <div class="checkbox-item">
                                <input type="checkbox" name="permissions[{{ $groupName }}][]" value="{{ $permission->name }}" id="perm_{{ $permission->id }}" 
                                    {{ $user->hasPermissionTo($permission->name) ? 'checked' : '' }}>
                                <label for="perm_{{ $permission->id }}">{{ $permission->name }}</label>
                            </div>
                        @endforeach
                    </div>
                </div>
            @empty
                <p>暂无权限</p>
            @endforelse
        </div>
        
        <button type="submit" class="btn btn-primary">保存</button>
        <a href="{{ route('users.index') }}" class="btn btn-secondary">返回</a>
    </form>
@endsection
