@extends('front.layout')

@section('title', '我的订单')

@section('content')
<h2 style="margin-bottom: 20px;">我的订单</h2>

@forelse($orders as $order)
<div style="background: #fff; padding: 20px; margin-bottom: 15px; border-radius: 8px;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; padding-bottom: 15px; border-bottom: 1px solid #eee;">
        <div>
            <span style="color: #999;">订单号：</span>{{ $order->order_no }}
            <span style="margin-left: 15px; color: #999;">下单时间：</span>{{ $order->created_at }}
        </div>
        <div style="color: #e4393c; font-size: 18px;">¥{{ $order->total_amount }}</div>
    </div>
    
    <div style="margin-bottom: 15px;">
        @foreach($order->items as $item)
        <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 10px;">
            <img src="{{ $item->product_image ? asset('storage/' . $item->product_image) : 'https://via.placeholder.com/50' }}" style="width: 50px; height: 50px; object-fit: cover;">
            <div>
                <div>{{ $item->product_name }}</div>
                <div style="color: #999; font-size: 12px;">{{ $item->sku_name ?: '默认' }} × {{ $item->quantity }}</div>
            </div>
        </div>
        @endforeach
    </div>
    
    <div style="display: flex; justify-content: space-between; align-items: center;">
        <span style="
            @if($order->status === 'completed') color: #28a745;
            @elseif($order->status === 'cancelled') color: #dc3545;
            @else color: #e4393c;
            @endif
        ">{{ $order->status_text }}</span>
        
        <div>
            <a href="{{ route('orders.show', $order) }}" class="btn btn-outline" style="padding: 5px 15px; font-size: 12px;">查看详情</a>
            @if(in_array($order->status, ['pending', 'paid']))
            <form method="POST" action="{{ route('orders.cancel', $order) }}" style="display: inline;">
                @csrf
                @method('PATCH')
                <button type="submit" style="background:none;border:1px solid #ddd;padding:4px 10px;border-radius:4px;margin-left:5px;cursor:pointer;">取消订单</button>
            </form>
            @endif
        </div>
    </div>
</div>
@empty
<div style="text-align: center; padding: 80px 0;">
    <p style="color: #999; margin-bottom: 20px;">暂无订单</p>
    <a href="{{ route('products.index') }}" class="btn">去购物</a>
</div>
@endforelse

<div class="pagination">
    {{ $orders->links() }}
</div>
@endsection
