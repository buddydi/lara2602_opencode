@extends('admin_layout')

@section('title', '角色详情')

@section('content')
    <h1>角色详情</h1>
    
    <div class="form-group">
        <strong>ID：</strong>{{ $role->id }}
    </div>
    
    <div class="form-group">
        <strong>角色名称：</strong>{{ $role->name }}
    </div>
    
    <div class="form-group">
        <strong>创建时间：</strong>{{ $role->created_at }}
    </div>
    
    <div class="form-group">
        <label class="section-title">权限：</label>
        <div>
            @forelse($permissions as $permission)
                <span class="badge">{{ $permission->name }}</span>
            @empty
                <span>暂无权限</span>
            @endforelse
        </div>
    </div>
    
    <div style="margin-top: 30px;">
        <a href="{{ route('roles.edit', $role) }}" class="btn btn-primary">编辑</a>
        <a href="{{ route('roles.index') }}" class="btn btn-secondary">返回列表</a>
    </div>
@endsection
