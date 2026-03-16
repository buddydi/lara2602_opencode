@extends('admin_layout')

@section('title', '会员详情')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">会员详情</h5>
        <a href="{{ route('admin.members.index') }}" class="btn btn-sm btn-outline-secondary">返回列表</a>
    </div>
    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="row">
            <div class="col-md-6">
                <h6>基本信息</h6>
                <form method="POST" action="{{ route('admin.members.update', $member) }}">
                    @csrf @method('PUT')
                    <div class="mb-3">
                        <label class="form-label">姓名</label>
                        <input type="text" name="name" value="{{ $member->name }}" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">手机号</label>
                        <input type="text" name="phone" value="{{ $member->phone }}" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">积分</label>
                        <input type="number" name="points" value="{{ $member->points }}" class="form-control" required min="0">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">状态</label>
                        <select name="status" class="form-control">
                            <option value="active" {{ $member->status === 'active' ? 'selected' : '' }}>正常</option>
                            <option value="inactive" {{ $member->status === 'inactive' ? 'selected' : '' }}>禁用</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">保存</button>
                </form>
            </div>
            <div class="col-md-6">
                <h6>会员等级</h6>
                @php
                    $levelName = '普通会员';
                    $nextLevelName = null;
                    $pointsNeeded = 0;
                    foreach($levelConfigs as $key => $level) {
                        if($member->points >= $level['min_points']) {
                            $levelName = $level['name'];
                        } elseif($nextLevelName === null) {
                            $nextLevelName = $level['name'];
                            $pointsNeeded = $level['min_points'] - $member->points;
                        }
                    }
                @endphp
                <p>当前等级: <strong>{{ $levelName }}</strong></p>
                <p>当前积分: {{ $member->points }}</p>
                @if($nextLevelName)
                    <p>距离下一等级({{ $nextLevelName }})还需: {{ $pointsNeeded }} 积分</p>
                @endif
            </div>
        </div>

        <hr>

        <h6>订单记录</h6>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>订单号</th>
                    <th>金额</th>
                    <th>状态</th>
                    <th>下单时间</th>
                </tr>
            </thead>
            <tbody>
                @forelse($member->orders as $order)
                <tr>
                    <td>{{ $order->order_no }}</td>
                    <td>¥{{ $order->total_amount }}</td>
                    <td>{{ $order->status }}</td>
                    <td>{{ $order->created_at->format('Y-m-d H:i') }}</td>
                </tr>
                @empty
                <tr><td colspan="4" class="text-center">暂无订单</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
