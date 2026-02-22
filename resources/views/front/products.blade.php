@extends('front.layout')

@section('title', '商品列表')

@section('content')
<div style="display: flex; gap: 20px;">
    <div style="width: 200px; background: #fff; padding: 20px; border-radius: 8px; height: fit-content;">
        <h3 style="margin-bottom: 15px; font-size: 16px;">商品分类</h3>
        <div style="margin-bottom: 20px;">
            @foreach($categories as $cat)
            <div style="margin-bottom: 10px;">
                <a href="{{ route('products.index', ['category_id' => $cat->id]) }}" style="text-decoration:none;color:#333;font-weight:500;">{{ $cat->name }}</a>
                @if($cat->children->count() > 0)
                <div style="margin-left: 10px; margin-top: 5px;">
                    @foreach($cat->children as $child)
                    <div><a href="{{ route('products.index', ['category_id' => $child->id]) }}" style="text-decoration:none;color:#666;font-size:13px;">{{ $child->name }}</a></div>
                    @endforeach
                </div>
                @endif
            </div>
            @endforeach
        </div>
        
        <h3 style="margin-bottom: 15px; font-size: 16px;">品牌</h3>
        <div>
            @foreach($brands as $brand)
            <div style="margin-bottom: 8px;">
                <a href="{{ route('products.index', ['brand_id' => $brand->id]) }}" style="text-decoration:none;color:#666;font-size:13px;">{{ $brand->name }}</a>
            </div>
            @endforeach
        </div>
    </div>
    
    <div style="flex: 1;">
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
        
        <div class="pagination">
            {{ $products->links() }}
        </div>
    </div>
</div>
@endsection
