<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;

class FrontCartController extends Controller
{
    public function index()
    {
        $cartItems = auth()->user()->cartItems()->with(['product', 'sku'])->get();
        $total = $cartItems->sum(fn($item) => ($item->sku ? $item->sku->price : $item->product->price) * $item->quantity);
        
        return view('front.cart', compact('cartItems', 'total'));
    }

    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'sku_id' => 'nullable|exists:product_skus,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $product = Product::findOrFail($request->product_id);
        $sku = null;
        
        if ($request->sku_id) {
            $sku = $product->skus()->find($request->sku_id);
        }

        $price = $sku ? $sku->price : $product->price;
        
        $cartItem = CartItem::updateOrCreate(
            [
                'user_id' => auth()->id(),
                'product_id' => $product->id,
                'product_sku_id' => $request->sku_id,
            ],
            ['quantity' => \DB::raw('quantity + ' . $request->quantity)]
        );

        return redirect()->route('cart.index')->with('success', '已添加到购物车');
    }

    public function update(Request $request, CartItem $cartItem)
    {
        if ($cartItem->user_id != auth()->id()) {
            return back()->with('error', '无权操作');
        }

        $request->validate(['quantity' => 'required|integer|min:1']);
        
        $cartItem->update(['quantity' => $request->quantity]);
        
        return back();
    }

    public function destroy(CartItem $cartItem)
    {
        if ($cartItem->user_id != auth()->id()) {
            return back()->with('error', '无权操作');
        }

        $cartItem->delete();
        
        return back()->with('success', '已从购物车移除');
    }
}
