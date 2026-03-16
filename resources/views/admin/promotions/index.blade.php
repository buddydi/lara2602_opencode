@extends('admin_layout')

@section('title', '促销活动')

@section('content')
<div class="page-header">
    <h1>促销活动</h1>
    <a href="{{ route('admin.promotions.create') }}" class="btn">创建活动</a>
</div>

<div class="filter-bar">
    <form method="GET" style="display: flex; gap: 10px; flex-wrap: wrap; align-items: center;">
        <select name="type" style="padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
            <option value="">全部类型</option>
            <option value="flash_sale" {{ request('type') === 'flash_sale' ? 'selected' : '' }}>秒杀</option>
            <option value="discount" {{ request('type') === 'discount' ? 'selected' : '' }}>折扣</option>
            <option value="full_reduce" {{ request('type') === 'full_reduce' ? 'selected' : '' }}>满减</option>
        </select>
        <select name="status" style="padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
            <option value="">全部状态</option>
            <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>进行中</option>
            <option value="upcoming" {{ request('status') === 'upcoming' ? 'selected' : '' }}>未开始</option>
            <option value="expired" {{ request('status') === 'expired' ? 'selected' : '' }}>已结束</option>
            <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>已禁用</option>
        </select>
        <button type="submit" class="btn">筛选</button>
    </form>
</div>

<table class="data-table">
    <thead>
        <tr>
            <th>ID</th>
            <th>活动名称</th>
            <th>类型</th>
            <th>优惠内容</th>
            <th>时间</th>
            <th>状态</th>
            <th>操作</th>
        </tr>
    </thead>
    <tbody>
        @foreach($promotions as $promotion)
        <tr>
            <td>{{ $promotion->id }}</td>
            <td>{{ $promotion->name }}</td>
            <td>
                @switch($promotion->type)
                    @case('flash_sale')
                    <span class="badge badge-danger">秒杀</span>
                    @break
                    @case('discount')
                    <span class="badge badge-warning">折扣</span>
                    @break
                    @case('full_reduce')
                    <span class="badge badge-success">满减</span>
                    @break
                @endswitch
            </td>
            <td>
                @if($promotion->type === 'discount')
                {{ $promotion->discount_rate }}折
                @elseif($promotion->type === 'full_reduce')
                满{{ $promotion->min_amount }}减{{ $promotion->reduce_amount }}
                @elseif($promotion->type === 'flash_sale')
                ¥{{ $promotion->discount_amount }}
                @endif
            </td>
            <td>
                {{ $promotion->start_time->format('Y-m-d H:i') }}<br>
                {{ $promotion->end_time->format('Y-m-d H:i') }}
            </td>
            <td>
                @if(!$promotion->is_active)
                <span class="badge badge-secondary">已禁用</span>
                @elseif(now() < $promotion->start_time)
                <span class="badge badge-info">未开始</span>
                @elseif(now() > $promotion->end_time)
                <span class="badge badge-secondary">已结束</span>
                @else
                <span class="badge badge-success">进行中</span>
                @endif
            </td>
            <td>
                <a href="{{ route('admin.promotions.show', $promotion) }}" class="btn btn-sm">查看</a>
                <a href="{{ route('admin.promotions.edit', $promotion) }}" class="btn btn-sm">编辑</a>
                <form method="POST" action="{{ route('admin.promotions.toggle', $promotion) }}" style="display: inline;">
                    @csrf
                    <button type="submit" class="btn btn-sm">{{ $promotion->is_active ? '禁用' : '启用' }}</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

<div class="pagination">
    {{ $promotions->links() }}
</div>
@endsection
