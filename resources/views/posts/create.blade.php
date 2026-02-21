@extends('admin_layout')

@section('title', '新建文章')

@section('content')
    <h1>新建文章</h1>
    
    <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        
        <div class="form-group">
            <label for="title">标题</label>
            <input type="text" name="title" id="title" value="{{ old('title') }}">
        </div>
        
        <div class="form-group">
            <label for="cover_image">封面图片</label>
            <input type="file" name="cover_image" id="cover_image" accept="image/*">
        </div>
        
        <div class="form-group">
            <label for="content">内容</label>
            <textarea name="content" id="content">{{ old('content') }}</textarea>
        </div>
        
        <div class="form-group">
            <label for="status">状态</label>
            <select name="status" id="status">
                <option value="draft">草稿</option>
                <option value="published">已发布</option>
                <option value="archived">归档</option>
            </select>
        </div>
        
        <button type="submit" class="btn btn-primary">创建</button>
        <a href="{{ route('posts.index') }}" class="btn btn-secondary">返回</a>
    </form>
@endsection
