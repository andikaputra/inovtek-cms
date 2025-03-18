<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\Json;
use App\Http\Controllers\Controller;
use App\Http\Interfaces\Admin\ShortLinkInterface;
use App\Http\Requests\Admin\ShortLink\ShortLinkStoreRequest;
use App\Http\Requests\Admin\ShortLink\ShortLinkUpdateRequest;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

final class ShortLinkController extends Controller
{
    public function __construct(private readonly ShortLinkInterface $shortLinkInterface) {}

    public function index(Request $request): View
    {
        return $this->shortLinkInterface->renderIndex(request: $request);
    }

    public function datatable(Request $request): JsonResponse
    {
        try {
            DB::beginTransaction();
            $data = $this->shortLinkInterface->renderDatatable(request: $request);
            DB::commit();

            return $data;
        } catch (Throwable $th) {
            DB::rollBack();

            return Json::error(error: $th->getMessage());
        }
    }

    public function create(Request $request): View
    {
        return $this->shortLinkInterface->renderCreate(request: $request);
    }

    public function store(ShortLinkStoreRequest $request): RedirectResponse
    {
        try {
            DB::beginTransaction();
            $this->shortLinkInterface->execStore(request: $request);
            DB::commit();

            return to_route('admin.short-link.index')->with('success', trans('response.success.store', ['data' => 'Short Link']));
        } catch (Throwable $th) {
            DB::rollBack();

            return to_route('admin.short-link.create')->with('error', $th->getMessage());
        }
    }

    public function edit(Request $request, string $id): View
    {
        return $this->shortLinkInterface->renderEdit(request: $request, id: $id);
    }

    public function update(ShortLinkUpdateRequest $request, string $id): RedirectResponse
    {
        try {
            DB::beginTransaction();
            $this->shortLinkInterface->execUpdate(request: $request, id: $id);
            DB::commit();

            return to_route('admin.short-link.index')->with('success', trans('response.success.update', ['data' => 'Short Link']));
        } catch (Throwable $th) {
            DB::rollBack();

            return to_route('admin.short-link.edit', ['id' => $id])->with('error', $th->getMessage());
        }
    }

    public function delete(Request $request, string $id): RedirectResponse
    {
        try {
            DB::beginTransaction();
            $this->shortLinkInterface->execDelete(request: $request, id: $id);
            DB::commit();

            return to_route('admin.short-link.index')->with('success', trans('response.success.delete', ['data' => 'Short Link']));
        } catch (Throwable $th) {
            DB::rollBack();

            return to_route('admin.short-link.index')->with('error', $th->getMessage());
        }
    }

    public function setStatus(Request $request, string $id): RedirectResponse
    {
        try {
            DB::beginTransaction();
            $this->shortLinkInterface->execSetStatus(request: $request, id: $id);
            DB::commit();

            return to_route('admin.short-link.index')->with('success', trans('response.success.switch', ['data' => 'Perubahan Status Short Link']));
        } catch (Throwable $th) {
            DB::rollBack();

            return to_route('admin.short-link.index')->with('error', $th->getMessage());
        }
    }

    public function redirect(Request $request, string $uniqueCode): RedirectResponse|View
    {
        try {
            DB::beginTransaction();
            $action = $this->shortLinkInterface->execRedirect(request: $request, uniqueCode: $uniqueCode);
            DB::commit();

            return $action;
        } catch (Throwable $th) {
            DB::rollBack();
            $data = [
                'title' => 'Data Tidak Ditemukan',
                'desc' => $th->getMessage(),
            ];

            return view('errors.short-link', $data);
        }
    }
}
