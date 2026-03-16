@extends('admin_layout')

@section('title', '出入库操作')

@section('content')
<div class="page-header">
    <h1>出入库操作</h1>
    <a href="{{ route('admin.stock.logs') }}" class="btn btn-outline">返回记录</a>
</div>

<div class="card" style="max-width: 600px;">
    <div class="card-body">
        <form method="POST" action="{{ route('admin.stock.store-log') }}">
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
                <label>SKU *</label>
                <select name="product_sku_id" id="skuSelect" required style="padding: 8px; border: 1px solid #ddd; border-radius: 4px; width: 100%;">
                    <option value="">请先选择商品</option>
                    @if($sku)
                    @foreach($sku as $s)
                    <option value="{{ $s->id }}">{{ $s->name ?: $s->sku }} (库存: {{ $s->stock }})</option>
                    @endforeach
                    @endif
                </select>
            </div>
            
            <div class="form-group">
                <label>类型 *</label>
                <select name="type" required style="padding: 8px; border: 1px solid #ddd; border-radius: 4px; width: 100%;">
                    <option value="in">入库</option>
                    <option value="out">出库</option>
                    <option value="adjust">调整</option>
                </select>
            </div>
            
            <div class="form-group">
                <label>数量 *</label>
                <input type="number" name="quantity" required min="1" value="1" style="padding: 8px; border: 1px solid #ddd; border-radius: 4px; width: 100%;">
            </div>
            
            <div class="form-group">
                <label>原因 *</label>
                <select name="reason" required style="padding: 8px; border: 1px solid #ddd; border-radius: 4px; width: 100%;">
                    @foreach(\App\Models\StockLog::getReasonOptions() as $key => $name)
                    <option value="{{ $key }}">{{ $name }}</option>
                    @endforeach
                </select>
            </div>
            
            <div class="form-group">
                <label>备注</label>
                <textarea name="remark" rows="3" style="padding: 8px; border: 1px solid #ddd; border-radius: 4px; width: 100%;"></textarea>
            </div>
            
            <button type="submit" class="btn">提交</button>
        </form>
    </div>
</div>

<script>
document.getElementById('productSelect').addEventListener('change', function() {
    const productId = this.value;
    const skuSelect = document.getElementById('skuSelect');
    
    if (!productId) {
        skuSelect.innerHTML = '<option value="">请先选择商品</option>';
        return;
    }
    
    fetch('{{ route("admin.stock.get-skus") }}?product_id=' + productId)
        .then(response => response.json())
        .then(data => {
            skuSelect.innerHTML = '<option value="">请选择SKU</option>';
            data.forEach(sku => {
                skuSelect.innerHTML += '<option value="' + sku.id + '">' + sku.name + ' (库存: ' + sku.stock + ')</option>';
            });
        });
});
</script>
@endsection
