<?php

namespace App\Http\Requests\Settings;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UpdateProfileRequest extends FormRequest
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
     */
    public function rules(): array
    {
        // ملاحظة: البريد الإلكتروني ورقم الجوال لهما مسار تعديل منفصل
        // (UpdateContactRequest) يتطلب تأكيد كلمة المرور، لذا لا يُعدَّلان هنا.
        if (session()->has('association')) {
            return [
                'full_name' => ['required', 'string', 'max:255'],
                'bio'       => ['nullable', 'string', 'max:5000'],
            ];
        }

        return [
            'full_name' => ['required', 'string', 'max:255'],
            'bio'       => ['nullable', 'string', 'max:5000'],
        ];
    }

    /**
     * رسائل الخطأ العربية
     */
    public function messages(): array
    {
        return [
            'full_name.required' => 'الاسم الكامل مطلوب',
            'full_name.string'   => 'الاسم الكامل يجب أن يكون نصاً',
            'full_name.max'      => 'الاسم الكامل يجب ألا يتجاوز 255 حرفاً',
            'bio.string'         => 'نبذة تعريفية يجب أن تكون نصاً',
            'bio.max'            => 'نبذة تعريفية يجب ألا تتجاوز 5000 حرف',
        ];
    }
}
