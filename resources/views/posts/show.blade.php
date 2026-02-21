@extends('admin_layout')

@section('title', $post->title)

@section('content')
    <h1>{{ $post->title }}</h1>
    
    @if($post->cover_image)
        <div style="margin-bottom: 20px;">
            <img src="{{ asset('storage/' . $post->cover_image) }}" alt="封面图" style="max-width: 100%; max-height: 400px;">
        </div>
    @endif
    
    <div class="form-group">
        <strong>作者：</strong>{{ $post->user->name }} | 
        <strong>状态：</strong>{{ $post->status }} | 
        <strong>发布时间：</strong>{{ $post->published_at ? $post->published_at->format('Y-m-d H:i') : '-' }}
    </div>
    
    <div class="form-group">
        {{ $post->content }}
    </div>
    
    <div style="margin-top: 30px;">
        <a href="{{ route('posts.edit', $post) }}" class="btn btn-primary">编辑</a>
        <form action="{{ route('posts.destroy', $post) }}" method="POST" style="display: inline;">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger" onclick="return confirm('确定删除？')">删除</button>
        </form>
        <a href="{{ route('posts.index') }}" class="btn btn-secondary">返回列表</a>
    </div>
@endsection
