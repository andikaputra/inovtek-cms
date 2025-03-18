<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\Json;
use App\Http\Controllers\Controller;
use App\Http\Interfaces\Admin\HomeDesaLinkInterface;
use App\Http\Requests\Admin\HomeDesaLink\HomeDesaLinkStoreRequest;
use App\Http\Requests\Admin\HomeDesaLink\HomeDesaLinkUpdateRequest;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

final class HomeDesaLinkController extends Controller
{
    public function __construct(private readonly HomeDesaLinkInterface $homeDesaLinkInterface) {}

    public function index(Request $request, string $id_provinsi): View
    {
        return $this->homeDesaLinkInterface->renderIndex(request: $request, id_provinsi: $id_provinsi);
    }

    public function datatable(Request $request, string $id_provinsi): JsonResponse
    {
        try {
            DB::beginTransaction();
            $data = $this->homeDesaLinkInterface->renderDatatable(request: $request, id_provinsi: $id_provinsi);
            DB::commit();

            return $data;
        } catch (Throwable $th) {
            DB::rollBack();

            return Json::error(error: $th->getMessage());
        }
    }

    public function create(Request $request, string $id_provinsi): View
    {
        return $this->homeDesaLinkInterface->renderCreate(request: $request, id_provinsi: $id_provinsi);
    }

    public function store(HomeDesaLinkStoreRequest $request, string $id_provinsi): RedirectResponse
    {
        try {
            DB::beginTransaction();
            $this->homeDesaLinkInterface->execStore(request: $request, id_provinsi: $id_provinsi);
            DB::commit();

            return to_route('admin.home.detail.link.index', ['id_provinsi' => $id_provinsi])->with('success', trans('response.success.store', ['data' => 'Tautan Wilayah']));
        } catch (Throwable $th) {
            DB::rollBack();

            return to_route('admin.home.detail.link.create', ['id_provinsi' => $id_provinsi])->with('error', $th->getMessage());
        }
    }

    public function edit(Request $request, string $id_provinsi, string $id): View
    {
        return $this->homeDesaLinkInterface->renderEdit(request: $request, id_provinsi: $id_provinsi, id: $id);
    }

    public function updateContent(HomeDesaLinkUpdateRequest $request, string $id_provinsi, string $id): RedirectResponse
    {
        try {
            DB::beginTransaction();
            $this->homeDesaLinkInterface->execUpdateContent(request: $request, id_provinsi: $id_provinsi, id: $id);
            DB::commit();

            return to_route('admin.home.detail.link.index', ['id_provinsi' => $id_provinsi])->with('success', trans('response.success.update', ['data' => 'Tautan Wilayah']));
        } catch (Throwable $th) {
            DB::rollBack();

            return to_route('admin.home.detail.link.edit', ['id_provinsi' => $id_provinsi, 'id' => $id])->with('error', $th->getMessage());
        }
    }

    public function updateActive(string $id_provinsi, string $id): RedirectResponse
    {
        try {
            DB::beginTransaction();
            $this->homeDesaLinkInterface->execUpdateActive(id_provinsi: $id_provinsi, id: $id);
            DB::commit();

            return to_route('admin.home.detail.link.index', ['id_provinsi' => $id_provinsi])->with('success', trans('response.success.switch', ['data' => 'Perubahan Status Tautan Wilayah']));
        } catch (Throwable $th) {
            DB::rollBack();

            return to_route('admin.home.detail.link.index', ['id_provinsi' => $id_provinsi])->with('error', $th->getMessage());
        }
    }

    public function delete(Request $request, string $id_provinsi, string $id): RedirectResponse
    {
        try {
            DB::beginTransaction();
            $this->homeDesaLinkInterface->execDelete(request: $request, id_provinsi: $id_provinsi, id: $id);
            DB::commit();

            return to_route('admin.home.detail.link.index', ['id_provinsi' => $id_provinsi])->with('success', trans('response.success.delete', ['data' => 'Tautan Wilayah']));
        } catch (Throwable $th) {
            DB::rollBack();

            return to_route('admin.home.detail.link.index', ['id_provinsi' => $id_provinsi])->with('error', $th->getMessage());
        }
    }
}
