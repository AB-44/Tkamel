<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\ServiceRequest;
use App\Models\OpportunityRequest;

class MyRequestController extends Controller
{
    public function index()
    {
        $authUser     = Auth::user();
        $assocSession = session('association');

        if (!$authUser && !$assocSession) {
            abort(401);
        }

        $serviceReqs = ServiceRequest::query()
            ->when($authUser,  fn ($q) => $q->where('user_id', $authUser->id))
            ->when(!$authUser, fn ($q) => $q->where('association_id', $assocSession['id']))
            ->get();

        $oppReqs = OpportunityRequest::with(['opportunity', 'project'])
            ->when($authUser,  fn ($q) => $q->where('user_id', $authUser->id))
            ->when(!$authUser, fn ($q) => $q->where('association_id', $assocSession['id']))
            ->get();

        $latestOppReqs = $oppReqs->map(function ($req) {
            if ($req->project_id) {
                $req->title    = $req->project->name ?? 'طلب مشروع محذوف';
                $req->sub      = 'طلب انضمام لمشروع';
                $req->color    = '#10b981';
                $req->icon     = 'fa-diagram-project';
                $req->type     = 'project';
            } else {
                $req->title    = $req->opportunity->title ?? 'طلب فرصة محذوفة';
                $req->sub      = 'طلب فرصة تطوع';
                $req->color    = '#f59e0b';
                $req->icon     = 'fa-hand-holding-heart';
                $req->type     = 'opportunity';
            }
            $req->date = $req->created_at;
            return $req;
        });

        $latestServiceReqs = $serviceReqs->map(function ($req) {
            $req->sub   = 'طلب خدمة مبادرون - ' . $this->getServiceTypeName($req->service_type);
            $req->color = '#3b82f6';
            $req->icon  = 'fa-screwdriver-wrench';
            $req->type  = 'service';
            $req->date  = $req->created_at;
            return $req;
        });

        $allRequests = $latestOppReqs->concat($latestServiceReqs)->sortByDesc('created_at')->values();

        $totalReqs = $allRequests->count();
        $pendingReqs = $serviceReqs->whereIn('status', ['pending', 'review'])->count() + $oppReqs->where('status', 'pending')->count();
        $approvedReqs = $serviceReqs->whereIn('status', ['approved', 'completed'])->count() + $oppReqs->where('status', 'approved')->count();
        $rejectedReqs = $serviceReqs->where('status', 'rejected')->count() + $oppReqs->where('status', 'rejected')->count();

        $stats = [
            'total_requests'       => $totalReqs,
            'pending_requests'     => $pendingReqs,
            'approved_requests'    => $approvedReqs,
            'rejected_requests'    => $rejectedReqs,
        ];

        return view('user.my-requests', compact('allRequests', 'stats'));
    }

    private function getServiceTypeName($type) {
        $map = [
            'units'       => 'بناء وحدات',
            'systems'     => 'بناء أنظمة',
            'training'    => 'تدريب المتطوعين',
            'initiatives' => 'تنسيق المبادرات',
            'consulting'  => 'استشارات متخصصة',
            'other'       => 'طلب آخر'
        ];
        return $map[$type] ?? 'خدمة عامة';
    }
}
