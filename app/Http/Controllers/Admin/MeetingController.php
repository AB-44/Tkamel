<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Meeting\StoreMeetingRequest;
use App\Http\Requests\Meeting\UpdateMeetingRequest;
use App\Http\Requests\Meeting\CancelMeetingRequest;
use App\Models\Meeting;
use App\Models\MeetingAgendaItem;
use App\Models\Association;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use App\Exceptions\UnauthorizedException;

class MeetingController extends Controller
{
    /**
     * GET /meetings  — Admin meetings page
     */
    public function index()
    {
        $this->syncPastMeetings();
        return view('meetings');
    }

    /**
     * GET /api/meetings  — Admin: all meetings as JSON
     */
    public function list()
    {
        $this->syncPastMeetings();

        $meetings = Meeting::with('agendaItems')
            ->orderByDesc('date')
            ->orderByDesc('time')
            ->get()
            ->map(function (Meeting $meeting) {
                $status = $meeting->status ?? 'upcoming';

                // Attendee count from meeting_association pivot
                $attendeeCount = 0;
                if (Schema::hasTable('meeting_association')) {
                    $attendeeCount = \DB::table('meeting_association')
                        ->where('meeting_id', $meeting->id)
                        ->count();
                }

                $report = null;
                if ($meeting->report_summary || $meeting->report_decisions) {
                    $report = [
                        'summary'   => $meeting->report_summary,
                        'decisions' => $meeting->report_decisions,
                        'attendees' => $meeting->report_attendees,
                        'actions'   => $meeting->report_actions,
                    ];
                }

                return [
                    'id'           => $meeting->id,
                    'title'        => $meeting->title,
                    'cat'          => $meeting->category,
                    'presenter'    => $meeting->presenter ?? '—',
                    'date'         => $meeting->date,
                    'end_date'     => $meeting->end_date,
                    'time'         => $meeting->time,
                    'end_time'     => $meeting->end_time,
                    'duration'     => $meeting->duration_minutes,
                    'type'         => $meeting->type ?? 'onsite',
                    'direction'    => $meeting->direction,
                    'status'       => $status,
                    'link'         => $meeting->link,
                    'location'     => $meeting->location,
                    'location_url' => $meeting->location_url,
                    'notes'        => $meeting->notes,
                    'invitation'   => $meeting->invitation_direction,
                    'agendaItems'  => $meeting->agendaItems->map(fn($a) => [
                        'id'        => $a->id,
                        'title'     => $a->topic_title,
                        'duration'  => $a->duration_minutes,
                        'presenter' => $a->presenter_name,
                    ]),
                    'cancelReason' => $meeting->cancel_reason,
                    'attendee_count' => $attendeeCount,
                    'report'       => $report,
                ];
            });

        return response()->json($meetings);
    }

    /**
     * GET /user/meetings  — User: meetings page
     */
    public function userIndex()
    {
        $this->syncPastMeetings();

        // ── Filter by association category if logged in as association ─────────
        $assocCategory = null;
        if (session()->has('association')) {
            $assocCategory = session('association')['category'] ?? session('association.category') ?? null;
        }

        $query = Meeting::with(['agendaItems'])
            ->where(function ($statusQ) {
                $statusQ->whereIn('status', ['upcoming', 'past', 'cancelled'])
                        ->orWhereNull('status');
            })
            ->orderByDesc('date')
            ->orderByDesc('time');

        // Get the association's numeric category_id from session
        $assocCategoryId = null;
        if (session()->has('association')) {
            $assocCategoryId = session('association.category_id')
                ?? session('association')['category_id']
                ?? null;
        }

        // Filter meetings: show if category is 'all', or FIND_IN_SET matches, or category is null/empty
        if ($assocCategoryId) {
            $query->where(function ($q) use ($assocCategoryId) {
                $q->where('category', 'all')
                  ->orWhereRaw('FIND_IN_SET(?, category)', [(string) $assocCategoryId])
                  ->orWhereNull('category')
                  ->orWhere('category', '')
                  // Legacy: invitation_direction == 'عام' means all
                  ->orWhere('invitation_direction', 'عام');
            });
        }

        $meetings = $query->get();

        $formattedMeetings = $meetings->map(function (Meeting $meeting) {
            $status = $meeting->status ?? 'upcoming';

            $report = null;
            if ($meeting->report_summary || $meeting->report_decisions) {
                $report = [
                    'summary'   => $meeting->report_summary,
                    'decisions' => $meeting->report_decisions,
                    'attendees' => $meeting->report_attendees,
                    'actions'   => $meeting->report_actions,
                ];
            }

            return [
                'id'           => $meeting->id,
                'title'        => $meeting->title,
                'cat'          => $meeting->category,
                'presenter'    => $meeting->presenter ?? '—',
                'date'         => $meeting->date,
                'end_date'     => $meeting->end_date,
                'time'         => $meeting->time,
                'end_time'     => $meeting->end_time,
                'duration'     => $meeting->duration_minutes,
                'type'         => $meeting->type ?? 'onsite',
                'direction'    => $meeting->direction,
                'status'       => $status,
                'link'         => $meeting->link,
                'location'     => $meeting->location,
                'location_url' => $meeting->location_url,
                'notes'        => $meeting->notes,
                'description'  => $meeting->description,
                'cancelReason' => $meeting->cancel_reason,
                'agendaItems'  => $meeting->agendaItems->map(fn($a) => [
                    'title'     => $a->topic_title,
                    'presenter' => $a->presenter_name,
                    'duration'  => $a->duration_minutes,
                ])->toArray(),
                'report'       => $report,
            ];
        });

        $attendingIds = [];
        if (Auth::check()) {
            // If User model has attendingMeetings
            if (method_exists(Auth::user(), 'attendingMeetings')) {
                $attendingIds = Auth::user()->attendingMeetings()->pluck('meetings.id')->toArray();
            }
        } elseif (session()->has('association')) {
            $assoc = Association::find(session('association.id'));
            if ($assoc && method_exists($assoc, 'attendingMeetings')) {
                $attendingIds = $assoc->attendingMeetings()->pluck('meetings.id')->toArray();
            }
        }

        $categories = \App\Models\AssociationCategory::where('is_active', true)->get();

        return view('user.meetings', [
            'formattedMeetings' => $formattedMeetings,
            'attendingIds'      => $attendingIds,
            'categories'        => $categories,
            'activeNav'         => 'meetings',
        ]);
    }


