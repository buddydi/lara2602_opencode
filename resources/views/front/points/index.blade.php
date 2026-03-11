@extends('front.layout')

@section('title', '我的积分')

@section('content')
<div style="background: #fff; padding: 30px; border-radius: 8px;">
    <h2 style="margin-bottom: 20px;">我的积分</h2>
    
    <div style="display: flex; gap: 20px; margin-bottom: 30px;">
        <div style="flex: 1; padding: 20px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 12px; color: #fff;">
            <div style="font-size: 14px; opacity: 0.9;">当前积分</div>
            <div style="font-size: 36px; font-weight: bold; margin: 10px 0;">{{ $customer->points }}</div>
            <div style="font-size: 14px; opacity: 0.9;">{{ $customer->member_level_name }}</div>
        </div>
        <div style="flex: 1; padding: 20px; background: #f9f9f9; border-radius: 12px;">
            <div style="color: #666; font-size: 14px; margin-bottom: 10px;">等级权益</div>
            @if($currentLevel)
            <div style="font-size: 18px; color: #e4393c;">享受 {{ $currentLevel['discount'] * 10 }} 折优惠</div>
            @else
            <div style="font-size: 18px; color: #e4393c;">享受 10 折优惠</div>
            @endif
            @if($nextLevel)
            <div style="margin-top: 15px; font-size: 12px; color: #666;">
                距离 {{ $nextLevel['name'] }} 还需 {{ $nextLevel['points_needed'] }} 积分
            </div>
            <div style="height: 6px; background: #eee; border-radius: 3px; margin-top: 8px; overflow: hidden;">
                @php
                    $progress = 0;
                    $currentMin = $currentLevel['min_points'];
                    $nextMin = $nextLevel['min_points'];
                    if ($nextMin > $currentMin) {
                        $progress = (($customer->points - $currentMin) / ($nextMin - $currentMin)) * 100;
                    }
                @endphp
                <div style="height: 100%; background: #667eea; width: {{ $progress }}%;"></div>
            </div>
            @else
            <div style="margin-top: 15px; font-size: 12px; color: #28a745;">已达到最高等级</div>
            @endif
        </div>
    </div>

    <h3 style="margin-bottom: 15px; font-size: 16px;">积分记录</h3>
    
    @if($records->isEmpty())
    <div style="text-align: center; padding: 50px; color: #999;">
        <p>暂无积分记录</p>
    </div>
    @else
    <table class="cart-table">
        <thead>
            <tr>
                <th>类型</th>
                <th>积分</th>
                <th>描述</th>
                <th>时间</th>
            </tr>
        </thead>
        <tbody>
            @foreach($records as $record)
            <tr>
                <td>
                    @if($record->points > 0)
                    <span style="color: #28a745;">获得</span>
                    @else
                    <span style="color: #dc3545;">使用</span>
                    @endif
                </td>
                <td style="{{ $record->points > 0 ? 'color: #28a745;' : 'color: #dc3545;' }}">
                    {{ $record->points > 0 ? '+' : '' }}{{ $record->points }}
                </td>
                <td>{{ $record->description }}</td>
                <td>{{ $record->created_at }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    
    <div style="margin-top: 20px;">
        {{ $records->links() }}
    </div>
    @endif
</div>
@endsection
