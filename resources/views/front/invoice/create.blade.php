@extends('front.layout')

@section('title', '申请发票')

@section('content')
<div style="background: #fff; padding: 30px; border-radius: 8px; max-width: 600px; margin: 0 auto;">
    <h2 style="margin-bottom: 20px; text-align: center;">申请发票</h2>
    
    <div style="background: #f9f9f9; padding: 20px; border-radius: 8px; margin-bottom: 20px;">
        <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
            <span style="color: #999;">订单编号：</span>
            <span>{{ $order->order_no }}</span>
        </div>
        <div style="display: flex; justify-content: space-between;">
            <span style="color: #999;">开票金额：</span>
            <span style="color: #e4393c; font-weight: bold;">¥{{ $order->pay_amount }}</span>
        </div>
    </div>
    
    <form method="POST" action="{{ route('orders.invoice.store', $order) }}">
        @csrf
        
        <div class="form-group">
            <label>发票类型 *</label>
            <select name="type" id="invoiceType" class="form-control" required onchange="toggleCompanyFields()">
                <option value="personal">个人</option>
                <option value="company">企业</option>
            </select>
        </div>
        
        <div id="companyFields" style="display: none;">
            <div class="form-group">
                <label>企业抬头 *</label>
                <input type="text" name="title" class="form-control" placeholder="请输入企业全称">
            </div>
            <div class="form-group">
                <label>税号 *</label>
                <input type="text" name="tax_no" class="form-control" placeholder="请输入纳税人识别号">
            </div>
        </div>
        
        <div class="form-group">
            <label>邮箱</label>
            <input type="email" name="email" class="form-control" placeholder="用于接收电子发票">
        </div>
        
        <div class="form-group">
            <label>电话</label>
            <input type="text" name="phone" class="form-control" placeholder="请输入联系电话">
        </div>
        
        <div class="form-group">
            <label>地址</label>
            <input type="text" name="address" class="form-control" placeholder="请输入地址">
        </div>
        
        <button type="submit" class="btn btn-block">提交发票申请</button>
        <a href="{{ route('orders.show', $order) }}" class="btn btn-outline btn-block" style="margin-top: 10px; text-align: center;">返回</a>
    </form>
</div>

<script>
function toggleCompanyFields() {
    var type = document.getElementById('invoiceType').value;
    document.getElementById('companyFields').style.display = type === 'company' ? 'block' : 'none';
}
</script>
@endsection
