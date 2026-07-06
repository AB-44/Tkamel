<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use App\Models\Association;
use App\Models\Opportunity;
use App\Models\JointProject;
use App\Models\Meeting;
use App\Models\OpportunityRequest;
use App\Exceptions\UnauthorizedException;

class DashboardController extends Controller
{
    /**
     * Admin Dashboard
     */
    public function adminDashboard()
    {
        $stats = [
            'associations_count'  => Association::where('status', 'approved')->count(),
            'opportunities_count' => Opportunity::count(),
            'projects_count'      => JointProject::count(),
            'completed_requests'  => OpportunityRequest::where('status', 'approved')->count()
                                   + Association::where('status', 'approved')->count(),
        ];

        $upcomingMeetings    = $this->getUpcomingMeetings();
        $activeProjects      = JointProject::orderBy('created_at', 'desc')->take(2)->get();
        $latestOpportunities = Opportunity::orderBy('created_at', 'desc')->take(2)->get();

        $latestOppRequests = OpportunityRequest::with(['opportunity', 'user', 'association'])
            ->whereNotNull('opportunity_id')
            ->orderBy('created_at', 'desc')
            ->take(2)
            ->get();
            
        $latestProjApps = OpportunityRequest::with(['project', 'user', 'association'])
            ->whereNotNull('project_id')
            ->orderBy('created_at', 'desc')
            ->take(2)
            ->get();

        return view('dashboard', compact('stats', 'upcomingMeetings', 'activeProjects', 'latestOpportunities', 'latestOppRequests', 'latestProjApps'));
    }

    /**
     * Admin Dashboard — JSON (used by the SPA dashboard section inside consulting.blade.php)
     */
    public function dashboardApi()
    {
        $stats = [
            'associations_count'  => Association::where('status', 'approved')->count(),
            'opportunities_count' => Opportunity::count(),
            'projects_count'      => JointProject::count(),
            'completed_requests'  => OpportunityRequest::where('status', 'approved')->count()
                                   + Association::where('status', 'approved')->count(),
        ];

        $upcomingMeetings = $this->getUpcomingMeetings()->map(function ($m) {
            return [
                'title'     => $m->title,
                'date_time' => $m->date_time,
                'category'  => is_string($m->category) ? $m->category : ($m->category->name ?? null),
                'type'      => $m->type,
            ];
        })->values();

        $activeProjects = JointProject::with('category')->orderBy('created_at', 'desc')->take(2)->get()
            ->map(function ($p) {
                $statusMap = [
                    'planning' => ['label' => 'قيد الإعداد', 'color' => 'b-prep'],
                    'active'   => ['label' => 'مستمر',       'color' => 'b-active'],
                    'idea'     => ['label' => 'فكرة',         'color' => 'b-idea'],
                ];
                $st = $statusMap[$p->status] ?? ['label' => $p->status, 'color' => 'b-prep'];
                return [
                    'name'          => $p->name,
                    'category_name' => $p->category->name ?? null,
                    'category_icon' => $p->category->icon ?? '🏢',
                    'start_date'    => $p->start_date,
                    'status_label'  => $st['label'],
                    'status_color'  => $st['color'],
                ];
            })->values();

        $latestOpportunities = Opportunity::with('targets')->orderBy('created_at', 'desc')->take(2)->get()
            ->map(function ($o) {
                return [
                    'title'         => $o->title,
                    'direction'     => $o->direction,
                    'deadline'      => $o->deadline,
                    'category_name' => null,
                    'category_icon' => '💡',
                    'is_closed'     => $o->deadline ? \Carbon\Carbon::parse($o->deadline)->isPast() : false,
                ];
            })->values();

        $latestOppRequests = OpportunityRequest::with(['opportunity', 'user', 'association'])
            ->whereNotNull('opportunity_id')
            ->orderBy('created_at', 'desc')
            ->take(2)
            ->get()
            ->map(function ($req) {
                return [
                    'title'      => $req->opportunity->title ?? 'طلب فرصة محذوفة',
                    'applicant'  => $req->association?->association_name ?? $req->user?->full_name ?? $req->user?->name ?? '—',
                    'created_at' => $req->created_at,
                ];
            })->values();

        $latestProjApps = OpportunityRequest::with(['project', 'user', 'association'])
            ->whereNotNull('project_id')
            ->orderBy('created_at', 'desc')
            ->take(2)
            ->get()
            ->map(function ($app) {
                return [
                    'title'      => $app->project->name ?? 'مشروع محذوف',
                    'applicant'  => $app->association?->association_name ?? $app->user?->full_name ?? $app->user?->name ?? '—',
                    'created_at' => $app->created_at,
                ];
            })->values();

        return response()->json([
            'stats'                 => $stats,
            'upcoming_meetings'     => $upcomingMeetings,
            'active_projects'       => $activeProjects,
            'latest_opportunities'  => $latestOpportunities,
            'latest_opp_requests'   => $latestOppRequests,
            'latest_proj_apps'      => $latestProjApps,
        ]);
    }

