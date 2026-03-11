@extends('front.layout')

@section('title', '申请退款')

@section('content')
<div style="background: #fff; padding: 30px; border-radius: 8px; max-width: 600px; margin: 0 auto;">
    <h2 style="margin-bottom: 20px; text-align: center;">申请退款</h2>
    
    <div style="background: #f9f9f9; padding: 20px; border-radius: 8px; margin-bottom: 20px;">
        <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
            <span style="color: #999;">订单编号：</span>
            <span>{{ $order->order_no }}</span>
        </div>
        <div style="display: flex; justify-content: space-between;">
            <span style="color: #999;">退款金额：</span>
            <span style="color: #e4393c; font-weight: bold;">¥{{ $order->pay_amount }}</span>
        </div>
    </div>
    
    <form method="POST" action="{{ route('orders.refund.store', $order) }}">
        @csrf
        
        <div class="form-group">
            <label>退款原因 *</label>
            <select name="reason" class="form-control" required>
                <option value="">请选择退款原因</option>
                <option value="不想要了">不想要了</option>
                <option value="商品质量问题">商品质量问题</option>
                <option value="商品与描述不符">商品与描述不符</option>
                <option value="物流太慢">物流太慢</option>
                <option value="其他原因">其他原因</option>
            </select>
        </div>
        
        <div class="form-group">
            <label>详细说明</label>
            <textarea name="description" rows="4" placeholder="请详细描述退款原因..." style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;"></textarea>
        </div>
        
        <button type="submit" class="btn btn-block">提交退款申请</button>
        <a href="{{ route('orders.show', $order) }}" class="btn btn-outline btn-block" style="margin-top: 10px; text-align: center;">返回</a>
    </form>
</div>
@endsection
