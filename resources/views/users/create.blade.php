@extends('admin_layout')

@section('title', '创建用户')

@section('content')
    <h1>创建用户</h1>
    
    <form action="{{ route('users.store') }}" method="POST">
        @csrf
        
        <div class="form-group">
            <label for="name">用户名</label>
            <input type="text" name="name" id="name" value="{{ old('name') }}">
            @error('name')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>
        
        <div class="form-group">
            <label for="email">邮箱</label>
            <input type="email" name="email" id="email" value="{{ old('email') }}">
            @error('email')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>
        
        <div class="form-group">
            <label for="password">密码</label>
            <input type="password" name="password" id="password">
            @error('password')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>
        
        <div class="form-group">
            <label for="password_confirmation">确认密码</label>
            <input type="password" name="password_confirmation" id="password_confirmation">
        </div>
        
        <button type="submit" class="btn btn-primary">创建</button>
        <a href="{{ route('users.index') }}" class="btn btn-secondary">返回</a>
    </form>
@endsection
