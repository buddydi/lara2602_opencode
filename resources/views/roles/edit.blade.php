@extends('admin_layout')

@section('title', '编辑角色')

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
    <h1>编辑角色</h1>
    
    <form action="{{ route('roles.update', $role) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="form-group">
            <label for="name">角色名称</label>
            <input type="text" name="name" id="name" value="{{ old('name', $role->name) }}">
            @error('name')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>
        
        <div class="form-group">
            <label class="section-title">分配权限</label>
            
            @forelse($permissions as $groupName => $groupPermissions)
                <div class="permission-group">
                    <h3>
                        {{ $groupName }}
                        <span style="float: right;">
                            <button type="button" class="btn btn-outline" style="padding: 3px 8px; font-size: 12px;" onclick="toggleAll('{{ $groupName }}', true)">全选</button>
                            <button type="button" class="btn btn-outline" style="padding: 3px 8px; font-size: 12px;" onclick="invertAll('{{ $groupName }}')">反选</button>
                        </span>
                    </h3>
                    <div class="checkbox-grid">
                        @foreach($groupPermissions as $permission)
                            <div class="checkbox-item">
                                <input type="checkbox" name="permissions[{{ $groupName }}][]" value="{{ $permission->name }}" id="perm_{{ $permission->id }}" 
                                    {{ $role->hasPermissionTo($permission->name) ? 'checked' : '' }}>
                                <label for="perm_{{ $permission->id }}">{{ $permission->name }}</label>
                            </div>
                        @endforeach
                    </div>
                </div>
            @empty
                <p>暂无权限</p>
            @endforelse
        </div>
        
        <button type="submit" class="btn btn-primary">更新</button>
        <a href="{{ route('roles.index') }}" class="btn btn-secondary">返回</a>
    </form>
@endsection
