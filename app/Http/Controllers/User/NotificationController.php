<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /** Resolve the identity of the logged-in user or association */
    private function resolveActorIds(): array
    {
        $userId        = null;
        $associationId = null;

        if (Auth::check()) {
            $pk = Auth::user()?->getAttribute('id');
            if (is_numeric($pk)) {
                $userId = (int) $pk;
            }
        }

        $assocSession = session('association');
        if (is_array($assocSession) && isset($assocSession['id']) && is_numeric($assocSession['id'])) {
            $associationId = (int) $assocSession['id'];
        }

        return [$userId, $associationId];
    }

    /** Return notifications for the logged-in user or association */
    public function index()
    {
        [$userId, $associationId] = $this->resolveActorIds();

        $query = Notification::query()->latest()->take(30);

        if ($userId) {
            $query->where('user_id', $userId);
        } elseif ($associationId) {
            $query->where('association_id', $associationId);
        } else {
            return response()->json(['notifications' => [], 'unread_count' => 0]);
        }

        $notifs = $query->get(['id', 'title', 'body', 'type', 'is_read', 'related_id', 'created_at']);

        return response()->json([
            'notifications' => $notifs,
            'unread_count'  => $notifs->where('is_read', false)->count(),
        ]);
    }

    /** Mark one notification as read */
    public function markRead($id)
    {
        [$userId, $associationId] = $this->resolveActorIds();

        $query = Notification::where('id', $id);
        if ($userId) {
            $query->where('user_id', $userId);
        } elseif ($associationId) {
            $query->where('association_id', $associationId);
        }

        $query->update(['is_read' => true]);

        return response()->json(['success' => true]);
    }

    /** Mark all notifications as read */
    public function markAllRead()
    {
        [$userId, $associationId] = $this->resolveActorIds();

        $query = Notification::where('is_read', false);
        if ($userId) {
            $query->where('user_id', $userId);
        } elseif ($associationId) {
            $query->where('association_id', $associationId);
        }

        $query->update(['is_read' => true]);

        return response()->json(['success' => true]);
    }

    /** Delete all notifications for the user/association */
    public function clearAll()
    {
        [$userId, $associationId] = $this->resolveActorIds();

        $query = Notification::query();
        if ($userId) {
            $query->where('user_id', $userId);
        } elseif ($associationId) {
            $query->where('association_id', $associationId);
        }

        $query->delete();

        return response()->json(['success' => true]);
    }

    /** Delete a single notification */
    public function deleteOne($id)
    {
        [$userId, $associationId] = $this->resolveActorIds();

        $query = Notification::where('id', $id);
        if ($userId) {
            $query->where('user_id', $userId);
        } elseif ($associationId) {
            $query->where('association_id', $associationId);
        }

        $query->delete();

        return response()->json(['success' => true]);
    }
}
