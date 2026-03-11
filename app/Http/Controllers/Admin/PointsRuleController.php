<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PointsRule;
use Illuminate\Http\Request;

class PointsRuleController extends Controller
{
    public function index()
    {
        $rules = PointsRule::all()->groupBy(function ($item) {
            if (str_contains($item->key, 'rate') || str_contains($item->key, 'max')) {
                return 'basic';
            }
            return 'level';
        });

        return view('admin.points-rules.index', compact('rules'));
    }

    public function update(Request $request)
    {
        $rules = $request->input('rules', []);
        
        foreach ($rules as $key => $value) {
            PointsRule::setValue($key, $value);
        }

        PointsRule::clearCache();

        return back()->with('success', '积分规则更新成功');
    }
}
