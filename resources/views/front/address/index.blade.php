@extends('front.layout')

@section('title', '收货地址')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
    <h2>收货地址</h2>
    <a href="{{ route('addresses.create') }}" class="btn">新增地址</a>
</div>

@forelse($addresses as $address)
<div style="background: #fff; padding: 20px; margin-bottom: 15px; border-radius: 8px; position: relative;">
    @if($address->is_default)
    <span style="position: absolute; top: 10px; right: 10px; background: #e4393c; color: #fff; padding: 2px 8px; font-size: 12px; border-radius: 3px;">默认</span>
    @endif
    <div style="font-size: 16px; font-weight: bold; margin-bottom: 10px;">{{ $address->name }} {{ $address->phone }}</div>
    <div style="color: #666; margin-bottom: 15px;">{{ $address->province }}{{ $address->city }}{{ $address->district }}{{ $address->detail_address }}</div>
    <div style="display: flex; gap: 10px;">
        <a href="{{ route('addresses.edit', $address) }}" style="color: #666; text-decoration: none;">编辑</a>
        <form method="POST" action="{{ route('addresses.destroy', $address) }}">
            @csrf
            @method('DELETE')
            <button type="submit" style="background:none;border:none;color:#e4393c;cursor:pointer;">删除</button>
        </form>
    </div>
</div>
@empty
<p style="text-align: center; padding: 50px; color: #999;">暂无收货地址</p>
@endforelse
@endsection
