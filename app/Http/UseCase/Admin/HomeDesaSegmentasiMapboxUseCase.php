<?php

namespace App\Http\UseCase\Admin;

use App\Constants\AppConst;
use App\Http\Interfaces\Admin\HomeDesaSegmentasiMapboxInterface;
use App\Http\Requests\Admin\HomeDesaSegmentasiMapbox\HomeDesaSegmentasiMapboxOrderRequest;
use App\Http\Requests\Admin\HomeDesaSegmentasiMapbox\HomeDesaSegmentasiMapboxStoreRequest;
use App\Http\Requests\Admin\HomeDesaSegmentasiMapbox\HomeDesaSegmentasiMapboxUpdateRequest;
use App\Models\RegionDetailMapbox;
use App\Services\Region\RegionQueryService;
use App\Services\RegionDetail\RegionDetailQueryService;
use App\Services\RegionDetailMapbox\RegionDetailMapboxCommandService;
use App\Services\RegionDetailMapbox\RegionDetailMapboxDatatableService;
use App\Services\RegionDetailMapbox\RegionDetailMapboxQueryService;
use Carbon\Carbon;
use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;

final class HomeDesaSegmentasiMapboxUseCase implements HomeDesaSegmentasiMapboxInterface
{
    public function __construct(
        private readonly RegionQueryService $regionQueryService,
        private readonly RegionDetailQueryService $regionDetailQueryService,
        private readonly RegionDetailMapboxDatatableService $regionDetailMapboxDatatableService,
        private readonly RegionDetailMapboxCommandService $regionDetailMapboxCommandService,
        private readonly RegionDetailMapboxQueryService $regionDetailMapboxQueryService,
    ) {}

    public function renderIndex(Request $request, string $id_provinsi, string $id_desa): View
    {
        $findRegion = $this->regionQueryService->findRegionById(id: $id_provinsi);
        if (! isset($findRegion)) {
            abort(Response::HTTP_NOT_FOUND);
        }

        if ($findRegion->existingApps && $findRegion->existingApps->isNotEmpty()) {
            $arr_existing_app = $findRegion->existingApps->pluck('code')->toArray();
            if (! in_array(AppConst::CODE_EXISTING_APP['02'], $arr_existing_app)) {
                abort(Response::HTTP_NOT_FOUND);
            }
        }

        $findDetailRegion = $this->regionDetailQueryService->findDetailRegionById(id_provinsi: $findRegion->id, id: $id_desa);
        if (! isset($findDetailRegion)) {
            abort(Response::HTTP_NOT_FOUND);
        }

        $findMapbox = $this->regionDetailMapboxQueryService->getAllCoordinate(id_desa: $findDetailRegion->id);
        $arr_map = $findMapbox->toArray();
        $next_update = Cache::get("coordinates_{$findDetailRegion->id}_last_update") != null ? Carbon::parse(Cache::get("coordinates_{$findDetailRegion->id}_last_update"))->addMinute(AppConst::MAP_REFRESH_LOAD_MINUTE)->format('H:i') : null;

        return view('admin.pages.home-desa-segmentasi-mapbox.index', compact('findRegion', 'findDetailRegion', 'arr_map', 'next_update'));
    }

    public function renderOrderList(Request $request, string $id_provinsi, string $id_desa): Collection
    {
        $findRegion = $this->regionQueryService->findRegionById(id: $id_provinsi);
        if (! isset($findRegion)) {
            throw new Exception(trans('response.error.view', ['data' => 'Segmentasi Mapbox', 'error' => 'Data Tidak Ditemukan']));
        }

        if ($findRegion->existingApps && $findRegion->existingApps->isNotEmpty()) {
            $arr_existing_app = $findRegion->existingApps->pluck('code')->toArray();
            if (! in_array(AppConst::CODE_EXISTING_APP['02'], $arr_existing_app)) {
                throw new Exception(trans('response.error.view', ['data' => 'Segmentasi Mapbox', 'error' => 'Data Tidak Ditemukan']));
            }
        }

        $findDetailRegion = $this->regionDetailQueryService->findDetailRegionById(id_provinsi: $findRegion->id, id: $id_desa);

        if (! isset($findDetailRegion)) {
            throw new Exception(trans('response.error.view', ['data' => 'Segmentasi Mapbox', 'error' => 'Data Tidak Ditemukan']));
        }

        return $this->regionDetailMapboxQueryService->getAllMapboxList(id_desa: $findDetailRegion->id, isActive: true);
    }

