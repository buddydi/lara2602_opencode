@extends('admin_layout')

@section('title', '预警配置')

@section('content')
<div class="page-header">
    <h1>预警配置</h1>
    <div>
        <a href="{{ route('admin.stock.index') }}" class="btn btn-outline">库存管理</a>
        <a href="{{ route('admin.stock.logs') }}" class="btn btn-outline">出入库记录</a>
        <a href="{{ route('admin.stock.create-alert') }}" class="btn">添加预警</a>
    </div>
</div>

<div class="filter-bar">
    <form method="GET" style="display: flex; gap: 10px; align-items: center;">
        <select name="is_enabled" style="padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
            <option value="">全部状态</option>
            <option value="1" {{ request('is_enabled') === '1' ? 'selected' : '' }}>已启用</option>
            <option value="0" {{ request('is_enabled') === '0' ? 'selected' : '' }}>已禁用</option>
        </select>
        <button type="submit" class="btn">筛选</button>
    </form>
</div>

<table class="data-table">
    <thead>
        <tr>
            <th>商品</th>
            <th>SKU</th>
            <th>低库存阈值</th>
            <th>紧急阈值</th>
            <th>状态</th>
            <th>通知管理员</th>
            <th>操作</th>
        </tr>
    </thead>
    <tbody>
        @forelse($alerts as $alert)
        <tr>
            <td>{{ $alert->product->name }}</td>
            <td>{{ $alert->sku?->name ?: '全部' }}</td>
            <td>{{ $alert->low_stock_threshold }}</td>
            <td>{{ $alert->critical_stock_threshold }}</td>
            <td>
                @if($alert->is_enabled)
                <span class="badge badge-success">已启用</span>
                @else
                <span class="badge badge-secondary">已禁用</span>
                @endif
            </td>
            <td>{{ $alert->notify_admin ? '是' : '否' }}</td>
            <td>
                <a href="{{ route('admin.stock.edit-alert', $alert) }}" class="btn btn-sm">编辑</a>
                <form method="POST" action="{{ route('admin.stock.destroy-alert', $alert) }}" style="display: inline;">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-sm" onclick="return confirm('确定删除？')">删除</button>
                </form>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="7" style="text-align: center; padding: 40px; color: #999;">
                暂无预警配置
            </td>
        </tr>
        @endforelse
    </tbody>
</table>

<div class="pagination">
    {{ $alerts->links() }}
</div>
@endsection
