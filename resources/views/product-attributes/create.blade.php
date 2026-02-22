<?php $currentRoute = 'product-attributes'; ?>
@extends('admin_layout')

@section('title', '新建商品属性')

@section('content')
<div class="section-title">新建商品属性</div>

<form action="{{ route('product-attributes.store') }}" method="POST">
    @csrf
    
    <div class="form-group">
        <label>属性名称 *</label>
        <input type="text" name="name" required placeholder="如：颜色、尺寸">
    </div>
    
    <div class="form-group">
        <label>类型 *</label>
        <select name="type" id="type" required>
            <option value="select">规格（可选值）</option>
            <option value="text">文本</option>
            <option value="number">数字</option>
        </select>
    </div>
    
    <div class="form-group" id="values-container">
        <label>属性值（每行一个，用于规格类型）</label>
        <textarea name="values_text" rows="5" placeholder="红色&#10;蓝色&#10;绿色"></textarea>
    </div>
    
    <div class="form-group">
        <label>排序</label>
        <input type="number" name="order" value="0">
    </div>
    
    <div class="form-group">
        <label>
            <input type="checkbox" name="is_required" value="1"> 必填
        </label>
    </div>
    
    <div class="form-group">
        <label>
            <input type="checkbox" name="is_filterable" value="1"> 可筛选
        </label>
    </div>
    
    <button type="submit" class="btn btn-primary">创建</button>
    <a href="{{ route('product-attributes.index') }}" class="btn btn-secondary">返回</a>
</form>

<script>
document.getElementById('type').addEventListener('change', function() {
    document.getElementById('values-container').style.display = 
        this.value === 'select' ? 'block' : 'none';
});
</script>
@endsection
