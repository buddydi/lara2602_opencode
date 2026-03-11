<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Refund;
use Illuminate\Http\Request;

class RefundController extends Controller
{
    public function index()
    {
        $refunds = Refund::with(['order', 'customer'])->latest()->paginate(15);
        return view('admin.refunds.index', compact('refunds'));
    }

    public function show(Refund $refund)
    {
        $refund->load(['order', 'customer']);
        return view('admin.refunds.show', compact('refund'));
    }

    public function approve(Refund $refund)
    {
        $refund->update([
            'status' => 'approved',
            'processed_at' => now(),
        ]);

        $refund->order->update(['status' => 'refunded']);

        return redirect()->route('admin.refunds.index')->with('success', '退款已通过');
    }

    public function reject(Request $request, Refund $refund)
    {
        $request->validate([
            'reject_reason' => 'required|max:500',
        ]);

        $refund->update([
            'status' => 'rejected',
            'reject_reason' => $request->reject_reason,
            'processed_at' => now(),
        ]);

        return redirect()->route('admin.refunds.index')->with('success', '退款已拒绝');
    }
}
