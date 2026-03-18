<?php

namespace App\Http\Controllers\Api;

use App\Models\Address;
use Illuminate\Http\Request;

class AddressController extends BaseApiController
{
    public function index(Request $request)
    {
        $addresses = Address::where('customer_id', $request->user()->id)
            ->orderBy('is_default', 'desc')
            ->get();

        return $this->success($addresses);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:50',
            'phone' => 'required|string|max:20',
            'province' => 'required|string|max:50',
            'city' => 'required|string|max:50',
            'district' => 'nullable|string|max:50',
            'detail_address' => 'required|string|max:255',
            'postal_code' => 'nullable|string|max:20',
            'is_default' => 'nullable|boolean',
        ]);

        $data['customer_id'] = $request->user()->id;

        if ($data['is_default'] ?? false) {
            Address::where('customer_id', $request->user()->id)
                ->update(['is_default' => false]);
        }

        $address = Address::create($data);

        return $this->success($address, '添加成功', 201);
    }

    public function show(Address $address, Request $request)
    {
        if ($address->customer_id !== $request->user()->id) {
            return $this->error('无权查看', 403);
        }

        return $this->success($address);
    }

    public function update(Request $request, Address $address)
    {
        if ($address->customer_id !== $request->user()->id) {
            return $this->error('无权操作', 403);
        }

        $data = $request->validate([
            'name' => 'required|string|max:50',
            'phone' => 'required|string|max:20',
            'province' => 'required|string|max:50',
            'city' => 'required|string|max:50',
            'district' => 'nullable|string|max:50',
            'detail_address' => 'required|string|max:255',
            'postal_code' => 'nullable|string|max:20',
            'is_default' => 'nullable|boolean',
        ]);

        if ($data['is_default'] ?? false) {
            Address::where('customer_id', $request->user()->id)
                ->where('id', '!=', $address->id)
                ->update(['is_default' => false]);
        }

        $address->update($data);

        return $this->success(null, '更新成功');
    }

    public function destroy(Address $address, Request $request)
    {
        if ($address->customer_id !== $request->user()->id) {
            return $this->error('无权操作', 403);
        }

        $address->delete();

        return $this->success(null, '删除成功');
    }
}
