@extends('admin_layout')

@section('title', '客户积分详情')

@section('content')
<div class="page-header">
    <h1>客户积分详情</h1>
    <a href="{{ route('admin.points.index') }}" class="btn btn-outline">返回列表</a>
</div>

<div class="info-card">
    <div class="info-row">
        <span class="label">客户姓名：</span>
        <span>{{ $customer->name }}</span>
    </div>
    <div class="info-row">
        <span class="label">邮箱：</span>
        <span>{{ $customer->email }}</span>
    </div>
    <div class="info-row">
        <span class="label">手机：</span>
        <span>{{ $customer->phone ?: '-' }}</span>
    </div>
    <div class="info-row">
        <span class="label">当前积分：</span>
        <span style="color: #e4393c; font-size: 24px; font-weight: bold;">{{ $customer->points }}</span>
    </div>
    <div class="info-row">
        <span class="label">会员等级：</span>
        <span>{{ $customer->member_level_name }}</span>
    </div>
</div>

<div style="display: flex; gap: 20px; margin: 20px 0;">
    <div style="flex: 1; padding: 20px; background: #f9f9f9; border-radius: 8px;">
        <h4 style="margin-bottom: 15px;">添加积分</h4>
        <form method="POST" action="{{ route('admin.points.add', $customer) }}">
            @csrf
            <div style="margin-bottom: 10px;">
                <input type="number" name="points" placeholder="积分数量" required min="1" style="padding: 8px; border: 1px solid #ddd; border-radius: 4px; width: 100%;">
            </div>
            <div style="margin-bottom: 10px;">
                <input type="text" name="description" placeholder="备注（可选）" style="padding: 8px; border: 1px solid #ddd; border-radius: 4px; width: 100%;">
            </div>
            <button type="submit" class="btn">添加</button>
        </form>
    </div>
    <div style="flex: 1; padding: 20px; background: #f9f9f9; border-radius: 8px;">
        <h4 style="margin-bottom: 15px;">扣减积分</h4>
        <form method="POST" action="{{ route('admin.points.deduct', $customer) }}">
            @csrf
            <div style="margin-bottom: 10px;">
                <input type="number" name="points" placeholder="积分数量" required min="1" max="{{ $customer->points }}" style="padding: 8px; border: 1px solid #ddd; border-radius: 4px; width: 100%;">
            </div>
            <div style="margin-bottom: 10px;">
                <input type="text" name="description" placeholder="备注（可选）" style="padding: 8px; border: 1px solid #ddd; border-radius: 4px; width: 100%;">
            </div>
            <button type="submit" class="btn btn-outline">扣减</button>
        </form>
    </div>
</div>

<h3 style="margin: 30px 0 15px;">积分记录</h3>

@if($records->isEmpty())
<p style="color: #999; text-align: center; padding: 30px;">暂无记录</p>
@else
<table class="data-table">
    <thead>
        <tr>
            <th>类型</th>
            <th>积分</th>
            <th>描述</th>
            <th>订单号</th>
            <th>时间</th>
        </tr>
    </thead>
    <tbody>
        @foreach($records as $record)
        <tr>
            <td>
                @if($record->points > 0)
                <span style="color: #28a745;">获得</span>
                @else
                <span style="color: #dc3545;">使用</span>
                @endif
            </td>
            <td style="{{ $record->points > 0 ? 'color: #28a745;' : 'color: #dc3545;' }}">
                {{ $record->points > 0 ? '+' : '' }}{{ $record->points }}
            </td>
            <td>{{ $record->description }}</td>
            <td>{{ $record->order?->order_no ?: '-' }}</td>
            <td>{{ $record->created_at }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

<div class="pagination">
    {{ $records->links() }}
</div>
@endif
@endsection
