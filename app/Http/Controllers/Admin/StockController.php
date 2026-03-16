<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductSku;
use App\Models\StockAlert;
use App\Models\StockLog;
use Illuminate\Http\Request;

class StockController extends Controller
{
    public function index(Request $request)
    {
        $query = ProductSku::with('product');

        if ($request->stock_status === 'low') {
            $query->where('stock', '<=', 10)->where('stock', '>', 0);
        } elseif ($request->stock_status === 'critical') {
            $query->where('stock', '<=', 5);
        } elseif ($request->stock_status === 'out') {
            $query->where('stock', '<=', 0);
        } elseif ($request->stock_status === 'normal') {
            $query->where('stock', '>', 10);
        }

        if ($request->product_name) {
            $query->whereHas('product', function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->product_name}%");
            });
        }

        $skus = $query->orderBy('stock', 'asc')->paginate(20);

        $stats = [
            'total' => ProductSku::count(),
            'out' => ProductSku::where('stock', '<=', 0)->count(),
            'critical' => ProductSku::where('stock', '<=', 5)->where('stock', '>', 0)->count(),
            'low' => ProductSku::where('stock', '<=', 10)->where('stock', '>', 0)->count(),
            'normal' => ProductSku::where('stock', '>', 10)->count(),
        ];

        return view('admin.stock.index', compact('skus', 'stats'));
    }

    public function logs(Request $request)
    {
        $query = StockLog::with(['product', 'sku', 'user']);

        if ($request->type) {
            $query->where('type', $request->type);
        }

        if ($request->product_id) {
            $query->where('product_id', $request->product_id);
        }

        if ($request->start_date) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }

        if ($request->end_date) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        $logs = $query->orderBy('created_at', 'desc')->paginate(30);

        return view('admin.stock.logs', compact('logs'));
    }

    public function createLog(Request $request)
    {
        $products = Product::where('status', 'published')->get();
        $sku = null;

        if ($request->product_id) {
            $product = Product::find($request->product_id);
            if ($product) {
                $sku = $product->skus()->get();
            }
        }

        return view('admin.stock.create-log', compact('products', 'sku'));
    }

    public function storeLog(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'product_sku_id' => 'required|exists:product_skus,id',
            'type' => 'required|in:in,out,adjust',
            'quantity' => 'required|integer',
            'reason' => 'required',
        ]);

        $sku = ProductSku::find($request->product_sku_id);
        $currentStock = $sku->stock;

        if ($request->type === 'out' && $request->quantity > $currentStock) {
            return back()->with('error', '库存不足，当前库存：' . $currentStock);
        }

        $quantity = $request->type === 'out' ? -$request->quantity : $request->quantity;
        $balance = $currentStock + $quantity;

        StockLog::record([
            'product_id' => $request->product_id,
            'product_sku_id' => $request->product_sku_id,
            'user_id' => auth()->id(),
            'type' => $request->type,
            'quantity' => $quantity,
            'balance' => $balance,
            'reason' => $request->reason,
            'remark' => $request->remark,
        ]);

        return redirect()->route('admin.stock.logs')->with('success', '库存记录成功');
    }

    public function alerts(Request $request)
    {
        $query = StockAlert::with(['product', 'sku']);

        if ($request->is_enabled !== null) {
            $query->where('is_enabled', $request->is_enabled);
        }

        $alerts = $query->orderBy('created_at', 'desc')->paginate(20);

        return view('admin.stock.alerts', compact('alerts'));
    }

    public function createAlert()
    {
        $products = Product::where('status', 'published')->get();
        return view('admin.stock.create-alert', compact('products'));
    }

    public function storeAlert(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'product_sku_id' => 'nullable',
            'low_stock_threshold' => 'required|integer|min:0',
            'critical_stock_threshold' => 'required|integer|min:0',
        ]);

        if ($request->product_sku_id) {
            $exists = StockAlert::where('product_id', $request->product_id)
                ->where('product_sku_id', $request->product_sku_id)
                ->exists();
        } else {
            $exists = StockAlert::where('product_id', $request->product_id)
                ->whereNull('product_sku_id')
                ->exists();
        }

        if ($exists) {
            return back()->with('error', '该商品已存在预警配置');
        }

        StockAlert::create([
            'product_id' => $request->product_id,
            'product_sku_id' => $request->product_sku_id ?: null,
            'low_stock_threshold' => $request->low_stock_threshold,
            'critical_stock_threshold' => $request->critical_stock_threshold,
            'is_enabled' => $request->is_enabled ?? true,
            'notify_admin' => $request->notify_admin ?? true,
            'notify_customer' => $request->notify_customer ?? false,
        ]);

        return redirect()->route('admin.stock.alerts')->with('success', '预警配置成功');
    }

    public function editAlert(StockAlert $stockAlert)
    {
        $products = Product::where('status', 'published')->get();
        $skus = $stockAlert->product->skus ?? collect();
        return view('admin.stock.edit-alert', compact('stockAlert', 'products', 'skus'));
    }

    public function updateAlert(Request $request, StockAlert $stockAlert)
    {
        $request->validate([
            'low_stock_threshold' => 'required|integer|min:0',
            'critical_stock_threshold' => 'required|integer|min:0',
        ]);

        $stockAlert->update([
            'low_stock_threshold' => $request->low_stock_threshold,
            'critical_stock_threshold' => $request->critical_stock_threshold,
            'is_enabled' => $request->is_enabled ?? true,
            'notify_admin' => $request->notify_admin ?? true,
            'notify_customer' => $request->notify_customer ?? false,
        ]);

        return redirect()->route('admin.stock.alerts')->with('success', '预警配置更新成功');
    }

    public function destroyAlert(StockAlert $stockAlert)
    {
        $stockAlert->delete();
        return back()->with('success', '预警配置删除成功');
    }

    public function getSkus(Request $request)
    {
        $product = Product::find($request->product_id);
        if (!$product) {
            return response()->json([]);
        }

        $skus = $product->skus->map(function ($sku) {
            return [
                'id' => $sku->id,
                'name' => $sku->name ?: $sku->sku,
                'stock' => $sku->stock,
            ];
        });

        return response()->json($skus);
    }
}
