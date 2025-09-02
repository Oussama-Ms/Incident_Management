<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Get unread notifications count for the current user
     */
    public function getUnreadCount()
    {
        $count = Notification::where('user_id', Auth::id())
            ->where('is_read', false)
            ->count();
        
        return response()->json(['count' => $count]);
    }

    /**
     * Get notifications for the current user
     */
    public function getNotifications()
    {
        $notifications = Notification::where('user_id', Auth::id())
            ->with(['sender', 'incident'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
        
        return response()->json(['notifications' => $notifications]);
    }

    /**
     * Mark notification as read
     */
    public function markAsRead(Request $request)
    {
        $request->validate([
            'notification_id' => 'required|exists:notifications,id'
        ]);
        
        $notification = Notification::where('id', $request->notification_id)
            ->where('user_id', Auth::id())
            ->first();
        
        if ($notification) {
            $notification->update(['is_read' => true]);
            return response()->json(['success' => true]);
        }
        
        return response()->json(['success' => false], 404);
    }

    /**
     * Mark all notifications as read
     */
    public function markAllAsRead()
    {
        Notification::where('user_id', Auth::id())
            ->where('is_read', false)
            ->update(['is_read' => true]);
        
        return response()->json(['success' => true]);
    }

    /**
     * Delete a notification
     */
    public function delete(Request $request)
    {
        $request->validate([
            'notification_id' => 'required|exists:notifications,id'
        ]);
        
        $notification = Notification::where('id', $request->notification_id)
            ->where('user_id', Auth::id())
            ->first();
        
        if ($notification) {
            $notification->delete();
            return response()->json(['success' => true]);
        }
        
        return response()->json(['success' => false], 404);
    }
} 