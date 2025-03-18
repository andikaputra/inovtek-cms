<?php

namespace App\Providers;

use App\Models\User;
use App\Services\Asset\AssetQueryService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\View\View as ViewView;
use Laravel\Passport\Passport;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        if (config('app.ngrok_status') == true || config('app.force_https') == true) {
            URL::forceScheme(scheme: 'https');
        }

        if ($this->app->environment('local')) {
            $this->app->register(\Laravel\Telescope\TelescopeServiceProvider::class);
            $this->app->register(TelescopeServiceProvider::class);
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {

        Passport::tokensExpireIn(now()->addHours(3));
        Passport::refreshTokensExpireIn(now()->addDays(1));
        Passport::personalAccessTokensExpireIn(now()->addHours(3));

        View::composer('*', function (ViewView $view) {
            if (Auth::check()) {
                $userId = Auth::user()->id;

                // Fetch or cache user profile image
                $profileImage = Cache::remember("user:{$userId}:profileImage", 300, function () use ($userId) {
                    return (new AssetQueryService)->loadAsset(pathType: User::class, pathId: $userId);
                });

                // Fetch or cache user notifications
                $userNotification = Cache::remember("user:{$userId}:notifications", 300, function () {
                    return Auth::user()->notifications;
                });

                // Fetch or cache user unread notifications
                $userUnreadNotification = Cache::remember("user:{$userId}:unreadNotifications", 300, function () {
                    return Auth::user()->unreadNotifications;
                });

                $view->with('userProfileImage', $profileImage);
                $view->with('userNotification', $userNotification);
                $view->with('userUnreadNotification', $userUnreadNotification);
            }
        });
    }
}
