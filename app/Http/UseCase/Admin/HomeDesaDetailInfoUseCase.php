<?php

namespace App\Http\UseCase\Admin;

use App\Http\Interfaces\Admin\HomeDesaDetailInfoInterface;
use App\Http\Requests\Admin\HomeDesaDetailInfo\HomeDesaDetailInfoUpdateRequest;
use App\Models\RegionDetailInfo;
use App\Services\Region\RegionQueryService;
use App\Services\RegionDetailInfo\RegionDetailInfoCommandService;
use App\Services\RegionDetailInfo\RegionDetailInfoQueryService;
use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

final class HomeDesaDetailInfoUseCase implements HomeDesaDetailInfoInterface
{
    public function __construct(
        private readonly RegionQueryService $regionQueryService,
        private readonly RegionDetailInfoQueryService $regionDetailInfoQueryService,
        private readonly RegionDetailInfoCommandService $regionDetailInfoCommandService
    ) {}

    public function renderEdit(Request $request, string $id_provinsi): View
    {
        $findRegion = $this->regionQueryService->findRegionById(id: $id_provinsi);
        if (! isset($findRegion)) {
            abort(Response::HTTP_NOT_FOUND);
        }

        $findDetailInfo = $this->regionDetailInfoQueryService->findDetailInfoByRegionId(id_provinsi: $findRegion->id);

        return view('admin.pages.home-desa-detail-info.edit', compact('findRegion', 'findDetailInfo'));
    }

    public function execUpdate(HomeDesaDetailInfoUpdateRequest $request, string $id_provinsi): ?RegionDetailInfo
    {
        $findRegion = $this->regionQueryService->findRegionById(id: $id_provinsi);
        if (! isset($findRegion)) {
            throw new Exception(trans('response.error.update', ['data' => 'Informasi Produk', 'error' => 'Data Gagal Diperbaharui']));
        }

        $storeUpdate = $this->regionDetailInfoCommandService->storeOrUpdate(request: $request, id_provinsi: $findRegion->id);
        if (! isset($storeUpdate)) {
            throw new Exception(trans('response.error.update', ['data' => 'Informasi Produk', 'error' => 'Data Gagal Diperbaharui']));
        }

        return $storeUpdate;
    }
}
