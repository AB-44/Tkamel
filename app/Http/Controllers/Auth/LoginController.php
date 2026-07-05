<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Association;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function showLogin()
    {
        if (Auth::check() || session('association')) {
            return $this->redirectByRole();
        }
        $categories = \App\Models\AssociationCategory::where('is_active', true)->get();
        return view('auth.login', compact('categories'));
    }
    
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ], [
            'email.required' => 'البريد الإلكتروني مطلوب',
            'email.email' => 'صيغة البريد الإلكتروني غير صحيحة',
            'password.required' => 'كلمة المرور مطلوبة',
        ]);

        $email = strtolower(trim($request->email));
        $pass = $request->password;
        $isJson = $request->expectsJson() || $request->ajax();

        // ── 1. User accounts (admin / user role) ─────────────────────────────
        if (Auth::attempt(['email' => $email, 'password' => $pass], $request->boolean('remember'))) {
            $request->session()->regenerate();
            $request->session()->save();
            $roleName = Auth::user()->role?->name ?? 'user';
            $redirect = ($roleName === 'admin') ? route('dashboard') : route('user.dashboard');
            if ($isJson) {
                return response()->json(['success' => true, 'type' => $roleName, 'name' => Auth::user()->full_name, 'redirect' => $redirect]);
            }
            return redirect($redirect);
        }

        // ── 2. Association accounts ───────────────────────────────────────────
        $assoc = Association::where('email', $email)->first();

        if ($assoc && Hash::check($pass, $assoc->password_hash)) {

            if ($assoc->status === 'pending') {
                $msg = 'طلبك قيد المراجعة حالياً. سيتم إخطارك خلال 48 ساعة عمل عند الموافقة.';
                if ($isJson)
                    return response()->json(['success' => false, 'message' => $msg, 'status' => 'pending'], 403);
                return back()->withErrors(['email' => $msg]);
            }

            if ($assoc->status === 'review') {
                $notes = $assoc->admin_notes ?: 'يرجى مراجعة بيانات التسجيل وتعديلها.';
                $msg = 'طلبك يحتاج إلى تعديلات من الإدارة. يرجى مراجعة ملاحظات الإدارة وتحديث بياناتك.';
                if ($isJson) {
                    return response()->json([
                        'success' => false,
                        'message' => $msg,
                        'status' => 'review',
                        'notes' => $notes,
                        'form_data' => [
                            'association_name' => $assoc->association_name,
                            'email' => $assoc->email,
                            'license_number' => $assoc->license_number,
                            'category' => $assoc->category,
                            'manager_name' => $assoc->manager_name,
                            'phone' => $assoc->phone,
                        ],
                    ], 403);
                }
                return back()->withErrors(['email' => $msg]);
            }

            if ($assoc->status === 'rejected') {
                $notes = $assoc->admin_notes ? " — السبب: {$assoc->admin_notes}" : '';
                $msg = "تم رفض طلب تسجيل جمعيتك{$notes}. يرجى التواصل مع الإدارة.";
                if ($isJson)
                    return response()->json(['success' => false, 'message' => $msg, 'status' => 'rejected'], 403);
                return back()->withErrors(['email' => $msg]);
            }

            // Lookup category numeric ID based on string category name
            $catId = \App\Models\AssociationCategory::where('name', $assoc->category)->value('id');

            // Approved — start association session
            $request->session()->regenerate();
            session([
                'association' => [
                    'id'          => $assoc->id,
                    'name'        => $assoc->association_name,
                    'email'       => $assoc->email,
                    'category'    => $assoc->category,      // category name (legacy)
                    'category_id' => $catId,               // numeric ID for filtering
                    'avatar'      => $assoc->avatar,
                ]
            ]);
            $request->session()->save();

            // Associations go to the user dashboard
            if ($isJson) {
                return response()->json(['success' => true, 'type' => 'association', 'name' => $assoc->association_name, 'redirect' => route('user.dashboard')]);
            }
            return redirect()->route('user.dashboard');
        }

        $msg = 'البريد الإلكتروني أو كلمة المرور غير صحيحة';
        if ($isJson)
            return response()->json(['success' => false, 'message' => $msg], 401);
        return back()->withErrors(['email' => $msg]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->forget('association');
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }

    private function redirectByRole(): \Illuminate\Http\RedirectResponse
    {
        // Session-based associations → admin dashboard
        if (session('association') && !Auth::check()) {
            return redirect()->route('dashboard');
        }
        // Auth users → by role
        $roleName = Auth::user()?->role?->name ?? 'user';
        return redirect($roleName === 'admin' ? route('dashboard') : route('user.dashboard'));
    }
}