    public function execOrderUpdate(HomeDesaSegmentasiMapboxOrderRequest $request, string $id_provinsi, string $id_desa): bool
    {
        $findRegion = $this->regionQueryService->findRegionById(id: $id_provinsi);
        if (! isset($findRegion)) {
            throw new Exception(trans('response.error.update', ['data' => 'Segmentasi Mapbox', 'error' => 'Data Tidak Ditemukan']));
        }

        if ($findRegion->existingApps && $findRegion->existingApps->isNotEmpty()) {
            $arr_existing_app = $findRegion->existingApps->pluck('code')->toArray();
            if (! in_array(AppConst::CODE_EXISTING_APP['02'], $arr_existing_app)) {
                throw new Exception(trans('response.error.update', ['data' => 'Segmentasi Mapbox', 'error' => 'Data Tidak Ditemukan']));
            }
        }

        $findDetailRegion = $this->regionDetailQueryService->findDetailRegionById(id_provinsi: $findRegion->id, id: $id_desa);

        if (! isset($findDetailRegion)) {
            throw new Exception(trans('response.error.update', ['data' => 'Segmentasi Mapbox', 'error' => 'Data Tidak Ditemukan']));
        }

        $listOrder = json_decode($request->route_order, true);

        foreach ($listOrder as $index => $idMapbox) {
            $findDetailRegionMapbox = $this->regionDetailMapboxQueryService->findMapboxById(id_desa: $findDetailRegion->id, id: $idMapbox);
            if (! isset($findDetailRegionMapbox)) {
                throw new Exception(trans('response.error.update', ['data' => 'Segmentasi Mapbox', 'error' => 'Data Tidak Ditemukan']));
            }

            $this->regionDetailMapboxCommandService->updateOrder(regionDetailMapbox: $findDetailRegionMapbox, index: $index + 1);
        }

        return true;
    }

    public function renderDatatable(Request $request, string $id_provinsi, string $id_desa): JsonResponse
    {
        $findRegion = $this->regionQueryService->findRegionById(id: $id_provinsi);
        if (! isset($findRegion)) {
            throw new Exception(trans('response.error.view', ['data' => 'Segmentasi Mapbox', 'error' => 'Data Tidak Ditemukan']));
        }

        if ($findRegion->existingApps && $findRegion->existingApps->isNotEmpty()) {
            $arr_existing_app = $findRegion->existingApps->pluck('code')->toArray();
            if (! in_array(AppConst::CODE_EXISTING_APP['02'], $arr_existing_app)) {
                throw new Exception(trans('response.error.view', ['data' => 'Segmentasi Mapbox', 'error' => 'Data Tidak Ditemukan']));
            }
        }

        $findDetailRegion = $this->regionDetailQueryService->findDetailRegionById(id_provinsi: $findRegion->id, id: $id_desa);

        if (! isset($findDetailRegion)) {
            throw new Exception(trans('response.error.view', ['data' => 'Segmentasi Mapbox', 'error' => 'Data Tidak Ditemukan']));
        }

        return $this->regionDetailMapboxDatatableService->mapboxDatatable(request: $request, id_desa: $findDetailRegion->id);
    }

    public function renderCreate(Request $request, string $id_provinsi, string $id_desa): View
    {
        $findRegion = $this->regionQueryService->findRegionById(id: $id_provinsi);
        if (! isset($findRegion)) {
            abort(Response::HTTP_NOT_FOUND);
        }

        if ($findRegion->existingApps && $findRegion->existingApps->isNotEmpty()) {
            $arr_existing_app = $findRegion->existingApps->pluck('code')->toArray();
            if (! in_array(AppConst::CODE_EXISTING_APP['02'], $arr_existing_app)) {
                abort(Response::HTTP_NOT_FOUND);
            }
        }

        $findDetailRegion = $this->regionDetailQueryService->findDetailRegionById(id_provinsi: $findRegion->id, id: $id_desa);

        if (! isset($findDetailRegion)) {
            abort(Response::HTTP_NOT_FOUND);
        }

        return view('admin.pages.home-desa-segmentasi-mapbox.create', compact('findRegion', 'findDetailRegion'));
    }

