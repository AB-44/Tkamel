<?php

namespace App\Http\Controllers;

use App\Models\Meeting;
use App\Models\MeetingAgendaItem;
use App\Models\Association;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\Rule;

class MeetingController extends Controller
{
    public function index()
    {
        $this->syncPastMeetings();

        return view('meetings');
        FJJV:
    }

    public function list()
    {
        $this->syncPastMeetings();

        $meetings = Meeting::orderByDesc('date')->orderByDesc('time')->get()->map(function (Meeting $meeting) {
            // Treat NULL status as 'upcoming' (legacy rows seeded before status column existed)
            $status = $meeting->status ?? 'upcoming';

            return [
                'id' => $meeting->id,
                'title' => $meeting->title,
                'cat' => $meeting->category,
                'presenter' => $meeting->presenter ?? '—',
                'date' => $meeting->date,
                'time' => $meeting->time,
                'type' => $meeting->type ?? 'onsite',
                'status' => $status,
                'link' => $meeting->link,
                'location' => $meeting->location,
                'location_url' => $meeting->location_url,
                'notes' => $meeting->notes,
                'duration' => $meeting->duration_minutes,
                'invitation' => $meeting->invitation_direction,
                'agendaItems' => $meeting->agendaItems->map(fn($a) => [
                    'id' => $a->id,
                    'title' => $a->topic_title,
                    'duration' => $a->duration_minutes,
                    'presenter' => $a->presenter_name,
                ]),
                'cancelReason' => $meeting->cancel_reason,
                'report' => [
                    'summary' => $meeting->report_summary,
                    'decisions' => $meeting->report_decisions,
                    'attendees' => $meeting->report_attendees,
                    'actions' => $meeting->report_actions,
                ],
            ];
        });

        return response()->json($meetings);
    }

    /**
     * Same as list() but accessible to regular users (no admin check).
     * Returns only upcoming/past meetings (no cancelled internals).
     */
    public function listForUser()
    {
        $this->syncPastMeetings();

        $meetings = Meeting::with(['agendaItems', 'targetAssociations'])
            ->whereIn('status', ['upcoming', 'past', 'cancelled'])
            ->orWhereNull('status')   // legacy rows with NULL status
            ->orderByDesc('date')->orderByDesc('time')
            ->get()
            ->map(function (Meeting $meeting) {
                $status = $meeting->status ?? 'upcoming';

                return [
                    'id' => $meeting->id,
                    'title' => $meeting->title,
                    'cat' => $meeting->category,
                    'presenter' => $meeting->presenter ?? '—',
                    'date' => $meeting->date,
                    'time' => $meeting->time,
                    'duration' => $meeting->duration_minutes,
                    'type' => $meeting->type ?? 'onsite',
                    'status' => $status,
                    'link' => $meeting->link,
                    'location' => $meeting->location,
                    'location_url' => $meeting->location_url,
                    'notes' => $meeting->notes,
                    'description' => $meeting->description,
                    'cancelReason' => $meeting->cancel_reason,
                    'targets' => $meeting->targetAssociations->map(fn($t) => $t->name)->toArray(),
                    'agendaItems' => $meeting->agendaItems->map(fn($a) => [
                        'title' => $a->title,
                        'presenter' => $a->presenter_name,
                        'duration' => $a->duration_minutes
                    ])->toArray(),
                ];
            });

        return response()->json($meetings);
    }

    public function store(Request $request)
    {
        $validated = $request->validate($this->rules());

        $meeting = new Meeting($this->buildPayload($validated, $request->user()?->id));
        $meeting->save();

        $this->syncAgendaItems($meeting, $request->input('agenda_items', []));

        // Notify the creator/admin so the shared bell shows immediate feedback.
        if ($request->user()?->id) {
            Notification::create([
                'user_id' => $request->user()->id,
                'title' => 'تم إنشاء اجتماع جديد',
                'body' => 'تمت إضافة الاجتماع: ' . $meeting->title,
                'type' => 'meeting_created',
                'related_id' => $meeting->id,
                'related_type' => Meeting::class,
                'is_read' => false,
            ]);
        }

        // Notify all regular users about the new meeting
        $userIds = User::whereHas('role', fn($q) => $q->where('name', 'user'))->pluck('id');
        foreach ($userIds as $uid) {
            Notification::create([
                'user_id' => $uid,
                'title' => 'اجتماع جديد',
                'body' => "تمت إضافة اجتماع جديد: {$meeting->title}",
                'type' => 'meeting_created',
                'related_id' => $meeting->id,
                'related_type' => Meeting::class,
                'is_read' => false,
            ]);
        }

        // Notify all approved associations about the new meeting
        $assocIds = Association::where('status', 'approved')->pluck('id');
        foreach ($assocIds as $aid) {
            Notification::create([
                'association_id' => $aid,
                'title' => 'اجتماع جديد',
                'body' => "تمت إضافة اجتماع جديد: {$meeting->title}",
                'type' => 'meeting_created',
                'related_id' => $meeting->id,
                'related_type' => Meeting::class,
                'is_read' => false,
            ]);
        }

        return response()->json(['success' => true, 'id' => $meeting->id]);
    }

    public function update(Request $request, Meeting $meeting)
    {
        $validated = $request->validate($this->rules(true));
        $meeting->update($this->buildPayload($validated, $meeting->created_by));
        $this->syncAgendaItems($meeting, $request->input('agenda_items', []));

        return response()->json(['success' => true]);
    }

