<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\Json;
use App\Http\Controllers\Controller;
use App\Http\Interfaces\Admin\HomeDesaAnnouncementInterface;
use App\Http\Requests\Admin\HomeDesaAnnouncement\HomeDesaAnnouncementStoreRequest;
use App\Http\Requests\Admin\HomeDesaAnnouncement\HomeDesaAnnouncementUpdateRequest;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

final class HomeDesaAnnouncementController extends Controller
{
    public function __construct(private readonly HomeDesaAnnouncementInterface $homeDesaAnnouncementInterface) {}

    public function index(Request $request, string $id_provinsi): View
    {
        return $this->homeDesaAnnouncementInterface->renderIndex(request: $request, id_provinsi: $id_provinsi);
    }

    public function datatable(Request $request, string $id_provinsi): JsonResponse
    {
        try {
            DB::beginTransaction();
            $data = $this->homeDesaAnnouncementInterface->renderDatatable(request: $request, id_provinsi: $id_provinsi);
            DB::commit();

            return $data;
        } catch (Throwable $th) {
            DB::rollBack();

            return Json::error(error: $th->getMessage());
        }
    }

    public function create(Request $request, string $id_provinsi): View
    {
        return $this->homeDesaAnnouncementInterface->renderCreate(request: $request, id_provinsi: $id_provinsi);
    }

    public function store(HomeDesaAnnouncementStoreRequest $request, string $id_provinsi): RedirectResponse
    {
        try {
            DB::beginTransaction();
            $this->homeDesaAnnouncementInterface->execStore(request: $request, id_provinsi: $id_provinsi);
            DB::commit();

            return to_route('admin.home.detail.pengumuman.index', ['id_provinsi' => $id_provinsi])->with('success', trans('response.success.store', ['data' => 'Pengumuman']));
        } catch (Throwable $th) {
            DB::rollBack();

            return to_route('admin.home.detail.pengumuman.create', ['id_provinsi' => $id_provinsi])->with('error', $th->getMessage());
        }
    }

    public function edit(Request $request, string $id_provinsi, string $id): View
    {
        return $this->homeDesaAnnouncementInterface->renderEdit(request: $request, id_provinsi: $id_provinsi, id: $id);
    }

    public function updateContent(HomeDesaAnnouncementUpdateRequest $request, string $id_provinsi, string $id): RedirectResponse
    {
        try {
            DB::beginTransaction();
            $this->homeDesaAnnouncementInterface->execUpdateContent(request: $request, id_provinsi: $id_provinsi, id: $id);
            DB::commit();

            return to_route('admin.home.detail.pengumuman.index', ['id_provinsi' => $id_provinsi])->with('success', trans('response.success.update', ['data' => 'Pengumuman']));
        } catch (Throwable $th) {
            DB::rollBack();

            return to_route('admin.home.detail.pengumuman.edit', ['id_provinsi' => $id_provinsi, 'id' => $id])->with('error', $th->getMessage());
        }
    }

    public function updateActive(string $id_provinsi, string $id): RedirectResponse
    {
        try {
            DB::beginTransaction();
            $this->homeDesaAnnouncementInterface->execUpdateActive(id_provinsi: $id_provinsi, id: $id);
            DB::commit();

            return to_route('admin.home.detail.pengumuman.index', ['id_provinsi' => $id_provinsi])->with('success', trans('response.success.switch', ['data' => 'Perubahan Status Pengumuman']));
        } catch (Throwable $th) {
            DB::rollBack();

            return to_route('admin.home.detail.pengumuman.index', ['id_provinsi' => $id_provinsi])->with('error', $th->getMessage());
        }
    }

    public function delete(Request $request, string $id_provinsi, string $id): RedirectResponse
    {
        try {
            DB::beginTransaction();
            $this->homeDesaAnnouncementInterface->execDelete(request: $request, id_provinsi: $id_provinsi, id: $id);
            DB::commit();

            return to_route('admin.home.detail.pengumuman.index', ['id_provinsi' => $id_provinsi])->with('success', trans('response.success.delete', ['data' => 'Pengumuman']));
        } catch (Throwable $th) {
            DB::rollBack();

            return to_route('admin.home.detail.pengumuman.index', ['id_provinsi' => $id_provinsi])->with('error', $th->getMessage());
        }
    }
}