    /**
     * Volunteer / Regular User Dashboard
     */
    public function userDashboard()
    {
        $authUser     = Auth::user();
        $assocSession = session('association');

        if (!$authUser && !$assocSession) {
            throw new UnauthorizedException();
        }

        $serviceReqs = \App\Models\ServiceRequest::query()
            ->when($authUser,  fn ($q) => $q->where('user_id', $authUser->id))
            ->when(!$authUser, fn ($q) => $q->where('association_id', $assocSession['id']))
            ->get();

        $oppReqs = OpportunityRequest::with(['opportunity', 'project'])
            ->when($authUser,  fn ($q) => $q->where('user_id', $authUser->id))
            ->when(!$authUser, fn ($q) => $q->where('association_id', $assocSession['id']))
            ->get();

        $totalReqs = $serviceReqs->count() + $oppReqs->count();
        $pendingReqs = $serviceReqs->whereIn('status', ['pending', 'review'])->count() + $oppReqs->where('status', 'pending')->count();
        $approvedReqs = $serviceReqs->whereIn('status', ['approved', 'completed'])->count() + $oppReqs->where('status', 'approved')->count();
        $rejectedReqs = $serviceReqs->where('status', 'rejected')->count() + $oppReqs->where('status', 'rejected')->count();

        $stats = [
            'associations_count'   => Association::where('status', 'approved')->count(),
            'opportunities_count'  => Opportunity::count(),
            'projects_count'       => JointProject::count(),
            'upcoming_meetings_count' => $this->getUpcomingMeetings()->count(),
            'total_requests'       => $totalReqs,
            'pending_requests'     => $pendingReqs,
            'approved_requests'    => $approvedReqs,
            'rejected_requests'    => $rejectedReqs,
        ];

        $upcomingMeetings    = $this->getUpcomingMeetings();
        $activeProjects      = JointProject::orderBy('created_at', 'desc')->take(2)->get();
        $latestOpportunities = Opportunity::orderBy('created_at', 'desc')->take(2)->get();

        $latestOppReqs = $oppReqs->map(function ($req) {
            if ($req->project_id) {
                $req->title = $req->project->name ?? 'طلب مشروع محذوف';
                $req->sub = 'طلب انضمام لمشروع';
                $req->color = '#10b981'; // emerald
                $req->typeIcon = 'fa-diagram-project';
            } else {
                $req->title = $req->opportunity->title ?? 'طلب فرصة محذوفة';
                $req->sub = 'طلب فرصة تطوع';
                $req->color = '#f59e0b'; // amber
                $req->typeIcon = 'fa-hand-holding-heart';
            }
            return $req;
        });

        $latestServiceReqs = $serviceReqs->map(function ($req) {
            $req->sub = 'طلب خدمة مبادرون';
            $req->color = '#3b82f6'; // blue
            $req->typeIcon = 'fa-screwdriver-wrench';
            return $req;
        });

        $latestRequests = $latestOppReqs->concat($latestServiceReqs)->sortByDesc('created_at')->take(4)->values();

        $viewerName = $authUser?->full_name ?? ($assocSession['name'] ?? '');

        return view('user.dashboard', compact(
            'stats',
            'upcomingMeetings',
            'activeProjects',
            'latestOpportunities',
            'latestRequests',
            'viewerName'
        ));
    }

