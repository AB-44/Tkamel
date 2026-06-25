<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

use App\Exceptions\UnauthorizedException;
use App\Exceptions\NotFoundException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'auth.tkamel' => \App\Http\Middleware\AuthMiddleware::class,
            'role'        => \App\Http\Middleware\RoleMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (UnauthorizedException $e, $request) {
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage()
                ], 401);
            }
            return redirect()->route('login');

        });


        $exceptions->render(function (NotFoundException $e, $request) {
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage()
                ], 404);
            }
            abort(404);
        });
            $exceptions->report(function (\Throwable $e) {
        if ($e instanceof \Illuminate\Auth\AuthenticationException ||
            $e instanceof \Symfony\Component\HttpKernel\Exception\NotFoundHttpException) {
            return false;
        }

        // سجّل أخطاء Validation بشكل خاص (بدون stack trace)
        if ($e instanceof \Illuminate\Validation\ValidationException) {
            \Illuminate\Support\Facades\Log::channel('daily')->warning(
                'خطأ تحقق (Validation): ' . $e->getMessage(),
                [
                    'errors'  => $e->errors(),
                    'url'     => request()->fullUrl(),
                    'method'  => request()->method(),
                    'input'   => request()->except(['password', 'password_confirmation']),
                    'user_id' => auth()->id() ?? 'زائر',
                ]
            );
            return false; // لا تسجّل مرتين
        }

            \Illuminate\Support\Facades\Log::channel('daily')->critical(
            'خطأ غير متوقع: ' . $e->getMessage(),
            [
                'exception' => get_class($e),
                'file'      => $e->getFile(),
                'line'      => $e->getLine(),
                'url'       => request()->fullUrl(),
                'user_id'   => auth()->id() ?? 'زائر',
            ]
        );
    });
    $exceptions->render(function (\Throwable $e, $request) {
    // فقط الأخطاء اللي مو HTTP errors عادية
    if (!$e instanceof \Symfony\Component\HttpKernel\Exception\HttpException) {
        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ في النظام، حاول مرة أخرى'
            ], 500);
        }
        // لو طلب عادي (مو API) يروح لصفحة خطأ
    }
});
    })->create();
