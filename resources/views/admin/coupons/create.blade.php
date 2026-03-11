@extends('admin_layout')

@section('title', '创建优惠券')

@section('content')
<div class="page-header">
    <h1>创建优惠券</h1>
</div>

<form method="POST" action="{{ route('admin.coupons.store') }}">
    @csrf
    
    <div class="card">
        <div class="card-body">
            <div class="form-group">
                <label>优惠码 *</label>
                <input type="text" name="code" value="{{ old('code') }}" required style="width: 300px; padding: 8px; border: 1px solid #ddd; border-radius: 4px;" placeholder="如：SAVE10">
                <small style="color: #666;">4-20位字母数字组合</small>
            </div>
            
            <div class="form-group">
                <label>优惠券名称 *</label>
                <input type="text" name="name" value="{{ old('name') }}" required style="width: 300px; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
            </div>
            
            <div class="form-group">
                <label>类型 *</label>
                <select name="type" id="coupon-type" onchange="toggleMaxDiscount()" style="padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
                    <option value="fixed" {{ old('type') === 'fixed' ? 'selected' : '' }}>固定金额（满减券）</option>
                    <option value="percentage" {{ old('type') === 'percentage' ? 'selected' : '' }}>百分比折扣（折扣券）</option>
                </select>
            </div>
            
            <div class="form-group">
                <label>优惠值 *</label>
                <input type="number" name="value" value="{{ old('value', 10) }}" required min="0" step="0.01" style="width: 150px; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
                <span id="value-unit">元</span>
            </div>
            
            <div class="form-group" id="max-discount-group" style="display: none;">
                <label>最高优惠金额</label>
                <input type="number" name="max_discount" value="{{ old('max_discount', 100) }}" min="0" step="0.01" style="width: 150px; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
                <span>元（折扣券最高优惠）</span>
            </div>
            
            <div class="form-group">
                <label>最低消费金额 *</label>
                <input type="number" name="min_amount" value="{{ old('min_amount', 100) }}" required min="0" step="0.01" style="width: 150px; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
                <span>元</span>
            </div>
            
            <div class="form-group">
                <label>有效期 *</label>
                <input type="datetime-local" name="start_date" value="{{ old('start_date') }}" required style="padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
                <span> 至 </span>
                <input type="datetime-local" name="end_date" value="{{ old('end_date') }}" required style="padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
            </div>
            
            <div class="form-group">
                <label>使用次数限制</label>
                <input type="number" name="usage_limit" value="{{ old('usage_limit', 0) }}" min="0" style="width: 150px; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
                <span>次（0=不限）</span>
            </div>
            
            <div class="form-group">
                <label>每人限领次数</label>
                <input type="number" name="per_user_limit" value="{{ old('per_user_limit', 1) }}" min="1" style="width: 150px; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
                <span>次</span>
            </div>
            
            <div class="form-group">
                <label>
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                    启用
                </label>
            </div>
            
            <div class="form-group">
                <label>描述</label>
                <textarea name="description" rows="3" style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">{{ old('description') }}</textarea>
            </div>
        </div>
    </div>

    <div style="margin-top: 20px;">
        <button type="submit" class="btn">创建</button>
        <a href="{{ route('admin.coupons.index') }}" class="btn btn-outline" style="margin-left: 10px;">返回</a>
    </div>
</form>

<script>
function toggleMaxDiscount() {
    var type = document.getElementById('coupon-type').value;
    var maxDiscountGroup = document.getElementById('max-discount-group');
    var valueUnit = document.getElementById('value-unit');
    
    if (type === 'percentage') {
        maxDiscountGroup.style.display = 'flex';
        maxDiscountGroup.style.alignItems = 'center';
        maxDiscountGroup.style.gap = '10px';
        valueUnit.textContent = '%';
    } else {
        maxDiscountGroup.style.display = 'none';
        valueUnit.textContent = '元';
    }
}

document.addEventListener('DOMContentLoaded', function() {
    toggleMaxDiscount();
});
</script>
@endsection
