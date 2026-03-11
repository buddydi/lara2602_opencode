<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\PointsRecord;
use Illuminate\Http\Request;

class PointsController extends Controller
{
    public function index(Request $request)
    {
        $query = Customer::query()->where('points', '>', 0);
        
        if ($request->level) {
            $query->where('member_level', $request->level);
        }
        
        if ($request->keyword) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->keyword}%")
                  ->orWhere('email', 'like', "%{$request->keyword}%")
                  ->orWhere('phone', 'like', "%{$request->keyword}%");
            });
        }

        $customers = $query->orderBy('points', 'desc')->paginate(20);
        $levels = Customer::getMemberLevelConfig();

        return view('admin.points.index', compact('customers', 'levels'));
    }

    public function show(Customer $customer)
    {
        $records = $customer->pointsRecords()
            ->with('order')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.points.show', compact('customer', 'records'));
    }

    public function add(Request $request, Customer $customer)
    {
        $request->validate([
            'points' => 'required|integer|min:1',
            'description' => 'nullable|max:200',
        ]);

        $customer->addPoints(
            $request->points,
            'admin_add',
            null,
            $request->description ?? '管理员添加积分'
        );

        return back()->with('success', '积分添加成功');
    }

    public function deduct(Request $request, Customer $customer)
    {
        $request->validate([
            'points' => 'required|integer|min:1',
            'description' => 'nullable|max:200',
        ]);

        if ($customer->points < $request->points) {
            return back()->with('error', '积分不足，当前积分：' . $customer->points);
        }

        $customer->usePoints(
            $request->points,
            'admin_deduct',
            null,
            $request->description ?? '管理员扣减积分'
        );

        return back()->with('success', '积分扣减成功');
    }
}
