@extends('admin_layout')

@section('title', '积分管理')

@section('content')
<div class="page-header">
    <h1>积分管理</h1>
</div>

<div class="filter-bar">
    <form method="GET" style="display: flex; gap: 10px; flex-wrap: wrap;">
        <select name="level" style="padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
            <option value="">全部等级</option>
            @foreach($levels as $key => $level)
            <option value="{{ $key }}" {{ request('level') === $key ? 'selected' : '' }}>
                {{ $level['name'] }}
            </option>
            @endforeach
        </select>
        <input type="text" name="keyword" placeholder="搜索客户..." value="{{ request('keyword') }}" style="padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
        <button type="submit" class="btn">搜索</button>
    </form>
</div>

<table class="data-table">
    <thead>
        <tr>
            <th>客户</th>
            <th>邮箱</th>
            <th>手机</th>
            <th>积分</th>
            <th>等级</th>
            <th>操作</th>
        </tr>
    </thead>
    <tbody>
        @foreach($customers as $customer)
        <tr>
            <td>{{ $customer->name }}</td>
            <td>{{ $customer->email }}</td>
            <td>{{ $customer->phone ?: '-' }}</td>
            <td style="color: #e4393c; font-weight: bold;">{{ $customer->points }}</td>
            <td>
                <span style="padding: 3px 8px; background: #f0f0f0; border-radius: 3px; font-size: 12px;">
                    {{ $customer->member_level_name }}
                </span>
            </td>
            <td>
                <a href="{{ route('admin.points.show', $customer) }}" class="btn btn-sm">查看详情</a>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

<div class="pagination">
    {{ $customers->links() }}
</div>
@endsection
