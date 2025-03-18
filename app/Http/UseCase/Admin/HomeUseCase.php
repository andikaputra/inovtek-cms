<?php

namespace App\Http\UseCase\Admin;

use App\Constants\SeoConst;
use App\Http\Interfaces\Admin\HomeInterface;
use App\Http\Requests\Admin\Home\HomeStoreRequest;
use App\Http\Requests\Admin\Home\HomeUpdateRequest;
use App\Http\Requests\Admin\SeoManagement\SeoManagementStoreUpdateRequest;
use App\Models\Region;
use App\Services\Asset\AssetCommandService;
use App\Services\Asset\AssetQueryService;
use App\Services\ExistingApp\ExistingAppQueryService;
use App\Services\Region\RegionCommandService;
use App\Services\Region\RegionQueryService;
use App\Services\SeoManagement\SeoManagementCommandService;
use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Symfony\Component\Uid\Ulid;

final class HomeUseCase implements HomeInterface
{
    public function __construct(
        private readonly RegionQueryService $regionQueryService,
        private readonly ExistingAppQueryService $existingAppQueryService,
        private readonly AssetQueryService $assetQueryService,
        private readonly RegionCommandService $regionCommandService,
        private readonly AssetCommandService $assetCommandService,
        private readonly SeoManagementCommandService $seoManagementCommandService
    ) {}

    public function renderIndex(Request $request): View
    {
        $request->merge(['paginate' => true]);

        $getAllRegion = $this->regionQueryService->getAllRegion(request: $request);
        $active = $getAllRegion->first()?->active_count ?? 0;
        $deactive = $getAllRegion->first()?->deactive_count ?? 0;
        $countData = [
            'all' => $active + $deactive,
            'active' => $active,
            'deactive' => $deactive,
        ];

        $existingApp = $this->existingAppQueryService->getAllExistingApp(request: $request);

        return view('admin.pages.home.index', compact('getAllRegion', 'countData', 'existingApp'));
    }

    public function renderEdit(Request $request, string $id): View
    {
        $findRegion = $this->regionQueryService->findRegionById(id: $id);
        if (! isset($findRegion)) {
            abort(Response::HTTP_NOT_FOUND);
        }

        $existingApp = $this->existingAppQueryService->getAllExistingApp(request: $request);
        $loadImage = $this->assetQueryService->getAllAsset(pathType: Region::class, pathId: $findRegion->id, withAssetKey: 'region-wallpaper-', usingLike: true);

        $arrayImg = $loadImage->map(function ($item) {
            return $item->asset_path ? asset('storage/'.$item->asset_path) : null;
        })->filter()->toArray();
        $findRegion->asset_arr = ! empty($arrayImg) ? implode(',', $arrayImg) : null;

        return view('admin.pages.home.edit', compact('existingApp', 'findRegion'));
    }

    public function renderCreate(Request $request): View
    {
        $existingApp = $this->existingAppQueryService->getAllExistingApp(request: $request);

        return view('admin.pages.home.create', compact('existingApp'));
    }

    public function execSwitch(string $id): ?Region
    {
        $findRegion = $this->regionQueryService->findRegionById(id: $id);
        if (! isset($findRegion)) {
            throw new Exception(trans('response.error.switch', ['data' => 'Perubahan Status Wilayah', 'error' => 'Data Tidak Ditemukan']));
        }

        return $this->regionCommandService->switch(region: $findRegion);
    }

    public function execDelete(string $id): bool
    {
        $findRegion = $this->regionQueryService->findRegionById(id: $id);
        if (! isset($findRegion)) {
            throw new Exception(trans('response.error.delete', ['data' => 'Wilayah', 'error' => 'Data Tidak Ditemukan']));
        }

        if ($findRegion->is_active) {
            throw new Exception(trans('response.error.delete', ['data' => 'Wilayah', 'error' => 'Mohon Nonaktifkan Terlebih Dahulu Wilayah Yang Ingin Dihapus']));
        }

        return $this->regionCommandService->delete(region: $findRegion);
    }

