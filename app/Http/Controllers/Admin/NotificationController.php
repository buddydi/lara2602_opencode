<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $query = Notification::with('customer');
        
        if ($request->type) {
            $query->where('type', $request->type);
        }
        
        if ($request->customer_id) {
            $query->where('customer_id', $request->customer_id);
        }
        
        $notifications = $query->orderBy('created_at', 'desc')->paginate(20);
        $customers = Customer::all();
        
        return view('admin.notifications.index', compact('notifications', 'customers'));
    }

    public function create()
    {
        $customers = Customer::all();
        return view('admin.notifications.create', compact('customers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_id' => 'required',
            'type' => 'required|in:order,refund,system',
            'title' => 'required|max:100',
            'content' => 'required',
        ]);

        if ($request->customer_id === 'all') {
            $customers = Customer::all();
            foreach ($customers as $customer) {
                Notification::send($customer->id, $request->type, $request->title, $request->content);
            }
            $message = '已向所有 ' . $customers->count() . ' 位用户发送消息';
        } else {
            Notification::send($request->customer_id, $request->type, $request->title, $request->content);
            $message = '消息发送成功';
        }

        return redirect()->route('admin.notifications.index')->with('success', $message);
    }

    public function destroy(Notification $notification)
    {
        $notification->delete();
        return back()->with('success', '删除成功');
    }
}
