<?php

namespace App\Http\UseCase\Admin;

use App\Constants\AppConst;
use App\Http\Interfaces\Admin\HomeDesaInterface;
use App\Http\Requests\Admin\HomeDesa\HomeDesaStoreRequest;
use App\Http\Requests\Admin\HomeDesa\HomeDesaUpdateRequest;
use App\Models\RegionDetail;
use App\Services\Region\RegionQueryService;
use App\Services\RegionDetail\RegionDetailCommandService;
use App\Services\RegionDetail\RegionDetailDatatableService;
use App\Services\RegionDetail\RegionDetailQueryService;
use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

final class HomeDesaUseCase implements HomeDesaInterface
{
    public function __construct(
        private readonly RegionQueryService $regionQueryService,
        private readonly RegionDetailCommandService $regionDetailCommandService,
        private readonly RegionDetailDatatableService $regionDetailDatatableService,
        private readonly RegionDetailQueryService $regionDetailQueryService
    ) {}

    public function renderIndex(Request $request, string $id_provinsi): View|RedirectResponse
    {
        $findRegion = $this->regionQueryService->findRegionById(id: $id_provinsi);
        if (! isset($findRegion)) {
            abort(Response::HTTP_NOT_FOUND);
        }

        return view('admin.pages.home-desa.index', compact('findRegion'));
    }

    public function renderCreate(Request $request, string $id_provinsi): View
    {
        $findRegion = $this->regionQueryService->findRegionById(id: $id_provinsi);
        if (! isset($findRegion)) {
            abort(Response::HTTP_NOT_FOUND);
        }

        if ($findRegion->existingApps && $findRegion->existingApps->isNotEmpty()) {
            $arr_existing_app = $findRegion->existingApps->pluck('code')->toArray();
            if (! in_array(AppConst::CODE_EXISTING_APP['02'], $arr_existing_app)) {
                return view('admin.pages.home-desa.create-custom', compact('findRegion'));
            }
        }

        return view('admin.pages.home-desa.create', compact('findRegion'));
    }

    public function execStore(HomeDesaStoreRequest $request, string $id_provinsi): ?RegionDetail
    {
        $findRegion = $this->regionQueryService->findRegionById(id: $id_provinsi);
        if (! isset($findRegion)) {
            throw new Exception(trans('response.error.store', ['data' => 'Desa/Kelurahan', 'error' => 'Data Tidak Ditemukan']));
        }

        if ($request->type == 'default') {
            [$latitude, $longitude] = explode(',', $request->lat_long);

            $request->merge([
                'latitude' => (float) trim($latitude),
                'longitude' => (float) trim($longitude),
            ]);
        }

        $storeRegionDetail = $this->regionDetailCommandService->store(request: $request, id_provinsi: $findRegion->id);

        if (! isset($storeRegionDetail)) {
            throw new Exception(trans('response.error.store', ['data' => 'Desa/Kelurahan', 'error' => 'Data Tidak Ditemukan']));
        }

        return $storeRegionDetail;
    }

    public function renderDatatable(Request $request, string $id_provinsi): JsonResponse
    {
        $findRegion = $this->regionQueryService->findRegionById(id: $id_provinsi);
        if (! isset($findRegion)) {
            throw new Exception(trans('response.error.view', ['data' => 'Desa/Kelurahan', 'error' => 'Data Tidak Ditemukan']));
        }

        return $this->regionDetailDatatableService->regionDetailDatatable(request: $request, id_provinsi: $findRegion->id);
    }

    public function execSwitch(string $id_provinsi, string $id): bool
    {
        $findRegion = $this->regionQueryService->findRegionById(id: $id_provinsi);
        if (! isset($findRegion)) {
            throw new Exception(trans('response.error.switch', ['data' => 'Perubahan Status Desa/Kelurahan', 'error' => 'Data Tidak Ditemukan']));
        }

        $findDetailRegion = $this->regionDetailQueryService->findDetailRegionById(id_provinsi: $findRegion->id, id: $id);

        if (! isset($findDetailRegion)) {
            throw new Exception(trans('response.error.switch', ['data' => 'Perubahan Status Desa/Kelurahan', 'error' => 'Data Tidak Ditemukan']));
        }

        return $this->regionDetailCommandService->setStatus(regionDetail: $findDetailRegion);
    }

    public function execDelete(string $id_provinsi, string $id): bool
    {
        $findRegion = $this->regionQueryService->findRegionById(id: $id_provinsi);
        if (! isset($findRegion)) {
            throw new Exception(trans('response.error.delete', ['data' => 'Desa/Kelurahan', 'error' => 'Data Tidak Ditemukan']));
        }

        $findDetailRegion = $this->regionDetailQueryService->findDetailRegionById(id_provinsi: $findRegion->id, id: $id);

        if (! isset($findDetailRegion)) {
            throw new Exception(trans('response.error.delete', ['data' => 'Desa/Kelurahan', 'error' => 'Data Tidak Ditemukan']));
        }

        if ($findDetailRegion->is_active) {
            throw new Exception(trans('response.error.delete', ['data' => 'Desa/Kelurahan', 'error' => 'Mohon nonaktifkan desa terlebih dahulu']));
        }

        return $this->regionDetailCommandService->delete(regionDetail: $findDetailRegion);
    }

    public function renderEdit(string $id_provinsi, string $id): View
    {
        $findRegion = $this->regionQueryService->findRegionById(id: $id_provinsi);
        if (! isset($findRegion)) {
            abort(Response::HTTP_NOT_FOUND);
        }

        $findDetailRegion = $this->regionDetailQueryService->findDetailRegionById(id_provinsi: $findRegion->id, id: $id);

        if (! isset($findDetailRegion)) {
            abort(Response::HTTP_NOT_FOUND);
        }

        if ($findRegion->existingApps && $findRegion->existingApps->isNotEmpty()) {
            $arr_existing_app = $findRegion->existingApps->pluck('code')->toArray();
            if (! in_array(AppConst::CODE_EXISTING_APP['02'], $arr_existing_app)) {
                return view('admin.pages.home-desa.edit-custom', compact('findRegion', 'findDetailRegion'));
            }
        }

        return view('admin.pages.home-desa.edit', compact('findRegion', 'findDetailRegion'));
    }

    public function execUpdate(HomeDesaUpdateRequest $request, string $id_provinsi, string $id): ?RegionDetail
    {
        $findRegion = $this->regionQueryService->findRegionById(id: $id_provinsi);
        if (! isset($findRegion)) {
            throw new Exception(trans('response.error.update', ['data' => 'Desa/Kelurahan', 'error' => 'Data Tidak Ditemukan']));
        }

        $findDetailRegion = $this->regionDetailQueryService->findDetailRegionById(id_provinsi: $findRegion->id, id: $id);

        if (! isset($findDetailRegion)) {
            throw new Exception(trans('response.error.update', ['data' => 'Desa/Kelurahan', 'error' => 'Data Tidak Ditemukan']));
        }

        if ($request->type == 'default') {
            [$latitude, $longitude] = explode(',', $request->lat_long);

            $request->merge([
                'latitude' => (float) trim($latitude),
                'longitude' => (float) trim($longitude),
            ]);
        }

        $updateDetailRegion = $this->regionDetailCommandService->update(request: $request, regionDetail: $findDetailRegion);

        if (! isset($updateDetailRegion)) {
            throw new Exception(trans('response.error.update', ['data' => 'Desa/Kelurahan', 'error' => 'Data Tidak Ditemukan']));
        }

        return $updateDetailRegion;
    }
}
