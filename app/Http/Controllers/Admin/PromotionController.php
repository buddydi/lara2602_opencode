<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Promotion;
use App\Models\Product;
use Illuminate\Http\Request;

class PromotionController extends Controller
{
    public function index(Request $request)
    {
        $query = Promotion::query();
        
        if ($request->type) {
            $query->where('type', $request->type);
        }
        
        if ($request->status === 'active') {
            $query->where('is_active', true)
                ->where('start_time', '<=', now())
                ->where('end_time', '>=', now());
        } elseif ($request->status === 'inactive') {
            $query->where('is_active', false);
        } elseif ($request->status === 'upcoming') {
            $query->where('start_time', '>', now());
        } elseif ($request->status === 'expired') {
            $query->where('end_time', '<', now());
        }
        
        $promotions = $query->orderBy('created_at', 'desc')->paginate(20);
        
        return view('admin.promotions.index', compact('promotions'));
    }

    public function create()
    {
        $products = Product::where('status', 'published')->get();
        return view('admin.promotions.create', compact('products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:100',
            'type' => 'required|in:flash_sale,discount,full_reduce',
            'description' => 'nullable',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
        ]);
        
        $data = $request->only(['name', 'type', 'description', 'start_time', 'end_time', 'max_quantity', 'max_per_user', 'is_active']);
        
        if ($request->type === 'discount') {
            $request->validate(['discount_rate' => 'required|numeric|min:1|max:99']);
            $data['discount_rate'] = $request->discount_rate;
        } elseif ($request->type === 'full_reduce') {
            $request->validate([
                'min_amount' => 'required|numeric|min:0',
                'reduce_amount' => 'required|numeric|min:0',
            ]);
            $data['min_amount'] = $request->min_amount;
            $data['reduce_amount'] = $request->reduce_amount;
        } elseif ($request->type === 'flash_sale') {
            $request->validate([
                'special_price' => 'required|numeric|min:0',
                'max_quantity' => 'required|integer|min:1',
            ]);
            $data['discount_amount'] = $request->special_price;
            $data['max_quantity'] = $request->max_quantity;
        }
        
        $promotion = Promotion::create($data);
        
        if ($request->has('product_ids')) {
            foreach ($request->product_ids as $productId) {
                $product = Product::find($productId);
                $pivotData = [];
                
                if ($request->type === 'flash_sale') {
                    $pivotData['special_price'] = $request->special_price;
                    $pivotData['stock_limit'] = $request->max_quantity;
                }
                
                $promotion->products()->attach($productId, $pivotData);
            }
        }
        
        return redirect()->route('admin.promotions.index')->with('success', '活动创建成功');
    }

    public function show(Promotion $promotion)
    {
        $promotion->load('products');
        return view('admin.promotions.show', compact('promotion'));
    }

    public function edit(Promotion $promotion)
    {
        $products = Product::where('status', 'published')->get();
        $promotion->load('products');
        $selectedProductIds = $promotion->products->pluck('id')->toArray();
        
        return view('admin.promotions.edit', compact('promotion', 'products', 'selectedProductIds'));
    }

    public function update(Request $request, Promotion $promotion)
    {
        $request->validate([
            'name' => 'required|max:100',
            'type' => 'required|in:flash_sale,discount,full_reduce',
            'description' => 'nullable',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
        ]);
        
        $data = $request->only(['name', 'type', 'description', 'start_time', 'end_time', 'max_quantity', 'max_per_user', 'is_active']);
        
        if ($request->type === 'discount') {
            $data['discount_rate'] = $request->discount_rate;
            $data['discount_amount'] = null;
            $data['min_amount'] = null;
            $data['reduce_amount'] = null;
        } elseif ($request->type === 'full_reduce') {
            $data['min_amount'] = $request->min_amount;
            $data['reduce_amount'] = $request->reduce_amount;
            $data['discount_rate'] = null;
            $data['discount_amount'] = null;
        } elseif ($request->type === 'flash_sale') {
            $data['discount_amount'] = $request->special_price;
            $data['max_quantity'] = $request->max_quantity;
        }
        
        $promotion->update($data);
        
        if ($request->has('product_ids')) {
            $promotion->products()->sync($request->product_ids);
        } else {
            $promotion->products()->detach();
        }
        
        return redirect()->route('admin.promotions.index')->with('success', '活动更新成功');
    }

    public function destroy(Promotion $promotion)
    {
        $promotion->delete();
        return redirect()->route('admin.promotions.index')->with('success', '活动删除成功');
    }

    public function toggle(Promotion $promotion)
    {
        $promotion->update(['is_active' => !$promotion->is_active]);
        return back()->with('success', $promotion->is_active ? '活动已启用' : '活动已禁用');
    }
}
