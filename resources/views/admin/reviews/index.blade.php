@extends('admin_layout')

@section('title', '评价管理')

@section('content')
<div class="card">
    <div class="card-header">
        <h3>评价列表</h3>
    </div>
    <div class="card-body">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>商品</th>
                    <th>客户</th>
                    <th>评分</th>
                    <th>评价内容</th>
                    <th>状态</th>
                    <th>评价时间</th>
                    <th>操作</th>
                </tr>
            </thead>
            <tbody>
                @foreach($reviews as $review)
                <tr>
                    <td>{{ $review->id }}</td>
                    <td>{{ $review->product->name }}</td>
                    <td>{{ $review->customer->name }}</td>
                    <td>{{ $review->rating }}星</td>
                    <td>{{ Str::limit($review->content, 30) }}</td>
                    <td>
                        @switch($review->status)
                            @case('pending')
                                <span class="badge badge-warning">待审核</span>
                                @break
                            @case('approved')
                                <span class="badge badge-success">通过</span>
                                @break
                            @case('rejected')
                                <span class="badge badge-danger">拒绝</span>
                                @break
                            @default
                                {{ $review->status }}
                        @endswitch
                    </td>
                    <td>{{ $review->created_at }}</td>
                    <td>
                        <a href="{{ route('admin.reviews.show', $review) }}" class="btn btn-sm btn-info">查看</a>
                        <form method="POST" action="{{ route('admin.reviews.destroy', $review) }}" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('确定删除？')">删除</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        {{ $reviews->links() }}
    </div>
</div>
@endsection
