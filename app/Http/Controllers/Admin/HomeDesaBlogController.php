<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\Json;
use App\Http\Controllers\Controller;
use App\Http\Interfaces\Admin\HomeDesaBlogInterface;
use App\Http\Requests\Admin\HomeDesaBlog\HomeDesaBlogStoreRequest;
use App\Http\Requests\Admin\HomeDesaBlog\HomeDesaBlogUpdateRequest;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

final class HomeDesaBlogController extends Controller
{
    public function __construct(private readonly HomeDesaBlogInterface $homeDesaBlogInterface) {}

    public function index(Request $request, string $id_provinsi): View
    {
        return $this->homeDesaBlogInterface->renderIndex(request: $request, id_provinsi: $id_provinsi);
    }

    public function datatable(Request $request, string $id_provinsi): JsonResponse
    {
        try {
            DB::beginTransaction();
            $data = $this->homeDesaBlogInterface->renderDatatable(request: $request, id_provinsi: $id_provinsi);
            DB::commit();

            return $data;
        } catch (Throwable $th) {
            DB::rollBack();

            return Json::error(error: $th->getMessage());
        }
    }

    public function create(Request $request, string $id_provinsi): View
    {
        return $this->homeDesaBlogInterface->renderCreate(request: $request, id_provinsi: $id_provinsi);
    }

    public function store(HomeDesaBlogStoreRequest $request, string $id_provinsi): RedirectResponse
    {
        try {
            DB::beginTransaction();
            $this->homeDesaBlogInterface->execStore(request: $request, id_provinsi: $id_provinsi);
            DB::commit();

            return to_route('admin.home.detail.blog.index', ['id_provinsi' => $id_provinsi])->with('success', trans('response.success.store', ['data' => 'Artikel Wilayah']));
        } catch (Throwable $th) {
            DB::rollBack();

            return to_route('admin.home.detail.blog.create', ['id_provinsi' => $id_provinsi])->with('error', $th->getMessage());
        }
    }

    public function edit(Request $request, string $id_provinsi, string $id): View
    {
        return $this->homeDesaBlogInterface->renderEdit(request: $request, id_provinsi: $id_provinsi, id: $id);
    }

    public function updateContent(HomeDesaBlogUpdateRequest $request, string $id_provinsi, string $id): RedirectResponse
    {
        try {
            DB::beginTransaction();
            $this->homeDesaBlogInterface->execUpdateContent(request: $request, id_provinsi: $id_provinsi, id: $id);
            DB::commit();

            return to_route('admin.home.detail.blog.index', ['id_provinsi' => $id_provinsi])->with('success', trans('response.success.update', ['data' => 'Artikel Wilayah']));
        } catch (Throwable $th) {
            DB::rollBack();

            return to_route('admin.home.detail.blog.edit', ['id_provinsi' => $id_provinsi, 'id' => $id])->with('error', $th->getMessage());
        }
    }

    public function updateActive(string $id_provinsi, string $id): RedirectResponse
    {
        try {
            DB::beginTransaction();
            $this->homeDesaBlogInterface->execUpdateActive(id_provinsi: $id_provinsi, id: $id);
            DB::commit();

            return to_route('admin.home.detail.blog.index', ['id_provinsi' => $id_provinsi])->with('success', trans('response.success.switch', ['data' => 'Perubahan Status Artikel Wilayah']));
        } catch (Throwable $th) {
            DB::rollBack();

            return to_route('admin.home.detail.blog.index', ['id_provinsi' => $id_provinsi])->with('error', $th->getMessage());
        }
    }

    public function updateGeneralBlog(string $id_provinsi, string $id): RedirectResponse
    {
        try {
            DB::beginTransaction();
            $this->homeDesaBlogInterface->execUpdateGeneralBlog(id_provinsi: $id_provinsi, id: $id);
            DB::commit();

            return to_route('admin.home.detail.blog.index', ['id_provinsi' => $id_provinsi])->with('success', trans('response.success.switch', ['data' => 'Perubahan Status Artikel Wilayah']));
        } catch (Throwable $th) {
            DB::rollBack();

            return to_route('admin.home.detail.blog.index', ['id_provinsi' => $id_provinsi])->with('error', $th->getMessage());
        }
    }

    public function delete(Request $request, string $id_provinsi, string $id): RedirectResponse
    {
        try {
            DB::beginTransaction();
            $this->homeDesaBlogInterface->execDelete(request: $request, id_provinsi: $id_provinsi, id: $id);
            DB::commit();

            return to_route('admin.home.detail.blog.index', ['id_provinsi' => $id_provinsi])->with('success', trans('response.success.delete', ['data' => 'Artikel Wilayah']));
        } catch (Throwable $th) {
            DB::rollBack();

            return to_route('admin.home.detail.blog.index', ['id_provinsi' => $id_provinsi])->with('error', $th->getMessage());
        }
    }
}
