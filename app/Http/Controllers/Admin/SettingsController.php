<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class SettingsController extends Controller
{
    public function uploadAvatar(Request $request)
    {
        $request->validate([
            'avatar' => ['required', 'file', 'image', 'max:2048'],
        ], [
            'avatar.required' => 'يرجى اختيار صورة',
            'avatar.image' => 'الملف يجب أن يكون صورة',
            'avatar.max' => 'الحد الأقصى للصورة 2 ميجابايت',
        ]);

        $user = Auth::user();
        $path = $request->file('avatar')->store('avatars', 'public');

        // Delete old avatar if exists
        if (!empty($user->avatar_path)) {
            Storage::disk('public')->delete($user->avatar_path);
        }

        $user->avatar_path = $path;
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'تم تحديث الصورة',
            'avatar_url' => asset('storage/' . $path),
        ]);
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'full_name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($user->id),
            ],
            'phone' => ['nullable', 'string', 'max:30'],
            'bio' => ['nullable', 'string', 'max:5000'],
        ], [
            'full_name.required' => 'الاسم الكامل مطلوب',
            'email.required' => 'البريد الإلكتروني مطلوب',
            'email.email' => 'صيغة البريد الإلكتروني غير صحيحة',
            'email.unique' => 'هذا البريد الإلكتروني مستخدم مسبقاً',
        ]);

        $user->fill([
            'full_name' => $validated['full_name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'bio' => $validated['bio'] ?? null,
        ]);
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'تم حفظ بيانات الملف الشخصي',
            'user' => [
                'full_name' => $user->full_name,
                'email' => $user->email,
                'phone' => $user->phone ?? null,
                'bio' => $user->bio ?? null,
                'avatar_url' => $user->avatar_path ? asset('storage/' . $user->avatar_path) : null,
            ],
        ]);
    }

    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'new_password' => ['required', 'string', 'min:8', 'max:255'],
            'confirm_password' => ['required', 'same:new_password'],
        ], [
            'new_password.required' => 'كلمة المرور الجديدة مطلوبة',
            'new_password.min' => 'كلمة المرور يجب ألا تقل عن 8 أحرف',
            'confirm_password.same' => 'تأكيد كلمة المرور غير مطابق',
        ]);

        $user = Auth::user();
        $user->password_hash = Hash::make($validated['new_password']);
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'تم تحديث كلمة المرور بنجاح',
        ]);
    }
}

