<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;

class FrontNotificationController extends Controller
{
    public function index(Request $request)
    {
        $customer = auth('customer')->user();
        $query = $customer->notifications();
        
        if ($request->type) {
            $query->where('type', $request->type);
        }
        
        if ($request->status) {
            $query->where('status', $request->status);
        }
        
        $notifications = $query->orderBy('created_at', 'desc')->paginate(20);
        $unreadCount = $customer->unreadNotifications()->count();
        
        return view('front.notifications.index', compact('notifications', 'unreadCount'));
    }

    public function show(Notification $notification)
    {
        if ($notification->customer_id != auth('customer')->id()) {
            return back()->with('error', '无权操作');
        }
        
        $notification->markAsRead();
        
        return view('front.notifications.show', compact('notification'));
    }

    public function markAllRead()
    {
        $customer = auth('customer')->user();
        $customer->unreadNotifications()->update(['status' => 'read']);
        
        return back()->with('success', '已全部标记为已读');
    }

    public function getUnreadCount()
    {
        $count = auth('customer')->user()->unreadNotifications()->count();
        return response()->json(['count' => $count]);
    }
}
