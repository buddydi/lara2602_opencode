@extends('admin_layout')

@section('title', $category->name)

@section('content')
    <h1>{{ $category->name }}</h1>
    
    <div class="form-group">
        <strong>父分类：</strong>{{ $category->parent ? $category->parent->name : '顶级分类' }} | 
        <strong>排序：</strong>{{ $category->order }} | 
        <strong>Slug：</strong>{{ $category->slug }}
    </div>
    
    <div class="form-group">
        {{ $category->description ?: '暂无描述' }}
    </div>
    
    <div style="margin-top: 30px;">
        <a href="{{ route('categories.edit', $category) }}" class="btn btn-primary">编辑</a>
        <form action="{{ route('categories.destroy', $category) }}" method="POST" style="display: inline;">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger" onclick="return confirm('确定删除？')">删除</button>
        </form>
        <a href="{{ route('categories.index') }}" class="btn btn-secondary">返回列表</a>
    </div>
@endsection
