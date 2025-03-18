<?php

use App\Helpers\Json;
use App\Http\Controllers\API\BlogController;
use App\Http\Controllers\API\HomeController;
use App\Http\Controllers\API\HomeDesaController;
use App\Http\Controllers\API\ShortLinkController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

// Check Host and Csp
Route::group(['middleware' => ['check.host']], function () {

    // Health Check
    Route::get('health-check', function (Request $request) {
        return Json::success(data: 'Application Is Running');
    });

    // Check Jwt
    Route::group(['middleware' => ['check.jwt']], function () {

        // API Client
        Route::group(['middleware' => ['api.client']], function () {
            // m2m
            Route::prefix('m2m')->group(function () {
                // Wilayah
                Route::prefix('wilayah')->group(function () {
                    Route::get('/', [HomeController::class, 'getAllWilayahData']);

                    // Identifier Wilayah
                    Route::prefix('{identifier}')->group(function () {
                        Route::get('/detail', [HomeController::class, 'getAllWilayahDetailData']);
                        Route::get('/product', [HomeDesaController::class, 'getAllDesaProductData']);
                        Route::get('/mapbox/{id}', [HomeDesaController::class, 'getDetailMapboxData']);
                        Route::get('/village', [HomeDesaController::class, 'getAllVillageData']);
                        Route::post('/quiz', [HomeController::class, 'postQuizRegistration']);
                        Route::get('/quiz-register', [HomeController::class, 'getQuizRegister']);
                    });
                });

                // Blog
                Route::prefix('blog')->group(function () {
                    Route::get('/', [BlogController::class, 'getAllBlogData']);
                    Route::get('detail/{identifier}', [BlogController::class, 'getAllDetailBlogData']);
                });

                // Short Link
                Route::prefix('short-link')->group(function () {
                    Route::get('/', [ShortLinkController::class, 'getAllShortLinkData']);
                    Route::get('detail/{identifier}', [ShortLinkController::class, 'getAllDetailShortLinkData']);
                });
            });
        });

        // File Storage
        Route::group(['prefix' => 'file'], function () {
            //File Preview
            Route::get('preview', function (Request $request) {
                try {
                    $url = $request->path && Storage::disk('public')->exists($request->path)
                        ? storage_path('app/public/'.$request->path)
                        : public_path('assets/images/default/no-image.jpg');

                    return response()->file($url);
                } catch (\Throwable $th) {
                    return response()->json([
                        'success' => false,
                        'error' => $th->getMessage(),
                    ], 400);
                }
            })->name('api.file.preview');

            //File Download
            Route::get('download', function (Request $request) {
                try {
                    $path = $request->path;

                    if ($path && Storage::disk('public')->exists($path)) {
                        return Storage::download('public/'.$request->path);
                    }

                    $fallbackPath = public_path('assets/images/default/no-image.jpg');

                    return response()->download($fallbackPath, 'no-image.jpg');
                } catch (\Throwable $th) {
                    return response()->json([
                        'success' => false,
                        'error' => $th->getMessage(),
                    ], 400);
                }
            })->name('api.file.download');
        });
    });
});
