<?php

namespace App\Http\UseCase\Admin;

use App\Constants\AppConst;
use App\Http\Interfaces\Admin\HomeDesaMapboxJalurInterface;
use App\Http\Requests\Admin\HomeDesaMapboxJalur\HomeDesaMapboxJalurOrderRequest;
use App\Http\Requests\Admin\HomeDesaMapboxJalur\HomeDesaMapboxJalurStoreRequest;
use App\Http\Requests\Admin\HomeDesaMapboxJalur\HomeDesaMapboxJalurUpdateRequest;
use App\Models\RegionDetailMapboxList;
use App\Services\Region\RegionQueryService;
use App\Services\RegionDetail\RegionDetailQueryService;
use App\Services\RegionDetailMapbox\RegionDetailMapboxQueryService;
use App\Services\RegionDetailMapboxList\RegionDetailMapboxListCommandService;
use App\Services\RegionDetailMapboxList\RegionDetailMapboxListDatatableService;
use App\Services\RegionDetailMapboxList\RegionDetailMapboxListQueryService;
use Carbon\Carbon;
use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;

final class HomeDesaMapboxJalurUseCase implements HomeDesaMapboxJalurInterface
{
    public function __construct(
        private readonly RegionQueryService $regionQueryService,
        private readonly RegionDetailQueryService $regionDetailQueryService,
        private readonly RegionDetailMapboxQueryService $regionDetailMapboxQueryService,
        private readonly RegionDetailMapboxListQueryService $regionDetailMapboxListQueryService,
        private readonly RegionDetailMapboxListDatatableService $regionDetailMapboxListDatatableService,
        private readonly RegionDetailMapboxListCommandService $regionDetailMapboxListCommandService
    ) {}

    public function renderIndex(Request $request, string $id_provinsi, string $id_desa, string $id_mapbox): View
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

        $findDetailRegionMapbox = $this->regionDetailMapboxQueryService->findMapboxById(id_desa: $findDetailRegion->id, id: $id_mapbox);

        if (! isset($findDetailRegionMapbox)) {
            abort(Response::HTTP_NOT_FOUND);
        }
        $findMapbox = $this->regionDetailMapboxListQueryService->getAllCoordinate(id_mapbox: $findDetailRegionMapbox->id);
        $arr_map = $findMapbox->toArray();
        $next_update = Cache::get("coordinates_{$findDetailRegionMapbox->id}_last_update") != null ? Carbon::parse(Cache::get("coordinates_{$findDetailRegionMapbox->id}_last_update"))->addMinute(AppConst::MAP_REFRESH_LOAD_MINUTE)->format('H:i') : null;

