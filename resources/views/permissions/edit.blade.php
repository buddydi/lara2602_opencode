@extends('admin_layout')

@section('title', '编辑权限')

@section('content')
    <h1>编辑权限</h1>
    
    <form action="{{ route('permissions.update', $permission) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="form-group">
            <label for="name">权限名称</label>
            <input type="text" name="name" id="name" value="{{ $permission->name }}" required>
        </div>
        
        <button type="submit" class="btn btn-primary">更新</button>
        <a href="{{ route('permissions.index') }}" class="btn btn-secondary">取消</a>
    </form>
@endsection
