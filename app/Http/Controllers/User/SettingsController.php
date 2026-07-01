<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Settings\UploadAvatarRequest;
use App\Http\Requests\Settings\UpdateProfileRequest;
use App\Http\Requests\Settings\UpdatePasswordRequest;
use App\Http\Requests\Settings\UpdateContactRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class SettingsController extends Controller
{
    public function uploadAvatar(UploadAvatarRequest $request)
    {
        $path = $request->file('avatar')->store('avatars', 'public');

        if (Auth::check()) {
            $user = Auth::user();

            // Delete old avatar if exists
            if (!empty($user->avatar_path)) {
                Storage::disk('public')->delete($user->avatar_path);
            }

            $user->avatar_path = $path;
            $user->save();
        } elseif (session()->has('association')) {
            $assoc = \App\Models\Association::find(session('association')['id']);
            if (!$assoc) {
                Storage::disk('public')->delete($path);
                return response()->json(['message' => 'Unauthenticated.'], 401);
            }

            // Delete old avatar if exists
            if (!empty($assoc->avatar)) {
                Storage::disk('public')->delete($assoc->avatar);
            }

            $assoc->avatar = $path;
            $assoc->save();
        } else {
            Storage::disk('public')->delete($path);
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

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
            $assoc->save();

            // Update session data
            $sessionData = session('association');
            $sessionData['name'] = $assoc->association_name;
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

    /**
     * تعديل البريد الإلكتروني و/أو رقم الجوال، ويتطلب تأكيد كلمة المرور الحالية.
     */
    public function updateContact(UpdateContactRequest $request)
    {
        $validated = $request->validated();

        if (Auth::check()) {
            $user = Auth::user();
            if (!Hash::check($validated['current_password'], $user->password_hash)) {
                return response()->json(['success' => false, 'errors' => ['current_password' => ['كلمة المرور الحالية غير صحيحة']]], 422);
            }

            if (array_key_exists('email', $validated) && $validated['email']) {
                $user->email = $validated['email'];
            }
            if (array_key_exists('phone', $validated)) {
                $user->phone = $validated['phone'] ?? null;
            }
            $user->save();

            $responseData = [
                'email' => $user->email,
                'phone' => $user->phone ?? null,
            ];
        } elseif (session()->has('association')) {
            $assoc = \App\Models\Association::find(session('association')['id']);
            if (!$assoc || !Hash::check($validated['current_password'], $assoc->password_hash)) {
                return response()->json(['success' => false, 'errors' => ['current_password' => ['كلمة المرور الحالية غير صحيحة']]], 422);
            }

            if (array_key_exists('email', $validated) && $validated['email']) {
                $assoc->email = $validated['email'];
            }
            if (array_key_exists('phone', $validated)) {
                $assoc->phone = $validated['phone'] ?? null;
            }
            $assoc->save();

            $sessionData = session('association');
            $sessionData['email'] = $assoc->email;
            session(['association' => $sessionData]);

            $responseData = [
                'email' => $assoc->email,
                'phone' => $assoc->phone ?? null,
            ];
        } else {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        return response()->json([
            'success' => true,
            'message' => 'تم تحديث بيانات التواصل بنجاح',
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
