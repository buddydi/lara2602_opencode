@extends('admin_layout')

@section('title', '客户管理')

@section('content')
<div class="card">
    <div class="card-header">
        <h3>客户列表</h3>
    </div>
    <div class="card-body">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>姓名</th>
                    <th>邮箱</th>
                    <th>手机</th>
                    <th>订单数</th>
                    <th>注册时间</th>
                    <th>操作</th>
                </tr>
            </thead>
            <tbody>
                @foreach($customers as $customer)
                <tr>
                    <td>{{ $customer->id }}</td>
                    <td>{{ $customer->name }}</td>
                    <td>{{ $customer->email }}</td>
                    <td>{{ $customer->phone ?: '-' }}</td>
                    <td>{{ $customer->orders_count }}</td>
                    <td>{{ $customer->created_at }}</td>
                    <td>
                        <a href="{{ route('admin.customers.show', $customer) }}" class="btn btn-sm btn-info">查看</a>
                        <form method="POST" action="{{ route('admin.customers.destroy', $customer) }}" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('确定删除此客户？')">删除</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        {{ $customers->links() }}
    </div>
</div>
@endsection
