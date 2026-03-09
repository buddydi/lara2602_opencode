@extends('front.layout')

@section('title', $product->name)

@section('content')
<div class="breadcrumb">
    <a href="{{ route('home') }}">首页</a> &gt; 
    <a href="{{ route('products.index') }}">商品列表</a> &gt; 
    {{ $product->name }}
</div>

<div class="product-detail">
    <div class="product-images">
        <img src="{{ $product->cover_image ? asset('storage/' . $product->cover_image) : 'https://via.placeholder.com/400' }}" alt="{{ $product->name }}">
    </div>
    <div class="product-main">
        <h1 class="product-title">{{ $product->name }}</h1>
        <p class="product-summary">{{ $product->description }}</p>
        
        <div style="background: #f9f9f9; padding: 20px; margin: 20px 0; border-radius: 8px;">
            <div style="font-size: 24px; color: #e4393c; margin-bottom: 10px;">
                ¥{{ $product->price }}
                @if($product->original_price > $product->price)
                <del style="font-size: 14px; color: #999; margin-left: 10px;">¥{{ $product->original_price }}</del>
                @endif
            </div>
            <div style="color: #666; font-size: 14px;">
                库存: {{ $product->skus->sum('stock') }} 件
            </div>
        </div>
        
        @if($product->skus->count() > 0)
        <div class="product-attrs">
            <div class="attr-row">
                <span class="attr-label">选择规格:</span>
                <div class="attr-values">
                    @foreach($product->skus as $sku)
                    <div class="attr-value" data-sku-id="{{ $sku->id }}" data-price="{{ $sku->price }}" data-stock="{{ $sku->stock }}">
                        {{ $sku->name ?: '默认' }}
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif
        
        <div class="attr-row">
            <span class="attr-label">数量:</span>
            <div class="quantity-selector">
                <button type="button" onclick="changeQuantity(-1)">-</button>
                <input type="number" id="quantity" value="1" min="1">
                <button type="button" onclick="changeQuantity(1)">+</button>
            </div>
        </div>
        
        <div class="action-buttons">
            <button class="btn btn-block" onclick="addToCart({{ $product->id }})" style="width: 200px;">加入购物车</button>
        </div>
    </div>
</div>

@if($product->review_count > 0)
<div style="margin-top: 30px; background: #fff; padding: 30px; border-radius: 8px;">
    <h2 style="font-size: 18px; margin-bottom: 20px;">
        用户评价 
        <span style="font-size: 14px; color: #666; font-weight: normal;">
            ({{ $product->review_count }}条评价)
            @if($product->rating > 0)
            <span style="color: #f5c518;">★</span> {{ number_format($product->rating, 1) }}分
            @endif
        </span>
    </h2>
    @foreach($product->reviews()->latest()->take(5)->get() as $review)
    <div style="padding: 15px; border-bottom: 1px solid #eee;">
        <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
            <span>{{ $review->customer->name }}</span>
            <span style="color: #f5c518;">
                @for($i = 1; $i <= 5; $i++)
                    {{ $i <= $review->rating ? '★' : '☆' }}
                @endfor
            </span>
        </div>
        <div style="color: #666;">{{ $review->content ?: '默认好评' }}</div>
        <div style="color: #999; font-size: 12px; margin-top: 5px;">{{ $review->created_at }}</div>
    </div>
    @endforeach
    @if($product->review_count > 5)
    <div style="text-align: center; margin-top: 15px;">
        <span style="color: #999;">查看更多评价</span>
    </div>
    @endif
</div>
@endif

<div style="margin-top: 30px; background: #fff; padding: 30px; border-radius: 8px;">
    <h2 style="font-size: 18px; margin-bottom: 20px;">商品详情</h2>
    <div style="line-height: 2; color: #666;">
        {!! nl2br(e($product->content)) !!}
    </div>
</div>

@if($relatedProducts->count() > 0)
<div style="margin-top: 30px;">
    <h2 style="font-size: 18px; margin-bottom: 20px;">相关商品</h2>
    <div class="product-grid">
        @foreach($relatedProducts as $related)
        <div class="product-card">
            <a href="{{ route('products.detail', $related) }}">
                <img src="{{ $related->cover_image ? asset('storage/' . $related->cover_image) : 'https://via.placeholder.com/300' }}" alt="{{ $related->name }}">
            </a>
            <div class="product-info">
                <div class="product-name">
                    <a href="{{ route('products.detail', $related) }}" style="text-decoration:none;color:inherit;">{{ $related->name }}</a>
                </div>
                <div class="product-price">¥{{ $related->price }}</div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endif

<form id="cart-form" method="POST" action="{{ route('cart.add') }}" style="display: none;">
    @csrf
    <input type="hidden" name="product_id" id="cart-product-id">
    <input type="hidden" name="sku_id" id="cart-sku-id">
    <input type="hidden" name="quantity" id="cart-quantity">
</form>

<script>
let selectedSkuId = null;
const isCustomerLoggedIn = {{ auth('customer')->check() ? 'true' : 'false' }};

function changeQuantity(delta) {
    const input = document.getElementById('quantity');
    let val = parseInt(input.value) + delta;
    if (val < 1) val = 1;
    input.value = val;
}

document.querySelectorAll('.attr-value').forEach(el => {
    el.addEventListener('click', function() {
        document.querySelectorAll('.attr-value').forEach(e => e.classList.remove('active'));
        this.classList.add('active');
        selectedSkuId = this.dataset.skuId;
        document.getElementById('cart-sku-id').value = selectedSkuId;
    });
});

if (document.querySelector('.attr-value')) {
    document.querySelector('.attr-value').click();
}

function addToCart(productId) {
    if (!isCustomerLoggedIn) {
        if (confirm('请先登录后加入购物车，是否立即登录？')) {
            window.location.href = '{{ route("customer.login") }}';
        }
        return;
    }
    
    document.getElementById('cart-product-id').value = productId;
    document.getElementById('cart-quantity').value = document.getElementById('quantity').value;
    document.getElementById('cart-form').submit();
}
</script>
@endsection
