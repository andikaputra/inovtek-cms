<?php

namespace App\Providers;

use App\Http\Interfaces\Auth\SangkuriangInterface;
use App\Http\UseCase\Auth\SangkuriangUseCase;
use Illuminate\Support\ServiceProvider;

class AuthBindProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(SangkuriangInterface::class, SangkuriangUseCase::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
