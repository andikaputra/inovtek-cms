<?php

use App\Http\Controllers\Admin\AboutAppController;
use App\Http\Controllers\Admin\AccountDisabledController;
use App\Http\Controllers\Admin\BlogController;
use App\Http\Controllers\Admin\FileManagerController;
use App\Http\Controllers\Admin\HomeController;
use App\Http\Controllers\Admin\HomeDesaAnnouncementController;
use App\Http\Controllers\Admin\HomeDesaBlogController;
use App\Http\Controllers\Admin\HomeDesaController;
use App\Http\Controllers\Admin\HomeDesaDetailInfoController;
use App\Http\Controllers\Admin\HomeDesaGalleryController;
use App\Http\Controllers\Admin\HomeDesaLinkController;
use App\Http\Controllers\Admin\HomeDesaMapboxJalurController;
use App\Http\Controllers\Admin\HomeDesaQuizLinkController;
use App\Http\Controllers\Admin\HomeDesaQuizRegistrantController;
use App\Http\Controllers\Admin\HomeDesaSegmentasiMapboxController;
use App\Http\Controllers\Admin\HomeDesaSocialMediaController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\SecurityController;
use App\Http\Controllers\Admin\SeoManagementController;
use App\Http\Controllers\Admin\ShortLinkController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\SangkuriangController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::middleware(['check.host', 'check.csp'])->group(function () {

    // Short Link
    Route::prefix('s')->group(function () {
        Route::get('{uniqueCode}', [ShortLinkController::class, 'redirect'])->name('short-link.redirect');
    });

    // Redirect Route
    Route::get('/', function () {
        return to_route('login');
    });

    // Account Disabled
    Route::prefix('account')->group(function () {
        Route::get('disabled', [AccountDisabledController::class, 'index'])->name('admin.account-disabled.index');
    });

    // Handle Login Sangkuriang
    Route::prefix('/')->group(function () {
        Route::get('login', [SangkuriangController::class, 'handleLogin'])->name('login');
        Route::get('error', [SangkuriangController::class, 'handleError'])->name('sangkuriang.error');
    });

    // Reset Password Endpoint
    Route::prefix('password')->middleware('throttle:5,1')->group(function () {
        Route::get('reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
        Route::post('reset', [ResetPasswordController::class, 'reset'])->name('password.update');
    });

    // Safety Entrance Authentification Endpoint
    Route::prefix('safety-entrance/{safety_entrance}')->group(function () {
        Route::middleware(['check.safety-entrance'])->group(function () {
            Route::prefix('login')->group(function () {
                Route::get('/', [LoginController::class, 'showLoginForm'])->name('safety-entrance.login');
                Route::post('/', [LoginController::class, 'login'])->name('safety-entrance.login-action');
            });

            Route::prefix('password-reset')->group(function () {
                Route::get('/', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('safety-entrance.password.request');
                Route::post('/', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('safety-entrance.password.email');
            });
        });
    });

    Auth::routes([
        'login' => false,
        'reset' => false,
        'register' => false,
        'password.confirm' => false,
    ]);

    Route::group(['prefix' => 'laravel-filemanager', 'middleware' => ['web', 'auth']], function () {
        \UniSharp\LaravelFilemanager\Lfm::routes();
    });

    // Route Admin
    Route::prefix('admin')->middleware(['auth', 'check.role:super_admin|admin'])->group(function () {

        // Home Wilayah
        Route::prefix('home')->group(function () {
            Route::get('/', [HomeController::class, 'index'])->name('admin.home.index');
            Route::get('create', [HomeController::class, 'create'])->name('admin.home.create');
            Route::post('store', [HomeController::class, 'store'])->name('admin.home.store');
            Route::get('read-notification', [HomeController::class, 'readNotification'])->name('admin.home.readNotification');
            Route::prefix('wilayah/{id_provinsi}')->middleware(['check.valid-province'])->group(function () {
                // Dashboard Desa
                Route::prefix('desa')->group(function () {
                    Route::get('/', [HomeDesaController::class, 'index'])->name('admin.home.detail.desa.index');
                    Route::get('create', [HomeDesaController::class, 'create'])->name('admin.home.detail.desa.create');
                    Route::post('store', [HomeDesaController::class, 'store'])->name('admin.home.detail.desa.store');
                    Route::get('datatable', [HomeDesaController::class, 'datatable'])->name('admin.home.detail.desa.datatable');
                    Route::get('edit/{id}', [HomeDesaController::class, 'edit'])->name('admin.home.detail.desa.edit');
                    Route::delete('delete/{id}', [HomeDesaController::class, 'delete'])->name('admin.home.detail.desa.delete');
                    // Update Route
                    Route::prefix('update/{id}')->group(function () {
                        Route::patch('content', [HomeDesaController::class, 'update'])->name('admin.home.detail.desa.update');
                        Route::patch('switch', [HomeDesaController::class, 'switch'])->name('admin.home.detail.desa.switch');
                    });

                    // Titik Mapbox
                    Route::prefix('segmentasi-mapbox/{id_desa}')->group(function () {
                        Route::get('/', [HomeDesaSegmentasiMapboxController::class, 'index'])->name('admin.home.detail.desa.segmentasi-mapbox.index');
                        Route::get('create', [HomeDesaSegmentasiMapboxController::class, 'create'])->name('admin.home.detail.desa.segmentasi-mapbox.create');
                        Route::post('store', [HomeDesaSegmentasiMapboxController::class, 'store'])->name('admin.home.detail.desa.segmentasi-mapbox.store');
                        Route::get('datatable', [HomeDesaSegmentasiMapboxController::class, 'datatable'])->name('admin.home.detail.desa.segmentasi-mapbox.datatable');
                        Route::prefix('list')->group(function () {
                            Route::get('/', [HomeDesaSegmentasiMapboxController::class, 'orderList'])->name('admin.home.detail.desa.segmentasi-mapbox.order-list');
                            Route::post('update', [HomeDesaSegmentasiMapboxController::class, 'orderUpdate'])->name('admin.home.detail.desa.segmentasi-mapbox.order-update');
                        });
                        Route::get('edit/{id}', [HomeDesaSegmentasiMapboxController::class, 'edit'])->name('admin.home.detail.desa.segmentasi-mapbox.edit');
                        Route::delete('delete/{id}', [HomeDesaSegmentasiMapboxController::class, 'delete'])->name('admin.home.detail.desa.segmentasi-mapbox.delete');
                        // Update Route
                        Route::prefix('update/{id}')->group(function () {
                            Route::patch('content', [HomeDesaSegmentasiMapboxController::class, 'update'])->name('admin.home.detail.desa.segmentasi-mapbox.update');
                            Route::patch('switch', [HomeDesaSegmentasiMapboxController::class, 'switch'])->name('admin.home.detail.desa.segmentasi-mapbox.switch');
                        });

                        // Titik Jalur/Rute
                        Route::prefix('jalur/{id_mapbox}')->group(function () {
                            Route::get('/', [HomeDesaMapboxJalurController::class, 'index'])->name('admin.home.detail.desa.segmentasi-mapbox.jalur.index');
                            Route::get('create', [HomeDesaMapboxJalurController::class, 'create'])->name('admin.home.detail.desa.segmentasi-mapbox.jalur.create');
                            Route::post('store', [HomeDesaMapboxJalurController::class, 'store'])->name('admin.home.detail.desa.segmentasi-mapbox.jalur.store');
                            Route::get('datatable', [HomeDesaMapboxJalurController::class, 'datatable'])->name('admin.home.detail.desa.segmentasi-mapbox.jalur.datatable');
                            Route::get('edit/{id}', [HomeDesaMapboxJalurController::class, 'edit'])->name('admin.home.detail.desa.segmentasi-mapbox.jalur.edit');
                            Route::delete('delete/{id}', [HomeDesaMapboxJalurController::class, 'delete'])->name('admin.home.detail.desa.segmentasi-mapbox.jalur.delete');
                            // Update Route
                            Route::prefix('update/{id}')->group(function () {
                                Route::patch('content', [HomeDesaMapboxJalurController::class, 'update'])->name('admin.home.detail.desa.segmentasi-mapbox.jalur.update');
                                Route::patch('switch', [HomeDesaMapboxJalurController::class, 'switch'])->name('admin.home.detail.desa.segmentasi-mapbox.jalur.switch');
                            });
                            Route::prefix('list')->group(function () {
                                Route::get('/', [HomeDesaMapboxJalurController::class, 'orderList'])->name('admin.home.detail.desa.segmentasi-mapbox.jalur.order-list');
                                Route::post('update', [HomeDesaMapboxJalurController::class, 'orderUpdate'])->name('admin.home.detail.desa.segmentasi-mapbox.jalur.order-update');
                            });
                        });
                    });
                });

                // Tentang Aplikasi
                Route::prefix('tentang-aplikasi')->group(function () {
                    Route::get('/', [HomeDesaDetailInfoController::class, 'edit'])->name('admin.home.detail.tentang-aplikasi.edit');
                    Route::patch('store-or-update', [HomeDesaDetailInfoController::class, 'update'])->name('admin.home.detail.tentang-aplikasi.update');
                });

                // Setting Wilayah
                Route::prefix('setting-wilayah')->group(function () {
                    Route::get('edit', [HomeController::class, 'edit'])->name('admin.home.detail.setting-wilayah.edit');
                    Route::patch('update', [HomeController::class, 'update'])->name('admin.home.detail.setting-wilayah.update');
                    Route::delete('delete', [HomeController::class, 'delete'])->name('admin.home.detail.setting-wilayah.delete');
                    Route::patch('switch', [HomeController::class, 'switch'])->name('admin.home.detail.setting-wilayah.switch');
                });

                // Galeri Wilayah
                Route::prefix('galeri-wilayah')->group(function () {
                    Route::get('edit', [HomeDesaGalleryController::class, 'edit'])->name('admin.home.detail.galeri-wilayah.edit');
                    Route::patch('update', [HomeDesaGalleryController::class, 'update'])->name('admin.home.detail.galeri-wilayah.update');
                });

                // Seo Management
                Route::prefix('seo-wilayah')->group(function () {
                    Route::get('edit/{type}/{id_key}', [SeoManagementController::class, 'edit'])->name('admin.home.detail.seo-wilayah.edit');
                    Route::patch('update/{type}/{id_key}', [SeoManagementController::class, 'update'])->name('admin.home.detail.seo-wilayah.update');
                });

                // Link
                Route::prefix('link')->group(function () {
                    Route::get('/', [HomeDesaLinkController::class, 'index'])->name('admin.home.detail.link.index');
                    Route::get('datatable', [HomeDesaLinkController::class, 'datatable'])->name('admin.home.detail.link.datatable');
                    Route::get('create', [HomeDesaLinkController::class, 'create'])->name('admin.home.detail.link.create');
                    Route::post('store', [HomeDesaLinkController::class, 'store'])->name('admin.home.detail.link.store');
                    Route::get('edit/{id}', [HomeDesaLinkController::class, 'edit'])->name('admin.home.detail.link.edit');
                    Route::delete('delete/{id}', [HomeDesaLinkController::class, 'delete'])->name('admin.home.detail.link.delete');
                    // Update Route
                    Route::prefix('update/{id}')->group(function () {
                        Route::patch('content', [HomeDesaLinkController::class, 'updateContent'])->name('admin.home.detail.link.update.content');
                        Route::patch('active', [HomeDesaLinkController::class, 'updateActive'])->name('admin.home.detail.link.update.active');
                    });
                });

                // Social Media
                Route::prefix('sosial-media')->group(function () {
                    Route::get('/', [HomeDesaSocialMediaController::class, 'index'])->name('admin.home.detail.sosial-media.index');
                    Route::get('datatable', [HomeDesaSocialMediaController::class, 'datatable'])->name('admin.home.detail.sosial-media.datatable');
                    Route::get('create', [HomeDesaSocialMediaController::class, 'create'])->name('admin.home.detail.sosial-media.create');
                    Route::post('store', [HomeDesaSocialMediaController::class, 'store'])->name('admin.home.detail.sosial-media.store');
                    Route::get('edit/{id}', [HomeDesaSocialMediaController::class, 'edit'])->name('admin.home.detail.sosial-media.edit');
                    Route::delete('delete/{id}', [HomeDesaSocialMediaController::class, 'delete'])->name('admin.home.detail.sosial-media.delete');
                    // Update Route
                    Route::prefix('update/{id}')->group(function () {
                        Route::patch('content', [HomeDesaSocialMediaController::class, 'updateContent'])->name('admin.home.detail.sosial-media.update.content');
                        Route::patch('active', [HomeDesaSocialMediaController::class, 'updateActive'])->name('admin.home.detail.sosial-media.update.active');
                    });
                });

                // Quiz
                Route::prefix('kuis')->group(function () {
                    Route::get('/', [HomeDesaQuizLinkController::class, 'index'])->name('admin.home.detail.kuis.index');
                    Route::get('datatable', [HomeDesaQuizLinkController::class, 'datatable'])->name('admin.home.detail.kuis.datatable');
                    Route::get('create', [HomeDesaQuizLinkController::class, 'create'])->name('admin.home.detail.kuis.create');
                    Route::post('store', [HomeDesaQuizLinkController::class, 'store'])->name('admin.home.detail.kuis.store');
                    Route::get('edit/{id}', [HomeDesaQuizLinkController::class, 'edit'])->name('admin.home.detail.kuis.edit');
                    Route::delete('delete/{id}', [HomeDesaQuizLinkController::class, 'delete'])->name('admin.home.detail.kuis.delete');
                    // Update Route
                    Route::prefix('update/{id}')->group(function () {
                        Route::patch('content', [HomeDesaQuizLinkController::class, 'updateContent'])->name('admin.home.detail.kuis.update.content');
                        Route::patch('active', [HomeDesaQuizLinkController::class, 'updateActive'])->name('admin.home.detail.kuis.update.active');
                    });

                    // Registrant
                    Route::prefix('registrant/{id}')->group(function () {
                        Route::get('/', [HomeDesaQuizRegistrantController::class, 'index'])->name('admin.home.detail.kuis.registrant.index');
                        Route::post('export', [HomeDesaQuizRegistrantController::class, 'export'])->name('admin.home.detail.kuis.registrant.export');
                        Route::get('datatable', [HomeDesaQuizRegistrantController::class, 'datatable'])->name('admin.home.detail.kuis.registrant.datatable');
                    });
                });

                // Announcement
                Route::prefix('pengumuman')->group(function () {
                    Route::get('/', [HomeDesaAnnouncementController::class, 'index'])->name('admin.home.detail.pengumuman.index');
                    Route::get('datatable', [HomeDesaAnnouncementController::class, 'datatable'])->name('admin.home.detail.pengumuman.datatable');
                    Route::get('create', [HomeDesaAnnouncementController::class, 'create'])->name('admin.home.detail.pengumuman.create');
                    Route::post('store', [HomeDesaAnnouncementController::class, 'store'])->name('admin.home.detail.pengumuman.store');
                    Route::get('edit/{id}', [HomeDesaAnnouncementController::class, 'edit'])->name('admin.home.detail.pengumuman.edit');
                    Route::delete('delete/{id}', [HomeDesaAnnouncementController::class, 'delete'])->name('admin.home.detail.pengumuman.delete');
                    // Update Route
                    Route::prefix('update/{id}')->group(function () {
                        Route::patch('content', [HomeDesaAnnouncementController::class, 'updateContent'])->name('admin.home.detail.pengumuman.update.content');
                        Route::patch('active', [HomeDesaAnnouncementController::class, 'updateActive'])->name('admin.home.detail.pengumuman.update.active');
                    });
                });

                // Artikel Wilayah
                Route::prefix('artikel')->group(function () {
                    Route::get('/', [HomeDesaBlogController::class, 'index'])->name('admin.home.detail.blog.index');
                    Route::get('datatable', [HomeDesaBlogController::class, 'datatable'])->name('admin.home.detail.blog.datatable');
                    Route::get('create', [HomeDesaBlogController::class, 'create'])->name('admin.home.detail.blog.create');
                    Route::post('store', [HomeDesaBlogController::class, 'store'])->name('admin.home.detail.blog.store');
                    Route::get('edit/{id}', [HomeDesaBlogController::class, 'edit'])->name('admin.home.detail.blog.edit');
                    Route::delete('delete/{id}', [HomeDesaBlogController::class, 'delete'])->name('admin.home.detail.blog.delete');
                    // Update Route
                    Route::prefix('update/{id}')->group(function () {
                        Route::patch('content', [HomeDesaBlogController::class, 'updateContent'])->name('admin.home.detail.blog.update.content');
                        Route::patch('active', [HomeDesaBlogController::class, 'updateActive'])->name('admin.home.detail.blog.update.active');
                        Route::patch('general-blog', [HomeDesaBlogController::class, 'updateGeneralBlog'])->name('admin.home.detail.blog.update.general-blog');
                    });
                });
            });
        });

        // Tentang Aplikasi
        Route::prefix('tentang-aplikasi')->group(function () {
            Route::get('/', [AboutAppController::class, 'edit'])->name('admin.tentang-aplikasi.edit');
            Route::patch('store-or-update', [AboutAppController::class, 'update'])->name('admin.tentang-aplikasi.update');
        });

        // Artikel Umum
        Route::prefix('artikel')->group(function () {
            Route::get('/', [BlogController::class, 'index'])->name('admin.blog.index');
            Route::get('datatable', [BlogController::class, 'datatable'])->name('admin.blog.datatable');
            Route::get('create', [BlogController::class, 'create'])->name('admin.blog.create');
            Route::post('store', [BlogController::class, 'store'])->name('admin.blog.store');
            Route::get('edit/{id}', [BlogController::class, 'edit'])->name('admin.blog.edit');
            Route::delete('delete/{id}', [BlogController::class, 'delete'])->name('admin.blog.delete');

            // Update Route
            Route::prefix('update/{id}')->group(function () {
                Route::patch('content', [BlogController::class, 'updateContent'])->name('admin.blog.update.content');
                Route::patch('active', [BlogController::class, 'updateActive'])->name('admin.blog.update.active');
            });

            // Seo Artikel Umum
            Route::prefix('seo-artikel-umum')->group(function () {
                Route::get('edit/{type}/{id_key}', [SeoManagementController::class, 'editUmum'])->name('admin.seo-artikel-umum.edit');
                Route::patch('update/{type}/{id_key}', [SeoManagementController::class, 'updateUmum'])->name('admin.seo-artikel-umum.update');
            });
        });

        // Master Data
        Route::prefix('master-data')->group(function () {
            // Short Link
            Route::prefix('short-link')->group(function () {
                Route::get('/', [ShortLinkController::class, 'index'])->name('admin.short-link.index');
                Route::get('datatable', [ShortLinkController::class, 'datatable'])->name('admin.short-link.datatable');
                Route::get('create', [ShortLinkController::class, 'create'])->name('admin.short-link.create');
                Route::post('store', [ShortLinkController::class, 'store'])->name('admin.short-link.store');
                Route::get('edit/{id}', [ShortLinkController::class, 'edit'])->name('admin.short-link.edit');
                Route::delete('delete/{id}', [ShortLinkController::class, 'delete'])->name('admin.short-link.delete');
                // Update Route
                Route::prefix('update/{id}')->group(function () {
                    Route::patch('content', [ShortLinkController::class, 'update'])->name('admin.short-link.update');
                    Route::patch('set-status', [ShortLinkController::class, 'setStatus'])->name('admin.short-link.set-status');
                });
            });

            // File Manager
            Route::prefix('file-manager')->group(function () {
                Route::get('/', [FileManagerController::class, 'index'])->name('admin.file-manager.index');
            });

            // User Management
            Route::prefix('user')->group(function () {
                Route::get('/', [UserController::class, 'index'])->name('admin.user.index');
                Route::get('datatable', [UserController::class, 'datatable'])->name('admin.user.datatable');
                Route::get('create', [UserController::class, 'create'])->name('admin.user.create');
                Route::post('store', [UserController::class, 'store'])->name('admin.user.store');
                Route::get('edit/{id}', [UserController::class, 'edit'])->name('admin.user.edit');
                Route::delete('delete/{id}', [UserController::class, 'delete'])->name('admin.user.delete');
                // Update Route
                Route::prefix('update/{id}')->group(function () {
                    Route::patch('content', [UserController::class, 'update'])->name('admin.user.update');
                    Route::patch('set-status', [UserController::class, 'setStatus'])->name('admin.user.set-status');
                });
            });
        });

        // Setting
        Route::prefix('setting')->group(function () {

            // Profile Setting
            Route::prefix('profile')->group(function () {
                Route::get('/', [ProfileController::class, 'index'])->name('admin.setting.profile.index');
                Route::patch('/', [ProfileController::class, 'update'])->name('admin.setting.profile.update');
            });

            // Security Setting
            Route::prefix('security')->group(function () {
                Route::get('/', [SecurityController::class, 'index'])->name('admin.setting.security.index');
                Route::patch('/', [SecurityController::class, 'update'])->name('admin.setting.security.update');
            });
        });
    });
});