    /**
     * GET /api/user/meetings  — User: read-only list
     */
    public function listForUser()
    {
        $this->syncPastMeetings();

        $meetings = Meeting::with(['agendaItems', 'targetAssociations'])
            ->whereIn('status', ['upcoming', 'past', 'cancelled'])
            ->orWhereNull('status')
            ->orderByDesc('date')
            ->orderByDesc('time')
            ->get()
            ->map(function (Meeting $meeting) {
                $status = $meeting->status ?? 'upcoming';

                return [
                    'id'           => $meeting->id,
                    'title'        => $meeting->title,
                    'cat'          => $meeting->category,
                    'presenter'    => $meeting->presenter ?? '—',
                    'date'         => $meeting->date,
                    'end_date'     => $meeting->end_date,
                    'time'         => $meeting->time,
                    'end_time'     => $meeting->end_time,
                    'duration'     => $meeting->duration_minutes,
                    'type'         => $meeting->type ?? 'onsite',
                    'status'       => $status,
                    'link'         => $meeting->link,
                    'location'     => $meeting->location,
                    'location_url' => $meeting->location_url,
                    'notes'        => $meeting->notes,
                    'description'  => $meeting->description,
                    'cancelReason' => $meeting->cancel_reason,
                    'targets'      => $meeting->targetAssociations->map(fn($t) => $t->name)->toArray(),
                    'agendaItems'  => $meeting->agendaItems->map(fn($a) => [
                        'title'     => $a->topic_title,
                        'presenter' => $a->presenter_name,
                        'duration'  => $a->duration_minutes,
                    ])->toArray(),
                ];
            });

        return response()->json($meetings);
    }

    /**
     * POST /meetings  — Create a new meeting
     */
    public function store(StoreMeetingRequest $request)
    {
        $validated = $request->validated();

        $meeting = new Meeting($this->buildPayload($validated, $request->user()?->id));
        $meeting->save();

        $this->syncAgendaItems($meeting, $request->input('agenda_items', []));

        // Notify creator/admin
        if ($request->user()?->id) {
            Notification::create([
                'user_id'      => $request->user()->id,
                'title'        => 'تم إنشاء اجتماع جديد',
                'body'         => 'تمت إضافة الاجتماع: ' . $meeting->title,
                'type'         => 'meeting_created',
                'related_id'   => $meeting->id,
                'related_type' => Meeting::class,
                'is_read'      => false,
            ]);
        }

        // ── Notify associations based on category selection ──────────────────────
        $category = $meeting->category ?? 'all';
        $catIds   = array_filter(array_map('trim', explode(',', $category)));
        $isAll    = in_array('all', $catIds) || empty($catIds);

        if ($isAll) {
            // Send to ALL approved associations
            $targetAssocIds = Association::where('status', 'approved')->pluck('id');
        } else {
            // Fetch category names from association_categories based on the IDs
            $catNames = \App\Models\AssociationCategory::whereIn('id', $catIds)->pluck('name')->toArray();
            
            // Send only to associations whose category string matches one of the selected category names
            $targetAssocIds = Association::where('status', 'approved')
                ->where(function($q) use ($catNames) {
                    $q->whereIn('category', $catNames);
                })
                ->pluck('id');
        }

        foreach ($targetAssocIds as $aid) {
            Notification::create([
                'association_id' => $aid,
                'title'          => 'اجتماع جديد',
                'body'           => "تمت إضافة اجتماع جديد: {$meeting->title}",
                'type'           => 'meeting_created',
                'related_id'     => $meeting->id,
                'related_type'   => Meeting::class,
                'is_read'        => false,
            ]);
        }

        // Notify all regular users (users always see all meetings)
        $userIds = User::whereHas('role', fn($q) => $q->where('name', 'user'))->pluck('id');
        foreach ($userIds as $uid) {
            Notification::create([
                'user_id'      => $uid,
                'title'        => 'اجتماع جديد',
                'body'         => "تمت إضافة اجتماع جديد: {$meeting->title}",
                'type'         => 'meeting_created',
                'related_id'   => $meeting->id,
                'related_type' => Meeting::class,
                'is_read'      => false,
            ]);
        }

        return response()->json(['success' => true, 'id' => $meeting->id]);
    }

