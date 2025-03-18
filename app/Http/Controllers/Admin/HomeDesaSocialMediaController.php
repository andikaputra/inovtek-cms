<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\Json;
use App\Http\Controllers\Controller;
use App\Http\Interfaces\Admin\HomeDesaSocialMediaInterface;
use App\Http\Requests\Admin\HomeDesaSocialMedia\HomeDesaSocialMediaStoreRequest;
use App\Http\Requests\Admin\HomeDesaSocialMedia\HomeDesaSocialMediaUpdateRequest;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

final class HomeDesaSocialMediaController extends Controller
{
    public function __construct(private readonly HomeDesaSocialMediaInterface $homeDesaSocialMediaInterface) {}

    public function index(Request $request, string $id_provinsi): View
    {
        return $this->homeDesaSocialMediaInterface->renderIndex(request: $request, id_provinsi: $id_provinsi);
    }

    public function datatable(Request $request, string $id_provinsi): JsonResponse
    {
        try {
            DB::beginTransaction();
            $data = $this->homeDesaSocialMediaInterface->renderDatatable(request: $request, id_provinsi: $id_provinsi);
            DB::commit();

            return $data;
        } catch (Throwable $th) {
            DB::rollBack();

            return Json::error(error: $th->getMessage());
        }
    }

    public function create(Request $request, string $id_provinsi): View
    {
        return $this->homeDesaSocialMediaInterface->renderCreate(request: $request, id_provinsi: $id_provinsi);
    }

    public function store(HomeDesaSocialMediaStoreRequest $request, string $id_provinsi): RedirectResponse
    {
        try {
            DB::beginTransaction();
            $this->homeDesaSocialMediaInterface->execStore(request: $request, id_provinsi: $id_provinsi);
            DB::commit();

            return to_route('admin.home.detail.sosial-media.index', ['id_provinsi' => $id_provinsi])->with('success', trans('response.success.store', ['data' => 'Sosial Media Wilayah']));
        } catch (Throwable $th) {
            DB::rollBack();

            return to_route('admin.home.detail.sosial-media.create', ['id_provinsi' => $id_provinsi])->with('error', $th->getMessage());
        }
    }

    public function edit(Request $request, string $id_provinsi, string $id): View
    {
        return $this->homeDesaSocialMediaInterface->renderEdit(request: $request, id_provinsi: $id_provinsi, id: $id);
    }

    public function updateContent(HomeDesaSocialMediaUpdateRequest $request, string $id_provinsi, string $id): RedirectResponse
    {
        try {
            DB::beginTransaction();
            $this->homeDesaSocialMediaInterface->execUpdateContent(request: $request, id_provinsi: $id_provinsi, id: $id);
            DB::commit();

            return to_route('admin.home.detail.sosial-media.index', ['id_provinsi' => $id_provinsi])->with('success', trans('response.success.update', ['data' => 'Sosial Media Wilayah']));
        } catch (Throwable $th) {
            DB::rollBack();

            return to_route('admin.home.detail.sosial-media.edit', ['id_provinsi' => $id_provinsi, 'id' => $id])->with('error', $th->getMessage());
        }
    }

    public function updateActive(string $id_provinsi, string $id): RedirectResponse
    {
        try {
            DB::beginTransaction();
            $this->homeDesaSocialMediaInterface->execUpdateActive(id_provinsi: $id_provinsi, id: $id);
            DB::commit();

            return to_route('admin.home.detail.sosial-media.index', ['id_provinsi' => $id_provinsi])->with('success', trans('response.success.switch', ['data' => 'Perubahan Status Sosial Media Wilayah']));
        } catch (Throwable $th) {
            DB::rollBack();

            return to_route('admin.home.detail.sosial-media.index', ['id_provinsi' => $id_provinsi])->with('error', $th->getMessage());
        }
    }

    public function delete(Request $request, string $id_provinsi, string $id): RedirectResponse
    {
        try {
            DB::beginTransaction();
            $this->homeDesaSocialMediaInterface->execDelete(request: $request, id_provinsi: $id_provinsi, id: $id);
            DB::commit();

            return to_route('admin.home.detail.sosial-media.index', ['id_provinsi' => $id_provinsi])->with('success', trans('response.success.delete', ['data' => 'Sosial Media Wilayah']));
        } catch (Throwable $th) {
            DB::rollBack();

            return to_route('admin.home.detail.sosial-media.index', ['id_provinsi' => $id_provinsi])->with('error', $th->getMessage());
        }
    }
}