        return view('admin.pages.home-desa-jalur-mapbox.index', compact('findRegion', 'findDetailRegion', 'findDetailRegionMapbox', 'arr_map', 'next_update'));
    }

    public function renderOrderList(Request $request, string $id_provinsi, string $id_desa, string $id_mapbox): Collection
    {
        $findRegion = $this->regionQueryService->findRegionById(id: $id_provinsi);
        if (! isset($findRegion)) {
            throw new Exception(trans('response.error.view', ['data' => 'Titik Jalur Mapbox', 'error' => 'Data Tidak Ditemukan']));
        }

        if ($findRegion->existingApps && $findRegion->existingApps->isNotEmpty()) {
            $arr_existing_app = $findRegion->existingApps->pluck('code')->toArray();
            if (! in_array(AppConst::CODE_EXISTING_APP['02'], $arr_existing_app)) {
                throw new Exception(trans('response.error.view', ['data' => 'Titik Jalur Mapbox', 'error' => 'Data Tidak Ditemukan']));
            }
        }

        $findDetailRegion = $this->regionDetailQueryService->findDetailRegionById(id_provinsi: $findRegion->id, id: $id_desa);

        if (! isset($findDetailRegion)) {
            throw new Exception(trans('response.error.view', ['data' => 'Titik Jalur Mapbox', 'error' => 'Data Tidak Ditemukan']));
        }

        $findDetailRegionMapbox = $this->regionDetailMapboxQueryService->findMapboxById(id_desa: $findDetailRegion->id, id: $id_mapbox);

        if (! isset($findDetailRegionMapbox)) {
            throw new Exception(trans('response.error.view', ['data' => 'Titik Jalur Mapbox', 'error' => 'Data Tidak Ditemukan']));
        }

        return $this->regionDetailMapboxListQueryService->getAllMapboxList(id_mapbox: $findDetailRegionMapbox->id, isActive: true);
    }

    public function execOrderUpdate(HomeDesaMapboxJalurOrderRequest $request, string $id_provinsi, string $id_desa, string $id_mapbox): bool
    {
        $findRegion = $this->regionQueryService->findRegionById(id: $id_provinsi);
        if (! isset($findRegion)) {
            throw new Exception(trans('response.error.update', ['data' => 'Segmentasi Mapbox', 'error' => 'Data Tidak Ditemukan']));
        }

        if ($findRegion->existingApps && $findRegion->existingApps->isNotEmpty()) {
            $arr_existing_app = $findRegion->existingApps->pluck('code')->toArray();
            if (! in_array(AppConst::CODE_EXISTING_APP['02'], $arr_existing_app)) {
                throw new Exception(trans('response.error.update', ['data' => 'Titik Jalur Mapbox', 'error' => 'Data Tidak Ditemukan']));
            }
        }

        $findDetailRegion = $this->regionDetailQueryService->findDetailRegionById(id_provinsi: $findRegion->id, id: $id_desa);

        if (! isset($findDetailRegion)) {
            throw new Exception(trans('response.error.update', ['data' => 'Titik Jalur Mapbox', 'error' => 'Data Tidak Ditemukan']));
        }

        $findDetailRegionMapbox = $this->regionDetailMapboxQueryService->findMapboxById(id_desa: $findDetailRegion->id, id: $id_mapbox);

        if (! isset($findDetailRegionMapbox)) {
            throw new Exception(trans('response.error.update', ['data' => 'Titik Jalur Mapbox', 'error' => 'Data Tidak Ditemukan']));
        }

        $listOrder = json_decode($request->route_order, true);

        foreach ($listOrder as $index => $idMapbox) {
            $findDetailRegionMapboxList = $this->regionDetailMapboxListQueryService->findMapboxListById(id_mapbox: $findDetailRegionMapbox->id, id: $idMapbox);

            if (! isset($findDetailRegionMapboxList)) {
                throw new Exception(trans('response.error.update', ['data' => 'Segmentasi Mapbox', 'error' => 'Data Tidak Ditemukan']));
            }

            $this->regionDetailMapboxListCommandService->updateOrder(regionDetailMapboxList: $findDetailRegionMapboxList, index: $index + 1);
        }

        return true;
    }

    public function renderDatatable(Request $request, string $id_provinsi, string $id_desa, string $id_mapbox): JsonResponse
    {
        $findRegion = $this->regionQueryService->findRegionById(id: $id_provinsi);
        if (! isset($findRegion)) {
            throw new Exception(trans('response.error.view', ['data' => 'Titik Jalur Mapbox', 'error' => 'Data Tidak Ditemukan']));
        }

        if ($findRegion->existingApps && $findRegion->existingApps->isNotEmpty()) {
            $arr_existing_app = $findRegion->existingApps->pluck('code')->toArray();
            if (! in_array(AppConst::CODE_EXISTING_APP['02'], $arr_existing_app)) {
                throw new Exception(trans('response.error.view', ['data' => 'Titik Jalur Mapbox', 'error' => 'Data Tidak Ditemukan']));
            }
        }

        $findDetailRegion = $this->regionDetailQueryService->findDetailRegionById(id_provinsi: $findRegion->id, id: $id_desa);
        if (! isset($findDetailRegion)) {
            throw new Exception(trans('response.error.view', ['data' => 'Titik Jalur Mapbox', 'error' => 'Data Tidak Ditemukan']));
        }

        $findDetailRegionMapbox = $this->regionDetailMapboxQueryService->findMapboxById(id_desa: $findDetailRegion->id, id: $id_mapbox);

        if (! isset($findDetailRegionMapbox)) {
            throw new Exception(trans('response.error.view', ['data' => 'Titik Jalur Mapbox', 'error' => 'Data Tidak Ditemukan']));
        }

        return $this->regionDetailMapboxListDatatableService->mapboxListDatatable(request: $request, id_mapbox: $findDetailRegionMapbox->id);
    }

    public function renderCreate(Request $request, string $id_provinsi, string $id_desa, string $id_mapbox): View
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

        $findDetailRegionMapbox = $this->regionDetailMapboxQueryService->findMapboxById(id_desa: $findDetailRegion->id, id: $id_mapbox);

        if (! isset($findDetailRegionMapbox)) {
            abort(Response::HTTP_NOT_FOUND);
        }

        return view('admin.pages.home-desa-jalur-mapbox.create', compact('findRegion', 'findDetailRegion', 'findDetailRegionMapbox'));
    }

    public function execStore(HomeDesaMapboxJalurStoreRequest $request, string $id_provinsi, string $id_desa, string $id_mapbox): ?RegionDetailMapboxList
    {
        $findRegion = $this->regionQueryService->findRegionById(id: $id_provinsi);
        if (! isset($findRegion)) {
            throw new Exception(trans('response.error.store', ['data' => 'Titik Jalur Mapbox', 'error' => 'Data Tidak Ditemukan']));
        }

        if ($findRegion->existingApps && $findRegion->existingApps->isNotEmpty()) {
            $arr_existing_app = $findRegion->existingApps->pluck('code')->toArray();
            if (! in_array(AppConst::CODE_EXISTING_APP['02'], $arr_existing_app)) {
                throw new Exception(trans('response.error.store', ['data' => 'Titik Jalur Mapbox', 'error' => 'Data Tidak Ditemukan']));
            }
        }

        $findDetailRegion = $this->regionDetailQueryService->findDetailRegionById(id_provinsi: $findRegion->id, id: $id_desa);
        if (! isset($findDetailRegion)) {
            throw new Exception(trans('response.error.store', ['data' => 'Titik Jalur Mapbox', 'error' => 'Data Tidak Ditemukan']));
        }

        $findDetailRegionMapbox = $this->regionDetailMapboxQueryService->findMapboxById(id_desa: $findDetailRegion->id, id: $id_mapbox);

        if (! isset($findDetailRegionMapbox)) {
            throw new Exception(trans('response.error.store', ['data' => 'Titik Jalur Mapbox', 'error' => 'Data Tidak Ditemukan']));
        }

        $totalData = $this->regionDetailMapboxListQueryService->getAllMapboxList(id_mapbox: $findDetailRegionMapbox->id, isActive: false)->count();
        [$latitude, $longitude] = explode(',', $request->lat_long);

        $request->merge([
            'latitude' => (float) trim($latitude),
            'longitude' => (float) trim($longitude),
            'order_point' => $totalData,
        ]);

        $storeData = $this->regionDetailMapboxListCommandService->store(request: $request, id_mapbox: $findDetailRegionMapbox->id);

        if (! isset($storeData)) {
            throw new Exception(trans('response.error.store', ['data' => 'Titik Jalur Mapbox', 'error' => 'Data Tidak Ditemukan']));
        }

        return $storeData;
    }

    public function renderEdit(Request $request, string $id_provinsi, string $id_desa, string $id_mapbox, string $id): View
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

        $findDetailRegionMapbox = $this->regionDetailMapboxQueryService->findMapboxById(id_desa: $findDetailRegion->id, id: $id_mapbox);

        if (! isset($findDetailRegionMapbox)) {
            abort(Response::HTTP_NOT_FOUND);
        }

        $findDetailRegionMapboxList = $this->regionDetailMapboxListQueryService->findMapboxListById(id_mapbox: $findDetailRegionMapbox->id, id: $id);

        if (! isset($findDetailRegionMapbox)) {
            abort(Response::HTTP_NOT_FOUND);
        }

        return view('admin.pages.home-desa-jalur-mapbox.edit', compact('findRegion', 'findDetailRegion', 'findDetailRegionMapbox', 'findDetailRegionMapboxList'));
    }

    public function execUpdate(HomeDesaMapboxJalurUpdateRequest $request, string $id_provinsi, string $id_desa, string $id_mapbox, string $id): ?RegionDetailMapboxList
    {
        $findRegion = $this->regionQueryService->findRegionById(id: $id_provinsi);
        if (! isset($findRegion)) {
            throw new Exception(trans('response.error.update', ['data' => 'Titik Jalur Mapbox', 'error' => 'Data Tidak Ditemukan']));
        }

        if ($findRegion->existingApps && $findRegion->existingApps->isNotEmpty()) {
            $arr_existing_app = $findRegion->existingApps->pluck('code')->toArray();
            if (! in_array(AppConst::CODE_EXISTING_APP['02'], $arr_existing_app)) {
                throw new Exception(trans('response.error.update', ['data' => 'Titik Jalur Mapbox', 'error' => 'Data Tidak Ditemukan']));
            }
        }

        $findDetailRegion = $this->regionDetailQueryService->findDetailRegionById(id_provinsi: $findRegion->id, id: $id_desa);
        if (! isset($findDetailRegion)) {
            throw new Exception(trans('response.error.update', ['data' => 'Titik Jalur Mapbox', 'error' => 'Data Tidak Ditemukan']));
        }

        $findDetailRegionMapbox = $this->regionDetailMapboxQueryService->findMapboxById(id_desa: $findDetailRegion->id, id: $id_mapbox);

        if (! isset($findDetailRegionMapbox)) {
            throw new Exception(trans('response.error.update', ['data' => 'Titik Jalur Mapbox', 'error' => 'Data Tidak Ditemukan']));
        }

        $findDetailRegionMapboxList = $this->regionDetailMapboxListQueryService->findMapboxListById(id_mapbox: $findDetailRegionMapbox->id, id: $id);

        if (! isset($findDetailRegionMapbox)) {
            throw new Exception(trans('response.error.update', ['data' => 'Titik Jalur Mapbox', 'error' => 'Data Tidak Ditemukan']));
        }

        [$latitude, $longitude] = explode(',', $request->lat_long);

        $request->merge([
            'latitude' => (float) trim($latitude),
            'longitude' => (float) trim($longitude),
        ]);

        $storeData = $this->regionDetailMapboxListCommandService->update(request: $request, regionDetailMapboxList: $findDetailRegionMapboxList);

        if (! isset($storeData)) {
            throw new Exception(trans('response.error.update', ['data' => 'Titik Jalur Mapbox', 'error' => 'Data Tidak Ditemukan']));
        }

        return $storeData;
    }

    public function execSwitch(string $id_provinsi, string $id_desa, string $id_mapbox, string $id): bool
    {
        $findRegion = $this->regionQueryService->findRegionById(id: $id_provinsi);
        if (! isset($findRegion)) {
            throw new Exception(trans('response.error.switch', ['data' => 'Perubahan Status Titik Jalur Mapbox', 'error' => 'Data Tidak Ditemukan']));
        }

        if ($findRegion->existingApps && $findRegion->existingApps->isNotEmpty()) {
            $arr_existing_app = $findRegion->existingApps->pluck('code')->toArray();
            if (! in_array(AppConst::CODE_EXISTING_APP['02'], $arr_existing_app)) {
                throw new Exception(trans('response.error.switch', ['data' => 'Perubahan Status Titik Jalur Mapbox', 'error' => 'Data Tidak Ditemukan']));
            }
        }

        $findDetailRegion = $this->regionDetailQueryService->findDetailRegionById(id_provinsi: $findRegion->id, id: $id_desa);
        if (! isset($findDetailRegion)) {
            throw new Exception(trans('response.error.switch', ['data' => 'Perubahan Status Titik Jalur Mapbox', 'error' => 'Data Tidak Ditemukan']));
        }

        $findDetailRegionMapbox = $this->regionDetailMapboxQueryService->findMapboxById(id_desa: $findDetailRegion->id, id: $id_mapbox);

        if (! isset($findDetailRegionMapbox)) {
            throw new Exception(trans('response.error.switch', ['data' => 'Perubahan Status Titik Jalur Mapbox', 'error' => 'Data Tidak Ditemukan']));
        }

        $findDetailRegionMapboxList = $this->regionDetailMapboxListQueryService->findMapboxListById(id_mapbox: $findDetailRegionMapbox->id, id: $id);

        if (! isset($findDetailRegionMapbox)) {
            throw new Exception(trans('response.error.switch', ['data' => 'Perubahan Status Titik Jalur Mapbox', 'error' => 'Data Tidak Ditemukan']));
        }

        return $this->regionDetailMapboxListCommandService->updateSwitch(regionDetailMapboxList: $findDetailRegionMapboxList);
    }

    public function execDelete(string $id_provinsi, string $id_desa, string $id_mapbox, string $id): bool
    {
        $findRegion = $this->regionQueryService->findRegionById(id: $id_provinsi);
        if (! isset($findRegion)) {
            throw new Exception(trans('response.error.delete', ['data' => 'Titik Jalur Mapbox', 'error' => 'Data Tidak Ditemukan']));
        }

        if ($findRegion->existingApps && $findRegion->existingApps->isNotEmpty()) {
            $arr_existing_app = $findRegion->existingApps->pluck('code')->toArray();
            if (! in_array(AppConst::CODE_EXISTING_APP['02'], $arr_existing_app)) {
                throw new Exception(trans('response.error.delete', ['data' => 'Titik Jalur Mapbox', 'error' => 'Data Tidak Ditemukan']));
            }
        }

        $findDetailRegion = $this->regionDetailQueryService->findDetailRegionById(id_provinsi: $findRegion->id, id: $id_desa);
        if (! isset($findDetailRegion)) {
            throw new Exception(trans('response.error.delete', ['data' => 'Titik Jalur Mapbox', 'error' => 'Data Tidak Ditemukan']));
        }

        $findDetailRegionMapbox = $this->regionDetailMapboxQueryService->findMapboxById(id_desa: $findDetailRegion->id, id: $id_mapbox);

        if (! isset($findDetailRegionMapbox)) {
            throw new Exception(trans('response.error.delete', ['data' => 'Titik Jalur Mapbox', 'error' => 'Data Tidak Ditemukan']));
        }

        $findDetailRegionMapboxList = $this->regionDetailMapboxListQueryService->findMapboxListById(id_mapbox: $findDetailRegionMapbox->id, id: $id);

        if (! isset($findDetailRegionMapbox)) {
            throw new Exception(trans('response.error.delete', ['data' => 'Titik Jalur Mapbox', 'error' => 'Data Tidak Ditemukan']));
        }

        return $this->regionDetailMapboxListCommandService->delete(regionDetailMapboxList: $findDetailRegionMapboxList);
    }
}
