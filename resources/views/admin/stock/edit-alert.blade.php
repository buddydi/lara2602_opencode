@extends('admin_layout')

@section('title', '编辑预警')

@section('content')
<div class="page-header">
    <h1>编辑库存预警</h1>
    <a href="{{ route('admin.stock.alerts') }}" class="btn btn-outline">返回列表</a>
</div>

<div class="card" style="max-width: 600px;">
    <div class="card-body">
        <form method="POST" action="{{ route('admin.stock.update-alert', $stockAlert) }}">
            @csrf
            @method('PUT')
            
            <div class="form-group">
                <label>商品</label>
                <input type="text" value="{{ $stockAlert->product->name }}" disabled style="padding: 8px; border: 1px solid #ddd; border-radius: 4px; width: 100%; background: #f5f5f5;">
            </div>
            
            @if($stockAlert->sku)
            <div class="form-group">
                <label>SKU</label>
                <input type="text" value="{{ $stockAlert->sku->name }}" disabled style="padding: 8px; border: 1px solid #ddd; border-radius: 4px; width: 100%; background: #f5f5f5;">
            </div>
            @endif
            
            <div class="form-group">
                <label>低库存预警阈值 *</label>
                <input type="number" name="low_stock_threshold" required min="0" value="{{ $stockAlert->low_stock_threshold }}" style="padding: 8px; border: 1px solid #ddd; border-radius: 4px; width: 100%;">
            </div>
            
            <div class="form-group">
                <label>紧急库存阈值 *</label>
                <input type="number" name="critical_stock_threshold" required min="0" value="{{ $stockAlert->critical_stock_threshold }}" style="padding: 8px; border: 1px solid #ddd; border-radius: 4px; width: 100%;">
            </div>
            
            <div class="form-group">
                <label>
                    <input type="checkbox" name="is_enabled" value="1" {{ $stockAlert->is_enabled ? 'checked' : '' }}> 启用预警
                </label>
            </div>
            
            <div class="form-group">
                <label>
                    <input type="checkbox" name="notify_admin" value="1" {{ $stockAlert->notify_admin ? 'checked' : '' }}> 通知管理员
                </label>
            </div>
            
            <button type="submit" class="btn">保存</button>
        </form>
    </div>
</div>
@endsection
