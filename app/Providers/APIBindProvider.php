<?php

namespace App\Providers;

use App\Http\Interfaces\API\BlogInterface;
use App\Http\Interfaces\API\HomeDesaInterface;
use App\Http\Interfaces\API\HomeInterface;
use App\Http\Interfaces\API\ShortLinkInterface;
use App\Http\UseCase\API\BlogUseCase;
use App\Http\UseCase\API\HomeDesaUseCase;
use App\Http\UseCase\API\HomeUseCase;
use App\Http\UseCase\API\ShortLinkUseCase;
use Illuminate\Support\ServiceProvider;

class APIBindProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(HomeInterface::class, HomeUseCase::class);
        $this->app->bind(BlogInterface::class, BlogUseCase::class);
        $this->app->bind(ShortLinkInterface::class, ShortLinkUseCase::class);
        $this->app->bind(HomeDesaInterface::class, HomeDesaUseCase::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
