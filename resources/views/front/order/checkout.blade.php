<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>结算页面 - NewShop</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; background: #f5f5f5; }
        .container { max-width: 1200px; margin: 0 auto; padding: 20px; }
        h1 { font-size: 24px; margin-bottom: 20px; }
        .section { background: #fff; border-radius: 8px; padding: 20px; margin-bottom: 20px; }
        .section-title { font-size: 16px; font-weight: bold; margin-bottom: 15px; padding-bottom: 10px; border-bottom: 1px solid #eee; }
        
        .address-list { display: grid; grid-template-columns: repeat(3, 1fr); gap: 15px; }
        .address-item { border: 2px solid #ddd; border-radius: 8px; padding: 15px; cursor: pointer; transition: all 0.2s; }
        .address-item:hover { border-color: #e4393c; }
        .address-item.selected { border-color: #e4393c; background: #fff5f5; }
        .address-item.default { position: relative; }
        .address-item.default::before { content: '默认'; position: absolute; top: 10px; right: 10px; background: #e4393c; color: #fff; font-size: 12px; padding: 2px 8px; border-radius: 4px; }
        .address-name { font-weight: bold; margin-bottom: 5px; }
        .address-phone { color: #666; font-size: 14px; }
        .address-detail { color: #999; font-size: 14px; margin-top: 5px; }
        
        .shipping-methods { display: flex; gap: 20px; }
        .shipping-method { flex: 1; border: 2px solid #ddd; border-radius: 8px; padding: 15px; cursor: pointer; text-align: center; transition: all 0.2s; }
        .shipping-method:hover { border-color: #e4393c; }
        .shipping-method.selected { border-color: #e4393c; background: #fff5f5; }
        .shipping-method-name { font-weight: bold; }
        .shipping-method-price { color: #e4393c; margin-top: 5px; }
        
        .cart-items { border: 1px solid #eee; border-radius: 8px; overflow: hidden; }
        .cart-item { display: flex; align-items: center; padding: 15px; border-bottom: 1px solid #eee; }
        .cart-item:last-child { border-bottom: none; }
        .cart-item-img { width: 80px; height: 80px; object-fit: cover; border-radius: 4px; background: #f5f5f5; }
        .cart-item-info { flex: 1; margin-left: 15px; }
        .cart-item-name { font-weight: bold; margin-bottom: 5px; }
        .cart-item-sku { color: #999; font-size: 14px; }
        .cart-item-price { color: #e4393c; font-size: 16px; font-weight: bold; }
        .cart-item-qty { color: #666; margin-left: 20px; }
        
        .summary { background: #fff; border-radius: 8px; padding: 20px; }
        .summary-row { display: flex; justify-content: space-between; margin-bottom: 10px; }
        .summary-total { font-size: 18px; font-weight: bold; border-top: 1px solid #eee; padding-top: 15px; margin-top: 15px; }
        .summary-total .price { color: #e4393c; font-size: 24px; }
        
        .btn { display: inline-block; padding: 12px 30px; background: #e4393c; color: #fff; border: none; border-radius: 4px; font-size: 16px; cursor: pointer; text-align: center; }
        .btn:hover { background: #d3303a; }
        .btn:disabled { background: #ccc; cursor: not-allowed; }
        
        .no-address { text-align: center; padding: 40px; color: #999; }
        .no-address a { color: #e4393c; }
    </style>
</head>
<body>
    <div class="container">
        <h1>确认订单</h1>
        
        <form id="checkout-form" method="POST" action="{{ route('orders.store') }}">
            @csrf
            <input type="hidden" name="address_id" id="address_id" value="">
            <input type="hidden" name="shipping_method" id="shipping_method" value="standard">
            <input type="hidden" name="payment_method" id="payment_method" value="online">
            
            <div class="section">
                <div class="section-title">收货地址</div>
                @if($addresses->count() > 0)
                <div class="address-list">
                    @foreach($addresses as $address)
                    <div class="address-item {{ $address->is_default ? 'default' : '' }}" data-id="{{ $address->id }}" onclick="selectAddress({{ $address->id }})">
                        <div class="address-name">{{ $address->name }}</div>
                        <div class="address-phone">{{ $address->phone }}</div>
                        <div class="address-detail">{{ $address->province }} {{ $address->city }} {{ $address->district }} {{ $address->detail_address }}</div>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="no-address">
                    暂无收货地址，<a href="{{ route('addresses.create') }}">添加新地址</a>
                </div>
                @endif
            </div>
            
            <div class="section">
                <div class="section-title">配送方式</div>
                <div class="shipping-methods">
                    <div class="shipping-method selected" data-method="standard" onclick="selectShipping('standard', 0)">
                        <div class="shipping-method-name">标准配送</div>
                        <div class="shipping-method-price">免费</div>
                    </div>
                    <div class="shipping-method" data-method="express" onclick="selectShipping('express', 10)">
                        <div class="shipping-method-name">快递配送</div>
                        <div class="shipping-method-price">¥10.00</div>
                    </div>
                </div>
            </div>
            
            <div class="section">
                <div class="section-title">商品清单</div>
                <div class="cart-items">
                    @foreach($cartItems as $item)
                    <div class="cart-item">
                        <img src="{{ $item->product->cover_image ?: '/images/placeholder.png' }}" class="cart-item-img" alt="{{ $item->product->name }}">
                        <div class="cart-item-info">
                            <div class="cart-item-name">{{ $item->product->name }}</div>
                            @if($item->sku)
                            <div class="cart-item-sku">{{ $item->sku->name }}</div>
                            @endif
                        </div>
                        <div class="cart-item-price">¥{{ ($item->sku ? $item->sku->price : $item->product->price) }}</div>
                        <div class="cart-item-qty">x {{ $item->quantity }}</div>
                    </div>
                    @endforeach
                </div>
            </div>
            
            <div class="summary">
                <div class="summary-row">
                    <span>商品总价</span>
                    <span>¥{{ number_format($subtotal, 2) }}</span>
                </div>
                <div class="summary-row">
                    <span>运费</span>
                    <span id="shipping-fee">¥0.00</span>
                </div>
                <div class="summary-row summary-total">
                    <span>应付总额</span>
                    <span class="price" id="total-price">¥{{ number_format($subtotal, 2) }}</span>
                </div>
                <button type="submit" class="btn" id="submit-btn" @if($addresses->count() == 0) disabled @endif>提交订单</button>
            </div>
        </form>
    </div>
    
    <script>
        let selectedAddressId = {{ $addresses->where('is_default', true)->first()?->id ?? $addresses->first()?->id ?? 0 }};
        let shippingFee = 0;
        
        function selectAddress(id) {
            selectedAddressId = id;
            document.getElementById('address_id').value = id;
            document.querySelectorAll('.address-item').forEach(item => {
                item.classList.toggle.dataset.id == id('selected', item);
            });
        }
        
        function selectShipping(method, fee) {
            shippingFee = fee;
            document.getElementById('shipping_method').value = method;
            document.getElementById('shipping-fee').textContent = '¥' + fee.toFixed(2);
            document.getElementById('total-price').textContent = '¥' + ({{ $subtotal }} + fee).toFixed(2);
            document.querySelectorAll('.shipping-method').forEach(item => {
                item.classList.toggle('selected', item.dataset.method == method);
            });
        }
        
        @if($addresses->count() > 0)
        selectAddress(selectedAddressId);
        @endif
    </script>
</body>
</html>
