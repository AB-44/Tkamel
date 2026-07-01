<?php

namespace App\Http\Requests\Settings;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UpdateContactRequest extends FormRequest
{
    /**
     * تحقق من صلاحية المستخدم لتنفيذ هذا الطلب
     */
    public function authorize(): bool
    {
        return Auth::check() || session()->has('association');
    }

    /**
     * قواعد التحقق
     * يسمح بتحديث الإيميل أو الرقم (أحدهما أو كلاهما)، ويتطلب كلمة المرور الحالية للتأكيد.
     */
    public function rules(): array
    {
        if (session()->has('association')) {
            $assocId = session('association')['id'];
            return [
                'current_password' => ['required', 'string'],
                'email' => [
                    'nullable',
                    'email',
                    'max:255',
                    Rule::unique('associations', 'email')->ignore($assocId),
                ],
                'phone' => ['nullable', 'string', 'max:30'],
            ];
        }

        $userId = Auth::id();
        return [
            'current_password' => ['required', 'string'],
            'email' => [
                'nullable',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($userId),
            ],
            'phone' => ['nullable', 'string', 'max:30'],
        ];
    }

    /**
     * رسائل الخطأ العربية
     */
    public function messages(): array
    {
        return [
            'current_password.required' => 'يرجى إدخال كلمة المرور الحالية للتأكيد',
            'email.email'                => 'صيغة البريد الإلكتروني غير صحيحة',
            'email.max'                  => 'البريد الإلكتروني يجب ألا يتجاوز 255 حرفاً',
            'email.unique'               => 'هذا البريد الإلكتروني مستخدم مسبقاً',
            'phone.string'               => 'رقم الجوال يجب أن يكون نصاً',
            'phone.max'                  => 'رقم الجوال يجب ألا يتجاوز 30 حرفاً',
        ];
    }
}
