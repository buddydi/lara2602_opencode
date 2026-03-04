@extends('front.layout')

@section('title', '支付订单')

@section('content')
<div style="background: #fff; padding: 30px; border-radius: 8px; max-width: 600px; margin: 0 auto;">
    <h2 style="margin-bottom: 20px; text-align: center;">支付订单</h2>
    
    <div style="background: #f9f9f9; padding: 20px; border-radius: 8px; margin-bottom: 20px;">
        <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
            <span style="color: #999;">订单编号：</span>
            <span>{{ $order->order_no }}</span>
        </div>
        <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
            <span style="color: #999;">商品数量：</span>
            <span>{{ $order->product_count }}</span>
        </div>
        <div style="display: flex; justify-content: space-between; font-size: 18px;">
            <span>应付金额：</span>
            <span style="color: #e4393c; font-weight: bold;">¥{{ $order->pay_amount }}</span>
        </div>
    </div>
    
    <form method="POST" action="{{ route('orders.processPayment', $order) }}">
        @csrf
        <h3 style="margin-bottom: 15px; font-size: 16px;">选择支付方式</h3>
        
        <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 15px; margin-bottom: 20px;">
            <label style="cursor: pointer;">
                <input type="radio" name="pay_method" value="alipay" checked style="display: none;">
                <div class="pay-method" style="border: 2px solid #ddd; border-radius: 8px; padding: 20px; text-align: center;" onclick="this.querySelector('input').checked=true">
                    <div style="font-size: 24px; margin-bottom: 5px;">💳</div>
                    <div>支付宝</div>
                </div>
            </label>
            
            <label style="cursor: pointer;">
                <input type="radio" name="pay_method" value="wechat" style="display: none;">
                <div class="pay-method" style="border: 2px solid #ddd; border-radius: 8px; padding: 20px; text-align: center;" onclick="this.querySelector('input').checked=true">
                    <div style="font-size: 24px; margin-bottom: 5px;">💬</div>
                    <div>微信支付</div>
                </div>
            </label>
            
            <label style="cursor: pointer;">
                <input type="radio" name="pay_method" value="balance" style="display: none;">
                <div class="pay-method" style="border: 2px solid #ddd; border-radius: 8px; padding: 20px; text-align: center;" onclick="this.querySelector('input').checked=true">
                    <div style="font-size: 24px; margin-bottom: 5px;">💰</div>
                    <div>余额支付</div>
                </div>
            </label>
        </div>
        
        <style>
            input[type="radio"]:checked + .pay-method {
                border-color: #e4393c;
                background: #fff5f5;
            }
        </style>
        
        <button type="submit" class="btn btn-block" style="font-size: 18px; padding: 15px;">确认支付 ¥{{ $order->pay_amount }}</button>
    </form>
    
    <div style="text-align: center; margin-top: 20px;">
        <a href="{{ route('orders.show', $order) }}" style="color: #666;">返回订单详情</a>
    </div>
</div>
@endsection
