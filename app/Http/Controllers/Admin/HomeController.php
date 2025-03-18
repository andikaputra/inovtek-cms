<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\Json;
use App\Http\Controllers\Controller;
use App\Http\Interfaces\Admin\HomeInterface;
use App\Http\Requests\Admin\Home\HomeStoreRequest;
use App\Http\Requests\Admin\Home\HomeUpdateRequest;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Throwable;

final class HomeController extends Controller
{
    public function __construct(private readonly HomeInterface $homeInterface) {}

    public function index(Request $request): View
    {
        return $this->homeInterface->renderIndex(request: $request);
    }

    public function create(Request $request): View
    {
        return $this->homeInterface->renderCreate(request: $request);
    }

    public function store(HomeStoreRequest $request): RedirectResponse
    {
        try {
            DB::beginTransaction();
            $this->homeInterface->execStore(request: $request);
            DB::commit();

            return to_route('admin.home.index')->with('success', trans('response.success.store', ['data' => 'Wilayah']));
        } catch (Throwable $th) {
            DB::rollBack();

            return to_route('admin.home.create')->with('error', $th->getMessage());
        }
    }

    public function readNotification(Request $request): JsonResponse
    {
        try {
            DB::beginTransaction();
            $this->homeInterface->execReadNotification(request: $request);
            DB::commit();

            return Json::success(data: 'Success');
        } catch (Throwable $th) {
            DB::rollback();

            return Json::error(error: $th->getMessage(), httpCode: $th->getCode() ?? Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function edit(Request $request, string $id): View
    {
        return $this->homeInterface->renderEdit(request: $request, id: $id);
    }

    public function update(HomeUpdateRequest $request, string $id): RedirectResponse
    {
        try {
            DB::beginTransaction();
            $this->homeInterface->execUpdate(request: $request, id: $id);
            DB::commit();

            return to_route('admin.home.detail.setting-wilayah.edit', ['id_provinsi' => $id])->with('success', trans('response.success.update', ['data' => 'Wilayah']));
        } catch (Throwable $th) {
            DB::rollBack();

            return to_route('admin.home.detail.setting-wilayah.edit', ['id_provinsi' => $id])->with('error', $th->getMessage());
        }
    }

    public function switch(string $id): RedirectResponse
    {
        try {
            DB::beginTransaction();
            $this->homeInterface->execSwitch(id: $id);
            DB::commit();

            return to_route('admin.home.detail.setting-wilayah.edit', ['id_provinsi' => $id])->with('success', trans('response.success.switch', ['data' => 'Perubahan status wilayah']));
        } catch (Throwable $th) {
            DB::rollBack();

            return to_route('admin.home.detail.setting-wilayah.edit', ['id_provinsi' => $id])->with('error', $th->getMessage());
        }
    }

    public function delete(string $id): RedirectResponse
    {
        try {
            DB::beginTransaction();
            $this->homeInterface->execDelete(id: $id);
            DB::commit();

            return to_route('admin.home.index')->with('success', trans('response.success.delete', ['data' => 'Wilayah']));
        } catch (Throwable $th) {
            DB::rollBack();

            return to_route('admin.home.detail.setting-wilayah.edit', ['id_provinsi' => $id])->with('error', $th->getMessage());
        }
    }
}
