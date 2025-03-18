<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\Json;
use App\Http\Controllers\Controller;
use App\Http\Interfaces\Admin\HomeDesaQuizLinkInterface;
use App\Http\Requests\Admin\HomeDesaQuiz\HomeDesaQuizLinkStoreRequest;
use App\Http\Requests\Admin\HomeDesaQuiz\HomeDesaQuizLinkUpdateRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Throwable;

final class HomeDesaQuizLinkController extends Controller
{
    public function __construct(private readonly HomeDesaQuizLinkInterface $homeDesaQuizInterface) {}

    public function index(Request $request, string $id_provinsi): View
    {
        return $this->homeDesaQuizInterface->renderIndex(request: $request, id_provinsi: $id_provinsi);
    }

    public function datatable(Request $request, string $id_provinsi): JsonResponse
    {
        try {
            DB::beginTransaction();
            $data = $this->homeDesaQuizInterface->renderDatatable(request: $request, id_provinsi: $id_provinsi);
            DB::commit();

            return $data;
        } catch (Throwable $th) {
            DB::rollBack();

            return Json::error(error: $th->getMessage());
        }
    }

    public function create(Request $request, string $id_provinsi): View
    {
        return $this->homeDesaQuizInterface->renderCreate(request: $request, id_provinsi: $id_provinsi);
    }

    public function store(HomeDesaQuizLinkStoreRequest $request, string $id_provinsi): RedirectResponse
    {
        try {
            DB::beginTransaction();
            $this->homeDesaQuizInterface->execStore(request: $request, id_provinsi: $id_provinsi);
            DB::commit();

            return to_route('admin.home.detail.kuis.index', ['id_provinsi' => $id_provinsi])->with('success', trans('response.success.store', ['data' => 'Kuis Wilayah']));
        } catch (Throwable $th) {
            DB::rollBack();

            return to_route('admin.home.detail.kuis.create', ['id_provinsi' => $id_provinsi])->with('error', $th->getMessage());
        }
    }

    public function edit(Request $request, string $id_provinsi, string $id): View
    {
        return $this->homeDesaQuizInterface->renderEdit(request: $request, id_provinsi: $id_provinsi, id: $id);
    }

    public function updateContent(HomeDesaQuizLinkUpdateRequest $request, string $id_provinsi, string $id): RedirectResponse
    {
        try {
            DB::beginTransaction();
            $this->homeDesaQuizInterface->execUpdateContent(request: $request, id_provinsi: $id_provinsi, id: $id);
            DB::commit();

            return to_route('admin.home.detail.kuis.index', ['id_provinsi' => $id_provinsi])->with('success', trans('response.success.update', ['data' => 'Kuis Wilayah']));
        } catch (Throwable $th) {
            DB::rollBack();

            return to_route('admin.home.detail.kuis.edit', ['id_provinsi' => $id_provinsi, 'id' => $id])->with('error', $th->getMessage());
        }
    }

    public function updateActive(string $id_provinsi, string $id): RedirectResponse
    {
        try {
            DB::beginTransaction();
            $this->homeDesaQuizInterface->execUpdateActive(id_provinsi: $id_provinsi, id: $id);
            DB::commit();

            return to_route('admin.home.detail.kuis.index', ['id_provinsi' => $id_provinsi])->with('success', trans('response.success.switch', ['data' => 'Perubahan Status Kuis Wilayah']));
        } catch (Throwable $th) {
            DB::rollBack();

            return to_route('admin.home.detail.kuis.index', ['id_provinsi' => $id_provinsi])->with('error', $th->getMessage());
        }
    }

    public function delete(Request $request, string $id_provinsi, string $id): RedirectResponse
    {
        try {
            DB::beginTransaction();
            $this->homeDesaQuizInterface->execDelete(request: $request, id_provinsi: $id_provinsi, id: $id);
            DB::commit();

            return to_route('admin.home.detail.kuis.index', ['id_provinsi' => $id_provinsi])->with('success', trans('response.success.delete', ['data' => 'Kuis Wilayah']));
        } catch (Throwable $th) {
            DB::rollBack();

            return to_route('admin.home.detail.kuis.index', ['id_provinsi' => $id_provinsi])->with('error', $th->getMessage());
        }
    }
}
