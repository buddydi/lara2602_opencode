<?php $currentRoute = 'product-categories'; ?>
@extends('admin_layout')

@section('title', '编辑商品分类')

@section('content')
<div class="section-title">编辑商品分类</div>

<form action="{{ route('product-categories.update', $productCategory) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    
    <div class="form-group">
        <label>分类名称 *</label>
        <input type="text" name="name" value="{{ $productCategory->name }}" required>
    </div>
    
    <div class="form-group">
        <label>上级分类</label>
        <select name="parent_id">
            <option value="">顶级分类</option>
            @foreach($categories as $cat)
            <option value="{{ $cat->id }}" {{ $productCategory->parent_id == $cat->id ? 'selected' : '' }}>
                {{ $cat->name }}
            </option>
            @endforeach
        </select>
    </div>
    
    <div class="form-group">
        <label>描述</label>
        <textarea name="description">{{ $productCategory->description }}</textarea>
    </div>
    
    <div class="form-group">
        <label>分类图片</label>
        @if($productCategory->image)
        <div style="margin-bottom: 10px;">
            <img src="{{ asset('storage/' . $productCategory->image) }}" style="max-width: 200px;">
        </div>
        @endif
        <input type="file" name="image" accept="image/*">
    </div>
    
    <div class="form-group">
        <label>排序</label>
        <input type="number" name="order" value="{{ $productCategory->order }}">
    </div>
    
    <div class="form-group">
        <label>
            <input type="checkbox" name="is_active" value="1" {{ $productCategory->is_active ? 'checked' : '' }}> 启用
        </label>
    </div>
    
    <button type="submit" class="btn btn-primary">更新</button>
    <a href="{{ route('product-categories.index') }}" class="btn btn-secondary">返回</a>
</form>
@endsection
