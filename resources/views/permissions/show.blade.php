@extends('admin_layout')

@section('title', '权限详情')

@section('content')
    <h1>权限详情</h1>
    
    <div class="form-group">
        <label>权限名称</label>
        <p>{{ $permission->name }}</p>
    </div>
    
    <div class="form-group">
        <label>Guard</label>
        <p>{{ $permission->guard_name }}</p>
    </div>
    
    <div class="form-group">
        <label>创建时间</label>
        <p>{{ $permission->created_at }}</p>
    </div>
    
    <a href="{{ route('permissions.index') }}" class="btn btn-secondary">返回列表</a>
    <a href="{{ route('permissions.edit', $permission) }}" class="btn btn-primary">编辑</a>
@endsection
