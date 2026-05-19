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
     * Volunteer / Regular User Dashboard
     */
    public function userDashboard()
    {
        $authUser     = Auth::user();
        $assocSession = session('association');

        if (!$authUser && !$assocSession) {
            abort(401);
        }

        $stats = [
            'associations_count'   => Association::where('status', 'approved')->count(),
            'opportunities_count'  => Opportunity::count(),
            'projects_count'       => JointProject::count(),
            'my_approved_requests' => OpportunityRequest::query()
                ->where('status', 'approved')
                ->when($authUser,  fn ($q) => $q->where('user_id', $authUser->id))
                ->when(!$authUser, fn ($q) => $q->where('association_id', $assocSession['id']))
                ->count(),
        ];

        $upcomingMeetings    = $this->getUpcomingMeetings();
        $activeProjects      = JointProject::orderBy('created_at', 'desc')->take(2)->get();
        $latestOpportunities = Opportunity::orderBy('created_at', 'desc')->take(2)->get();

        $latestRequests = OpportunityRequest::with(['opportunity', 'project'])
            ->when($authUser,  fn ($q) => $q->where('user_id', $authUser->id))
            ->when(!$authUser, fn ($q) => $q->where('association_id', $assocSession['id']))
            ->orderBy('created_at', 'desc')
            ->take(2)
            ->get()
            ->map(function ($req) {
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

    private function getUpcomingMeetings()
    {
        if (Schema::hasColumn('meetings', 'date_time')) {
            return Meeting::where('date_time', '>=', now())
                ->orderBy('date_time', 'asc')
                ->take(2)
                ->get();
        }

        return Meeting::whereDate('date', '>=', now()->toDateString())
            ->orderBy('date', 'asc')
            ->orderBy('time', 'asc')
            ->take(2)
            ->get()
            ->map(function ($meeting) {
                $meeting->date_time = trim(($meeting->date ?? '') . ' ' . ($meeting->time ?? '00:00'));
                return $meeting;
            });
    }
}
