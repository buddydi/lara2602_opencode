<?php

namespace App\Http\Controllers;

use App\Models\Address;
use Illuminate\Http\Request;

class FrontAddressController extends Controller
{
    public function index()
    {
        $addresses = auth('customer')->user()->addresses()->orderBy('is_default', 'desc')->get();
        return view('front.address.index', compact('addresses'));
    }

    public function create()
    {
        return view('front.address.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|max:255',
            'phone' => 'required|max:20',
            'province' => 'required|max:100',
            'city' => 'required|max:100',
            'district' => 'required|max:100',
            'detail_address' => 'required|max:500',
            'postal_code' => 'nullable|max:10',
        ]);

        if ($request->is_default) {
            auth('customer')->user()->addresses()->update(['is_default' => false]);
        }

        auth('customer')->user()->addresses()->create($validated);

        return redirect()->route('addresses.index')->with('success', '地址添加成功');
    }

    public function edit(Address $address)
    {
        if ($address->customer_id != auth('customer')->id()) {
            return back()->with('error', '无权操作');
        }
        return view('front.address.edit', compact('address'));
    }

    public function update(Request $request, Address $address)
    {
        if ($address->customer_id != auth('customer')->id()) {
            return back()->with('error', '无权操作');
        }

        $validated = $request->validate([
            'name' => 'required|max:255',
            'phone' => 'required|max:20',
            'province' => 'required|max:100',
            'city' => 'required|max:100',
            'district' => 'required|max:100',
            'detail_address' => 'required|max:500',
            'postal_code' => 'nullable|max:10',
        ]);

        if ($request->is_default) {
            auth('customer')->user()->addresses()->where('id', '!=', $address->id)->update(['is_default' => false]);
        }

        $address->update($validated);

        return redirect()->route('addresses.index')->with('success', '地址更新成功');
    }

    public function destroy(Address $address)
    {
        if ($address->customer_id != auth('customer')->id()) {
            return back()->with('error', '无权操作');
        }

        $address->delete();

        return back()->with('success', '地址删除成功');
    }
}
