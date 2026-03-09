@extends('admin_layout')

@section('title', '评价详情')

@section('content')
<div class="card">
    <div class="card-header">
        <h3>评价详情</h3>
    </div>
    <div class="card-body">
        <table class="table">
            <tr>
                <th width="150">ID</th>
                <td>{{ $review->id }}</td>
            </tr>
            <tr>
                <th>商品</th>
                <td>{{ $review->product->name }}</td>
            </tr>
            <tr>
                <th>客户</th>
                <td>{{ $review->customer->name }}</td>
            </tr>
            <tr>
                <th>订单号</th>
                <td>{{ $review->order->order_no }}</td>
            </tr>
            <tr>
                <th>评分</th>
                <td>
                    @for($i = 1; $i <= 5; $i++)
                        <span style="color: {{ $i <= $review->rating ? '#f5c518' : '#ddd' }};">★</span>
                    @endfor
                </td>
            </tr>
            <tr>
                <th>评价内容</th>
                <td>{{ $review->content ?: '无评价内容' }}</td>
            </tr>
            <tr>
                <th>状态</th>
                <td>
                    <form method="POST" action="{{ route('admin.reviews.update', $review) }}" class="form-inline">
                        @csrf
                        @method('PUT')
                        <select name="status" class="form-control mr-2">
                            <option value="1" {{ $review->status == 1 ? 'selected' : '' }}>显示</option>
                            <option value="0" {{ $review->status == 0 ? 'selected' : '' }}>隐藏</option>
                        </select>
                        <button type="submit" class="btn btn-primary">更新状态</button>
                    </form>
                </td>
            </tr>
            <tr>
                <th>评价时间</th>
                <td>{{ $review->created_at }}</td>
            </tr>
        </table>
        
        <a href="{{ route('admin.reviews.index') }}" class="btn btn-secondary mt-3">返回列表</a>
    </div>
</div>
@endsection
