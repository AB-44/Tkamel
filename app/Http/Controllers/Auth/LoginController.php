<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Association;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    /**
     * Show the login / register page.
     */
    public function showLogin()
    {
        if (Auth::check() || session('association')) {
            return redirect()->route('dashboard');
        }

        return view('auth.login');
    }

    /**
     * Handle login for both Users (admin) and Associations.
     */
    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ], [
            'email.required'    => 'البريد الإلكتروني مطلوب',
            'email.email'       => 'صيغة البريد الإلكتروني غير صحيحة',
            'password.required' => 'كلمة المرور مطلوبة',
        ]);

        $email    = strtolower(trim($request->email));
        $password = $request->password;
        $remember = $request->boolean('remember');

        // ── 1. Try User (admin) accounts first ──────────────────────────
        if (Auth::attempt(['email' => $email, 'password' => $password], $remember)) {
            $request->session()->regenerate();

            return response()->json([
                'success'  => true,
                'type'     => 'user',
                'name'     => Auth::user()->full_name,
                'redirect' => route('dashboard'),
            ]);
        }

        // ── 2. Try Association accounts ──────────────────────────────────
        $association = Association::where('email', $email)->first();

        if ($association && Hash::check($password, $association->password_hash)) {
            // Store association info in session
            $request->session()->regenerate();
            session([
                'association' => [
                    'id'    => $association->id,
                    'name'  => $association->association_name,
                    'email' => $association->email,
                ],
            ]);

            return response()->json([
                'success'  => true,
                'type'     => 'association',
                'name'     => $association->association_name,
                'redirect' => route('dashboard'),
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'البريد الإلكتروني أو كلمة المرور غير صحيحة',
        ], 401);
    }

    /**
     * Log the user out.
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->forget('association');
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
