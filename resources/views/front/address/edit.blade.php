@extends('front.layout')

@section('title', '编辑收货地址')

@section('content')
<div class="form-card">
    <h2 class="form-title">编辑收货地址</h2>
    
    <form method="POST" action="{{ route('addresses.update', $address) }}">
        @csrf
        @method('PUT')
        
        <div class="form-group">
            <label>收货人姓名 *</label>
            <input type="text" name="name" value="{{ $address->name }}" required>
        </div>
        
        <div class="form-group">
            <label>联系电话 *</label>
            <input type="text" name="phone" value="{{ $address->phone }}" required>
        </div>
        
        <div class="form-group">
            <label>省份 *</label>
            <input type="text" name="province" value="{{ $address->province }}" required>
        </div>
        
        <div class="form-group">
            <label>城市 *</label>
            <input type="text" name="city" value="{{ $address->city }}" required>
        </div>
        
        <div class="form-group">
            <label>区/县 *</label>
            <input type="text" name="district" value="{{ $address->district }}" required>
        </div>
        
        <div class="form-group">
            <label>详细地址 *</label>
            <textarea name="detail_address" required>{{ $address->detail_address }}</textarea>
        </div>
        
        <div class="form-group">
            <label>邮政编码</label>
            <input type="text" name="postal_code" value="{{ $address->postal_code }}">
        </div>
        
        <div class="form-group">
            <label>
                <input type="checkbox" name="is_default" value="1" {{ $address->is_default ? 'checked' : '' }}> 设为默认地址
            </label>
        </div>
        
        <button type="submit" class="btn btn-block">保存</button>
        <a href="{{ route('addresses.index') }}" class="btn btn-outline btn-block" style="margin-top: 10px; text-align: center;">返回</a>
    </form>
</div>
@endsection
