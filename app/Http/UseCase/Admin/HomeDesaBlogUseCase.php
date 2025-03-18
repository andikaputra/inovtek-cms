<?php

namespace App\Http\UseCase\Admin;

use App\Constants\SeoConst;
use App\Http\Interfaces\Admin\HomeDesaBlogInterface;
use App\Http\Requests\Admin\HomeDesaBlog\HomeDesaBlogStoreRequest;
use App\Http\Requests\Admin\HomeDesaBlog\HomeDesaBlogUpdateRequest;
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

final class HomeDesaBlogUseCase implements HomeDesaBlogInterface
{
    public function __construct(
        private readonly RegionQueryService $regionQueryService,
        private readonly BlogDatatableService $blogDatatableService,
        private readonly BlogQueryService $blogQueryService,
        private readonly BlogCommandService $blogCommandService,
        private readonly AssetCommandService $assetCommandService,
        private readonly AssetQueryService $assetQueryService,
        private readonly SeoManagementCommandService $seoManagementCommandService
    ) {}

    public function renderIndex(Request $request, string $id_provinsi): View
    {
        $findRegion = $this->regionQueryService->findRegionById(id: $id_provinsi);
        if (! isset($findRegion)) {
            abort(Response::HTTP_NOT_FOUND);
        }

        return view('admin.pages.home-desa-blog.index', compact('findRegion'));
    }

    public function renderDatatable(Request $request, string $id_provinsi): JsonResponse
    {
        $findRegion = $this->regionQueryService->findRegionById(id: $id_provinsi);
        if (! isset($findRegion)) {
            throw new Exception(trans('response.error.view', ['data' => 'Artikel Wilayah', 'error' => 'Data tidak ditemukan']));
        }

        return $this->blogDatatableService->blogDatatable(request: $request, onlyRegionData: true, id_provinsi: $findRegion->id);
    }

    public function renderCreate(Request $request, string $id_provinsi): View
    {
        $findRegion = $this->regionQueryService->findRegionById(id: $id_provinsi);
        if (! isset($findRegion)) {
            abort(Response::HTTP_NOT_FOUND);
        }

        return view('admin.pages.home-desa-blog.create', compact('findRegion'));
    }

    public function execStore(HomeDesaBlogStoreRequest $request, string $id_provinsi): ?Blog
    {
        $findRegion = $this->regionQueryService->findRegionById(id: $id_provinsi);
        if (! isset($findRegion)) {
            throw new Exception(trans('response.error.store', ['data' => 'Artikel Wilayah', 'error' => 'Data tidak ditemukan']));
        }

        $storeData = $this->blogCommandService->store(request: $request, id_provinsi: $findRegion->id);
        if (! isset($storeData)) {
            throw new Exception(trans('response.error.store', ['data' => 'Artikel Wilayah', 'error' => 'Data tidak ditemukan']));
        }

        $explodeFilePath = explode(','.config('app.url'), $request->image);

        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'svg', 'webp'];
        foreach ($explodeFilePath as $index => $filePath) {
            $explodeStorage = explode('storage/', $filePath);
            $fileUrl = trim($explodeStorage[1]); // Trim spaces
            $extension = strtolower(pathinfo($fileUrl, PATHINFO_EXTENSION));

            if (! in_array($extension, $allowedExtensions)) {
                throw new Exception(trans('response.error.store', ['data' => 'Artikel Wilayah', 'error' => 'File yang diunggah bukan gambar yang valid']));
            }
            $this->assetCommandService->storeAsset(pathType: Blog::class, pathId: $storeData->id, pathName: $explodeStorage[1], assetKey: 'blog-asset-key-'.Ulid::generate());
        }

        $newRequest = new SeoManagementStoreUpdateRequest([
            'meta_title' => $request->title,
            'meta_robot' => 'index,follow',
            'meta_author' => Auth::user()->name,
            'meta_keyword' => 'provinsi '.strtolower($findRegion->province).',kabupaten/wilayah '.strtolower($findRegion->regency).','.
                strtolower(implode(',', $findRegion->existingApps?->pluck('display')->toArray())).','.$storeData->slug,
            'meta_language' => 'id,en',
            'meta_description' => $request->title,
            'meta_og_title' => $request->title,
            'meta_og_url' => null, //TODO: Change to app URL
            'meta_og_type' => SeoConst::SEO_TYPE_KEY['01'],
            'meta_og_description' => $request->title,
        ]);

        $this->seoManagementCommandService->storeUpdate(request: $newRequest, seoType: Blog::class, seoId: $storeData->id);

