<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\Json;
use App\Http\Controllers\Controller;
use App\Http\Interfaces\Admin\HomeDesaInterface;
use App\Http\Requests\Admin\HomeDesa\HomeDesaStoreRequest;
use App\Http\Requests\Admin\HomeDesa\HomeDesaUpdateRequest;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

final class HomeDesaController extends Controller
{
    public function __construct(private readonly HomeDesaInterface $homeDesaInterface) {}

    public function index(Request $request, string $id_provinsi): View|RedirectResponse
    {
        return $this->homeDesaInterface->renderIndex(request: $request, id_provinsi: $id_provinsi);
    }

    public function create(Request $request, string $id_provinsi): View
    {
        return $this->homeDesaInterface->renderCreate(request: $request, id_provinsi: $id_provinsi);
    }

    public function edit(string $id_provinsi, string $id): View
    {
        return $this->homeDesaInterface->renderEdit(id_provinsi: $id_provinsi, id: $id);
    }

    public function store(HomeDesaStoreRequest $request, string $id_provinsi): RedirectResponse
    {
        try {
            DB::beginTransaction();
            $this->homeDesaInterface->execStore(request: $request, id_provinsi: $id_provinsi);
            DB::commit();

            return to_route('admin.home.detail.desa.index', ['id_provinsi' => $id_provinsi])->with('success', trans('response.success.store', ['data' => 'Desa/Kelurahan']));
        } catch (Throwable $th) {
            DB::rollBack();

            return to_route('admin.home.detail.desa.index', ['id_provinsi' => $id_provinsi])->with('error', $th->getMessage());
        }
    }

    public function update(HomeDesaUpdateRequest $request, string $id_provinsi, string $id): RedirectResponse
    {
        try {
            DB::beginTransaction();
            $this->homeDesaInterface->execUpdate(request: $request, id_provinsi: $id_provinsi, id: $id);
            DB::commit();

            return to_route('admin.home.detail.desa.index', ['id_provinsi' => $id_provinsi])->with('success', trans('response.success.update', ['data' => 'Desa/Kelurahan']));
        } catch (Throwable $th) {
            DB::rollBack();

            return to_route('admin.home.detail.desa.edit', ['id_provinsi' => $id_provinsi, 'id' => $id])->with('error', $th->getMessage());
        }
    }

    public function datatable(Request $request, string $id_provinsi): JsonResponse
    {
        try {
            DB::beginTransaction();
            $data = $this->homeDesaInterface->renderDatatable(request: $request, id_provinsi: $id_provinsi);
            DB::commit();

            return $data;
        } catch (Throwable $th) {
            DB::rollBack();

            return Json::error(error: $th->getMessage());
        }
    }

    public function switch(string $id_provinsi, string $id): RedirectResponse
    {
        try {
            DB::beginTransaction();
            $data = $this->homeDesaInterface->execSwitch(id_provinsi: $id_provinsi, id: $id);
            DB::commit();

            return to_route('admin.home.detail.desa.index', ['id_provinsi' => $id_provinsi])->with('success', trans('response.success.switch', ['data' => 'Perubahan Status Desa/Kelurahan']));
        } catch (Throwable $th) {
            DB::rollBack();

            return to_route('admin.home.detail.desa.index', ['id_provinsi' => $id_provinsi])->with('error', $th->getMessage());
        }
    }

    public function delete(string $id_provinsi, string $id): RedirectResponse
    {
        try {
            DB::beginTransaction();
            $data = $this->homeDesaInterface->execDelete(id_provinsi: $id_provinsi, id: $id);
            DB::commit();

            return to_route('admin.home.detail.desa.index', ['id_provinsi' => $id_provinsi])->with('success', trans('response.success.delete', ['data' => 'Status Desa/Kelurahan']));
        } catch (Throwable $th) {
            DB::rollBack();

            return to_route('admin.home.detail.desa.index', ['id_provinsi' => $id_provinsi])->with('error', $th->getMessage());
        }
    }
}
