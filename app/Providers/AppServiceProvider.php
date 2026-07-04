<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        //  تسجيل الدخول: 5 محاولات بالدقيقة لكل IP
        RateLimiter::for('login', function (Request $request) {
            return Limit::perMinute(5)
                ->by($request->ip())
                ->response(function () use ($request) {
                    $msg = 'محاولات دخول كثيرة جدًا، حاول بعد دقيقة.';
                    if ($request->expectsJson() || $request->ajax()) {
                        return response()->json(['success' => false, 'message' => $msg], 429);
                    }
                    return back()->withErrors(['email' => $msg]);
                });
        });

        //  التسجيل: يمنع إنشاء حسابات/جمعيات وهمية بسرعة
        RateLimiter::for('register', function (Request $request) {
            return Limit::perMinute(3)->by($request->ip());
        });

        //  كل نقاط /api/* (تستخدمها كل الجلسات بعد تسجيل الدخول)
        //  رُفع الحد من 60 إلى 300 بالدقيقة لأن هذه لوحة تحكم داخلية
        //  يفتح فيها كل تنقل بين الأقسام عدة طلبات GET متزامنة
        //  (مثل loadAll في المشاريع التي تطلب 3 endpoints دفعة واحدة)،
        //  والحد القديم كان يُستهلك بسرعة عند التنقل الطبيعي ويسبب 429.
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(300)->by($request->user()?->id ?: $request->ip());
        });
        RateLimiter::for('upload', function (Request $request) {
            $key = $request->user()?->id
                ?: ($request->session()->get('association')['id'] ?? null)
                ?: $request->ip();
            return Limit::perMinute(20)->by($key);
        });

        // رفع الصورة: حد مستقل أعلى قليلاً عند التجربة المتكررة
        RateLimiter::for('avatar', function (Request $request) {
            $key = $request->user()?->id
                ?: ($request->session()->get('association')['id'] ?? null)
                ?: $request->ip();
            return Limit::perMinute(10)->by($key);
        });
        if (config('app.env') === 'production') {
        URL::forceScheme('https');
    }
    }
}
