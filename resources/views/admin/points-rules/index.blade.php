@extends('admin_layout')

@section('title', '积分规则设置')

@section('content')
@php
use App\Models\PointsRule;
@endphp
<div class="page-header">
    <h1>积分规则设置</h1>
</div>

<form method="POST" action="{{ route('admin.points-rules.update') }}">
    @csrf
    
    <div class="card" style="margin-bottom: 20px;">
        <div class="card-header">
            <h3>基本规则</h3>
        </div>
        <div class="card-body">
            <div class="form-group">
                <label>消费获得积分比例</label>
                <div style="display: flex; align-items: center; gap: 10px;">
                    <span>消费 1 元 = </span>
                    <input type="number" name="rules[points_rate]" value="{{ PointsRule::getValue('points_rate', 1) }}" min="1" style="width: 80px; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
                    <span> 积分</span>
                </div>
                <small style="color: #666;">客户消费后获得的积分 = 消费金额 × 此比例</small>
            </div>
            
            <div class="form-group">
                <label>积分抵扣比例</label>
                <div style="display: flex; align-items: center; gap: 10px;">
                    <span>使用 </span>
                    <input type="number" name="rules[deduction_rate]" value="{{ PointsRule::getValue('deduction_rate', 100) }}" min="1" style="width: 80px; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
                    <span> 积分 = 抵扣 1 元</span>
                </div>
                <small style="color: #666;">积分抵扣现金的计算比例</small>
            </div>
            
            <div class="form-group">
                <label>单次订单最大使用积分比例</label>
                <div style="display: flex; align-items: center; gap: 10px;">
                    <input type="number" name="rules[max_deduction]" value="{{ PointsRule::getValue('max_deduction', 50) }}" min="0" max="100" style="width: 80px; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
                    <span> %（订单金额的百分比）</span>
                </div>
                <small style="color: #666;">如设置为50%，则最多可用积分抵扣订单金额的50%。设置为0表示不限制</small>
            </div>
        </div>
    </div>

    <div class="card" style="margin-bottom: 20px;">
        <div class="card-header">
            <h3>会员等级设置</h3>
        </div>
        <div class="card-body">
            <table class="table">
                <thead>
                    <tr>
                        <th>等级</th>
                        <th>最低积分</th>
                        <th>折扣</th>
                        <th>等级名称</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><span class="badge" style="background: #cd7f32; color: #fff; padding: 5px 10px;">青铜</span></td>
                        <td><input type="number" name="rules[bronze_min]" value="{{ PointsRule::getValue('bronze_min', 0) }}" min="0" style="width: 100px; padding: 8px; border: 1px solid #ddd; border-radius: 4px;"></td>
                        <td><input type="number" name="rules[bronze_discount]" value="{{ PointsRule::getValue('bronze_discount', 1.0) }}" min="0.1" max="1" step="0.01" style="width: 80px; padding: 8px; border: 1px solid #ddd; border-radius: 4px;"></td>
                        <td>青铜会员</td>
                    </tr>
                    <tr>
                        <td><span class="badge" style="background: #c0c0c0; color: #fff; padding: 5px 10px;">白银</span></td>
                        <td><input type="number" name="rules[silver_min]" value="{{ PointsRule::getValue('silver_min', 1000) }}" min="0" style="width: 100px; padding: 8px; border: 1px solid #ddd; border-radius: 4px;"></td>
                        <td><input type="number" name="rules[silver_discount]" value="{{ PointsRule::getValue('silver_discount', 0.98) }}" min="0.1" max="1" step="0.01" style="width: 80px; padding: 8px; border: 1px solid #ddd; border-radius: 4px;"></td>
                        <td>白银会员</td>
                    </tr>
                    <tr>
                        <td><span class="badge" style="background: #ffd700; color: #333; padding: 5px 10px;">黄金</span></td>
                        <td><input type="number" name="rules[gold_min]" value="{{ PointsRule::getValue('gold_min', 5000) }}" min="0" style="width: 100px; padding: 8px; border: 1px solid #ddd; border-radius: 4px;"></td>
                        <td><input type="number" name="rules[gold_discount]" value="{{ PointsRule::getValue('gold_discount', 0.95) }}" min="0.1" max="1" step="0.01" style="width: 80px; padding: 8px; border: 1px solid #ddd; border-radius: 4px;"></td>
                        <td>黄金会员</td>
                    </tr>
                    <tr>
                        <td><span class="badge" style="background: #e5e4e2; color: #333; padding: 5px 10px;">铂金</span></td>
                        <td><input type="number" name="rules[platinum_min]" value="{{ PointsRule::getValue('platinum_min', 20000) }}" min="0" style="width: 100px; padding: 8px; border: 1px solid #ddd; border-radius: 4px;"></td>
                        <td><input type="number" name="rules[platinum_discount]" value="{{ PointsRule::getValue('platinum_discount', 0.92) }}" min="0.1" max="1" step="0.01" style="width: 80px; padding: 8px; border: 1px solid #ddd; border-radius: 4px;"></td>
                        <td>铂金会员</td>
                    </tr>
                    <tr>
                        <td><span class="badge" style="background: #b9f2ff; color: #333; padding: 5px 10px;">钻石</span></td>
                        <td><input type="number" name="rules[diamond_min]" value="{{ PointsRule::getValue('diamond_min', 50000) }}" min="0" style="width: 100px; padding: 8px; border: 1px solid #ddd; border-radius: 4px;"></td>
                        <td><input type="number" name="rules[diamond_discount]" value="{{ PointsRule::getValue('diamond_discount', 0.88) }}" min="0.1" max="1" step="0.01" style="width: 80px; padding: 8px; border: 1px solid #ddd; border-radius: 4px;"></td>
                        <td>钻石会员</td>
                    </tr>
                </tbody>
            </table>
            <small style="color: #666;">折扣说明：0.98 = 9.8折，0.95 = 9.5折，以此类推</small>
        </div>
    </div>

    <div style="margin-top: 20px;">
        <button type="submit" class="btn">保存设置</button>
        <a href="{{ route('admin.points.index') }}" class="btn btn-outline" style="margin-left: 10px;">返回积分管理</a>
    </div>
</form>
@endsection
