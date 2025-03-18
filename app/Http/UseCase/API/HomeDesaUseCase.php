<?php

namespace App\Http\UseCase\API;

use App\Constants\AppConst;
use App\Http\Interfaces\API\HomeDesaInterface;
use App\Models\RegionDetailMapbox;
use App\Services\Region\RegionQueryService;
use App\Services\RegionDetail\RegionDetailQueryService;
use App\Services\RegionDetailMapbox\RegionDetailMapboxQueryService;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

final class HomeDesaUseCase implements HomeDesaInterface
{
    public function __construct(
        private readonly RegionDetailQueryService $regionDetailQueryService,
        private readonly RegionDetailMapboxQueryService $regionDetailMapboxQueryService,
        private readonly RegionQueryService $regionQueryService,
    ) {}

    public function renderGetAllDesaProductData(Request $request, string $identifier): array
    {
        $region = $this->regionQueryService->findRegionByIdentifier(identifier: $identifier);
        if (! $region) {
            throw new Exception(
                trans('response.error.view', ['data' => 'Produk Desa', 'error' => 'Data Tidak Ditemukan']),
                Response::HTTP_NOT_FOUND
            );
        }

        if ($region->existingApps?->isNotEmpty()) {
            $appCodes = $region->existingApps->pluck('code')->toArray();
            if (in_array(AppConst::CODE_EXISTING_APP['02'], $appCodes)) {
                $regionDetails = $this->regionDetailQueryService->getAllRegionDetail(
                    identifier: $identifier,
                );

                // Set assets, wallpaper data, dan hapus field yang tidak diperlukan
                $regionDetails->each(function ($item) {
                    $item->mapbox_collection = $this->regionDetailMapboxQueryService->getAllCoordinate(id_desa: $item->id)->map(function ($item) {

                        return $item;
                    });
                    $item->makeHidden(['active_count', 'deactive_count', 'deleted_at']);
                });
            }
        }

        return [
            'village' => $regionDetails ?? [],
            'about' => [
                'intro_video_url' => optional($region->existingApps?->where('code', AppConst::CODE_EXISTING_APP['02'])->first())->existingAppInfo?->intro_video_url,
                'tutorial_video_url' => optional($region->existingApps?->where('code', AppConst::CODE_EXISTING_APP['02'])->first())->existingAppInfo?->tutorial_video_url,
            ],
            'disaster_mitigation_info' => $region->regionDetailInfo?->mitigation,
            'seo' => $region->seos,
        ];
    }

    public function renderGetAllVillageData(Request $request, string $identifier): Collection
    {
        $region = $this->regionQueryService->findRegionByIdentifier(identifier: $identifier);
        if (! $region) {
            throw new Exception(
                trans('response.error.view', ['data' => 'Desa/Kelurahan', 'error' => 'Data Tidak Ditemukan']),
                Response::HTTP_NOT_FOUND
            );
        }

        return $this->regionDetailQueryService->getAllRegionDetail(identifier: $region->id)->map(function ($item) {
            $item->makeHidden(['deleted_at', 'latitude', 'longitude', 'map_url']);

            return $item;
        });
    }

    public function renderGetDetailMapboxData(Request $request, string $identifier, string $id): ?RegionDetailMapbox
    {
        $region = $this->regionQueryService->findRegionByIdentifier(identifier: $identifier);
        if (! $region) {
            throw new Exception(
                trans('response.error.view', ['data' => 'Product 360 Vr', 'error' => 'Data Tidak Ditemukan']),
                Response::HTTP_NOT_FOUND
            );
        }

        if ($region->existingApps?->isNotEmpty()) {
            $appCodes = $region->existingApps->pluck('code')->toArray();
            if (! in_array(AppConst::CODE_EXISTING_APP['02'], $appCodes)) {
                throw new Exception(
                    trans('response.error.view', ['data' => 'Product 360 Vr', 'error' => 'Bukan merupakan product yang valid']),
                    Response::HTTP_NOT_FOUND
                );
            }
        }

        $findMapboxById = $this->regionDetailMapboxQueryService->findMapboxById(id: $id, api: true);
        if (! isset($findMapboxById)) {
            throw new Exception(
                trans('response.error.view', ['data' => 'Titik Mapbox', 'error' => 'Data Tidak Ditemukan']),
                Response::HTTP_NOT_FOUND
            );
        }

        if (! $findMapboxById->regionDetail?->is_active) {
            throw new Exception(
                trans('response.error.view', ['data' => 'Titik Mapbox', 'error' => 'Data Tidak Ditemukan']),
                Response::HTTP_NOT_FOUND
            );
        }

        $findMapboxById->regionDetail?->makeHidden(['deleted_at']);

        $findMapboxById->makeHidden(['latitude', 'longitude', 'map_url']);

        return $findMapboxById;
    }
}
