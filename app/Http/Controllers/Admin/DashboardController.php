<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Refund;
use DB;

class DashboardController extends Controller
{
    public function statistics()
    {
        $today = now()->startOfDay();
        $thisMonth = now()->startOfMonth();
        
        $orderCount = Order::count();
        $orderAmount = Order::where('status', '!=', 'cancelled')->sum('pay_amount');
        
        $todayOrders = Order::where('created_at', '>=', $today)->count();
        $todayAmount = Order::where('created_at', '>=', $today)->where('status', '!=', 'cancelled')->sum('pay_amount');
        
        $monthOrders = Order::where('created_at', '>=', $thisMonth)->count();
        $monthAmount = Order::where('created_at', '>=', $thisMonth)->where('status', '!=', 'cancelled')->sum('pay_amount');
        
        $customerCount = Customer::count();
        $productCount = Product::where('status', 'active')->count();
        
        $pendingRefunds = Refund::where('status', 'pending')->count();
        
        $recentOrders = Order::with('customer')->latest()->limit(10)->get();
        
        $topProducts = DB::table('order_items')
            ->select('product_name', DB::raw('SUM(quantity) as total_qty, SUM(total) as total_amount'))
            ->groupBy('product_name')
            ->orderByDesc('total_qty')
            ->limit(10)
            ->get();
        
        $ordersByStatus = Order::select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->get()
            ->pluck('count', 'status')
            ->toArray();

        return view('admin.statistics.index', compact(
            'orderCount',
            'orderAmount',
            'todayOrders',
            'todayAmount',
            'monthOrders',
            'monthAmount',
            'customerCount',
            'productCount',
            'pendingRefunds',
            'recentOrders',
            'topProducts',
            'ordersByStatus'
        ));
    }
}
