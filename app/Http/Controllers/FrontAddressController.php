<?php

namespace App\Http\Controllers;

use App\Models\Address;
use Illuminate\Http\Request;

class FrontAddressController extends Controller
{
    public function index()
    {
        $addresses = auth('customer')->user()->addresses()->orderBy('is_default', 'desc')->get();
        
        if (request()->expectsJson()) {
            return response()->json(['addresses' => $addresses]);
        }
        
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
            'is_default' => 'nullable|boolean',
        ]);

        if ($request->is_default) {
            auth('customer')->user()->addresses()->update(['is_default' => false]);
        }

        $validated['is_default'] = $request->boolean('is_default');
        auth('customer')->user()->addresses()->create($validated);

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'message' => '地址添加成功']);
        }

        return redirect()->route('addresses.index')->with('success', '地址添加成功');
    }

    public function show(Address $address)
    {
        if ($address->customer_id != auth('customer')->id()) {
            return response()->json(['success' => false, 'message' => '无权操作'], 403);
        }
        return response()->json($address);
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
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => '无权操作'], 403);
            }
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
            'is_default' => 'nullable|boolean',
        ]);

        if ($request->is_default) {
            auth('customer')->user()->addresses()->where('id', '!=', $address->id)->update(['is_default' => false]);
        }

        $validated['is_default'] = $request->boolean('is_default');
        $address->update($validated);

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'message' => '地址更新成功']);
        }

        return redirect()->route('addresses.index')->with('success', '地址更新成功');
    }

    public function destroy(Address $address)
    {
        if ($address->customer_id != auth('customer')->id()) {
            return response()->json(['success' => false, 'message' => '无权操作'], 403);
        }

        $address->delete();

        return response()->json(['success' => true, 'message' => '地址删除成功']);
    }

    public function setDefault(Address $address)
    {
        if ($address->customer_id != auth('customer')->id()) {
            return response()->json(['success' => false, 'message' => '无权操作'], 403);
        }

        auth('customer')->user()->addresses()->update(['is_default' => false]);
        $address->update(['is_default' => true]);

        return response()->json(['success' => true, 'message' => '已设为默认地址']);
    }
}
