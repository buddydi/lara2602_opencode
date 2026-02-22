<?php $currentRoute = 'brands'; ?>
@extends('admin_layout')

@section('title', '新建品牌')

@section('content')
<div class="section-title">新建品牌</div>

<form action="{{ route('brands.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    
    <div class="form-group">
        <label>品牌名称 *</label>
        <input type="text" name="name" required>
    </div>
    
    <div class="form-group">
        <label>描述</label>
        <textarea name="description"></textarea>
    </div>
    
    <div class="form-group">
        <label>Logo</label>
        <input type="file" name="logo" accept="image/*">
    </div>
    
    <div class="form-group">
        <label>
            <input type="checkbox" name="is_active" value="1" checked> 启用
        </label>
    </div>
    
    <button type="submit" class="btn btn-primary">创建</button>
    <a href="{{ route('brands.index') }}" class="btn btn-secondary">返回</a>
</form>
@endsection
