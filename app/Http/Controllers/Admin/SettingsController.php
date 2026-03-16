<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaymentMethod;
use App\Models\ShippingMethod;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function index()
    {
        return redirect()->route('admin.settings.payment');
    }

    public function paymentIndex()
    {
        $payments = PaymentMethod::orderBy('order')->get();
        return view('admin.settings.payment', compact('payments'));
    }

    public function paymentStore(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:255|unique:payment_methods,code',
            'description' => 'nullable|string',
            'icon' => 'nullable|string|max:255',
            'order' => 'nullable|integer|min:0',
            'is_enabled' => 'nullable|boolean',
            'config' => 'nullable|array',
        ]);

        $data['is_enabled'] = $request->has('is_enabled');
        $data['order'] = $data['order'] ?? 0;

        PaymentMethod::create($data);

        return back()->with('success', '支付方式添加成功');
    }

    public function paymentUpdate(Request $request, PaymentMethod $payment)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:255|unique:payment_methods,code,' . $payment->id,
            'description' => 'nullable|string',
            'icon' => 'nullable|string|max:255',
            'order' => 'nullable|integer|min:0',
            'is_enabled' => 'nullable|boolean',
            'config' => 'nullable|array',
        ]);

        $data['is_enabled'] = $request->has('is_enabled');
        $data['order'] = $data['order'] ?? 0;

        $payment->update($data);

        return back()->with('success', '支付方式更新成功');
    }

    public function paymentDestroy(PaymentMethod $payment)
    {
        $payment->delete();
        return back()->with('success', '支付方式删除成功');
    }

    public function shippingIndex()
    {
        $shippings = ShippingMethod::orderBy('order')->get();
        return view('admin.settings.shipping', compact('shippings'));
    }

    public function shippingStore(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:255|unique:shipping_methods,code',
            'description' => 'nullable|string',
            'first_weight' => 'required|numeric|min:0',
            'first_price' => 'required|numeric|min:0',
            'continue_weight' => 'required|numeric|min:0',
            'continue_price' => 'required|numeric|min:0',
            'free_shipping_amount' => 'nullable|numeric|min:0',
            'estimated_days' => 'nullable|integer|min:0',
            'order' => 'nullable|integer|min:0',
            'is_enabled' => 'nullable|boolean',
        ]);

        $data['is_enabled'] = $request->has('is_enabled');
        $data['order'] = $data['order'] ?? 0;
        $data['free_shipping_amount'] = $data['free_shipping_amount'] ?? null;

        ShippingMethod::create($data);

        return back()->with('success', '配送方式添加成功');
    }

    public function shippingUpdate(Request $request, ShippingMethod $shipping)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:255|unique:shipping_methods,code,' . $shipping->id,
            'description' => 'nullable|string',
            'first_weight' => 'required|numeric|min:0',
            'first_price' => 'required|numeric|min:0',
            'continue_weight' => 'required|numeric|min:0',
            'continue_price' => 'required|numeric|min:0',
            'free_shipping_amount' => 'nullable|numeric|min:0',
            'estimated_days' => 'nullable|integer|min:0',
            'order' => 'nullable|integer|min:0',
            'is_enabled' => 'nullable|boolean',
        ]);

        $data['is_enabled'] = $request->has('is_enabled');
        $data['order'] = $data['order'] ?? 0;
        $data['free_shipping_amount'] = $data['free_shipping_amount'] ?? null;

        $shipping->update($data);

        return back()->with('success', '配送方式更新成功');
    }

    public function shippingDestroy(ShippingMethod $shipping)
    {
        $shipping->delete();
        return back()->with('success', '配送方式删除成功');
    }

    public function shop()
    {
        return view('admin.settings.shop');
    }

    public function shopUpdate(Request $request)
    {
        $data = $request->validate([
            'shop_name' => 'required|string|max:255',
            'shop_description' => 'nullable|string',
            'shop_logo' => 'nullable|string|max:255',
            'contact_phone' => 'nullable|string|max:50',
            'contact_email' => 'nullable|email',
            'address' => 'nullable|string|max:500',
        ]);

        foreach ($data as $key => $value) {
            setting([$key => $value]);
        }

        return back()->with('success', '店铺设置已更新');
    }
}
