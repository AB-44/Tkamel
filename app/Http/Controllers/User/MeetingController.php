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
        $assocCategory = null;
        if (session()->has('association')) {
            $assocCategory = session('association')['category'] ?? session('association.category') ?? null;
        }

        $query = Meeting::orderBy('date_time', 'asc');

        // Show meetings where invitation_direction is 'عام' or matches the association's category
        // If no category (admin or regular user), show all
        if ($assocCategory) {
            $query->where(function ($q) use ($assocCategory) {
                $q->where('invitation_direction', 'عام')
                  ->orWhere('invitation_direction', $assocCategory)
                  ->orWhereNull('invitation_direction');
            });
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
                'location_url' => $m->location_url ?? '',
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
