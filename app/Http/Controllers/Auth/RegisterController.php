<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Association;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class RegisterController extends Controller
{
    /**
     * Handle association registration.
     */
    public function register(Request $request)
    {
        $request->validate([
            'association_name' => 'required|string|max:255',
            'email'            => 'required|email|unique:associations,email',
            'license_number'   => 'required|string|unique:associations,license_number',
            'category'         => 'required|string',
            'manager_name'     => 'required|string|max:255',
            'phone'            => 'required|string|max:20',
            'password'         => ['required', 'confirmed', Password::min(8)],
        ], [
            'association_name.required' => 'اسم الجمعية مطلوب',
            'email.required'            => 'البريد الإلكتروني مطلوب',
            'email.email'               => 'صيغة البريد الإلكتروني غير صحيحة',
            'email.unique'              => 'البريد الإلكتروني مسجل مسبقاً',
            'license_number.required'   => 'رقم الترخيص مطلوب',
            'license_number.unique'     => 'رقم الترخيص مسجل مسبقاً',
            'category.required'         => 'يرجى اختيار تصنيف الجمعية',
            'manager_name.required'     => 'اسم المسؤول مطلوب',
            'phone.required'            => 'رقم الجوال مطلوب',
            'password.required'         => 'كلمة المرور مطلوبة',
            'password.confirmed'        => 'كلمتا المرور غير متطابقتين',
            'password.min'              => 'كلمة المرور يجب أن تكون 8 أحرف على الأقل',
        ]);

        Association::create([
            'association_name' => $request->association_name,
            'email'            => strtolower(trim($request->email)),
            'license_number'   => $request->license_number,
            'category'         => $request->category,
            'manager_name'     => $request->manager_name,
            'phone'            => $request->phone,
            'password_hash'    => Hash::make($request->password),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'تم إنشاء الحساب بنجاح، سيتم مراجعة طلبكم خلال 48 ساعة عمل',
        ]);
    }
}
