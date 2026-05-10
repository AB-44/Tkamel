<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Association;
use App\Models\Notification;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class RegisterController extends Controller
{
    /**
     * Handle association registration.
     * If the email already exists with status='review', UPDATE the existing record
     * (the association is resubmitting after admin requested modifications).
     * Otherwise, CREATE a new record.
     */
    public function register(Request $request)
    {
        $email = strtolower(trim($request->input('email', '')));

        // Check if this is a re-submission after a review request
        $existing = Association::where('email', $email)->first();
        $isResubmit = $existing && $existing->status === 'review';

        // Validation — email unique rule skips current record on resubmit
        $emailRule = $isResubmit
            ? 'required|email|exists:associations,email'
            : 'required|email|unique:associations,email';

        $licenseRule = $isResubmit
            ? ['required', 'string', function($attr, $val, $fail) use ($existing) {
                $conflict = Association::where('license_number', $val)
                    ->where('id', '!=', $existing->id)->exists();
                if ($conflict) $fail('رقم الترخيص مسجل مسبقاً');
              }]
            : 'required|string|unique:associations,license_number';

        $request->validate([
            'association_name' => 'required|string|max:255',
            'email'            => $emailRule,
            'license_number'   => $licenseRule,
            'category'         => 'required|string',
            'manager_name'     => 'required|string|max:255',
            'phone'            => 'required|string|max:20',
            'password'         => ['required', 'confirmed', Password::min(8)],
        ], [
            'association_name.required' => 'اسم الجمعية مطلوب',
            'email.required'            => 'البريد الإلكتروني مطلوب',
            'email.email'               => 'صيغة البريد الإلكتروني غير صحيحة',
            'email.unique'              => 'البريد الإلكتروني مسجل مسبقاً',
            'email.exists'              => 'البريد الإلكتروني غير مسجل',
            'license_number.required'   => 'رقم الترخيص مطلوب',
            'license_number.unique'     => 'رقم الترخيص مسجل مسبقاً',
            'category.required'         => 'يرجى اختيار تصنيف الجمعية',
            'manager_name.required'     => 'اسم المسؤول مطلوب',
            'phone.required'            => 'رقم الجوال مطلوب',
            'password.required'         => 'كلمة المرور مطلوبة',
            'password.confirmed'        => 'كلمتا المرور غير متطابقتين',
            'password.min'              => 'كلمة المرور يجب أن تكون 8 أحرف على الأقل',
        ]);

        if ($isResubmit) {
            // UPDATE existing record — reset to pending for re-review
            $existing->update([
                'association_name' => $request->association_name,
                'license_number'   => $request->license_number,
                'category'         => $request->category,
                'manager_name'     => $request->manager_name,
                'phone'            => $request->phone,
                'password_hash'    => Hash::make($request->password),
                'status'           => 'pending',   // back to pending for admin to re-review
                'admin_notes'      => null,         // clear previous notes
                'reviewed_at'      => null,
            ]);
            $assoc = $existing;
        } else {
            // CREATE new record
            $assoc = Association::create([
                'association_name' => $request->association_name,
                'email'            => $email,
                'license_number'   => $request->license_number,
                'category'         => $request->category,
                'manager_name'     => $request->manager_name,
                'phone'            => $request->phone,
                'password_hash'    => Hash::make($request->password),
                'status'           => 'pending',
            ]);
        }

        // Notify every admin
        $this->notifyAdmins($assoc, $isResubmit);

        return response()->json([
            'success' => true,
            'message' => $isResubmit
                ? 'تم تحديث بيانات طلبك بنجاح، سيتم مراجعة التعديلات خلال 48 ساعة عمل'
                : 'تم إنشاء الحساب بنجاح، سيتم مراجعة طلبكم خلال 48 ساعة عمل',
        ]);
    }

    private function notifyAdmins(Association $assoc, bool $isResubmit = false): void
    {
        $adminRoleId = Role::where('name', 'admin')->value('id');
        if (!$adminRoleId) return;

        $admins = User::where('role_id', $adminRoleId)->pluck('id');
        $title  = $isResubmit ? 'تحديث طلب تسجيل جمعية' : 'طلب تسجيل جمعية جديدة';
        $body   = $isResubmit
            ? "قامت جمعية «{$assoc->association_name}» بتحديث بيانات طلب تسجيلها بعد طلب التعديل. المسؤول: {$assoc->manager_name}"
            : "تقدمت جمعية «{$assoc->association_name}» بطلب تسجيل جديد. المسؤول: {$assoc->manager_name} — التصنيف: {$assoc->category}";

        $notifications = $admins->map(fn($adminId) => [
            'user_id'      => $adminId,
            'association_id' => $assoc->id,
            'title'        => $title,
            'body'         => $body,
            'type'         => 'association_registration',
            'is_read'      => false,
            'related_id'   => $assoc->id,
            'related_type' => Association::class,
            'created_at'   => now(),
            'updated_at'   => now(),
        ])->toArray();

        if (!empty($notifications)) {
            Notification::insert($notifications);
        }
    }
}
