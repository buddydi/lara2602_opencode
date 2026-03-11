@extends('admin_layout')

@section('title', '发票管理')

@section('content')
<div class="card">
    <div class="card-header">
        <h3>发票列表</h3>
    </div>
    <div class="card-body">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>订单号</th>
                    <th>客户</th>
                    <th>发票类型</th>
                    <th>发票抬头</th>
                    <th>金额</th>
                    <th>状态</th>
                    <th>申请时间</th>
                    <th>操作</th>
                </tr>
            </thead>
            <tbody>
                @foreach($invoices as $invoice)
                <tr>
                    <td>{{ $invoice->id }}</td>
                    <td>{{ $invoice->order->order_no }}</td>
                    <td>{{ $invoice->customer->name }}</td>
                    <td>{{ $invoice->type_text }}</td>
                    <td>{{ $invoice->title }}</td>
                    <td>¥{{ $invoice->amount }}</td>
                    <td>
                        @if($invoice->status === 'pending')
                            <span class="badge badge-warning">待开</span>
                        @else
                            <span class="badge badge-success">已开</span>
                        @endif
                    </td>
                    <td>{{ $invoice->created_at }}</td>
                    <td>
                        <a href="{{ route('admin.invoices.show', $invoice) }}" class="btn btn-sm btn-info">查看</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        {{ $invoices->links() }}
    </div>
</div>
@endsection