    /**
     * PUT /meetings/{meeting}  — Update an existing meeting
     */
    public function update(UpdateMeetingRequest $request, Meeting $meeting)
    {
        $validated = $request->validated();
        $meeting->update($this->buildPayload($validated, $meeting->created_by));
        $this->syncAgendaItems($meeting, $request->input('agenda_items', []));

        return response()->json(['success' => true]);
    }

    /**
     * POST /api/meetings/{meeting}/join  — User registers attendance (notifies admin)
     */
    public function joinMeeting(Request $request, Meeting $meeting)
    {
        $userName = $request->input('user_name')
            ?? Auth::user()?->full_name
            ?? 'مستخدم';

        $meetingType = $meeting->type === 'online' ? 'عبر الإنترنت' : 'حضوري';
        $action      = $meeting->type === 'online'  ? 'انضم إلى' : 'سيحضر';

        // Notify admin
        $admin = User::whereHas('role', fn($q) => $q->where('name', 'admin'))->first();
        if ($admin) {
            Notification::create([
                'user_id'      => $admin->id,
                'title'        => 'انضمام إلى اجتماع',
                'body'         => $userName . ' ' . $action . ' الاجتماع: ' . $meeting->title . ' (' . $meetingType . ')',
                'type'         => 'meeting_joined',
                'related_id'   => $meeting->id,
                'related_type' => Meeting::class,
                'is_read'      => false,
            ]);
        }

        return response()->json(['success' => true]);
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
            if (method_exists($user, 'attendingMeetings')) {
                $isAttending = $user->attendingMeetings()->where('meetings.id', $id)->exists();

                if ($isAttending) {
                    $user->attendingMeetings()->detach($id);
                    return response()->json(['message' => 'تم إلغاء الحضور', 'status' => 'detached']);
                } else {
                    $user->attendingMeetings()->attach($id);
                    return response()->json(['message' => 'تم تسجيل حضورك بنجاح ✅', 'status' => 'attached']);
                }
            }
        }

        // Case 2: Association logged in via session
        if (session()->has('association')) {
            $assoc = Association::find(session('association.id'));

            if (!$assoc) {
                return response()->json(['message' => 'لم يُعثر على الجمعية'], 404);
            }

            if (method_exists($assoc, 'attendingMeetings')) {
                $isAttending = $assoc->attendingMeetings()->where('meetings.id', $id)->exists();

                if ($isAttending) {
                    $assoc->attendingMeetings()->detach($id);
                    return response()->json(['message' => 'تم إلغاء حضور جمعيتك', 'status' => 'detached']);
                } else {
                    $assoc->attendingMeetings()->attach($id);
                    return response()->json(['message' => 'تم تسجيل حضور جمعيتك بنجاح ✅', 'status' => 'attached']);
                }
            }
        }

