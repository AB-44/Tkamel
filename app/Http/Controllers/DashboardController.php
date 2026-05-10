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
     * Admin & Association Dashboard
     */
    public function adminDashboard()
    {
        // Stats
        $stats = [
            'associations_count' => Association::where('status', 'approved')->count(),
            'opportunities_count' => Opportunity::count(),
            'projects_count' => JointProject::count(),
            'completed_requests' => OpportunityRequest::where('status', 'approved')->count() + Association::where('status', 'approved')->count(),
        ];

        // Upcoming meetings (Next 2)
        $upcomingMeetings = $this->getUpcomingMeetings();

        // Active Joint Projects (Latest 2)
        $activeProjects = JointProject::orderBy('created_at', 'desc')
            ->take(2)
            ->get();

        // Latest Opportunities (Latest 2)
        $latestOpportunities = Opportunity::orderBy('created_at', 'desc')
            ->take(2)
            ->get();

        return view('dashboard', compact('stats', 'upcomingMeetings', 'activeProjects', 'latestOpportunities'));
    }

    /**
     * Volunteer / Regular User Dashboard
     */
    public function userDashboard()
    {
        $authUser = Auth::user();
        $assocSession = session('association');

        // Regular users use Auth; approved associations use session only (no Auth::user()).
        if (!$authUser && !$assocSession) {
            abort(401);
        }

        $stats = [
            'associations_count' => Association::where('status', 'approved')->count(),
            'opportunities_count' => Opportunity::count(),
            'projects_count' => JointProject::count(),
            'my_approved_requests' => OpportunityRequest::query()
                ->where('status', 'approved')
                ->when($authUser, fn ($q) => $q->where('user_id', $authUser->id))
                ->when(!$authUser, fn ($q) => $q->where('association_id', $assocSession['id']))
                ->count(),
        ];

        // Upcoming meetings
        $upcomingMeetings = $this->getUpcomingMeetings();

        // Active Projects
        $activeProjects = JointProject::orderBy('created_at', 'desc')
            ->take(2)
            ->get();

        // Latest Opportunities
        $latestOpportunities = Opportunity::orderBy('created_at', 'desc')
            ->take(2)
            ->get();

        // Latest requests (volunteer accounts: user_id; associations: association_id)
        $latestRequests = OpportunityRequest::with('opportunity')
            ->when($authUser, fn ($q) => $q->where('user_id', $authUser->id))
            ->when(!$authUser, fn ($q) => $q->where('association_id', $assocSession['id']))
            ->orderBy('created_at', 'desc')
            ->take(2)
            ->get();

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
