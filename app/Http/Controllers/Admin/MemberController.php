<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\PointsRule;
use Illuminate\Http\Request;

class MemberController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        
        $members = Customer::with('addresses')
            ->when($search, function($query) use ($search) {
                $query->where(function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%")
                      ->orWhere('phone', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(15);

        $levelConfigs = PointsRule::getMemberLevelConfig();

        return view('admin.members.index', compact('members', 'levelConfigs', 'search'));
    }

    public function show(Customer $member)
    {
        $member->load(['orders', 'addresses', 'pointsRecords']);
        
        $levelConfigs = PointsRule::getMemberLevelConfig();
        
        return view('admin.members.show', compact('member', 'levelConfigs'));
    }

    public function update(Request $request, Customer $member)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'points' => 'required|integer|min:0',
            'status' => 'required|in:active,inactive',
        ]);

        $member->update($data);

        return back()->with('success', '会员信息已更新');
    }

    public function destroy(Customer $member)
    {
        $member->delete();
        return redirect()->route('admin.members.index')->with('success', '会员已删除');
    }

    public function levelIndex()
    {
        $levelConfigs = PointsRule::getMemberLevelConfig();
        return view('admin.members.levels', compact('levelConfigs'));
    }

    public function levelUpdate(Request $request)
    {
        $data = $request->validate([
            'bronze_min' => 'nullable|integer|min:0',
            'bronze_discount' => 'nullable|numeric|min:0|max:100',
            'silver_min' => 'nullable|integer|min:0',
            'silver_discount' => 'nullable|numeric|min:0|max:100',
            'gold_min' => 'nullable|integer|min:0',
            'gold_discount' => 'nullable|numeric|min:0|max:100',
            'platinum_min' => 'nullable|integer|min:0',
            'platinum_discount' => 'nullable|numeric|min:0|max:100',
            'diamond_min' => 'nullable|integer|min:0',
            'diamond_discount' => 'nullable|numeric|min:0|max:100',
        ]);

        foreach ($data as $key => $value) {
            PointsRule::setValue($key, $value);
        }

        PointsRule::clearCache();

        return back()->with('success', '会员等级已更新');
    }
}