    /**
     * Volunteer / Regular User Dashboard — JSON
     * (used by the SPA dashboard section inside user/consulting.blade.php)
     */
    public function userDashboardApi()
    {
        $authUser     = Auth::user();
        $assocSession = session('association');

        if (!$authUser && !$assocSession) {
            throw new UnauthorizedException();
        }

        $serviceReqs = \App\Models\ServiceRequest::query()
            ->when($authUser,  fn ($q) => $q->where('user_id', $authUser->id))
            ->when(!$authUser, fn ($q) => $q->where('association_id', $assocSession['id']))
            ->get();

        $oppReqs = OpportunityRequest::with(['opportunity', 'project'])
            ->when($authUser,  fn ($q) => $q->where('user_id', $authUser->id))
            ->when(!$authUser, fn ($q) => $q->where('association_id', $assocSession['id']))
            ->get();

        $totalReqs = $serviceReqs->count() + $oppReqs->count();
        $pendingReqs = $serviceReqs->whereIn('status', ['pending', 'review'])->count() + $oppReqs->where('status', 'pending')->count();
        $approvedReqs = $serviceReqs->whereIn('status', ['approved', 'completed'])->count() + $oppReqs->where('status', 'approved')->count();
        $rejectedReqs = $serviceReqs->where('status', 'rejected')->count() + $oppReqs->where('status', 'rejected')->count();

        $stats = [
            'associations_count'      => Association::where('status', 'approved')->count(),
            'opportunities_count'     => Opportunity::count(),
            'projects_count'          => JointProject::count(),
            'upcoming_meetings_count' => $this->getUpcomingMeetings()->count(),
            'total_requests'          => $totalReqs,
            'pending_requests'        => $pendingReqs,
            'approved_requests'       => $approvedReqs,
            'rejected_requests'       => $rejectedReqs,
        ];

        $upcomingMeetings = $this->getUpcomingMeetings()->map(function ($m) {
            return [
                'title'          => $m->title,
                'date_time'      => $m->date_time,
                'meeting_type'   => $m->meeting_type,
                'end_date_time'  => $m->end_date_time,
                'link'           => $m->link,
            ];
        })->values();

        $activeProjects = JointProject::with('category')->orderBy('created_at', 'desc')->take(2)->get()
            ->map(function ($p) {
                return [
                    'name'          => $p->name,
                    'category_name' => $p->category->name ?? null,
                    'category_icon' => $p->category->icon ?? '🏢',
                    'progress'      => $p->progress ?? 0,
                ];
            })->values();

        $latestOpportunities = Opportunity::orderBy('created_at', 'desc')->take(2)->get()
            ->map(function ($o) {
                return [
                    'title'        => $o->title,
                    'organization' => $o->organization ?? 'تكامل',
                ];
            })->values();

        $latestOppReqs = $oppReqs->map(function ($req) {
            if ($req->project_id) {
                $title = $req->project->name ?? 'طلب مشروع محذوف';
                $sub = 'طلب انضمام لمشروع';
                $color = '#10b981';
                $icon = 'fa-diagram-project';
            } else {
                $title = $req->opportunity->title ?? 'طلب فرصة محذوفة';
                $sub = 'طلب فرصة تطوع';
                $color = '#f59e0b';
                $icon = 'fa-hand-holding-heart';
            }
            return [
                'title'      => $title,
                'sub'        => $sub,
                'color'      => $color,
                'typeIcon'   => $icon,
                'status'     => $req->status,
                'created_at' => $req->created_at,
            ];
        });

        $latestServiceReqs = $serviceReqs->map(function ($req) {
            return [
                'title'      => $req->title ?? 'طلب خدمة',
                'sub'        => 'طلب خدمة مبادرون',
                'color'      => '#3b82f6',
                'typeIcon'   => 'fa-screwdriver-wrench',
                'status'     => $req->status,
                'created_at' => $req->created_at,
            ];
        });

        $latestRequests = $latestOppReqs->concat($latestServiceReqs)
            ->sortByDesc('created_at')
            ->take(4)
            ->values();

        return response()->json([
            'stats'                => $stats,
            'upcoming_meetings'    => $upcomingMeetings,
            'active_projects'      => $activeProjects,
            'latest_opportunities' => $latestOpportunities,
            'latest_requests'      => $latestRequests,
        ]);
    }

    private function getUpcomingMeetings()
    {
        // First sync past meetings to ensure status is up to date
        // Since we duplicate the sync logic, it's better to just query 'upcoming' and 'null' 
        // and also ensure they haven't ended yet based on the date/time.
        
        $meetings = Meeting::where(function ($q) {
                $q->where('status', 'upcoming')->orWhereNull('status');
            })
            ->orderBy('date', 'asc')
            ->orderBy('time', 'asc')
            ->get();

        // Filter out past meetings using the exact logic the frontend uses (to avoid timezone mismatch if simple)
        $now = now();
        
        return $meetings->filter(function ($m) use ($now) {
            $endDate = $m->end_date ?: $m->date;
            $endTime = $m->end_time ?: '23:59';
            
            // If the meeting has a specific time but no end time, we should consider it past if the start time has passed.
            // But since duration was previously used, let's just use the start time + 1 hour as a fallback if end_time is missing,
            // or just rely on the strict end_time.
            if (!$m->end_time && $m->time) {
                $endTime = \Carbon\Carbon::parse($m->time)->addHours(1)->format('H:i');
            }
            
            $endDateTime = \Carbon\Carbon::parse($endDate . ' ' . $endTime);
            return $endDateTime->greaterThanOrEqualTo($now);
        })->take(2)->values()->map(function ($meeting) {
            $meeting->date_time = trim(($meeting->date ?? '') . ' ' . ($meeting->time ?? '00:00'));
            return $meeting;
        });
    }
}
