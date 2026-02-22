<?php $currentRoute = 'products'; ?>
@extends('admin_layout')

@section('title', '新建商品')

@section('content')
<div class="section-title">新建商品</div>

<form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    
    <div class="form-group">
        <label>商品名称 *</label>
        <input type="text" name="name" required>
    </div>
    
    <div class="form-group">
        <label>分类</label>
        <select name="category_id">
            <option value="">选择分类</option>
            @foreach($categories as $cat)
            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
            @endforeach
        </select>
    </div>
    
    <div class="form-group">
        <label>品牌</label>
        <select name="brand_id">
            <option value="">选择品牌</option>
            @foreach($brands as $brand)
            <option value="{{ $brand->id }}">{{ $brand->name }}</option>
            @endforeach
        </select>
    </div>
    
    <div class="form-group">
        <label>价格 *</label>
        <input type="number" name="price" step="0.01" required>
    </div>
    
    <div class="form-group">
        <label>原价</label>
        <input type="number" name="original_price" step="0.01">
    </div>
    
    <div class="form-group">
        <label>封面图片</label>
        <input type="file" name="cover_image" accept="image/*">
    </div>
    
    <div class="form-group">
        <label>简短描述</label>
        <textarea name="description"></textarea>
    </div>
    
    <div class="form-group">
        <label>详细介绍</label>
        <textarea name="content" rows="5"></textarea>
    </div>
    
    <div class="form-group">
        <label>状态</label>
        <select name="status" required>
            <option value="draft">草稿</option>
            <option value="published">已发布</option>
            <option value="archived">已归档</option>
        </select>
    </div>
    
    @if($attributes->count() > 0)
    <div class="form-group">
        <label>商品属性</label>
        @foreach($attributes as $attribute)
        <div style="margin-bottom: 15px; padding: 10px; border: 1px solid #ddd; border-radius: 4px;">
            <strong>{{ $attribute->name }}</strong>
            @if($attribute->values->count() > 0)
            <div style="margin-top: 8px;">
                @foreach($attribute->values as $value)
                <label style="font-weight: normal; margin-right: 15px;">
                    <input type="checkbox" name="attribute_values[{{ $attribute->id }}][]" value="{{ $value->id }}"> {{ $value->value }}
                </label>
                @endforeach
            </div>
            @else
            <input type="text" name="attribute_text[{{ $attribute->id }}]" placeholder="请输入{{ $attribute->name }}" style="margin-top: 8px;">
            @endif
        </div>
        @endforeach
    </div>
    @endif
    
    <button type="submit" class="btn btn-primary">创建</button>
    <a href="{{ route('products.index') }}" class="btn btn-secondary">返回</a>
</form>
@endsection
