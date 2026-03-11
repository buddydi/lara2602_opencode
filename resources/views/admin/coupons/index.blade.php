@extends('admin_layout')

@section('title', '优惠券管理')

@section('content')
<div class="page-header">
    <h1>优惠券管理</h1>
    <a href="{{ route('admin.coupons.create') }}" class="btn">新增优惠券</a>
</div>

<div class="filter-bar">
    <form method="GET" style="display: flex; gap: 10px; flex-wrap: wrap;">
        <select name="status" style="padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
            <option value="">全部状态</option>
            <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>启用</option>
            <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>禁用</option>
        </select>
        <input type="text" name="keyword" placeholder="搜索名称/编码..." value="{{ request('keyword') }}" style="padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
        <button type="submit" class="btn">搜索</button>
    </form>
</div>

<table class="data-table">
    <thead>
        <tr>
            <th>编码</th>
            <th>名称</th>
            <th>类型</th>
            <th>优惠</th>
            <th>最低消费</th>
            <th>有效期</th>
            <th>已使用/限制</th>
            <th>状态</th>
            <th>操作</th>
        </tr>
    </thead>
    <tbody>
        @foreach($coupons as $coupon)
        <tr>
            <td><code>{{ $coupon->code }}</code></td>
            <td>{{ $coupon->name }}</td>
            <td>
                @if($coupon->type === 'fixed')
                <span class="badge">满减券</span>
                @else
                <span class="badge">折扣券</span>
                @endif
            </td>
            <td>
                @if($coupon->type === 'fixed')
                ¥{{ $coupon->value }}
                @else
                {{ $coupon->value }}%
                @endif
            </td>
            <td>¥{{ $coupon->min_amount }}</td>
            <td>
                {{ $coupon->start_date->format('Y-m-d') }} ~ {{ $coupon->end_date->format('Y-m-d') }}
            </td>
            <td>
                {{ $coupon->usage_count }}/{{ $coupon->usage_limit ?: '∞' }}
            </td>
            <td>
                @if($coupon->is_active)
                <span class="badge badge-success">启用</span>
                @else
                <span class="badge badge-danger">禁用</span>
                @endif
            </td>
            <td>
                <a href="{{ route('admin.coupons.show', $coupon) }}" class="btn btn-sm">查看</a>
                <a href="{{ route('admin.coupons.edit', $coupon) }}" class="btn btn-sm btn-outline">编辑</a>
                <a href="{{ route('admin.coupons.toggle', $coupon) }}" class="btn btn-sm btn-outline" onclick="return confirm('确定切换状态？')">
                    {{ $coupon->is_active ? '禁用' : '启用' }}
                </a>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

<div class="pagination">
    {{ $coupons->links() }}
</div>
@endsection
