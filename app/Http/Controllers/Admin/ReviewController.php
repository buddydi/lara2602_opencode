<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OrderReview;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index()
    {
        $reviews = OrderReview::with(['customer', 'product', 'order'])->latest()->paginate(15);
        return view('admin.reviews.index', compact('reviews'));
    }

    public function show(OrderReview $review)
    {
        return view('admin.reviews.show', compact('review'));
    }

    public function update(Request $request, OrderReview $review)
    {
        $request->validate([
            'status' => 'required|in:pending,approved,rejected',
        ]);

        $review->update(['status' => $request->status]);
        return back()->with('success', '更新成功');
    }

    public function destroy(OrderReview $review)
    {
        $review->delete();
        return redirect()->route('admin.reviews.index')->with('success', '删除成功');
    }
}
