@extends('admin_layout')

@section('title', '新建权限')

@section('content')
    <h1>新建权限</h1>
    
    <form action="{{ route('permissions.store') }}" method="POST">
        @csrf
        
        <div class="form-group">
            <label for="name">权限名称</label>
            <input type="text" name="name" id="name" value="{{ old('name') }}" placeholder="例如: post create">
            @error('name')
                <div class="error">{{ $message }}</div>
            @enderror
            <p style="color: #666; font-size: 12px; margin-top: 5px;">
                建议格式：控制器名 + 操作名，如 post create, post edit, user delete 等
            </p>
        </div>
        
        <button type="submit" class="btn btn-primary">创建</button>
        <a href="{{ route('permissions.index') }}" class="btn btn-secondary">返回</a>
    </form>
@endsection
