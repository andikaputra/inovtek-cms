<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\Json;
use App\Http\Controllers\Controller;
use App\Http\Interfaces\Admin\UserInterface;
use App\Http\Requests\Admin\User\UserStoreRequest;
use App\Http\Requests\Admin\User\UserUpdateRequest;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

final class UserController extends Controller
{
    public function __construct(private readonly UserInterface $userInterface) {}

    public function index(Request $request): View
    {
        return $this->userInterface->renderIndex(request: $request);
    }

    public function datatable(Request $request): JsonResponse
    {
        try {
            DB::beginTransaction();
            $data = $this->userInterface->renderDatatable(request: $request);
            DB::commit();

            return $data;
        } catch (Throwable $th) {
            DB::rollBack();

            return Json::error(error: $th->getMessage());
        }
    }

    public function create(Request $request): View
    {
        return $this->userInterface->renderCreate(request: $request);
    }

    public function store(UserStoreRequest $request): RedirectResponse
    {
        try {
            DB::beginTransaction();
            $this->userInterface->execStore(request: $request);
            DB::commit();

            return to_route('admin.user.index')->with('success', trans('response.success.store', ['data' => 'User']));
        } catch (Throwable $th) {
            DB::rollBack();

            return to_route('admin.user.create')->with('error', $th->getMessage());
        }
    }

    public function edit(Request $request, string $id): View
    {
        return $this->userInterface->renderEdit(request: $request, id: $id);
    }

    public function update(UserUpdateRequest $request, string $id): RedirectResponse
    {
        try {
            DB::beginTransaction();
            $this->userInterface->execUpdate(request: $request, id: $id);
            DB::commit();

            return to_route('admin.user.index')->with('success', trans('response.success.update', ['data' => 'User']));
        } catch (Throwable $th) {
            DB::rollBack();

            return to_route('admin.user.edit', ['id' => $id])->with('error', $th->getMessage());
        }
    }

    public function delete(Request $request, string $id): RedirectResponse
    {
        try {
            DB::beginTransaction();
            $this->userInterface->execDelete(request: $request, id: $id);
            DB::commit();

            return to_route('admin.user.index')->with('success', trans('response.success.delete', ['data' => 'User']));
        } catch (Throwable $th) {
            DB::rollBack();

            return to_route('admin.user.index')->with('error', $th->getMessage());
        }
    }

    public function setStatus(Request $request, string $id): RedirectResponse
    {
        try {
            DB::beginTransaction();
            $this->userInterface->execSetStatus(request: $request, id: $id);
            DB::commit();

            return to_route('admin.user.index')->with('success', trans('response.success.switch', ['data' => 'Perubahan Status User']));
        } catch (Throwable $th) {
            DB::rollBack();

            return to_route('admin.user.index')->with('error', $th->getMessage());
        }
    }
}