        throw new UnauthorizedException();
    }

    /**
     * POST /meetings/{meeting}/cancel  — Cancel a meeting
     */
    public function cancel(CancelMeetingRequest $request, Meeting $meeting)
    {
        $validated = $request->validated();

        $meeting->update([
            'status'        => 'cancelled',
            'cancel_reason' => $validated['cancel_reason'],
        ]);

        return response()->json(['success' => true]);
    }

    /**
     * DELETE /meetings/{meeting}  — Delete a meeting
     */
    public function destroy(Meeting $meeting)
    {
        $meeting->delete();
        return response()->json(['success' => true]);
    }

    /**
     * GET /api/meetings/{meeting}/attendees  — List attending associations
     */
    public function attendees(Meeting $meeting)
    {
        if (!Schema::hasTable('meeting_association')) {
            return response()->json(['total' => 0, 'associations' => []]);
        }

        $rows = \DB::table('meeting_association as ma')
            ->join('associations as a', 'a.id', '=', 'ma.association_id')
            ->where('ma.meeting_id', $meeting->id)
            ->select(
                'a.id',
                'a.association_name',
                'a.manager_name',
                'a.email',
                'ma.created_at as registered_at'
            )
            ->orderBy('ma.created_at', 'asc')
            ->get();

        return response()->json([
            'total'        => $rows->count(),
            'associations' => $rows,
        ]);
    }

    // ─── Private helpers ────────────────────────────────────────────────

    private function syncAgendaItems(Meeting $meeting, array $items): void
    {
        $meeting->agendaItems()->delete();
        foreach ($items as $i => $item) {
            if (empty($item['title'])) continue;
            MeetingAgendaItem::create([
                'meeting_id'       => $meeting->id,
                'topic_title'      => $item['title'],
                'duration_minutes' => $item['duration'] ?? 15,
                'presenter_name'   => $item['presenter'] ?? null,
                'order_index'      => $i,
            ]);
        }
    }

    private function buildPayload(array $validated, ?int $createdBy): array
    {
        $meetingDateTime = $validated['date'] . ' ' . ($validated['time'] ?? '00:00') . ':00';

        $payload = [
            'created_by'           => $createdBy,
            'title'                => $validated['title'],
            'main_speaker'         => $validated['presenter'],
            'description'          => $validated['notes'] ?? null,
            'date_time'            => $meetingDateTime,
            'meeting_type'         => ($validated['type'] ?? 'onsite') === 'online' ? 'online' : 'in_person',
            'direction'            => 'local',
            'category'             => $validated['category'],
            'presenter'            => $validated['presenter'],
            'date'                 => $validated['date'],
            'end_date'             => $validated['end_date'] ?? null,
            'time'                 => $validated['time'] ?? null,
            'end_time'             => $validated['end_time'] ?? null,
            'invitation_direction' => $validated['invitation_direction'] ?? null,
            'type'                 => $validated['type'],
            'status'               => $validated['status'] ?? 'upcoming',
            'link'                 => $validated['link'] ?? null,
            'location'             => $validated['location'] ?? null,
            'location_url'         => $validated['location_url'] ?? null,
            'notes'                => $validated['notes'] ?? null,
            'report_summary'       => $validated['report_summary'] ?? null,
            'report_decisions'     => $validated['report_decisions'] ?? null,
            'report_attendees'     => $validated['report_attendees'] ?? null,
            'report_actions'       => $validated['report_actions'] ?? null,
        ];

        // Write only columns that exist (safe against schema variants)
        $existingColumns = Schema::getColumnListing('meetings');
        return array_intersect_key($payload, array_flip($existingColumns));
    }

    private function syncPastMeetings(): void
    {
        $nowDate = now()->toDateString();
        $nowTime = now()->format('H:i');

        // ── Mark as PAST ────────────────────────────────────────────────────────
        // CASE 1: end_date is set → expired when end_date passed, or end_date=today & end_time passed
        Meeting::where(function ($q) { $q->where('status', 'upcoming')->orWhereNull('status'); })
            ->whereNotNull('end_date')
            ->where(function ($q) use ($nowDate, $nowTime) {
                $q->whereDate('end_date', '<', $nowDate)
                  ->orWhere(function ($s) use ($nowDate, $nowTime) {
                      $s->whereDate('end_date', $nowDate)
                        ->whereNotNull('end_time')
                        ->where('end_time', '<', $nowTime);
                  });
            })
            ->update(['status' => 'past']);

        // CASE 2: no end_date but end_time set → expired when date passed, or date=today & end_time passed
        Meeting::where(function ($q) { $q->where('status', 'upcoming')->orWhereNull('status'); })
            ->whereNull('end_date')
            ->whereNotNull('end_time')
            ->where(function ($q) use ($nowDate, $nowTime) {
                $q->whereDate('date', '<', $nowDate)
                  ->orWhere(function ($s) use ($nowDate, $nowTime) {
                      $s->whereDate('date', $nowDate)
                        ->where('end_time', '<', $nowTime);
                  });
            })
            ->update(['status' => 'past']);

        // CASE 3: no end_date, no end_time → expired only when DATE itself has fully passed (strict yesterday or earlier)
        // A meeting today with no end_time stays "upcoming" until end of day
        Meeting::where(function ($q) { $q->where('status', 'upcoming')->orWhereNull('status'); })
            ->whereNull('end_date')
            ->whereNull('end_time')
            ->whereDate('date', '<', $nowDate)
            ->update(['status' => 'past']);

        // ── Fix meetings with null status that should be upcoming ───────────────
        Meeting::whereNull('status')
            ->whereDate('date', '>=', $nowDate)
            ->update(['status' => 'upcoming']);
    }
}

