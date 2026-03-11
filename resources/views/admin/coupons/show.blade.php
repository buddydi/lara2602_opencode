@extends('admin_layout')

@section('title', '优惠券详情')

@section('content')
<div class="page-header">
    <h1>优惠券详情</h1>
    <a href="{{ route('admin.coupons.index') }}" class="btn btn-outline">返回列表</a>
</div>

<div class="info-card">
    <div class="info-row">
        <span class="label">优惠码：</span>
        <span><code>{{ $coupon->code }}</code></span>
    </div>
    <div class="info-row">
        <span class="label">名称：</span>
        <span>{{ $coupon->name }}</span>
    </div>
    <div class="info-row">
        <span class="label">类型：</span>
        <span>{{ $coupon->type === 'fixed' ? '固定金额' : '百分比折扣' }}</span>
    </div>
    <div class="info-row">
        <span class="label">优惠值：</span>
        <span style="color: #e4393c; font-size: 20px;">
            @if($coupon->type === 'fixed')
            ¥{{ $coupon->value }}
            @else
            {{ $coupon->value }}% @if($coupon->max_discount)（最高¥{{ $coupon->max_discount }}）
            @endif
            @endif
        </span>
    </div>
    <div class="info-row">
        <span class="label">最低消费：</span>
        <span>¥{{ $coupon->min_amount }}</span>
    </div>
    <div class="info-row">
        <span class="label">有效期：</span>
        <span>{{ $coupon->start_date->format('Y-m-d H:i') }} ~ {{ $coupon->end_date->format('Y-m-d H:i') }}</span>
    </div>
    <div class="info-row">
        <span class="label">使用限制：</span>
        <span>共 {{ $coupon->usage_limit ?: '不限' }} 次，每人 {{ $coupon->per_user_limit }} 次</span>
    </div>
    <div class="info-row">
        <span class="label">已使用：</span>
        <span>{{ $coupon->usage_count }} 次</span>
    </div>
    <div class="info-row">
        <span class="label">状态：</span>
        <span>
            @if($coupon->is_active)
            <span class="badge badge-success">启用</span>
            @else
            <span class="badge badge-danger">禁用</span>
            @endif
        </span>
    </div>
    @if($coupon->description)
    <div class="info-row">
        <span class="label">描述：</span>
        <span>{{ $coupon->description }}</span>
    </div>
    @endif
</div>

<h3 style="margin: 30px 0 15px;">使用记录</h3>

@if($coupon->usages->isEmpty())
<p style="color: #999; text-align: center; padding: 30px;">暂无使用记录</p>
@else
<table class="data-table">
    <thead>
        <tr>
            <th>用户</th>
            <th>订单号</th>
            <th>使用时间</th>
        </tr>
    </thead>
    <tbody>
        @foreach($coupon->usages as $usage)
        <tr>
            <td>{{ $usage->customer->name }}</td>
            <td>{{ $usage->order?->order_no ?: '-' }}</td>
            <td>{{ $usage->used_at }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
@endif
@endsection
