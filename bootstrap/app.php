<?php

use App\Http\Middleware\CheckSafetyEntrance;
use App\Http\Middleware\CheckUserRole;
use App\Http\Middleware\CheckValidProvince;
use App\Http\Middleware\ContentSecurityPolicy;
use App\Http\Middleware\EnsureJwtIsValid;
use App\Http\Middleware\ValidateHostRequest;
use Illuminate\Auth\Middleware\RedirectIfAuthenticated;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Laravel\Passport\Http\Middleware\CheckClientCredentials;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'check.role' => CheckUserRole::class,
            'check.jwt' => EnsureJwtIsValid::class,
            'api.client' => CheckClientCredentials::class,
            'check.safety-entrance' => CheckSafetyEntrance::class,
            'check.valid-province' => CheckValidProvince::class,
            'check.csp' => ContentSecurityPolicy::class,
            'check.host' => ValidateHostRequest::class,
        ]);

        RedirectIfAuthenticated::redirectUsing(function ($request) {
            if ($request) {
                return route('admin.home.index');
            }
        });
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
