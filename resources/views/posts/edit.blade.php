@extends('admin_layout')

@section('title', '编辑文章')

@section('content')
    <h1>编辑文章</h1>
    
    <form action="{{ route('posts.update', $post) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
        <div class="form-group">
            <label for="title">标题</label>
            <input type="text" name="title" id="title" value="{{ old('title', $post->title) }}">
        </div>
        
        <div class="form-group">
            <label for="cover_image">封面图片</label>
            @if($post->cover_image)
                <div style="margin-bottom: 10px;">
                    <img src="{{ asset('storage/' . $post->cover_image) }}" alt="封面图" style="max-width: 200px; max-height: 200px;">
                </div>
            @endif
            <input type="file" name="cover_image" id="cover_image" accept="image/*">
        </div>
        
        <div class="form-group">
            <label for="content">内容</label>
            <textarea name="content" id="content">{{ old('content', $post->content) }}</textarea>
        </div>
        
        <div class="form-group">
            <label for="status">状态</label>
            <select name="status" id="status">
                <option value="draft" {{ $post->status === 'draft' ? 'selected' : '' }}>草稿</option>
                <option value="published" {{ $post->status === 'published' ? 'selected' : '' }}>已发布</option>
                <option value="archived" {{ $post->status === 'archived' ? 'selected' : '' }}>归档</option>
            </select>
        </div>
        
        <button type="submit" class="btn btn-primary">更新</button>
        <a href="{{ route('posts.index') }}" class="btn btn-secondary">返回</a>
    </form>
@endsection
