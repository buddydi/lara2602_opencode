@extends('admin_layout')

@section('title', '会员管理')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">会员列表</h5>
        <div>
            <a href="{{ route('admin.members.levels') }}" class="btn btn-sm btn-outline-secondary">等级设置</a>
        </div>
    </div>
    <div class="card-body">
        <form method="get" class="mb-3">
            <div class="row g-3">
                <div class="col-md-4">
                    <input type="text" name="search" value="{{ $search }}" class="form-control" placeholder="搜索姓名/邮箱/手机号">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary">搜索</button>
                </div>
            </div>
        </form>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>姓名</th>
                    <th>邮箱</th>
                    <th>手机号</th>
                    <th>积分</th>
                    <th>等级</th>
                    <th>状态</th>
                    <th>注册时间</th>
                    <th>操作</th>
                </tr>
            </thead>
            <tbody>
                @forelse($members as $member)
                <tr>
                    <td>{{ $member->id }}</td>
                    <td>{{ $member->name }}</td>
                    <td>{{ $member->email }}</td>
                    <td>{{ $member->phone ?? '-' }}</td>
                    <td>{{ $member->points }}</td>
                    <td>
                        @php
                            $levelName = '普通会员';
                            foreach($levelConfigs as $key => $level) {
                                if($member->points >= $level['min_points']) {
                                    $levelName = $level['name'];
                                }
                            }
                        @endphp
                        {{ $levelName }}
                    </td>
                    <td>
                        @if($member->status === 'active')
                            <span class="badge bg-success">正常</span>
                        @else
                            <span class="badge bg-secondary">禁用</span>
                        @endif
                    </td>
                    <td>{{ $member->created_at->format('Y-m-d') }}</td>
                    <td>
                        <a href="{{ route('admin.members.show', $member) }}" class="btn btn-sm btn-outline-primary">查看</a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="9" class="text-center">暂无数据</td></tr>
                @endforelse
            </tbody>
        </table>

        {{ $members->links() }}
    </div>
</div>
@endsection
