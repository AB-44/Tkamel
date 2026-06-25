<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Settings\UploadAvatarRequest;
use App\Http\Requests\Settings\UpdateProfileRequest;
use App\Http\Requests\Settings\UpdatePasswordRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class SettingsController extends Controller
{
    public function uploadAvatar(UploadAvatarRequest $request)
    {
        $user = Auth::user();
        $path = $request->file('avatar')->store('avatars', 'public');

        // Delete old avatar if exists
        if (!empty($user->avatar_path)) {
            Storage::disk('public')->delete($user->avatar_path);
        }

        $user->avatar_path = $path;
        $user->save();

        return response()->json([
            'success'    => true,
            'message'    => 'تم تحديث الصورة',
            'avatar_url' => asset('storage/' . $path),
        ]);
    }

    public function updateProfile(UpdateProfileRequest $request)
    {
        $validated = $request->validated();

        if (Auth::check()) {
            $user = Auth::user();
            $user->full_name = $validated['full_name'];
            $user->email     = $validated['email'];
            $user->phone     = $validated['phone'] ?? null;
            $user->bio       = $validated['bio']   ?? null;
            $user->save();

            $responseData = [
                'full_name'  => $user->full_name,
                'email'      => $user->email,
                'phone'      => $user->phone ?? null,
                'bio'        => $user->bio   ?? null,
                'avatar_url' => $user->avatar_path
                    ? asset('storage/' . $user->avatar_path)
                    : null,
            ];
        } elseif (session()->has('association')) {
            $assoc = \App\Models\Association::find(session('association')['id']);
            if (!$assoc) {
                return response()->json(['message' => 'Unauthenticated.'], 401);
            }
            $assoc->association_name = $validated['full_name'];
            $assoc->email            = $validated['email'];
            $assoc->phone            = $validated['phone'] ?? null;
            $assoc->save();

            // Update session data
            $sessionData = session('association');
            $sessionData['name'] = $assoc->association_name;
            $sessionData['email'] = $assoc->email;
            session(['association' => $sessionData]);

            $responseData = [
                'full_name'  => $assoc->association_name,
                'email'      => $assoc->email,
                'phone'      => $assoc->phone ?? null,
                'bio'        => null,
                'avatar_url' => $assoc->avatar ? asset('storage/' . $assoc->avatar) : null,
            ];
        } else {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        return response()->json([
            'success' => true,
            'message' => 'تم حفظ بيانات الملف الشخصي',
            'user'    => $responseData,
        ]);
    }

    public function updatePassword(UpdatePasswordRequest $request)
    {
        $validated = $request->validated();

        if (Auth::check()) {
            $user = Auth::user();
            if (!Hash::check($validated['current_password'], $user->password_hash)) {
                return response()->json(['success' => false, 'errors' => ['current_password' => ['كلمة المرور الحالية غير صحيحة']]], 422);
            }
            $user->password_hash = Hash::make($validated['new_password']);
            $user->save();
        } elseif (session()->has('association')) {
            $assoc = \App\Models\Association::find(session('association')['id']);
            if (!$assoc || !Hash::check($validated['current_password'], $assoc->password_hash)) {
                return response()->json(['success' => false, 'errors' => ['current_password' => ['كلمة المرور الحالية غير صحيحة']]], 422);
            }
            $assoc->password_hash = Hash::make($validated['new_password']);
            $assoc->save();
        } else {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        return response()->json([
            'success' => true,
            'message' => 'تم تحديث كلمة المرور بنجاح',
        ]);
    }
}
