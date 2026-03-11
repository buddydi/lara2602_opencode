@extends('front.layout')

@section('title', '消息详情')

@section('content')
<div style="background: #fff; padding: 30px; border-radius: 8px;">
    <a href="{{ route('notifications.index') }}" style="color: #666; text-decoration: none;">&lt; 返回消息列表</a>
    
    <div style="margin-top: 20px; padding: 20px; background: #f9f9f9; border-radius: 8px;">
        <h3 style="margin-bottom: 15px;">{{ $notification->title }}</h3>
        <div style="color: #999; font-size: 12px; margin-bottom: 15px;">
            {{ $notification->created_at }}
            @if($notification->type === 'order')
            <span style="margin-left: 15px;">订单通知</span>
            @elseif($notification->type === 'refund')
            <span style="margin-left: 15px;">退款通知</span>
            @elseif($notification->type === 'system')
            <span style="margin-left: 15px;">系统通知</span>
            @endif
        </div>
        <div style="line-height: 1.8; color: #333;">
            {{ $notification->content }}
        </div>
    </div>
    
    @if($notification->data)
    <div style="margin-top: 20px;">
        @if(isset($notification->data['order_id']))
        <a href="{{ route('orders.show', $notification->data['order_id']) }}" class="btn">查看订单</a>
        @endif
    </div>
    @endif
</div>
@endsection