    public function execStore(HomeDesaSegmentasiMapboxStoreRequest $request, string $id_provinsi, string $id_desa): ?RegionDetailMapbox
    {
        $findRegion = $this->regionQueryService->findRegionById(id: $id_provinsi);
        if (! isset($findRegion)) {
            throw new Exception(trans('response.error.store', ['data' => 'Segmentasi Mapbox', 'error' => 'Data Tidak Ditemukan']));
        }

        if ($findRegion->existingApps && $findRegion->existingApps->isNotEmpty()) {
            $arr_existing_app = $findRegion->existingApps->pluck('code')->toArray();
            if (! in_array(AppConst::CODE_EXISTING_APP['02'], $arr_existing_app)) {
                throw new Exception(trans('response.error.store', ['data' => 'Segmentasi Mapbox', 'error' => 'Data Tidak Ditemukan']));
            }
        }

        $findDetailRegion = $this->regionDetailQueryService->findDetailRegionById(id_provinsi: $findRegion->id, id: $id_desa);

        if (! isset($findDetailRegion)) {
            throw new Exception(trans('response.error.store', ['data' => 'Segmentasi Mapbox', 'error' => 'Data Tidak Ditemukan']));
        }

        $totalData = $this->regionDetailMapboxQueryService->getAllMapboxList(id_desa: $findDetailRegion->id, isActive: false)->count();
        [$latitude, $longitude] = explode(',', $request->lat_long);

        $request->merge([
            'latitude' => (float) trim($latitude),
            'longitude' => (float) trim($longitude),
            'order_point' => $totalData + 1,
        ]);

        $storeData = $this->regionDetailMapboxCommandService->store(request: $request, id_desa: $findDetailRegion->id);

        if (! isset($storeData)) {
            throw new Exception(trans('response.error.store', ['data' => 'Segmentasi Mapbox', 'error' => 'Data Tidak Ditemukan']));
        }

        return $storeData;
    }

    public function renderEdit(Request $request, string $id_provinsi, string $id_desa, string $id): View
    {
        $findRegion = $this->regionQueryService->findRegionById(id: $id_provinsi);
        if (! isset($findRegion)) {
            abort(Response::HTTP_NOT_FOUND);
        }

        if ($findRegion->existingApps && $findRegion->existingApps->isNotEmpty()) {
            $arr_existing_app = $findRegion->existingApps->pluck('code')->toArray();
            if (! in_array(AppConst::CODE_EXISTING_APP['02'], $arr_existing_app)) {
                abort(Response::HTTP_NOT_FOUND);
            }
        }

        $findDetailRegion = $this->regionDetailQueryService->findDetailRegionById(id_provinsi: $findRegion->id, id: $id_desa);

        if (! isset($findDetailRegion)) {
            abort(Response::HTTP_NOT_FOUND);
        }

        $findDetailRegionMapbox = $this->regionDetailMapboxQueryService->findMapboxById(id_desa: $findDetailRegion->id, id: $id);

        if (! isset($findDetailRegionMapbox)) {
            abort(Response::HTTP_NOT_FOUND);
        }

        return view('admin.pages.home-desa-segmentasi-mapbox.edit', compact('findRegion', 'findDetailRegion', 'findDetailRegionMapbox'));
    }

    public function execUpdate(HomeDesaSegmentasiMapboxUpdateRequest $request, string $id_provinsi, string $id_desa, string $id): ?RegionDetailMapbox
    {
        $findRegion = $this->regionQueryService->findRegionById(id: $id_provinsi);
        if (! isset($findRegion)) {
            throw new Exception(trans('response.error.update', ['data' => 'Segmentasi Mapbox', 'error' => 'Data Tidak Ditemukan']));
        }

        if ($findRegion->existingApps && $findRegion->existingApps->isNotEmpty()) {
            $arr_existing_app = $findRegion->existingApps->pluck('code')->toArray();
            if (! in_array(AppConst::CODE_EXISTING_APP['02'], $arr_existing_app)) {
                throw new Exception(trans('response.error.update', ['data' => 'Segmentasi Mapbox', 'error' => 'Data Tidak Ditemukan']));
            }
        }

        $findDetailRegion = $this->regionDetailQueryService->findDetailRegionById(id_provinsi: $findRegion->id, id: $id_desa);

        if (! isset($findDetailRegion)) {
            throw new Exception(trans('response.error.update', ['data' => 'Segmentasi Mapbox', 'error' => 'Data Tidak Ditemukan']));
        }

        $findDetailRegionMapbox = $this->regionDetailMapboxQueryService->findMapboxById(id_desa: $findDetailRegion->id, id: $id);

        if (! isset($findDetailRegionMapbox)) {
            throw new Exception(trans('response.error.update', ['data' => 'Segmentasi Mapbox', 'error' => 'Data Tidak Ditemukan']));
        }

        [$latitude, $longitude] = explode(',', $request->lat_long);

        $request->merge([
            'latitude' => (float) trim($latitude),
            'longitude' => (float) trim($longitude),
        ]);

        $updateData = $this->regionDetailMapboxCommandService->update(request: $request, regionDetailMapbox: $findDetailRegionMapbox);

        if (! isset($updateData)) {
            throw new Exception(trans('response.error.update', ['data' => 'Segmentasi Mapbox', 'error' => 'Data Tidak Ditemukan']));
        }

        return $updateData;
    }

