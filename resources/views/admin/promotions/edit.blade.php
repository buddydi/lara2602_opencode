@extends('admin_layout')

@section('title', '编辑活动')

@section('content')
<div class="page-header">
    <h1>编辑促销活动</h1>
    <a href="{{ route('admin.promotions.index') }}" class="btn btn-outline">返回列表</a>
</div>

<div class="card" style="max-width: 800px;">
    <div class="card-body">
        <form method="POST" action="{{ route('admin.promotions.update', $promotion) }}">
            @csrf
            @method('PUT')
            
            <div class="form-group">
                <label>活动名称 *</label>
                <input type="text" name="name" value="{{ $promotion->name }}" required style="padding: 8px; border: 1px solid #ddd; border-radius: 4px; width: 100%;">
            </div>
            
            <div class="form-group">
                <label>活动类型</label>
                <input type="text" value="{{ \App\Models\Promotion::getTypeOptions()[$promotion->type] }}" disabled style="padding: 8px; border: 1px solid #ddd; border-radius: 4px; width: 100%; background: #f5f5f5;">
                <input type="hidden" name="type" value="{{ $promotion->type }}">
            </div>
            
            <div class="form-group">
                <label>活动描述</label>
                <textarea name="description" rows="3" style="padding: 8px; border: 1px solid #ddd; border-radius: 4px; width: 100%;">{{ $promotion->description }}</textarea>
            </div>
            
            <div class="form-group">
                <label>开始时间 *</label>
                <input type="datetime-local" name="start_time" value="{{ str_replace(' ', 'T', $promotion->start_time) }}" required style="padding: 8px; border: 1px solid #ddd; border-radius: 4px; width: 100%;">
            </div>
            
            <div class="form-group">
                <label>结束时间 *</label>
                <input type="datetime-local" name="end_time" value="{{ str_replace(' ', 'T', $promotion->end_time) }}" required style="padding: 8px; border: 1px solid #ddd; border-radius: 4px; width: 100%;">
            </div>
            
            @if($promotion->type === 'discount')
            <div class="form-group">
                <label>折扣率（1-99）</label>
                <input type="number" name="discount_rate" value="{{ $promotion->discount_rate }}" min="1" max="99" style="padding: 8px; border: 1px solid #ddd; border-radius: 4px; width: 100%;">
            </div>
            @elseif($promotion->type === 'full_reduce')
            <div class="form-group">
                <label>最低消费金额</label>
                <input type="number" name="min_amount" value="{{ $promotion->min_amount }}" step="0.01" style="padding: 8px; border: 1px solid #ddd; border-radius: 4px; width: 100%;">
            </div>
            <div class="form-group">
                <label>减掉金额</label>
                <input type="number" name="reduce_amount" value="{{ $promotion->reduce_amount }}" step="0.01" style="padding: 8px; border: 1px solid #ddd; border-radius: 4px; width: 100%;">
            </div>
            @elseif($promotion->type === 'flash_sale')
            <div class="form-group">
                <label>秒杀价格</label>
                <input type="number" name="special_price" value="{{ $promotion->discount_amount }}" step="0.01" style="padding: 8px; border: 1px solid #ddd; border-radius: 4px; width: 100%;">
            </div>
            <div class="form-group">
                <label>限购数量</label>
                <input type="number" name="max_quantity" value="{{ $promotion->max_quantity }}" style="padding: 8px; border: 1px solid #ddd; border-radius: 4px; width: 100%;">
            </div>
            @endif
            
            <div class="form-group">
                <label>每人限购</label>
                <input type="number" name="max_per_user" value="{{ $promotion->max_per_user }}" min="1" style="padding: 8px; border: 1px solid #ddd; border-radius: 4px; width: 100%;">
            </div>
            
            <div class="form-group">
                <label>选择商品</label>
                <div style="max-height: 200px; overflow-y: auto; border: 1px solid #ddd; padding: 10px;">
                    @foreach($products as $product)
                    <div style="margin-bottom: 5px;">
                        <label>
                            <input type="checkbox" name="product_ids[]" value="{{ $product->id }}" {{ in_array($product->id, $selectedProductIds) ? 'checked' : '' }}> {{ $product->name }}
                        </label>
                    </div>
                    @endforeach
                </div>
            </div>
            
            <div class="form-group">
                <label>
                    <input type="checkbox" name="is_active" value="1" {{ $promotion->is_active ? 'checked' : '' }}> 启用活动
                </label>
            </div>
            
            <button type="submit" class="btn">保存</button>
        </form>
    </div>
</div>
@endsection
