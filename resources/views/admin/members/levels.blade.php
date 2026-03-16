@extends('admin_layout')

@section('title', '会员等级设置')

@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">会员等级设置</h5>
    </div>
    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <form method="POST" action="{{ route('admin.members.levels.update') }}">
            @csrf @method('PUT')
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>等级</th>
                        <th>最低积分</th>
                        <th>折扣(%)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($levelConfigs as $key => $level)
                    <tr>
                        <td>{{ $level['name'] }}</td>
                        <td>
                            <input type="number" name="{{ $key }}_min" value="{{ $level['min_points'] }}" class="form-control" min="0">
                        </td>
                        <td>
                            <input type="number" name="{{ $key }}_discount" value="{{ $level['discount'] * 100 }}" class="form-control" min="0" max="100" step="0.1">
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <button type="submit" class="btn btn-primary">保存设置</button>
        </form>
    </div>
</div>
@endsection