    public function execStore(HomeStoreRequest $request): ?Region
    {
        $storeData = $this->regionCommandService->store(request: $request);
        if (! isset($storeData)) {
            throw new Exception(trans('response.error.store', ['data' => 'Wilayah', 'error' => 'Data Tidak Ditemukan']));
        }

        $storeExistingApp = $this->regionCommandService->regionExistingApp(region: $storeData, request: $request);
        if (! isset($storeExistingApp)) {
            throw new Exception(trans('response.error.store', ['data' => 'Wilayah', 'error' => 'Data Gagal Ditambahkan']));
        }

        $explodeFilePath = explode(','.config('app.url'), $request->image);
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'svg', 'webp'];
        foreach ($explodeFilePath as $filePath) {
            $explodeStorage = explode('storage/', $filePath);
            $fileUrl = trim($explodeStorage[1]);
            $extension = strtolower(pathinfo($fileUrl, PATHINFO_EXTENSION));

            if (! in_array($extension, $allowedExtensions)) {
                throw new Exception(trans('response.error.store', ['data' => 'Wilayah', 'error' => 'File yang diunggah bukan gambar yang valid']));
            }
            $this->assetCommandService->storeAsset(pathType: Region::class, pathId: $storeData->id, pathName: $explodeStorage[1], assetKey: 'region-wallpaper-'.Ulid::generate());
        }

        // Create SEO
        $newRequest = new SeoManagementStoreUpdateRequest([
            'meta_title' => 'Provinsi '.$storeData->province.' Kabupaten/Wilayah '.$storeData->regency,
            'meta_robot' => 'index,follow',
            'meta_author' => 'Inovtek BNPB',
            'meta_keyword' => 'provinsi '.strtolower($storeData->province).',kabupaten/wilayah '.strtolower($storeData->regency).','.
                strtolower(implode(',', $storeData->existingApps?->pluck('display')->toArray())),
            'meta_language' => 'id,en',
            'meta_description' => 'Provinsi '.$storeData->province.' Kabupaten/Wilayah '.$storeData->regency,
            'meta_og_title' => 'Provinsi '.$storeData->province.' Kabupaten/Wilayah '.$storeData->regency,
            'meta_og_url' => null, // TODO: Change to app URL
            'meta_og_type' => SeoConst::SEO_TYPE_KEY['01'],
            'meta_og_description' => 'Provinsi '.$storeData->province.' Kabupaten/Wilayah '.$storeData->regency,
        ]);

        $this->seoManagementCommandService->storeUpdate(request: $newRequest, seoType: Region::class, seoId: $storeData->id);

        return $storeData;
    }

    public function execUpdate(HomeUpdateRequest $request, string $id): ?Region
    {
        $findRegion = $this->regionQueryService->findRegionById(id: $id);
        if (! isset($findRegion)) {
            throw new Exception(trans('response.error.update', ['data' => 'Wilayah', 'error' => 'Data Tidak Ditemukan']));
        }

        $updateData = $this->regionCommandService->update(request: $request, region: $findRegion);
        if (! isset($updateData)) {
            throw new Exception(trans('response.error.update', ['data' => 'Wilayah', 'error' => 'Data Tidak Ditemukan']));
        }

        $updateExistingApp = $this->regionCommandService->regionExistingApp(region: $updateData, request: $request);
        if (! isset($updateExistingApp)) {
            throw new Exception(trans('response.error.update', ['data' => 'Wilayah', 'error' => 'Data Gagal Ditambahkan']));
        }

        $this->assetCommandService->deleteAllAsset(pathType: Region::class, pathId: $updateData->id);

        $explodeFilePath = explode(','.config('app.url'), $request->image);
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'svg', 'webp'];
        foreach ($explodeFilePath as $filePath) {
            $explodeStorage = explode('storage/', $filePath);
            $fileUrl = trim($explodeStorage[1]);
            $extension = strtolower(pathinfo($fileUrl, PATHINFO_EXTENSION));

            if (! in_array($extension, $allowedExtensions)) {
                throw new Exception(trans('response.error.update', ['data' => 'Wilayah', 'error' => 'File yang diunggah bukan gambar yang valid']));
            }
            $this->assetCommandService->storeAsset(pathType: Region::class, pathId: $updateData->id, pathName: $explodeStorage[1], assetKey: 'region-wallpaper-'.Ulid::generate());
        }

        return $updateData;
    }

    public function execReadNotification(Request $request): bool
    {
        return $this->regionCommandService->readNotification(request: $request);
    }
}
