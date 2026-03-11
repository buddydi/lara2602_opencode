<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    public function index(Request $request)
    {
        $query = Coupon::query();
        
        if ($request->status) {
            if ($request->status === 'active') {
                $query->where('is_active', true);
            } else {
                $query->where('is_active', false);
            }
        }
        
        if ($request->keyword) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->keyword}%")
                  ->orWhere('code', 'like', "%{$request->keyword}%");
            });
        }

        $coupons = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('admin.coupons.index', compact('coupons'));
    }

    public function create()
    {
        return view('admin.coupons.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|unique:coupons|alpha_num|min:4|max:20',
            'name' => 'required|max:100',
            'type' => 'required|in:fixed,percentage',
            'value' => 'required|numeric|min:0',
            'min_amount' => 'required|numeric|min:0',
            'max_discount' => 'nullable|numeric|min:0',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'usage_limit' => 'required|integer|min:0',
            'per_user_limit' => 'required|integer|min:1',
            'description' => 'nullable|max:500',
        ]);

        Coupon::create([
            'code' => strtoupper($request->code),
            'name' => $request->name,
            'type' => $request->type,
            'value' => $request->value,
            'min_amount' => $request->min_amount,
            'max_discount' => $request->max_discount,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'usage_limit' => $request->usage_limit,
            'per_user_limit' => $request->per_user_limit,
            'is_active' => $request->is_active ?? true,
            'description' => $request->description,
        ]);

        return redirect()->route('admin.coupons.index')->with('success', '优惠券创建成功');
    }

    public function show(Coupon $coupon)
    {
        $coupon->load(['usages.customer', 'usages.order']);
        return view('admin.coupons.show', compact('coupon'));
    }

    public function edit(Coupon $coupon)
    {
        return view('admin.coupons.edit', compact('coupon'));
    }

    public function update(Request $request, Coupon $coupon)
    {
        $request->validate([
            'code' => 'required|alpha_num|min:4|max:20|unique:coupons,code,' . $coupon->id,
            'name' => 'required|max:100',
            'type' => 'required|in:fixed,percentage',
            'value' => 'required|numeric|min:0',
            'min_amount' => 'required|numeric|min:0',
            'max_discount' => 'nullable|numeric|min:0',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'usage_limit' => 'required|integer|min:0',
            'per_user_limit' => 'required|integer|min:1',
            'description' => 'nullable|max:500',
        ]);

        $coupon->update([
            'code' => strtoupper($request->code),
            'name' => $request->name,
            'type' => $request->type,
            'value' => $request->value,
            'min_amount' => $request->min_amount,
            'max_discount' => $request->max_discount,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'usage_limit' => $request->usage_limit,
            'per_user_limit' => $request->per_user_limit,
            'is_active' => $request->is_active ?? true,
            'description' => $request->description,
        ]);

        return redirect()->route('admin.coupons.index')->with('success', '优惠券更新成功');
    }

    public function destroy(Coupon $coupon)
    {
        $coupon->delete();
        return redirect()->route('admin.coupons.index')->with('success', '优惠券删除成功');
    }

    public function toggle(Coupon $coupon)
    {
        $coupon->update(['is_active' => !$coupon->is_active]);
        return back()->with('success', $coupon->is_active ? '优惠券已启用' : '优惠券已禁用');
    }
}
