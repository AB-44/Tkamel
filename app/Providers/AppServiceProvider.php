<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

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
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });
            RateLimiter::for('upload', fn (Request $request) =>
            Limit::perMinute(5)->by($request->user()->id)
        );
    }
}