    public function joinMeeting(Request $request, Meeting $meeting)
    {
        $userName = $request->input('user_name')
            ?? Auth::user()?->full_name
            ?? 'مستخدم';

        $meetingType = $meeting->type === 'online' ? 'عبر الإنترنت' : 'حضوري';
        $action = $meeting->type === 'online' ? 'انضم إلى' : 'سيحضر';

        // Notify the admin
        $admin = User::whereHas('role', fn($q) => $q->where('name', 'admin'))->first();
        if ($admin) {
            Notification::create([
                'user_id' => $admin->id,
                'title' => 'انضمام إلى اجتماع',
                'body' => $userName . ' ' . $action . ' الاجتماع: ' . $meeting->title . ' (' . $meetingType . ')',
                'type' => 'meeting_joined',
                'related_id' => $meeting->id,
                'related_type' => Meeting::class,
                'is_read' => false,
            ]);
        }

        return response()->json(['success' => true]);
    }

    public function cancel(Request $request, Meeting $meeting)
    {
        $validated = $request->validate([
            'cancel_reason' => ['required', 'string', 'min:5', 'max:1000'],
        ]);

        $meeting->update([
            'status' => 'cancelled',
            'cancel_reason' => $validated['cancel_reason'],
        ]);

        return response()->json(['success' => true]);
    }

    public function destroy(Meeting $meeting)
    {
        $meeting->delete();
        return response()->json(['success' => true]);
    }

    private function rules(bool $isUpdate = false): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'category' => ['required', 'string', 'max:100'],
            'presenter' => ['required', 'string', 'max:255'],
            'date' => ['required', 'date'],
            'time' => ['nullable', 'date_format:H:i'],
            'duration_minutes' => ['nullable', 'integer', 'min:1'],
            'type' => ['required', Rule::in(['online', 'onsite'])],
            'invitation_direction' => ['nullable', 'string', 'max:100'],
            'link' => ['nullable', 'url', 'max:1000'],
            'location' => ['nullable', 'string', 'max:255'],
            'location_url' => ['nullable', 'url', 'max:1000'],
            'notes' => ['nullable', 'string', 'max:5000'],
            'status' => [$isUpdate ? 'sometimes' : 'nullable', Rule::in(['upcoming', 'past', 'cancelled'])],
            'report_summary' => ['nullable', 'string', 'max:5000'],
            'report_decisions' => ['nullable', 'string', 'max:5000'],
            'report_attendees' => ['nullable', 'integer', 'min:0'],
            'report_actions' => ['nullable', 'string', 'max:5000'],
            'agenda_items' => ['nullable', 'array'],
            'agenda_items.*.title' => ['required', 'string', 'max:255'],
            'agenda_items.*.duration' => ['nullable', 'integer', 'min:1'],
            'agenda_items.*.presenter' => ['nullable', 'string', 'max:255'],
        ];
    }

    private function syncAgendaItems(Meeting $meeting, array $items): void
    {
        $meeting->agendaItems()->delete();
        foreach ($items as $i => $item) {
            if (empty($item['title']))
                continue;
            MeetingAgendaItem::create([
                'meeting_id' => $meeting->id,
                'topic_title' => $item['title'],
                'duration_minutes' => $item['duration'] ?? 15,
                'presenter_name' => $item['presenter'] ?? null,
                'order_index' => $i,
            ]);
        }
    }

    private function buildPayload(array $validated, ?int $createdBy): array
    {
        $meetingDateTime = $validated['date'] . ' ' . ($validated['time'] ?? '00:00') . ':00';

        $payload = [
            'created_by' => $createdBy,
            'title' => $validated['title'],
            'main_speaker' => $validated['presenter'],
            'description' => $validated['notes'] ?? null,
            'date_time' => $meetingDateTime,
            'meeting_type' => ($validated['type'] ?? 'onsite') === 'online' ? 'online' : 'in_person',
            'direction' => 'local',
            'category' => $validated['category'],
            'presenter' => $validated['presenter'],
            'date' => $validated['date'],
            'time' => $validated['time'] ?? null,
            'duration_minutes' => $validated['duration_minutes'] ?? null,
            'invitation_direction' => $validated['invitation_direction'] ?? null,
            'type' => $validated['type'],
            'status' => $validated['status'] ?? 'upcoming',
            'link' => $validated['link'] ?? null,
            'location' => $validated['location'] ?? null,
            'location_url' => $validated['location_url'] ?? null,
            'notes' => $validated['notes'] ?? null,
            'report_summary' => $validated['report_summary'] ?? null,
            'report_decisions' => $validated['report_decisions'] ?? null,
            'report_attendees' => $validated['report_attendees'] ?? null,
            'report_actions' => $validated['report_actions'] ?? null,
        ];

        // Some environments may still be on older/newer meetings schema variants.
        // Write only columns that actually exist to avoid SQL unknown column failures.
        $existingColumns = Schema::getColumnListing('meetings');
        return array_intersect_key($payload, array_flip($existingColumns));
    }

    private function syncPastMeetings(): void
    {
        // Handle both 'upcoming' and NULL status (legacy rows seeded before status column)
        Meeting::where(function ($q) {
            $q->where('status', 'upcoming')->orWhereNull('status');
        })
            ->where(function ($query) {
                $query->whereDate('date', '<', now()->toDateString())
                    ->orWhere(function ($sub) {
                        $sub->whereDate('date', now()->toDateString())
                            ->whereNotNull('time')
                            ->where('time', '<', now()->format('H:i'));
                    });
            })
            ->update(['status' => 'past']);

        // Assign 'upcoming' to any remaining NULL status rows (future meetings)
        Meeting::whereNull('status')
            ->whereDate('date', '>=', now()->toDateString())
            ->update(['status' => 'upcoming']);
    }
}

