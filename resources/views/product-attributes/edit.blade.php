<?php $currentRoute = 'product-attributes'; ?>
@extends('admin_layout')

@section('title', '编辑商品属性')

@section('content')
<div class="section-title">编辑商品属性</div>

<form action="{{ route('product-attributes.update', $productAttribute) }}" method="POST">
    @csrf
    @method('PUT')
    
    <div class="form-group">
        <label>属性名称 *</label>
        <input type="text" name="name" value="{{ $productAttribute->name }}" required>
    </div>
    
    <div class="form-group">
        <label>类型 *</label>
        <select name="type" id="type" required>
            <option value="select" {{ $productAttribute->type == 'select' ? 'selected' : '' }}>规格（可选值）</option>
            <option value="text" {{ $productAttribute->type == 'text' ? 'selected' : '' }}>文本</option>
            <option value="number" {{ $productAttribute->type == 'number' ? 'selected' : '' }}>数字</option>
        </select>
    </div>
    
    <div class="form-group" id="values-container" style="{{ $productAttribute->type == 'select' ? 'block' : 'none' }}">
        <label>属性值（每行一个，用于规格类型）</label>
        <textarea name="values_text" rows="5">
@foreach($productAttribute->values as $val){{ $val->value }}
@endforeach</textarea>
    </div>
    
    <div class="form-group">
        <label>排序</label>
        <input type="number" name="order" value="{{ $productAttribute->order }}">
    </div>
    
    <div class="form-group">
        <label>
            <input type="checkbox" name="is_required" value="1" {{ $productAttribute->is_required ? 'checked' : '' }}> 必填
        </label>
    </div>
    
    <div class="form-group">
        <label>
            <input type="checkbox" name="is_filterable" value="1" {{ $productAttribute->is_filterable ? 'checked' : '' }}> 可筛选
        </label>
    </div>
    
    <button type="submit" class="btn btn-primary">更新</button>
    <a href="{{ route('product-attributes.index') }}" class="btn btn-secondary">返回</a>
</form>

<script>
document.getElementById('type').addEventListener('change', function() {
    document.getElementById('values-container').style.display = 
        this.value === 'select' ? 'block' : 'none';
});
</script>
@endsection
