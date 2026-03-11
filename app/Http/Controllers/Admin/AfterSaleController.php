<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AfterSale;
use Illuminate\Http\Request;

class AfterSaleController extends Controller
{
    public function index(Request $request)
    {
        $query = AfterSale::with(['order', 'customer']);
        
        if ($request->type) {
            $query->where('type', $request->type);
        }
        
        if ($request->status) {
            $query->where('status', $request->status);
        }
        
        $afterSales = $query->orderBy('created_at', 'desc')->paginate(15);
        
        return view('admin.after-sales.index', compact('afterSales'));
    }

    public function show(AfterSale $afterSale)
    {
        $afterSale->load(['order', 'customer', 'orderItem']);
        return view('admin.after-sales.show', compact('afterSale'));
    }

    public function update(Request $request, AfterSale $afterSale)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,completed,rejected',
            'admin_remark' => 'nullable|max:500',
        ]);

        $afterSale->update([
            'status' => $request->status,
            'admin_remark' => $request->admin_remark,
        ]);

        return back()->with('success', '售后处理成功');
    }
}
