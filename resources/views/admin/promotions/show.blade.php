@extends('admin_layout')

@section('title', '活动详情')

@section('content')
<div class="page-header">
    <h1>{{ $promotion->name }}</h1>
    <div>
        <a href="{{ route('admin.promotions.index') }}" class="btn btn-outline">返回列表</a>
        <a href="{{ route('admin.promotions.edit', $promotion) }}" class="btn btn-outline">编辑</a>
    </div>
</div>

<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
    <div class="card">
        <div class="card-header">
            <h3>活动信息</h3>
        </div>
        <div class="card-body">
            <div class="info-row">
                <span class="label">活动类型：</span>
                <span>{{ \App\Models\Promotion::getTypeOptions()[$promotion->type] }}</span>
            </div>
            <div class="info-row">
                <span class="label">优惠内容：</span>
                <span>
                    @if($promotion->type === 'discount')
                    {{ $promotion->discount_rate }}折
                    @elseif($promotion->type === 'full_reduce')
                    满{{ $promotion->min_amount }}减{{ $promotion->reduce_amount }}
                    @elseif($promotion->type === 'flash_sale')
                    ¥{{ $promotion->discount_amount }}
                    @endif
                </span>
            </div>
            <div class="info-row">
                <span class="label">活动时间：</span>
                <span>{{ $promotion->start_time }} ~ {{ $promotion->end_time }}</span>
            </div>
            <div class="info-row">
                <span class="label">每人限购：</span>
                <span>{{ $promotion->max_per_user ?: '不限' }}</span>
            </div>
            <div class="info-row">
                <span class="label">状态：</span>
                <span>
                    @if(!$promotion->is_active)
                    <span class="badge badge-secondary">已禁用</span>
                    @elseif(now() < $promotion->start_time)
                    <span class="badge badge-info">未开始</span>
                    @elseif(now() > $promotion->end_time)
                    <span class="badge badge-secondary">已结束</span>
                    @else
                    <span class="badge badge-success">进行中</span>
                    @endif
                </span>
            </div>
            @if($promotion->description)
            <div class="info-row">
                <span class="label">活动描述：</span>
                <span>{{ $promotion->description }}</span>
            </div>
            @endif
        </div>
    </div>
</div>

<div class="card" style="margin-top: 20px;">
    <div class="card-header">
        <h3>参与商品</h3>
    </div>
    <div class="card-body">
        @if($promotion->products->count() > 0)
        <table class="data-table">
            <thead>
                <tr>
                    <th>商品名称</th>
                    <th>原价</th>
                    <th>活动价</th>
                    <th>已售</th>
                </tr>
            </thead>
            <tbody>
                @foreach($promotion->products as $product)
                <tr>
                    <td>{{ $product->name }}</td>
                    <td>¥{{ $product->price }}</td>
                    <td>
                        @if($product->pivot->special_price)
                        ¥{{ $product->pivot->special_price }}
                        @else
                        -
                        @endif
                    </td>
                    <td>{{ $product->pivot->sold_count ?: 0 }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <p style="color: #999; text-align: center; padding: 20px;">暂无商品参与</p>
        @endif
    </div>
</div>
@endsection
