@extends('admin_layout')

@section('title', '添加预警')

@section('content')
<div class="page-header">
    <h1>添加库存预警</h1>
    <a href="{{ route('admin.stock.alerts') }}" class="btn btn-outline">返回列表</a>
</div>

<div class="card" style="max-width: 600px;">
    <div class="card-body">
        <form method="POST" action="{{ route('admin.stock.store-alert') }}">
            @csrf
            
            <div class="form-group">
                <label>商品 *</label>
                <select name="product_id" id="productSelect" required style="padding: 8px; border: 1px solid #ddd; border-radius: 4px; width: 100%;">
                    <option value="">请选择商品</option>
                    @foreach($products as $product)
                    <option value="{{ $product->id }}">{{ $product->name }}</option>
                    @endforeach
                </select>
            </div>
            
            <div class="form-group">
                <label>SKU (可选，不选则对该商品所有SKU生效)</label>
                <select name="product_sku_id" id="skuSelect" style="padding: 8px; border: 1px solid #ddd; border-radius: 4px; width: 100%;">
                    <option value="">全部SKU</option>
                </select>
            </div>
            
            <div class="form-group">
                <label>低库存预警阈值 *</label>
                <input type="number" name="low_stock_threshold" required min="0" value="10" style="padding: 8px; border: 1px solid #ddd; border-radius: 4px; width: 100%;">
                <small style="color: #666;">库存低于此值时发送低库存预警</small>
            </div>
            
            <div class="form-group">
                <label>紧急库存阈值 *</label>
                <input type="number" name="critical_stock_threshold" required min="0" value="5" style="padding: 8px; border: 1px solid #ddd; border-radius: 4px; width: 100%;">
                <small style="color: #666;">库存低于此值时发送紧急预警</small>
            </div>
            
            <div class="form-group">
                <label>
                    <input type="checkbox" name="is_enabled" value="1" checked> 启用预警
                </label>
            </div>
            
            <div class="form-group">
                <label>
                    <input type="checkbox" name="notify_admin" value="1" checked> 通知管理员
                </label>
            </div>
            
            <button type="submit" class="btn">保存</button>
        </form>
    </div>
</div>

<script>
document.getElementById('productSelect').addEventListener('change', function() {
    const productId = this.value;
    const skuSelect = document.getElementById('skuSelect');
    
    if (!productId) {
        skuSelect.innerHTML = '<option value="">全部SKU</option>';
        return;
    }
    
    fetch('{{ route("admin.stock.get-skus") }}?product_id=' + productId)
        .then(response => response.json())
        .then(data => {
            skuSelect.innerHTML = '<option value="">全部SKU</option>';
            data.forEach(sku => {
                skuSelect.innerHTML += '<option value="' + sku.id + '">' + sku.name + ' (库存: ' + sku.stock + ')</option>';
            });
        });
});
</script>
@endsection