    public function execSwitch(string $id_provinsi, string $id_desa, string $id): bool
    {
        $findRegion = $this->regionQueryService->findRegionById(id: $id_provinsi);
        if (! isset($findRegion)) {
            throw new Exception(trans('response.error.update', ['data' => 'Segmentasi Mapbox', 'error' => 'Data Tidak Ditemukan']));
        }

        if ($findRegion->existingApps && $findRegion->existingApps->isNotEmpty()) {
            $arr_existing_app = $findRegion->existingApps->pluck('code')->toArray();
            if (! in_array(AppConst::CODE_EXISTING_APP['02'], $arr_existing_app)) {
                throw new Exception(trans('response.error.update', ['data' => 'Segmentasi Mapbox', 'error' => 'Data Tidak Ditemukan']));
            }
        }

        $findDetailRegion = $this->regionDetailQueryService->findDetailRegionById(id_provinsi: $findRegion->id, id: $id_desa);

        if (! isset($findDetailRegion)) {
            throw new Exception(trans('response.error.update', ['data' => 'Segmentasi Mapbox', 'error' => 'Data Tidak Ditemukan']));
        }

        $findDetailRegionMapbox = $this->regionDetailMapboxQueryService->findMapboxById(id_desa: $findDetailRegion->id, id: $id);

        if (! isset($findDetailRegionMapbox)) {
            throw new Exception(trans('response.error.update', ['data' => 'Segmentasi Mapbox', 'error' => 'Data Tidak Ditemukan']));
        }

        return $this->regionDetailMapboxCommandService->updateSwitch(regionDetailMapbox: $findDetailRegionMapbox);
    }

    public function execDelete(string $id_provinsi, string $id_desa, string $id): bool
    {
        $findRegion = $this->regionQueryService->findRegionById(id: $id_provinsi);
        if (! isset($findRegion)) {
            throw new Exception(trans('response.error.delete', ['data' => 'Segmentasi Mapbox', 'error' => 'Data Tidak Ditemukan']));
        }

        if ($findRegion->existingApps && $findRegion->existingApps->isNotEmpty()) {
            $arr_existing_app = $findRegion->existingApps->pluck('code')->toArray();
            if (! in_array(AppConst::CODE_EXISTING_APP['02'], $arr_existing_app)) {
                throw new Exception(trans('response.error.delete', ['data' => 'Segmentasi Mapbox', 'error' => 'Data Tidak Ditemukan']));
            }
        }

        $findDetailRegion = $this->regionDetailQueryService->findDetailRegionById(id_provinsi: $findRegion->id, id: $id_desa);

        if (! isset($findDetailRegion)) {
            throw new Exception(trans('response.error.delete', ['data' => 'Segmentasi Mapbox', 'error' => 'Data Tidak Ditemukan']));
        }

        $findDetailRegionMapbox = $this->regionDetailMapboxQueryService->findMapboxById(id_desa: $findDetailRegion->id, id: $id);

        if (! isset($findDetailRegionMapbox)) {
            throw new Exception(trans('response.error.delete', ['data' => 'Segmentasi Mapbox', 'error' => 'Data Tidak Ditemukan']));
        }

        return $this->regionDetailMapboxCommandService->delete(regionDetailMapbox: $findDetailRegionMapbox);
    }
}
