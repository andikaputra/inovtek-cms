<?php

namespace App\Providers;

use App\Http\Interfaces\Admin\AboutAppInterface;
use App\Http\Interfaces\Admin\AccountDisabledInterface;
use App\Http\Interfaces\Admin\BlogInterface;
use App\Http\Interfaces\Admin\FileManagerInterface;
use App\Http\Interfaces\Admin\HomeDesaAnnouncementInterface;
use App\Http\Interfaces\Admin\HomeDesaBlogInterface;
use App\Http\Interfaces\Admin\HomeDesaDetailInfoInterface;
use App\Http\Interfaces\Admin\HomeDesaGalleryInterface;
use App\Http\Interfaces\Admin\HomeDesaInterface;
use App\Http\Interfaces\Admin\HomeDesaLinkInterface;
use App\Http\Interfaces\Admin\HomeDesaMapboxJalurInterface;
use App\Http\Interfaces\Admin\HomeDesaQuizLinkInterface;
use App\Http\Interfaces\Admin\HomeDesaQuizRegistrantInterface;
use App\Http\Interfaces\Admin\HomeDesaSegmentasiMapboxInterface;
use App\Http\Interfaces\Admin\HomeDesaSocialMediaInterface;
use App\Http\Interfaces\Admin\HomeInterface;
use App\Http\Interfaces\Admin\ProfileInterface;
use App\Http\Interfaces\Admin\SecurityInterface;
use App\Http\Interfaces\Admin\SeoManagementInterface;
use App\Http\Interfaces\Admin\ShortLinkInterface;
use App\Http\Interfaces\Admin\UserInterface;
use App\Http\UseCase\Admin\AboutAppUseCase;
use App\Http\UseCase\Admin\AccountDisabledUseCase;
use App\Http\UseCase\Admin\BlogUseCase;
use App\Http\UseCase\Admin\FileManagerUseCase;
use App\Http\UseCase\Admin\HomeDesaAnnouncementUseCase;
use App\Http\UseCase\Admin\HomeDesaBlogUseCase;
use App\Http\UseCase\Admin\HomeDesaDetailInfoUseCase;
use App\Http\UseCase\Admin\HomeDesaGalleryUseCase;
use App\Http\UseCase\Admin\HomeDesaLinkUseCase;
use App\Http\UseCase\Admin\HomeDesaMapboxJalurUseCase;
use App\Http\UseCase\Admin\HomeDesaQuizLinkUseCase;
use App\Http\UseCase\Admin\HomeDesaQuizRegistrantUseCase;
use App\Http\UseCase\Admin\HomeDesaSegmentasiMapboxUseCase;
use App\Http\UseCase\Admin\HomeDesaSocialMediaUseCase;
use App\Http\UseCase\Admin\HomeDesaUseCase;
use App\Http\UseCase\Admin\HomeUseCase;
use App\Http\UseCase\Admin\ProfileUseCase;
use App\Http\UseCase\Admin\SecurityUseCase;
use App\Http\UseCase\Admin\SeoManagementUseCase;
use App\Http\UseCase\Admin\ShortLinkUseCase;
use App\Http\UseCase\Admin\UserUseCase;
use Illuminate\Support\ServiceProvider;

class AdminBindProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(BlogInterface::class, BlogUseCase::class);
        $this->app->bind(HomeInterface::class, HomeUseCase::class);
        $this->app->bind(UserInterface::class, UserUseCase::class);
        $this->app->bind(ProfileInterface::class, ProfileUseCase::class);
        $this->app->bind(AboutAppInterface::class, AboutAppUseCase::class);
        $this->app->bind(HomeDesaInterface::class, HomeDesaUseCase::class);
        $this->app->bind(SecurityInterface::class, SecurityUseCase::class);
        $this->app->bind(ShortLinkInterface::class, ShortLinkUseCase::class);
        $this->app->bind(FileManagerInterface::class, FileManagerUseCase::class);
        $this->app->bind(HomeDesaBlogInterface::class, HomeDesaBlogUseCase::class);
        $this->app->bind(HomeDesaLinkInterface::class, HomeDesaLinkUseCase::class);
        $this->app->bind(SeoManagementInterface::class, SeoManagementUseCase::class);
        $this->app->bind(HomeDesaGalleryInterface::class, HomeDesaGalleryUseCase::class);
        $this->app->bind(AccountDisabledInterface::class, AccountDisabledUseCase::class);
        $this->app->bind(HomeDesaQuizLinkInterface::class, HomeDesaQuizLinkUseCase::class);
        $this->app->bind(HomeDesaDetailInfoInterface::class, HomeDesaDetailInfoUseCase::class);
        $this->app->bind(HomeDesaSocialMediaInterface::class, HomeDesaSocialMediaUseCase::class);
        $this->app->bind(HomeDesaMapboxJalurInterface::class, HomeDesaMapboxJalurUseCase::class);
        $this->app->bind(HomeDesaAnnouncementInterface::class, HomeDesaAnnouncementUseCase::class);
        $this->app->bind(HomeDesaQuizRegistrantInterface::class, HomeDesaQuizRegistrantUseCase::class);
        $this->app->bind(HomeDesaSegmentasiMapboxInterface::class, HomeDesaSegmentasiMapboxUseCase::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
