<?php

namespace App\Http\UseCase\Admin;

use App\Constants\AppConst;
use App\Http\Interfaces\Admin\HomeDesaGalleryInterface;
use App\Http\Requests\Admin\HomeDesaGallery\HomeDesaGalleryUpdateRequest;
use App\Models\RegionGallery;
use App\Services\Asset\AssetCommandService;
use App\Services\Asset\AssetQueryService;
use App\Services\Region\RegionQueryService;
use App\Services\RegionGallery\RegionGalleryCommandService;
use App\Services\RegionGallery\RegionGalleryQueryService;
use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Symfony\Component\Uid\Ulid;

final class HomeDesaGalleryUseCase implements HomeDesaGalleryInterface
{
    public function __construct(
        private readonly RegionQueryService $regionQueryService,
        private readonly RegionGalleryQueryService $regionGalleryQueryService,
        private readonly AssetQueryService $assetQueryService,
        private readonly RegionGalleryCommandService $regionGalleryCommandService,
        private readonly AssetCommandService $assetCommandService,
    ) {}

    public function renderEdit(Request $request, string $id_provinsi): View
    {
        $findRegion = $this->regionQueryService->findRegionById(id: $id_provinsi);
        if (! isset($findRegion)) {
            abort(Response::HTTP_NOT_FOUND);
        }

        $findGallery = $this->regionGalleryQueryService->findGalleryByRegionId(id_provinsi: $findRegion->id);
        if (isset($findGallery)) {
            $loadImage = $this->assetQueryService->getAllAsset(
                pathType: RegionGallery::class,
                pathId: $findGallery->id,
                withAssetKey: 'region-gallery-',
                usingLike: true
            );
            $arrayImg = $loadImage->map(function ($item) {
                return $item->asset_path ? asset('storage/'.$item->asset_path) : null;
            })->filter()->toArray();
            $findGallery->asset_arr = ! empty($arrayImg) ? implode(',', $arrayImg) : null;
        }

        return view('admin.pages.home-desa-gallery.edit', compact('findRegion', 'findGallery'));
    }

    public function execUpdate(HomeDesaGalleryUpdateRequest $request, string $id_provinsi): RegionGallery
    {
        $findRegion = $this->regionQueryService->findRegionById(id: $id_provinsi);
        if (! isset($findRegion)) {
            abort(Response::HTTP_NOT_FOUND);
        }

        $storeOrUpdateGallery = $this->regionGalleryCommandService->storeOrUpdate(
            request: $request,
            id_provinsi: $findRegion->id
        );

        if (! isset($storeOrUpdateGallery)) {
            throw new Exception(trans('response.error.store', ['data' => 'Galeri Wilayah', 'error' => 'Data Gagal Ditambahkan']));
        }

        $explodeFilePath = explode(','.config('app.url'), $request->image);

        if (count($explodeFilePath) < AppConst::MINIMUM_GALLERY_IMAGE) {
            throw new Exception(trans('response.error.update', ['data' => 'Galeri Wilayah', 'error' => 'Mohon memasukkan gambar minimal '.AppConst::MINIMUM_GALLERY_IMAGE.' gambar untuk hasil yang lebih optimal']));
        }

        $this->assetCommandService->deleteAllAsset(pathType: RegionGallery::class, pathId: $storeOrUpdateGallery->id);

        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'svg', 'webp'];
        foreach ($explodeFilePath as $index => $filePath) {
            $explodeStorage = explode('storage/', $filePath);
            $fileUrl = trim($explodeStorage[1]);
            $extension = strtolower(pathinfo($fileUrl, PATHINFO_EXTENSION));

            if (! in_array($extension, $allowedExtensions)) {
                throw new Exception(trans('response.error.store', ['data' => 'Galeri Wilayah', 'error' => 'File yang diunggah bukan gambar yang valid']));
            }
            $this->assetCommandService->storeAsset(
                pathType: RegionGallery::class,
                pathId: $storeOrUpdateGallery->id,
                pathName: $fileUrl,
                assetKey: 'region-gallery-'.Ulid::generate()
            );
        }

        return $storeOrUpdateGallery;
    }
}