        return $storeData;
    }

    public function renderEdit(Request $request, string $id_provinsi, string $id): View
    {
        $findRegion = $this->regionQueryService->findRegionById(id: $id_provinsi);
        if (! isset($findRegion)) {
            abort(Response::HTTP_NOT_FOUND);
        }

        $blog = $this->blogQueryService->getById(id: $id, id_provinsi: $findRegion->id);
        if (! isset($blog)) {
            abort(Response::HTTP_NOT_FOUND);
        }

        if ($blog->regions->count() > 1) {
            abort(Response::HTTP_NOT_FOUND);
        }

        $loadImage = $this->assetQueryService->getAllAsset(pathType: Blog::class, pathId: $blog->id, withAssetKey: 'blog-asset-key-', usingLike: true);

        $arrayImg = $loadImage->map(function ($item) {
            return $item->asset_path ? asset('storage/'.$item->asset_path) : null;
        })->filter()->toArray();

        $blog->image = $loadImage->map(function ($item) {
            $item->path = $item->asset_path ? $item->asset_path : null;

            return $item;
        });
        $blog->image_arr = ! empty($arrayImg) ? implode(',', $arrayImg) : null;

        return view('admin.pages.home-desa-blog.edit', compact('blog', 'findRegion'));
    }

    public function execUpdateContent(HomeDesaBlogUpdateRequest $request, string $id_provinsi, string $id): ?Blog
    {
        $findRegion = $this->regionQueryService->findRegionById(id: $id_provinsi);
        if (! isset($findRegion)) {
            throw new Exception(trans('response.error.update', ['data' => 'Artikel Wilayah', 'error' => 'Data tidak ditemukan']));
        }

        $blog = $this->blogQueryService->getById(id: $id, id_provinsi: $findRegion->id);
        if (! isset($blog)) {
            throw new Exception(trans('response.error.update', ['data' => 'Artikel Wilayah', 'error' => 'Data tidak ditemukan']));
        }

        if ($blog->regions->count() > 1) {
            throw new Exception(trans('response.error.update', ['data' => 'Artikel Wilayah', 'error' => 'Data tidak ditemukan']));
        }

        $updateData = $this->blogCommandService->updateContent(request: $request, query: $blog);
        if (! isset($updateData)) {
            throw new Exception(trans('response.error.store', ['data' => 'Artikel Wilayah', 'error' => 'Data tidak ditemukan']));
        }

        if (isset($request->image)) {
            $this->assetCommandService->deleteAllAsset(pathType: Blog::class, pathId: $updateData->id);
            $explodeFilePath = explode(','.config('app.url'), $request->image);

            $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'svg', 'webp'];
            foreach ($explodeFilePath as $index => $filePath) {
                $explodeStorage = explode('storage/', $filePath);
                $fileUrl = trim($explodeStorage[1]); // Trim spaces
                $extension = strtolower(pathinfo($fileUrl, PATHINFO_EXTENSION));

                if (! in_array($extension, $allowedExtensions)) {
                    throw new Exception(trans('response.error.update', ['data' => 'Artikel Wilayah', 'error' => 'File yang diunggah bukan gambar yang valid']));
                }
                $this->assetCommandService->storeAsset(pathType: Blog::class, pathId: $updateData->id, pathName: $explodeStorage[1], assetKey: 'blog-asset-key-'.Ulid::generate());
            }
        }

        return $updateData;
    }

    public function execUpdateActive(string $id_provinsi, string $id): bool
    {
        $findRegion = $this->regionQueryService->findRegionById(id: $id_provinsi);
        if (! isset($findRegion)) {
            throw new Exception(trans('response.error.switch', ['data' => 'Perubahan Status Artikel Wilayah', 'error' => 'Data tidak ditemukan']));
        }

        $blog = $this->blogQueryService->getById(id: $id, id_provinsi: $findRegion->id);
        if (! isset($blog)) {
            throw new Exception(trans('response.error.switch', ['data' => 'Perubahan Status Artikel Wilayah', 'error' => 'Data tidak ditemukan']));
        }

        if ($blog->regions->count() > 1) {
            throw new Exception(trans('response.error.switch', ['data' => 'Perubahan Status Artikel Wilayah', 'error' => 'Data tidak ditemukan']));
        }

        return $this->blogCommandService->updateActive(query: $blog);
    }

    public function execUpdateGeneralBlog(string $id_provinsi, string $id): bool
    {
        $findRegion = $this->regionQueryService->findRegionById(id: $id_provinsi);
        if (! isset($findRegion)) {
            throw new Exception(trans('response.error.switch', ['data' => 'Perubahan Status Artikel Wilayah', 'error' => 'Data tidak ditemukan']));
        }

        $blog = $this->blogQueryService->getById(id: $id, id_provinsi: $findRegion->id);
        if (! isset($blog)) {
            throw new Exception(trans('response.error.switch', ['data' => 'Perubahan Status Artikel Wilayah', 'error' => 'Data tidak ditemukan']));
        }

        if ($blog->regions->count() > 1) {
            throw new Exception(trans('response.error.switch', ['data' => 'Perubahan Status Artikel Wilayah', 'error' => 'Data tidak ditemukan']));
        }

        return $this->blogCommandService->updateGeneralBlog(query: $blog);
    }

    public function execDelete(Request $request, string $id_provinsi, string $id): bool
    {
        $findRegion = $this->regionQueryService->findRegionById(id: $id_provinsi);
        if (! isset($findRegion)) {
            throw new Exception(trans('response.error.delete', ['data' => 'Artikel Wilayah', 'error' => 'Data tidak ditemukan']));
        }

        $blog = $this->blogQueryService->getById(id: $id, id_provinsi: $findRegion->id);
        if (! isset($blog)) {
            throw new Exception(trans('response.error.delete', ['data' => 'Artikel Wilayah', 'error' => 'Data tidak ditemukan']));
        }

        if ($blog->is_active) {
            throw new Exception(trans('response.error.delete', ['data' => 'Artikel Wilayah', 'error' => 'Mohon nonaktifkan artikel terlebih dahulu']));
        }

        if ($blog->regions->count() > 1) {
            throw new Exception(trans('response.error.delete', ['data' => 'Artikel Wilayah', 'error' => 'Data tidak ditemukan']));
        }

        $this->assetCommandService->deleteAllAsset(pathType: Blog::class, pathId: $blog->id);
        $this->seoManagementCommandService->delete(seoType: Blog::class, seoId: $blog->id);

        return $this->blogCommandService->delete(query: $blog);
    }
}
