<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /** Return notifications for the logged-in admin */
    public function index()
    {
        $notifs = Notification::where('user_id', Auth::id())
            ->latest()
            ->take(30)
            ->get(['id', 'title', 'body', 'type', 'is_read', 'related_id', 'created_at']);

        return response()->json([
            'notifications' => $notifs,
            'unread_count'  => $notifs->where('is_read', false)->count(),
        ]);
    }

    /** Mark one notification as read */
    public function markRead($id)
    {
        Notification::where('id', $id)
            ->where('user_id', Auth::id())
            ->update(['is_read' => true]);

        return response()->json(['success' => true]);
    }

    /** Mark all notifications as read */
    public function markAllRead()
    {
        Notification::where('user_id', Auth::id())
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return response()->json(['success' => true]);
    }
}
