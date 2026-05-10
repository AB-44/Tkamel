<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Usage: ->middleware('role:admin')  or  ->middleware('role:admin,association')
     *
     * Supported role values:
     *  - 'admin'       : Auth::user() with role name = 'admin'
     *  - 'user'        : Auth::user() with role name = 'user'
     *  - 'association' : session-based association login (no Auth::user())
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $isAssocSession = (bool) session('association');
        $isAuthUser     = Auth::check();

        // Not logged in at all → go to login
        if (!$isAuthUser && !$isAssocSession) {
            return redirect()->route('login');
        }

        // Determine effective role
        if ($isAssocSession && !$isAuthUser) {
            $effectiveRole = 'association';
        } else {
            $effectiveRole = Auth::user()->role?->name ?? '';
        }

        if (!in_array($effectiveRole, $roles)) {
            abort(403, 'غير مصرح لك بالوصول إلى هذه الصفحة');
        }

        return $next($request);
    }
}
