@extends('front.layout')

@section('title', '首页')

@section('content')
<div class="product-grid">
    @forelse($products as $product)
    <div class="product-card">
        <a href="{{ route('products.detail', $product) }}">
            <img src="{{ $product->cover_image ? asset('storage/' . $product->cover_image) : 'https://via.placeholder.com/300' }}" alt="{{ $product->name }}">
        </a>
        <div class="product-info">
            <div class="product-name">
                <a href="{{ route('products.detail', $product) }}" style="text-decoration:none;color:inherit;">{{ $product->name }}</a>
            </div>
            <div class="product-price">
                ¥{{ $product->price }}
                @if($product->original_price > $product->price)
                <del>¥{{ $product->original_price }}</del>
                @endif
            </div>
        </div>
    </div>
    @empty
    <p style="grid-column: 1/-1; text-align: center; padding: 50px;">暂无商品</p>
    @endforelse
</div>

<div style="text-align: center; margin-top: 30px;">
    <a href="{{ route('products.index') }}" class="btn btn-outline">查看更多商品</a>
</div>
@endsection
