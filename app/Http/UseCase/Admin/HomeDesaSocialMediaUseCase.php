<?php

namespace App\Http\UseCase\Admin;

use App\Http\Interfaces\Admin\HomeDesaSocialMediaInterface;
use App\Http\Requests\Admin\HomeDesaSocialMedia\HomeDesaSocialMediaStoreRequest;
use App\Http\Requests\Admin\HomeDesaSocialMedia\HomeDesaSocialMediaUpdateRequest;
use App\Models\LinkCollection;
use App\Services\Asset\AssetCommandService;
use App\Services\Asset\AssetQueryService;
use App\Services\LinkCollection\LinkCollectionCommandService;
use App\Services\LinkCollection\LinkCollectionDatatableService;
use App\Services\LinkCollection\LinkCollectionQueryService;
use App\Services\Region\RegionQueryService;
use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Symfony\Component\Uid\Ulid;

final class HomeDesaSocialMediaUseCase implements HomeDesaSocialMediaInterface
{
    public function __construct(
        private readonly RegionQueryService $regionQueryService,
        private readonly LinkCollectionDatatableService $linkCollectionDatatableService,
        private readonly LinkCollectionCommandService $linkCollectionCommandService,
        private readonly LinkCollectionQueryService $linkCollectionQueryService,
        private readonly AssetCommandService $assetCommandService,
        private readonly AssetQueryService $assetQueryService
    ) {}

    public function renderIndex(Request $request, string $id_provinsi): View
    {
        $findRegion = $this->regionQueryService->findRegionById(id: $id_provinsi);
        if (! isset($findRegion)) {
            abort(Response::HTTP_NOT_FOUND);
        }

        return view('admin.pages.home-desa-social-media.index', compact('findRegion'));
    }

    public function renderDatatable(Request $request, string $id_provinsi): JsonResponse
    {
        $findRegion = $this->regionQueryService->findRegionById(id: $id_provinsi);
        if (! isset($findRegion)) {
            throw new Exception(trans('response.error.view', ['data' => 'Sosial Media Wilayah', 'error' => 'Data tidak ditemukan']));
        }

        return $this->linkCollectionDatatableService->linkSosmedDatatable(request: $request, id_provinsi: $findRegion->id, isSocialMedia: true);
    }

    public function renderCreate(Request $request, string $id_provinsi): View
    {
        $findRegion = $this->regionQueryService->findRegionById(id: $id_provinsi);
        if (! isset($findRegion)) {
            abort(Response::HTTP_NOT_FOUND);
        }

        return view('admin.pages.home-desa-social-media.create', compact('findRegion'));
    }

    public function execStore(HomeDesaSocialMediaStoreRequest $request, string $id_provinsi): ?LinkCollection
    {
        $findRegion = $this->regionQueryService->findRegionById(id: $id_provinsi);
        if (! isset($findRegion)) {
            throw new Exception(trans('response.error.store', ['data' => 'Sosial Media Wilayah', 'error' => 'Data tidak ditemukan']));
        }

        $storeData = $this->linkCollectionCommandService->store(request: $request, id_provinsi: $findRegion->id, isSocialMedia: true);

        if (! isset($storeData)) {
            throw new Exception(trans('response.error.store', ['data' => 'Sosial Media Wilayah', 'error' => 'Data tidak ditemukan']));
        }

        $explodeFilePath = explode(','.config('app.url'), $request->image);
        if (count($explodeFilePath) > 1) {
            throw new Exception(trans('response.error.store', ['data' => 'Sosial Media Wilayah', 'error' => 'Tidak dapat menambahkan lebih dari 1 gambar']));
        }

        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'svg', 'webp'];
        foreach ($explodeFilePath as $index => $filePath) {
            $explodeStorage = explode('storage/', $filePath);
            $fileUrl = trim($explodeStorage[1]);
            $extension = strtolower(pathinfo($fileUrl, PATHINFO_EXTENSION));

            if (! in_array($extension, $allowedExtensions)) {
                throw new Exception(trans('response.error.store', ['data' => 'Sosial Media Wilayah', 'error' => 'File yang diunggah bukan gambar yang valid']));
            }
            $this->assetCommandService->storeAsset(pathType: LinkCollection::class, pathId: $storeData->id, pathName: $explodeStorage[1], assetKey: 'link-collection-icon-'.Ulid::generate());
        }

