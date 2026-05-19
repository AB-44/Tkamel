<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Meeting;
use App\Models\Association;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MeetingController extends Controller
{
    /**
     * GET /user/meetings
     * Returns the meetings page with attending IDs for the current entity.
     */
    public function index()
    {
        // ── Auto-filter by association category ───────────────────────────────
        $categoryFilter = null;
        if (!Auth::check() && session()->has('association')) {
            $assocCategory = session('association')['category'] ?? null;
            if ($assocCategory) {
                // Since meetings use string categories now, we just match the name
                $categoryFilter = $assocCategory;
            }
        }

        $query = Meeting::orderBy('date_time', 'asc');

        // Filter by the association's category (includes all statuses: active, cancelled, completed)
        if ($categoryFilter) {
            $query->where('category', $categoryFilter);
        }

        $meetings = $query->get();

        // Detect who is logged in: regular user or association via session
        $attendingIds = [];
        if (Auth::check()) {
            $attendingIds = Auth::user()->attendingMeetings()->pluck('meetings.id')->toArray();
        } elseif (session()->has('association')) {
            $assoc = Association::find(session('association.id'));
            if ($assoc) {
                $attendingIds = $assoc->attendingMeetings()->pluck('meetings.id')->toArray();
            }
        }

        $formattedMeetings = $meetings->map(function ($m) {
            $report = null;
            if ($m->report_summary || $m->report_decisions) {
                $report = [
                    'summary'   => $m->report_summary,
                    'decisions' => $m->report_decisions,
                    'attendees' => $m->report_attendees,
                    'actions'   => $m->report_actions,
                ];
            }

            $type = ($m->meeting_type === 'in_person') ? 'onsite' : ($m->meeting_type ?? 'onsite');
            $status = in_array($m->status, ['canceled', 'cancelled']) ? 'cancelled' : ($m->status ?? 'active');

            return [
                'id'           => $m->id,
                'title'        => $m->title,
                'cat'          => $m->category ?? 'غير مصنف',
                'type'         => $type,
                'date'         => \Carbon\Carbon::parse($m->date_time)->format('Y-m-d'),
                'time'         => \Carbon\Carbon::parse($m->date_time)->format('H:i'),
                'end_time'     => $m->end_date_time ? \Carbon\Carbon::parse($m->end_date_time)->format('H:i') : '',
                'location'     => $m->location ?? '',
                'link'         => $m->link ?? '',
                'presenter'    => $m->main_speaker ?? 'مقدم الاجتماع',
                'notes'        => $m->description ?? '',
                'status'       => $status,
                'cancelReason' => $m->cancel_reason ?? '',
                'report'       => $report,
            ];
        });

        $categories = \App\Models\AssociationCategory::where('is_active', true)->get();

        return view('user.meetings', [
            'formattedMeetings' => $formattedMeetings,
            'attendingIds'      => $attendingIds,
            'categories'        => $categories,
            'activeNav'         => 'meetings',
        ]);
    }

    /**
     * POST /user/meetings/{id}/attendance
     * Toggles attendance for the current user OR association.
     */
    public function toggleAttendance($id)
    {
        $meeting = Meeting::findOrFail($id);

        // Case 1: Regular authenticated user
        if (Auth::check()) {
            $user = Auth::user();
            $isAttending = $user->attendingMeetings()->where('meeting_id', $id)->exists();

            if ($isAttending) {
                $user->attendingMeetings()->detach($id);
                return response()->json(['message' => 'تم إلغاء الحضور', 'status' => 'detached']);
            } else {
                $user->attendingMeetings()->attach($id);
                return response()->json(['message' => 'تم تسجيل حضورك بنجاح ✅', 'status' => 'attached']);
            }
        }

        // Case 2: Association logged in via session
        if (session()->has('association')) {
            $assoc = Association::find(session('association.id'));

            if (!$assoc) {
                return response()->json(['message' => 'لم يُعثر على الجمعية'], 404);
            }

            $isAttending = $assoc->attendingMeetings()->where('meeting_id', $id)->exists();

            if ($isAttending) {
                $assoc->attendingMeetings()->detach($id);
                return response()->json(['message' => 'تم إلغاء حضور جمعيتك', 'status' => 'detached']);
            } else {
                $assoc->attendingMeetings()->attach($id);
                return response()->json(['message' => 'تم تسجيل حضور جمعيتك بنجاح ✅', 'status' => 'attached']);
            }
        }

        return response()->json(['message' => 'غير مصرح'], 401);
    }
}
