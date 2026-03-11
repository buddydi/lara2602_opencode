@extends('front.layout')

@section('title', '申请售后')

@section('content')
<div style="background: #fff; padding: 30px; border-radius: 8px;">
    <h2 style="margin-bottom: 20px;">申请售后服务</h2>
    
    <div style="margin-bottom: 30px; padding: 15px; background: #f9f9f9; border-radius: 8px;">
        <div><span style="color: #999;">订单编号：</span>{{ $order->order_no }}</div>
        <div><span style="color: #999;">订单金额：</span>¥{{ $order->pay_amount }}</div>
    </div>
    
    <form method="POST" action="{{ route('after-sales.store', $order) }}">
        @csrf
        
        <div style="margin-bottom: 20px;">
            <label style="display: block; margin-bottom: 8px; font-weight: bold;">服务类型 *</label>
            <div style="display: flex; gap: 20px;">
                <label style="display: flex; align-items: center; cursor: pointer;">
                    <input type="radio" name="type" value="return" required>
                    <span style="margin-left: 8px;">退货</span>
                </label>
                <label style="display: flex; align-items: center; cursor: pointer;">
                    <input type="radio" name="type" value="exchange">
                    <span style="margin-left: 8px;">换货</span>
                </label>
            </div>
            @error('type')
                <div style="color: #e74c3c; margin-top: 5px;">{{ $message }}</div>
            @enderror
        </div>
        
        <div style="margin-bottom: 20px;">
            <label style="display: block; margin-bottom: 8px; font-weight: bold;">申请原因 *</label>
            <select name="reason" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;">
                <option value="">请选择原因</option>
                <option value="质量问题">质量问题</option>
                <option value="商品不符">商品不符</option>
                <option value="商品损坏">商品损坏</option>
                <option value="不喜欢">不喜欢</option>
                <option value="其他">其他</option>
            </select>
            @error('reason')
                <div style="color: #e74c3c; margin-top: 5px;">{{ $message }}</div>
            @enderror
        </div>
        
        <div style="margin-bottom: 20px;">
            <label style="display: block; margin-bottom: 8px; font-weight: bold;">详细说明</label>
            <textarea name="description" rows="4" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;" placeholder="请详细描述您遇到的问题"></textarea>
        </div>
        
        <div style="display: flex; gap: 10px;">
            <button type="submit" class="btn">提交申请</button>
            <a href="{{ route('orders.show', $order) }}" class="btn btn-outline">返回</a>
        </div>
    </form>
</div>
@endsection
