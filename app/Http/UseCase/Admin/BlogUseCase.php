<?php

namespace App\Http\UseCase\Admin;

use App\Constants\SeoConst;
use App\Http\Interfaces\Admin\BlogInterface;
use App\Http\Requests\Admin\Blog\BlogStoreRequest;
use App\Http\Requests\Admin\Blog\BlogUpdateRequest;
use App\Http\Requests\Admin\SeoManagement\SeoManagementStoreUpdateRequest;
use App\Models\Blog;
use App\Services\Asset\AssetCommandService;
use App\Services\Asset\AssetQueryService;
use App\Services\Blog\BlogCommandService;
use App\Services\Blog\BlogDatatableService;
use App\Services\Blog\BlogQueryService;
use App\Services\Region\RegionQueryService;
use App\Services\SeoManagement\SeoManagementCommandService;
use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\Uid\Ulid;

final class BlogUseCase implements BlogInterface
{
    public function __construct(
        private readonly BlogDatatableService $blogDatatableService,
        private readonly RegionQueryService $regionQueryService,
        private readonly BlogCommandService $blogCommandService,
        private readonly BlogQueryService $blogQueryService,
        private readonly AssetCommandService $assetCommandService,
        private readonly AssetQueryService $assetQueryService,
        private readonly SeoManagementCommandService $seoManagementCommandService
    ) {}

    public function renderIndex(Request $request): View
    {
        return view('admin.pages.blog.index');
    }

    public function renderDatatable(Request $request): JsonResponse
    {
        return $this->blogDatatableService->blogDatatable(request: $request);
    }

    public function renderCreate(Request $request): View
    {
        $regions = $this->regionQueryService->getAllRegion(request: $request);

        return view('admin.pages.blog.create', compact('regions'));
    }

    public function execStore(BlogStoreRequest $request): ?Blog
    {
        $storeData = $this->blogCommandService->store(request: $request);

        if (! isset($storeData)) {
            throw new Exception(trans('response.error.store', ['data' => 'Artikel Umum', 'error' => 'Data tidak ditemukan']));
        }

        $explodeFilePath = explode(','.config('app.url'), $request->image);
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'svg', 'webp'];

        foreach ($explodeFilePath as $filePath) {
            $explodeStorage = explode('storage/', $filePath);
            $fileUrl = trim($explodeStorage[1]);
            $extension = strtolower(pathinfo($fileUrl, PATHINFO_EXTENSION));

            if (! in_array($extension, $allowedExtensions)) {
                throw new Exception(trans('response.error.store', ['data' => 'Artikel Umum', 'error' => 'File yang diunggah bukan gambar yang valid']));
            }
            $this->assetCommandService->storeAsset(pathType: Blog::class, pathId: $storeData->id, pathName: $explodeStorage[1], assetKey: 'blog-asset-key-'.Ulid::generate());
        }

        $newRequest = new SeoManagementStoreUpdateRequest([
            'meta_title' => $request->title,
            'meta_robot' => 'index,follow',
            'meta_author' => Auth::user()->name,
            'meta_keyword' => $storeData->slug,
            'meta_language' => 'id,en',
            'meta_description' => $request->title,
            'meta_og_title' => $request->title,
            'meta_og_url' => null,
            'meta_og_type' => SeoConst::SEO_TYPE_KEY['01'],
            'meta_og_description' => $request->title,
        ]);

        $this->seoManagementCommandService->storeUpdate(request: $newRequest, seoType: Blog::class, seoId: $storeData->id);

        return $storeData;
    }

    public function renderEdit(Request $request, string $id): View
    {
        $blog = $this->blogQueryService->getById(id: $id);

        if (! isset($blog)) {
            abort(Response::HTTP_NOT_FOUND);
        }

        $loadImage = $this->assetQueryService->getAllAsset(pathType: Blog::class, pathId: $blog->id, withAssetKey: 'blog-asset-key-', usingLike: true);
        $arrayImg = $loadImage->map(fn ($item) => $item->asset_path ? asset('storage/'.$item->asset_path) : null)->filter()->toArray();

        $blog->image = $loadImage->map(function ($item) {
            $item->path = $item->asset_path ? $item->asset_path : null;

            return $item;
        });

        $blog->image_arr = ! empty($arrayImg) ? implode(',', $arrayImg) : null;
        $regions = $this->regionQueryService->getAllRegion(request: $request);

        return view('admin.pages.blog.edit', compact('blog', 'regions'));
    }

    public function execUpdateContent(BlogUpdateRequest $request, string $id): ?Blog
    {
        $blog = $this->blogQueryService->getById(id: $id);

        if (! isset($blog)) {
            throw new Exception(trans('response.error.update', ['data' => 'Artikel Wilayah', 'error' => 'Data tidak ditemukan']));
        }

        $updateData = $this->blogCommandService->updateContent(request: $request, query: $blog, isArtikelUmum: true);

        if (! isset($updateData)) {
            throw new Exception(trans('response.error.store', ['data' => 'Artikel Wilayah', 'error' => 'Data tidak ditemukan']));
        }

        if (isset($request->image)) {
            $this->assetCommandService->deleteAllAsset(pathType: Blog::class, pathId: $updateData->id);
            $explodeFilePath = explode(','.config('app.url'), $request->image);
            $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'svg', 'webp'];

            foreach ($explodeFilePath as $filePath) {
                $explodeStorage = explode('storage/', $filePath);
                $fileUrl = trim($explodeStorage[1]);
                $extension = strtolower(pathinfo($fileUrl, PATHINFO_EXTENSION));

                if (! in_array($extension, $allowedExtensions)) {
                    throw new Exception(trans('response.error.update', ['data' => 'Artikel Wilayah', 'error' => 'File yang diunggah bukan gambar yang valid']));
                }
                $this->assetCommandService->storeAsset(pathType: Blog::class, pathId: $updateData->id, pathName: $explodeStorage[1], assetKey: 'blog-asset-key-'.Ulid::generate());
            }
        }

        return $updateData;
    }

    public function execUpdateActive(string $id): bool
    {
        $blog = $this->blogQueryService->getById(id: $id);

        if (! isset($blog)) {
            throw new Exception(trans('response.error.switch', ['data' => 'Perubahan Status Artikel Wilayah', 'error' => 'Data tidak ditemukan']));
        }

        return $this->blogCommandService->updateActive(query: $blog);
    }

    public function execDelete(Request $request, string $id): bool
    {
        $blog = $this->blogQueryService->getById(id: $id);

        if (! isset($blog)) {
            throw new Exception(trans('response.error.delete', ['data' => 'Artikel Wilayah', 'error' => 'Data tidak ditemukan']));
        }

        if ($blog->is_active) {
            throw new Exception(trans('response.error.delete', ['data' => 'Artikel Wilayah', 'error' => 'Mohon nonaktifkan artikel terlebih dahulu']));
        }

        $this->assetCommandService->deleteAllAsset(pathType: Blog::class, pathId: $blog->id);
        $this->seoManagementCommandService->delete(seoType: Blog::class, seoId: $blog->id);

        return $this->blogCommandService->delete(query: $blog);
    }
}
