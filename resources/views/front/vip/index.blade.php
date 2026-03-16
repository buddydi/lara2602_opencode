@extends('front.layout')

@section('title', 'VIP会员中心')

@section('content')
<div style="background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%); padding: 40px; border-radius: 16px; color: #fff; margin-bottom: 30px;">
    <div style="display: flex; justify-content: space-between; align-items: center;">
        <div>
            <div style="font-size: 14px; opacity: 0.8; margin-bottom: 5px;">当前等级</div>
            <div style="font-size: 28px; font-weight: bold; margin-bottom: 10px;">{{ $currentLevelConfig['name'] }}</div>
            <div style="font-size: 14px;">
                <span>当前积分：</span>
                <span style="color: #ffd700; font-size: 20px; font-weight: bold;">{{ $customer->points ?? 0 }}</span>
            </div>
        </div>
        <div style="text-align: center;">
            <div style="width: 80px; height: 80px; border-radius: 50%; background: linear-gradient(135deg, #ffd700, #ffaa00); display: flex; align-items: center; justify-content: center; font-size: 36px; color: #1a1a2e; font-weight: bold;">
                {{ substr($currentLevelConfig['name'], 0, 1) }}
            </div>
        </div>
    </div>
    
    @if(!empty($nextLevels) && $nextLevelPoints > 0)
    <div style="margin-top: 30px;">
        <div style="display: flex; justify-content: space-between; font-size: 12px; margin-bottom: 8px;">
            <span>距离 {{ $nextLevels[0]['config']['name'] }} 还需 {{ $nextLevelPoints - ($customer->points ?? 0) }} 积分</span>
            <span>{{ round($progress) }}%</span>
        </div>
        <div style="height: 8px; background: rgba(255,255,255,0.2); border-radius: 4px; overflow: hidden;">
            <div style="width: {{ min($progress, 100) }}%; height: 100%; background: linear-gradient(90deg, #ffd700, #ffaa00);"></div>
        </div>
    </div>
    @endif
</div>

<h3 style="margin-bottom: 20px;">会员等级权益</h3>
<div style="display: grid; grid-template-columns: repeat(5, 1fr); gap: 15px; margin-bottom: 40px;">
    @foreach($config as $key => $level)
    <div style="background: #fff; padding: 20px; border-radius: 12px; text-align: center; border: 2px solid {{ $key === $currentLevel ? '#ffd700' : '#eee' }}; {{ $key === $currentLevel ? 'box-shadow: 0 4px 12px rgba(255,215,0,0.3)' : '' }}">
        <div style="font-size: 24px; margin-bottom: 10px;">
            @switch($key)
                @case('bronze')
                🟤
                @break
                @case('silver')
                ⚪
                @break
                @case('gold')
                🟡
                @break
                @case('platinum')
                ⚫
                @break
                @case('diamond')
                💎
                @break
            @endswitch
        </div>
        <div style="font-weight: bold; margin-bottom: 5px;">{{ $level['name'] }}</div>
        <div style="font-size: 12px; color: #666;">≥ {{ $level['min_points'] }} 积分</div>
        <div style="font-size: 14px; color: #e4393c; margin-top: 10px;">
            {{ $level['discount'] * 10 }} 折
        </div>
    </div>
    @endforeach
</div>

<h3 style="margin-bottom: 20px;">如何获取积分</h3>
<div style="background: #fff; padding: 30px; border-radius: 12px; margin-bottom: 30px;">
    <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 30px;">
        <div style="text-align: center;">
            <div style="font-size: 36px; margin-bottom: 15px;">🛒</div>
            <div style="font-weight: bold; margin-bottom: 5px;">消费购物</div>
            <div style="color: #666; font-size: 14px;">消费1元= {{ \App\Models\PointsRule::getValue('points_rate', 1) }} 积分</div>
        </div>
        <div style="text-align: center;">
            <div style="font-size: 36px; margin-bottom: 15px;">📝</div>
            <div style="font-weight: bold; margin-bottom: 5px;">完善资料</div>
            <div style="color: #666; font-size: 14px;">首次完善资料 +{{ \App\Models\PointsRule::getValue('profile_points', 0) }} 积分</div>
        </div>
        <div style="text-align: center;">
            <div style="font-size: 36px; margin-bottom: 15px;">📅</div>
            <div style="font-weight: bold; margin-bottom: 5px;">每日签到</div>
            <div style="color: #666; font-size: 14px;">每日签到 +{{ \App\Models\PointsRule::getValue('checkin_points', 1) }} 积分</div>
        </div>
    </div>
</div>

<h3 style="margin-bottom: 20px;">会员特权</h3>
<div style="background: #fff; padding: 30px; border-radius: 12px;">
    <table style="width: 100%;">
        <thead>
            <tr>
                <th style="text-align: left; padding-bottom: 15px;">特权</th>
                @foreach($config as $level)
                <th style="text-align: center; padding-bottom: 15px;">{{ $level['name'] }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style="padding: 12px 0; border-bottom: 1px solid #eee;">购物折扣</td>
                @foreach($config as $level)
                <td style="text-align: center; padding: 12px; border-bottom: 1px solid #eee;">{{ $level['discount'] * 10 }}折</td>
                @endforeach
            </tr>
            <tr>
                <td style="padding: 12px 0; border-bottom: 1px solid #eee;">专属客服</td>
                @foreach($config as $key => $level)
                <td style="text-align: center; padding: 12px; border-bottom: 1px solid #eee;">
                    @if(in_array($key, ['gold', 'platinum', 'diamond']))
                    <span style="color: #28a745;">✓</span>
                    @else
                    <span style="color: #ccc;">-</span>
                    @endif
                </td>
                @endforeach
            </tr>
            <tr>
                <td style="padding: 12px 0; border-bottom: 1px solid #eee;">免费配送</td>
                @foreach($config as $key => $level)
                <td style="text-align: center; padding: 12px; border-bottom: 1px solid #eee;">
                    @if(in_array($key, ['platinum', 'diamond']))
                    <span style="color: #28a745;">✓</span>
                    @else
                    <span style="color: #ccc;">-</span>
                    @endif
                </td>
                @endforeach
            </tr>
            <tr>
                <td style="padding: 12px 0;">生日礼包</td>
                @foreach($config as $key => $level)
                <td style="text-align: center; padding: 12px;">
                    @if($key === 'diamond')
                    <span style="color: #28a745;">✓</span>
                    @else
                    <span style="color: #ccc;">-</span>
                    @endif
                </td>
                @endforeach
            </tr>
        </tbody>
    </table>
</div>
@endsection
