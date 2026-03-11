@extends('admin_layout')

@section('title', '发票详情')

@section('content')
<div class="card">
    <div class="card-header">
        <h3>发票详情</h3>
    </div>
    <div class="card-body">
        <table class="table">
            <tr>
                <th width="150">ID</th>
                <td>{{ $invoice->id }}</td>
            </tr>
            <tr>
                <th>订单号</th>
                <td>{{ $invoice->order->order_no }}</td>
            </tr>
            <tr>
                <th>客户</th>
                <td>{{ $invoice->customer->name }} ({{ $invoice->customer->email }})</td>
            </tr>
            <tr>
                <th>发票类型</th>
                <td>{{ $invoice->type_text }}</td>
            </tr>
            <tr>
                <th>发票抬头</th>
                <td>{{ $invoice->title }}</td>
            </tr>
            @if($invoice->tax_no)
            <tr>
                <th>税号</th>
                <td>{{ $invoice->tax_no }}</td>
            </tr>
            @endif
            @if($invoice->email)
            <tr>
                <th>邮箱</th>
                <td>{{ $invoice->email }}</td>
            </tr>
            @endif
            @if($invoice->phone)
            <tr>
                <th>电话</th>
                <td>{{ $invoice->phone }}</td>
            </tr>
            @endif
            @if($invoice->address)
            <tr>
                <th>地址</th>
                <td>{{ $invoice->address }}</td>
            </tr>
            @endif
            <tr>
                <th>开票金额</th>
                <td style="color: #e4393c; font-weight: bold;">¥{{ $invoice->amount }}</td>
            </tr>
            <tr>
                <th>状态</th>
                <td>
                    @if($invoice->status === 'pending')
                        <span class="badge badge-warning">待开</span>
                    @else
                        <span class="badge badge-success">已开</span>
                    @endif
                </td>
            </tr>
            @if($invoice->invoice_no)
            <tr>
                <th>发票号</th>
                <td>{{ $invoice->invoice_no }}</td>
            </tr>
            @endif
            <tr>
                <th>申请时间</th>
                <td>{{ $invoice->created_at }}</td>
            </tr>
            @if($invoice->issued_at)
            <tr>
                <th>开票时间</th>
                <td>{{ $invoice->issued_at }}</td>
            </tr>
            @endif
        </table>
        
        @if($invoice->status === 'pending')
        <div style="margin-top: 20px;">
            <form method="POST" action="{{ route('admin.invoices.issue', $invoice) }}" class="form-inline">
                @csrf
                <div class="form-group mr-2">
                    <label>发票号：</label>
                    <input type="text" name="invoice_no" class="form-control" placeholder="请输入发票号" required>
                </div>
                <button type="submit" class="btn btn-primary">开具发票</button>
            </form>
        </div>
        @endif
        
        <a href="{{ route('admin.invoices.index') }}" class="btn btn-secondary mt-3">返回列表</a>
    </div>
</div>
@endsection
