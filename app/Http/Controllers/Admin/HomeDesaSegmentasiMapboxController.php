<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\Json;
use App\Http\Controllers\Controller;
use App\Http\Interfaces\Admin\HomeDesaSegmentasiMapboxInterface;
use App\Http\Requests\Admin\HomeDesaSegmentasiMapbox\HomeDesaSegmentasiMapboxOrderRequest;
use App\Http\Requests\Admin\HomeDesaSegmentasiMapbox\HomeDesaSegmentasiMapboxStoreRequest;
use App\Http\Requests\Admin\HomeDesaSegmentasiMapbox\HomeDesaSegmentasiMapboxUpdateRequest;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Throwable;

final class HomeDesaSegmentasiMapboxController extends Controller
{
    public function __construct(private readonly HomeDesaSegmentasiMapboxInterface $homeDesaSegmentasiMapboxInterface) {}

    public function index(Request $request, string $id_provinsi, string $id_desa): View
    {
        return $this->homeDesaSegmentasiMapboxInterface->renderIndex(request: $request, id_provinsi: $id_provinsi, id_desa: $id_desa);
    }

    public function orderList(Request $request, string $id_provinsi, string $id_desa): JsonResponse
    {
        try {
            DB::beginTransaction();
            $data = $this->homeDesaSegmentasiMapboxInterface->renderOrderList(request: $request, id_provinsi: $id_provinsi, id_desa: $id_desa);
            DB::commit();

            return Json::success(data: $data);
        } catch (Throwable $th) {
            DB::rollback();

            return Json::error(error: $th->getMessage(), httpCode: $th->getCode() ?? Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function orderUpdate(HomeDesaSegmentasiMapboxOrderRequest $request, string $id_provinsi, string $id_desa): RedirectResponse
    {
        try {
            DB::beginTransaction();
            $this->homeDesaSegmentasiMapboxInterface->execOrderUpdate(request: $request, id_provinsi: $id_provinsi, id_desa: $id_desa);
            DB::commit();

            return to_route('admin.home.detail.desa.segmentasi-mapbox.index', ['id_provinsi' => $id_provinsi, 'id_desa' => $id_desa])->with('success', trans('response.success.update', ['data' => 'Titik Mapbox']));
        } catch (Throwable $th) {
            DB::rollBack();

            return to_route('admin.home.detail.desa.segmentasi-mapbox.index', ['id_provinsi' => $id_provinsi, 'id_desa' => $id_desa])->with('error', $th->getMessage());
        }
    }

    public function datatable(Request $request, string $id_provinsi, string $id_desa): JsonResponse
    {
        try {
            DB::beginTransaction();
            $data = $this->homeDesaSegmentasiMapboxInterface->renderDatatable(request: $request, id_provinsi: $id_provinsi, id_desa: $id_desa);
            DB::commit();

            return $data;
        } catch (Throwable $th) {
            DB::rollBack();

            return Json::error(error: $th->getMessage());
        }
    }

    public function create(Request $request, string $id_provinsi, string $id_desa): View
    {
        return $this->homeDesaSegmentasiMapboxInterface->renderCreate(request: $request, id_provinsi: $id_provinsi, id_desa: $id_desa);
    }

    public function store(HomeDesaSegmentasiMapboxStoreRequest $request, string $id_provinsi, string $id_desa): RedirectResponse
    {
        try {
            DB::beginTransaction();
            $this->homeDesaSegmentasiMapboxInterface->execStore(request: $request, id_provinsi: $id_provinsi, id_desa: $id_desa);
            DB::commit();

            return to_route('admin.home.detail.desa.segmentasi-mapbox.index', ['id_provinsi' => $id_provinsi, 'id_desa' => $id_desa])->with('success', trans('response.success.store', ['data' => 'Titik Mapbox']));
        } catch (Throwable $th) {
            DB::rollBack();

            return to_route('admin.home.detail.desa.segmentasi-mapbox.create', ['id_provinsi' => $id_provinsi, 'id_desa' => $id_desa])->with('error', $th->getMessage());
        }
    }

    public function edit(Request $request, string $id_provinsi, string $id_desa, string $id): View
    {
        return $this->homeDesaSegmentasiMapboxInterface->renderEdit(request: $request, id_provinsi: $id_provinsi, id_desa: $id_desa, id: $id);
    }

    public function update(HomeDesaSegmentasiMapboxUpdateRequest $request, string $id_provinsi, string $id_desa, string $id): RedirectResponse
    {
        try {
            DB::beginTransaction();
            $this->homeDesaSegmentasiMapboxInterface->execUpdate(request: $request, id_provinsi: $id_provinsi, id_desa: $id_desa, id: $id);
            DB::commit();

            return to_route('admin.home.detail.desa.segmentasi-mapbox.index', ['id_provinsi' => $id_provinsi, 'id_desa' => $id_desa])->with('success', trans('response.success.update', ['data' => 'Titik Mapbox']));
        } catch (Throwable $th) {
            DB::rollBack();

            return to_route('admin.home.detail.desa.segmentasi-mapbox.edit', ['id_provinsi' => $id_provinsi, 'id_desa' => $id_desa, 'id' => $id])->with('error', $th->getMessage());
        }
    }

    public function switch(string $id_provinsi, string $id_desa, string $id): RedirectResponse
    {
        try {
            DB::beginTransaction();
            $this->homeDesaSegmentasiMapboxInterface->execSwitch(id_provinsi: $id_provinsi, id_desa: $id_desa, id: $id);
            DB::commit();

            return to_route('admin.home.detail.desa.segmentasi-mapbox.index', ['id_provinsi' => $id_provinsi, 'id_desa' => $id_desa])->with('success', trans('response.success.update', ['data' => 'Titik Mapbox']));
        } catch (Throwable $th) {
            DB::rollBack();

            return to_route('admin.home.detail.desa.segmentasi-mapbox.index', ['id_provinsi' => $id_provinsi, 'id_desa' => $id_desa])->with('error', $th->getMessage());
        }
    }

    public function delete(string $id_provinsi, string $id_desa, string $id): RedirectResponse
    {
        try {
            DB::beginTransaction();
            $this->homeDesaSegmentasiMapboxInterface->execDelete(id_provinsi: $id_provinsi, id_desa: $id_desa, id: $id);
            DB::commit();

            return to_route('admin.home.detail.desa.segmentasi-mapbox.index', ['id_provinsi' => $id_provinsi, 'id_desa' => $id_desa])->with('success', trans('response.success.delete', ['data' => 'Titik Mapbox']));
        } catch (Throwable $th) {
            DB::rollBack();

            return to_route('admin.home.detail.desa.segmentasi-mapbox.index', ['id_provinsi' => $id_provinsi, 'id_desa' => $id_desa])->with('error', $th->getMessage());
        }
    }
}
