<?php $currentRoute = 'brands'; ?>
@extends('admin_layout')

@section('title', '编辑品牌')

@section('content')
<div class="section-title">编辑品牌</div>

<form action="{{ route('brands.update', $brand) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    
    <div class="form-group">
        <label>品牌名称 *</label>
        <input type="text" name="name" value="{{ $brand->name }}" required>
    </div>
    
    <div class="form-group">
        <label>描述</label>
        <textarea name="description">{{ $brand->description }}</textarea>
    </div>
    
    <div class="form-group">
        <label>Logo</label>
        @if($brand->logo)
        <div style="margin-bottom: 10px;">
            <img src="{{ asset('storage/' . $brand->logo) }}" style="width: 100px;">
        </div>
        @endif
        <input type="file" name="logo" accept="image/*">
    </div>
    
    <div class="form-group">
        <label>
            <input type="checkbox" name="is_active" value="1" {{ $brand->is_active ? 'checked' : '' }}> 启用
        </label>
    </div>
    
    <button type="submit" class="btn btn-primary">更新</button>
    <a href="{{ route('brands.index') }}" class="btn btn-secondary">返回</a>
</form>
@endsection