        return $storeData;
    }

    public function renderEdit(Request $request, string $id_provinsi, string $id): View
    {
        $findRegion = $this->regionQueryService->findRegionById(id: $id_provinsi);
        if (! isset($findRegion)) {
            abort(Response::HTTP_NOT_FOUND);
        }

        $findLink = $this->linkCollectionQueryService->findLinkById(id_provinsi: $findRegion->id, id: $id);
        if (! isset($findLink)) {
            abort(Response::HTTP_NOT_FOUND);
        }

        if (! $findLink->is_social_media) {
            abort(Response::HTTP_NOT_FOUND);
        }
        $icon = $this->assetQueryService->loadAsset(pathType: LinkCollection::class, pathId: $findLink->id);
        $findLink->icon = isset($icon) ? $icon : null;

        return view('admin.pages.home-desa-social-media.edit', compact('findRegion', 'findLink'));
    }

    public function execUpdateContent(HomeDesaSocialMediaUpdateRequest $request, string $id_provinsi, string $id): ?LinkCollection
    {
        $findRegion = $this->regionQueryService->findRegionById(id: $id_provinsi);
        if (! isset($findRegion)) {
            throw new Exception(trans('response.error.update', ['data' => 'Sosial Media Wilayah', 'error' => 'Data tidak ditemukan']));
        }

        $findLink = $this->linkCollectionQueryService->findLinkById(id_provinsi: $findRegion->id, id: $id);
        if (! isset($findLink)) {
            throw new Exception(trans('response.error.update', ['data' => 'Sosial Media Wilayah', 'error' => 'Data tidak ditemukan']));
        }

        if (! $findLink->is_social_media) {
            throw new Exception(trans('response.error.update', ['data' => 'Sosial Media Wilayah', 'error' => 'Data tidak ditemukan']));
        }

        $updateData = $this->linkCollectionCommandService->update(request: $request, linkCollection: $findLink);

        if (! isset($updateData)) {
            throw new Exception(trans('response.error.update', ['data' => 'Sosial Media Wilayah', 'error' => 'Data tidak ditemukan']));
        }

        $explodeFilePath = explode(','.config('app.url'), $request->image);
        if (count($explodeFilePath) > 1) {
            throw new Exception(trans('response.error.update', ['data' => 'Sosial Media Wilayah', 'error' => 'Tidak dapat menambahkan lebih dari 1 gambar']));
        }

        $this->assetCommandService->deleteAllAsset(pathType: LinkCollection::class, pathId: $updateData->id);

        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'svg', 'webp'];
        foreach ($explodeFilePath as $index => $filePath) {
            $explodeStorage = explode('storage/', $filePath);
            $fileUrl = trim($explodeStorage[1]);
            $extension = strtolower(pathinfo($fileUrl, PATHINFO_EXTENSION));

            if (! in_array($extension, $allowedExtensions)) {
                throw new Exception(trans('response.error.update', ['data' => 'Sosial Media Wilayah', 'error' => 'File yang diunggah bukan gambar yang valid']));
            }
            $this->assetCommandService->storeAsset(pathType: LinkCollection::class, pathId: $updateData->id, pathName: $explodeStorage[1], assetKey: 'link-collection-icon-'.Ulid::generate());
        }

        return $updateData;
    }

    public function execUpdateActive(string $id_provinsi, string $id): bool
    {
        $findRegion = $this->regionQueryService->findRegionById(id: $id_provinsi);
        if (! isset($findRegion)) {
            throw new Exception(trans('response.error.switch', ['data' => 'Perubahan Status Sosial Media Wilayah', 'error' => 'Data tidak ditemukan']));
        }

        $findLink = $this->linkCollectionQueryService->findLinkById(id_provinsi: $findRegion->id, id: $id);
        if (! isset($findLink)) {
            throw new Exception(trans('response.error.switch', ['data' => 'Perubahan Status Sosial Media Wilayah', 'error' => 'Data tidak ditemukan']));
        }

        if (! $findLink->is_social_media) {
            throw new Exception(trans('response.error.switch', ['data' => 'Perubahan Status Sosial Media Wilayah', 'error' => 'Data tidak ditemukan']));
        }

        return $this->linkCollectionCommandService->updateActive(linkCollection: $findLink);
    }

    public function execDelete(Request $request, string $id_provinsi, string $id): bool
    {
        $findRegion = $this->regionQueryService->findRegionById(id: $id_provinsi);
        if (! isset($findRegion)) {
            throw new Exception(trans('response.error.delete', ['data' => 'Sosial Media Wilayah', 'error' => 'Data tidak ditemukan']));
        }

        $findLink = $this->linkCollectionQueryService->findLinkById(id_provinsi: $findRegion->id, id: $id);
        if (! isset($findLink)) {
            throw new Exception(trans('response.error.delete', ['data' => 'Sosial Media Wilayah', 'error' => 'Data tidak ditemukan']));
        }

        if (! $findLink->is_social_media) {
            throw new Exception(trans('response.error.delete', ['data' => 'Sosial Media Wilayah', 'error' => 'Data tidak ditemukan']));
        }

        $this->assetCommandService->deleteAllAsset(pathType: LinkCollection::class, pathId: $findLink->id);

        return $this->linkCollectionCommandService->delete(linkCollection: $findLink);
    }
}
