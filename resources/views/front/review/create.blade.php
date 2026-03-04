@extends('front.layout')

@section('title', '评价商品')

@section('content')
<div style="background: #fff; padding: 30px; border-radius: 8px; max-width: 600px; margin: 0 auto;">
    <h2 style="margin-bottom: 20px; text-align: center;">评价商品</h2>
    
    <div style="display: flex; gap: 15px; padding: 15px; background: #f9f9f9; border-radius: 8px; margin-bottom: 20px;">
        <img src="{{ $item->product_image ? asset('storage/' . $item->product_image) : 'https://via.placeholder.com/80' }}" style="width: 80px; height: 80px; object-fit: cover; border-radius: 4px;">
        <div>
            <div style="font-weight: bold;">{{ $item->product_name }}</div>
            <div style="color: #999; font-size: 14px;">{{ $item->sku_name ?: '默认' }} × {{ $item->quantity }}</div>
            <div style="color: #e4393c; margin-top: 5px;">¥{{ $item->total }}</div>
        </div>
    </div>
    
    <form method="POST" action="{{ route('orders.review.store', [$order, $item]) }}">
        @csrf
        
        <div style="margin-bottom: 20px;">
            <div style="margin-bottom: 10px;">评分</div>
            <div style="display: flex; gap: 10px;">
                @for($i = 1; $i <= 5; $i++)
                <label style="cursor: pointer;">
                    <input type="radio" name="rating" value="{{ $i }}" {{ $i == 5 ? 'checked' : '' }} style="display: none;">
                    <span class="star" data-value="{{ $i }}" onclick="this.previousElementSibling.checked=true;updateStars({{ $i }})" style="font-size: 24px; color: #ddd;">★</span>
                </label>
                @endfor
            </div>
        </div>
        
        <div class="form-group">
            <label>评价内容</label>
            <textarea name="content" rows="4" placeholder="分享你的使用体验..." style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; resize: vertical;"></textarea>
        </div>
        
        <button type="submit" class="btn btn-block">提交评价</button>
        <a href="{{ route('orders.show', $order) }}" class="btn btn-outline btn-block" style="margin-top: 10px; text-align: center;">返回</a>
    </form>
</div>

<style>
    input[type="radio"]:checked + .star { color: #f5c518; }
    .star:hover { color: #f5c518; }
</style>

<script>
function updateStars(value) {
    document.querySelectorAll('.star').forEach((star, index) => {
        star.style.color = index < value ? '#f5c518' : '#ddd';
    });
}
</script>
@endsection
