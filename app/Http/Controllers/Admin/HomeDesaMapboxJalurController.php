<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\Json;
use App\Http\Controllers\Controller;
use App\Http\Interfaces\Admin\HomeDesaMapboxJalurInterface;
use App\Http\Requests\Admin\HomeDesaMapboxJalur\HomeDesaMapboxJalurOrderRequest;
use App\Http\Requests\Admin\HomeDesaMapboxJalur\HomeDesaMapboxJalurStoreRequest;
use App\Http\Requests\Admin\HomeDesaMapboxJalur\HomeDesaMapboxJalurUpdateRequest;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Throwable;

final class HomeDesaMapboxJalurController extends Controller
{
    public function __construct(private readonly HomeDesaMapboxJalurInterface $homeDesaMapboxJalurInterface) {}

    public function index(Request $request, string $id_provinsi, string $id_desa, string $id_mapbox): View
    {
        return $this->homeDesaMapboxJalurInterface->renderIndex(request: $request, id_provinsi: $id_provinsi, id_desa: $id_desa, id_mapbox: $id_mapbox);
    }

    public function orderList(Request $request, string $id_provinsi, string $id_desa, string $id_mapbox): JsonResponse
    {
        try {
            DB::beginTransaction();
            $data = $this->homeDesaMapboxJalurInterface->renderOrderList(request: $request, id_provinsi: $id_provinsi, id_desa: $id_desa, id_mapbox: $id_mapbox);
            DB::commit();

            return Json::success(data: $data);
        } catch (Throwable $th) {
            DB::rollback();

            return Json::error(error: $th->getMessage(), httpCode: $th->getCode() ?? Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function orderUpdate(HomeDesaMapboxJalurOrderRequest $request, string $id_provinsi, string $id_desa, string $id_mapbox): RedirectResponse
    {
        try {
            DB::beginTransaction();
            $this->homeDesaMapboxJalurInterface->execOrderUpdate(request: $request, id_provinsi: $id_provinsi, id_desa: $id_desa, id_mapbox: $id_mapbox);
            DB::commit();

            return to_route('admin.home.detail.desa.segmentasi-mapbox.jalur.index', ['id_provinsi' => $id_provinsi, 'id_desa' => $id_desa, 'id_mapbox' => $id_mapbox])->with('success', trans('response.success.update', ['data' => 'Titik Jalur']));
        } catch (Throwable $th) {
            DB::rollBack();

            return to_route('admin.home.detail.desa.segmentasi-mapbox.jalur.index', ['id_provinsi' => $id_provinsi, 'id_desa' => $id_desa, 'id_mapbox' => $id_mapbox])->with('error', $th->getMessage());
        }
    }

    public function datatable(Request $request, string $id_provinsi, string $id_desa, string $id_mapbox): JsonResponse
    {
        try {
            DB::beginTransaction();
            $data = $this->homeDesaMapboxJalurInterface->renderDatatable(request: $request, id_provinsi: $id_provinsi, id_desa: $id_desa, id_mapbox: $id_mapbox);
            DB::commit();

            return $data;
        } catch (Throwable $th) {
            DB::rollBack();

            return Json::error(error: $th->getMessage());
        }
    }

    public function create(Request $request, string $id_provinsi, string $id_desa, string $id_mapbox): View
    {
        return $this->homeDesaMapboxJalurInterface->renderCreate(request: $request, id_provinsi: $id_provinsi, id_desa: $id_desa, id_mapbox: $id_mapbox);
    }

    public function store(HomeDesaMapboxJalurStoreRequest $request, string $id_provinsi, string $id_desa, string $id_mapbox): RedirectResponse
    {
        try {
            DB::beginTransaction();
            $this->homeDesaMapboxJalurInterface->execStore(request: $request, id_provinsi: $id_provinsi, id_desa: $id_desa, id_mapbox: $id_mapbox);
            DB::commit();

            return to_route('admin.home.detail.desa.segmentasi-mapbox.jalur.index', ['id_provinsi' => $id_provinsi, 'id_desa' => $id_desa, 'id_mapbox' => $id_mapbox])->with('success', trans('response.success.store', ['data' => 'Titik Jalur Mapbox']));
        } catch (Throwable $th) {
            DB::rollBack();

            return to_route('admin.home.detail.desa.segmentasi-mapbox.jalur.create', ['id_provinsi' => $id_provinsi, 'id_desa' => $id_desa, 'id_mapbox' => $id_mapbox])->with('error', $th->getMessage());
        }
    }

    public function edit(Request $request, string $id_provinsi, string $id_desa, string $id_mapbox, string $id): View
    {
        return $this->homeDesaMapboxJalurInterface->renderEdit(request: $request, id_provinsi: $id_provinsi, id_desa: $id_desa, id_mapbox: $id_mapbox, id: $id);
    }

    public function update(HomeDesaMapboxJalurUpdateRequest $request, string $id_provinsi, string $id_desa, string $id_mapbox, string $id): RedirectResponse
    {
        try {
            DB::beginTransaction();
            $this->homeDesaMapboxJalurInterface->execUpdate(request: $request, id_provinsi: $id_provinsi, id_desa: $id_desa, id_mapbox: $id_mapbox, id: $id);
            DB::commit();

            return to_route('admin.home.detail.desa.segmentasi-mapbox.jalur.index', ['id_provinsi' => $id_provinsi, 'id_desa' => $id_desa, 'id_mapbox' => $id_mapbox])->with('success', trans('response.success.update', ['data' => 'Titik Jalur Mapbox']));
        } catch (Throwable $th) {
            DB::rollBack();

            return to_route('admin.home.detail.desa.segmentasi-mapbox.jalur.edit', ['id_provinsi' => $id_provinsi, 'id_desa' => $id_desa, 'id_mapbox' => $id_mapbox, 'id' => $id])->with('error', $th->getMessage());
        }
    }

    public function switch(string $id_provinsi, string $id_desa, string $id_mapbox, string $id): RedirectResponse
    {
        try {
            DB::beginTransaction();
            $this->homeDesaMapboxJalurInterface->execSwitch(id_provinsi: $id_provinsi, id_desa: $id_desa, id_mapbox: $id_mapbox, id: $id);
            DB::commit();

            return to_route('admin.home.detail.desa.segmentasi-mapbox.jalur.index', ['id_provinsi' => $id_provinsi, 'id_desa' => $id_desa, 'id_mapbox' => $id_mapbox])->with('success', trans('response.success.update', ['data' => 'Titik Jalur Mapbox']));
        } catch (Throwable $th) {
            DB::rollBack();

            return to_route('admin.home.detail.desa.segmentasi-mapbox.jalur.index', ['id_provinsi' => $id_provinsi, 'id_desa' => $id_desa, 'id_mapbox' => $id_mapbox])->with('error', $th->getMessage());
        }
    }

    public function delete(string $id_provinsi, string $id_desa, string $id_mapbox, string $id): RedirectResponse
    {
        try {
            DB::beginTransaction();
            $this->homeDesaMapboxJalurInterface->execDelete(id_provinsi: $id_provinsi, id_desa: $id_desa, id_mapbox: $id_mapbox, id: $id);
            DB::commit();

            return to_route('admin.home.detail.desa.segmentasi-mapbox.jalur.index', ['id_provinsi' => $id_provinsi, 'id_desa' => $id_desa, 'id_mapbox' => $id_mapbox])->with('success', trans('response.success.delete', ['data' => 'Titik Jalur Mapbox']));
        } catch (Throwable $th) {
            DB::rollBack();

            return to_route('admin.home.detail.desa.segmentasi-mapbox.jalur.index', ['id_provinsi' => $id_provinsi, 'id_desa' => $id_desa, 'id_mapbox' => $id_mapbox])->with('error', $th->getMessage());
        }
    }
}
