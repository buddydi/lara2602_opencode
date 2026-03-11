<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', '商城')</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; background: #f5f5f5; }
        .header { background: #fff; box-shadow: 0 2px 4px rgba(0,0,0,0.1); position: sticky; top: 0; z-index: 100; }
        .header-inner { max-width: 1200px; margin: 0 auto; padding: 15px 20px; display: flex; align-items: center; justify-content: space-between; }
        .logo { font-size: 24px; font-weight: bold; color: #e4393c; text-decoration: none; }
        .nav { display: flex; gap: 30px; }
        .nav a { text-decoration: none; color: #333; font-size: 14px; }
        .nav a:hover { color: #e4393c; }
        .user-area { display: flex; align-items: center; gap: 15px; }
        .user-area a { text-decoration: none; color: #666; font-size: 14px; }
        .cart-icon { position: relative; }
        .cart-badge { position: absolute; top: -8px; right: -8px; background: #e4393c; color: #fff; font-size: 12px; padding: 2px 6px; border-radius: 10px; }
        .mini-cart { position: relative; }
        .mini-cart-dropdown { display: none; position: absolute; right: 0; top: 100%; width: 280px; background: #fff; border: 1px solid #eee; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); z-index: 1000; padding: 10px; }
        .mini-cart:hover .mini-cart-dropdown { display: block; }
        .mini-cart-items { max-height: 200px; overflow-y: auto; }
        .mini-cart-item { display: flex; gap: 10px; padding: 10px 0; border-bottom: 1px solid #eee; }
        .mini-cart-item img { width: 40px; height: 40px; object-fit: cover; border-radius: 4px; }
        .mini-cart-item-info { flex: 1; }
        .mini-cart-item-info .name { font-size: 12px; color: #333; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
        .mini-cart-item-info .price { font-size: 12px; color: #e4393c; }
        .mini-cart-total { padding: 10px 0; font-size: 14px; color: #333; text-align: right; }
        .mini-cart-btn { display: block; text-align: center; background: #e4393c; color: #fff; padding: 8px; border-radius: 4px; text-decoration: none; }
        .mini-cart-empty { padding: 20px; text-align: center; color: #999; }
        .container { max-width: 1200px; margin: 20px auto; padding: 0 20px; }
        .product-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; }
        .product-card { background: #fff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.06); transition: transform 0.2s; }
        .product-card:hover { transform: translateY(-4px); box-shadow: 0 4px 12px rgba(0,0,0,0.1); }
        .product-card img { width: 100%; height: 200px; object-fit: cover; }
        .product-info { padding: 15px; }
        .product-name { font-size: 14px; color: #333; margin-bottom: 10px; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
        .product-price { color: #e4393c; font-size: 18px; font-weight: bold; }
        .product-price del { font-size: 12px; color: #999; font-weight: normal; margin-left: 5px; }
        .btn { display: inline-block; padding: 8px 20px; background: #e4393c; color: #fff; border: none; border-radius: 4px; cursor: pointer; text-decoration: none; font-size: 14px; }
        .btn:hover { background: #c23536; }
        .btn-outline { background: #fff; border: 1px solid #e4393c; color: #e4393c; }
        .btn-outline:hover { background: #fff5f5; }
        .btn-block { display: block; width: 100%; text-align: center; }
        .pagination { display: flex; justify-content: center; margin-top: 30px; gap: 10px; }
        .pagination a, .pagination span { padding: 8px 12px; background: #fff; border: 1px solid #ddd; color: #333; text-decoration: none; }
        .pagination .active { background: #e4393c; color: #fff; border-color: #e4393c; }
        .breadcrumb { padding: 15px 0; color: #666; font-size: 14px; }
        .breadcrumb a { color: #666; text-decoration: none; }
        .product-detail { display: flex; gap: 30px; background: #fff; padding: 30px; border-radius: 8px; }
        .product-images { width: 400px; }
        .product-images img { width: 100%; height: 400px; object-fit: cover; border-radius: 8px; }
        .product-main { flex: 1; }
        .product-title { font-size: 24px; margin-bottom: 15px; }
        .product-summary { color: #666; margin-bottom: 20px; }
        .product-attrs { margin: 20px 0; }
        .attr-row { display: flex; align-items: center; margin-bottom: 15px; }
        .attr-label { width: 80px; color: #666; }
        .attr-values { display: flex; gap: 10px; flex-wrap: wrap; }
        .attr-value { padding: 6px 15px; border: 1px solid #ddd; cursor: pointer; }
        .attr-value.active { border-color: #e4393c; background: #fff5f5; color: #e4393c; }
        .quantity-selector { display: flex; align-items: center; }
        .quantity-selector input { width: 50px; text-align: center; border: 1px solid #ddd; padding: 6px; }
        .quantity-selector button { width: 30px; height: 30px; border: 1px solid #ddd; background: #fff; cursor: pointer; }
        .action-buttons { display: flex; gap: 15px; margin-top: 30px; }
        .cart-table { width: 100%; background: #fff; border-collapse: collapse; }
        .cart-table th { background: #f5f5f5; padding: 15px; text-align: left; }
        .cart-table td { padding: 15px; border-bottom: 1px solid #eee; }
        .cart-summary { background: #fff; padding: 20px; margin-top: 20px; text-align: right; }
        .cart-total { font-size: 24px; color: #e4393c; }
        .form-card { background: #fff; padding: 30px; border-radius: 8px; max-width: 500px; margin: 50px auto; }
        .form-title { font-size: 20px; text-align: center; margin-bottom: 30px; }
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; margin-bottom: 8px; color: #333; }
        .form-group input, .form-group select, .form-group textarea { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; }
        .alert { padding: 12px 15px; background: #fdecea; border: 1px solid #fadbd8; color: #e74c3c; margin-bottom: 20px; border-radius: 4px; }
        .success { background: #d4edda; border-color: #c3e6cb; color: #155724; }
    </style>
    @yield('styles')
</head>
<body>
    <div class="header">
        <div class="header-inner">
            <a href="{{ route('home') }}" class="logo">商城</a>
            <nav class="nav">
                <a href="{{ route('home') }}">首页</a>
                <a href="{{ route('products.index') }}">商品列表</a>
            </nav>
            <div class="user-area">
                <div class="mini-cart">
                    <a href="{{ route('cart.index') }}" class="cart-icon">
                        购物车
                        @auth('customer')
                            @if(Auth::guard('customer')->user()->cartItems->sum('quantity') > 0)
                                <span class="cart-badge">{{ Auth::guard('customer')->user()->cartItems->sum('quantity') }}</span>
                            @endif
                        @endauth
                    </a>
                    <div class="mini-cart-dropdown">
                        @auth('customer')
                            @php
                                $miniCartItems = Auth::guard('customer')->user()->cartItems()->with('product')->take(3)->get();
                                $miniCartTotal = $miniCartItems->sum(fn($item) => ($item->sku ? $item->sku->price : $item->product->price) * $item->quantity);
                            @endphp
                            @if($miniCartItems->count() > 0)
                                <div class="mini-cart-items">
                                    @foreach($miniCartItems as $item)
                                    <div class="mini-cart-item">
                                        <img src="{{ $item->product->cover_image ? asset('storage/' . $item->product->cover_image) : 'https://via.placeholder.com/40' }}" alt="">
                                        <div class="mini-cart-item-info">
                                            <div class="name">{{ $item->product->name }}</div>
                                            <div class="price">¥{{ $item->sku ? $item->sku->price : $item->product->price }} x {{ $item->quantity }}</div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                                <div class="mini-cart-total">合计: ¥{{ $miniCartTotal }}</div>
                                <a href="{{ route('cart.index') }}" class="mini-cart-btn">查看购物车</a>
                            @else
                                <div class="mini-cart-empty">购物车是空的</div>
                            @endif
                        @else
                            <div class="mini-cart-empty">请先登录</div>
                        @endauth
                    </div>
                </div>
                @auth('customer')
                    <a href="{{ route('orders.index') }}" style="position: relative;">
                        我的订单
                        @if(Auth::guard('customer')->user()->orders()->count() > 0)
                            <span class="cart-badge" style="top: -5px;">{{ Auth::guard('customer')->user()->orders()->count() }}</span>
                        @endif
                    </a>
                    <a href="{{ route('addresses.index') }}">收货地址</a>
                    <a href="{{ route('invoices.index') }}">我的发票</a>
                    <a href="{{ route('refunds.index') }}">退款记录</a>
                    <a href="{{ route('points.index') }}">我的积分</a>
                    <a href="{{ route('notifications.index') }}">我的消息</a>
                    <span>{{ Auth::guard('customer')->user()->name }}</span>
                    <form method="POST" action="{{ route('customer.logout') }}" style="display:inline;">
                        @csrf
                        <button type="submit" style="background:none;border:none;color:#666;cursor:pointer;font-size:14px;">退出</button>
                    </form>
                @else
                    <a href="{{ route('customer.login') }}">登录</a>
                    <a href="{{ route('customer.register') }}">注册</a>
                @endauth
            </div>
        </div>
    </div>
    
    <div class="container">
        @if(session('success'))
            <div class="alert success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert">{{ session('error') }}</div>
        @endif
        
        @yield('content')
    </div>
</body>
</html>
