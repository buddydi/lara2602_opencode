@extends('admin_layout')

@section('title', '创建活动')

@section('content')
<div class="page-header">
    <h1>创建促销活动</h1>
    <a href="{{ route('admin.promotions.index') }}" class="btn btn-outline">返回列表</a>
</div>

<div class="card" style="max-width: 800px;">
    <div class="card-body">
        <form method="POST" action="{{ route('admin.promotions.store') }}">
            @csrf
            
            <div class="form-group">
                <label>活动名称 *</label>
                <input type="text" name="name" required style="padding: 8px; border: 1px solid #ddd; border-radius: 4px; width: 100%;">
            </div>
            
            <div class="form-group">
                <label>活动类型 *</label>
                <select name="type" id="typeSelect" required style="padding: 8px; border: 1px solid #ddd; border-radius: 4px; width: 100%;" onchange="toggleFields()">
                    <option value="">请选择</option>
                    <option value="flash_sale">秒杀</option>
                    <option value="discount">折扣</option>
                    <option value="full_reduce">满减</option>
                </select>
            </div>
            
            <div class="form-group">
                <label>活动描述</label>
                <textarea name="description" rows="3" style="padding: 8px; border: 1px solid #ddd; border-radius: 4px; width: 100%;"></textarea>
            </div>
            
            <div class="form-group">
                <label>开始时间 *</label>
                <input type="datetime-local" name="start_time" required style="padding: 8px; border: 1px solid #ddd; border-radius: 4px; width: 100%;">
            </div>
            
            <div class="form-group">
                <label>结束时间 *</label>
                <input type="datetime-local" name="end_time" required style="padding: 8px; border: 1px solid #ddd; border-radius: 4px; width: 100%;">
            </div>
            
            <!-- 折扣 -->
            <div id="discountField" style="display: none;">
                <div class="form-group">
                    <label>折扣率（1-99）*</label>
                    <input type="number" name="discount_rate" min="1" max="99" placeholder="如 80 表示 8 折" style="padding: 8px; border: 1px solid #ddd; border-radius: 4px; width: 100%;">
                </div>
            </div>
            
            <!-- 满减 -->
            <div id="fullReduceField" style="display: none;">
                <div class="form-group">
                    <label>最低消费金额 *</label>
                    <input type="number" name="min_amount" step="0.01" min="0" placeholder="满足此金额才能减" style="padding: 8px; border: 1px solid #ddd; border-radius: 4px; width: 100%;">
                </div>
                <div class="form-group">
                    <label>减掉金额 *</label>
                    <input type="number" name="reduce_amount" step="0.01" min="0" placeholder="减掉的金额" style="padding: 8px; border: 1px solid #ddd; border-radius: 4px; width: 100%;">
                </div>
            </div>
            
            <!-- 秒杀 -->
            <div id="flashSaleField" style="display: none;">
                <div class="form-group">
                    <label>秒杀价格 *</label>
                    <input type="number" name="special_price" step="0.01" min="0" placeholder="秒杀价格" style="padding: 8px; border: 1px solid #ddd; border-radius: 4px; width: 100%;">
                </div>
                <div class="form-group">
                    <label>限购数量 *</label>
                    <input type="number" name="max_quantity" min="1" placeholder="每人限购数量" style="padding: 8px; border: 1px solid #ddd; border-radius: 4px; width: 100%;">
                </div>
            </div>
            
            <div class="form-group">
                <label>每人限购</label>
                <input type="number" name="max_per_user" min="1" placeholder="不限制则留空" style="padding: 8px; border: 1px solid #ddd; border-radius: 4px; width: 100%;">
            </div>
            
            <div class="form-group">
                <label>选择商品</label>
                <div style="max-height: 200px; overflow-y: auto; border: 1px solid #ddd; padding: 10px;">
                    @foreach($products as $product)
                    <div style="margin-bottom: 5px;">
                        <label>
                            <input type="checkbox" name="product_ids[]" value="{{ $product->id }}"> {{ $product->name }}
                        </label>
                    </div>
                    @endforeach
                </div>
            </div>
            
            <div class="form-group">
                <label>
                    <input type="checkbox" name="is_active" value="1" checked> 启用活动
                </label>
            </div>
            
            <button type="submit" class="btn">创建</button>
        </form>
    </div>
</div>

<script>
function toggleFields() {
    var type = document.getElementById('typeSelect').value;
    document.getElementById('discountField').style.display = type === 'discount' ? 'block' : 'none';
    document.getElementById('fullReduceField').style.display = type === 'full_reduce' ? 'block' : 'none';
    document.getElementById('flashSaleField').style.display = type === 'flash_sale' ? 'block' : 'none';
}
</script>
@endsection
