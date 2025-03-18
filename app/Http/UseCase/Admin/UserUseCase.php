<?php

namespace App\Http\UseCase\Admin;

use App\Http\Interfaces\Admin\UserInterface;
use App\Http\Requests\Admin\User\UserStoreRequest;
use App\Http\Requests\Admin\User\UserUpdateRequest;
use App\Models\User;
use App\Services\User\UserCommandService;
use App\Services\User\UserDatatableService;
use App\Services\User\UserQueryService;
use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

final class UserUseCase implements UserInterface
{
    public function __construct(
        private readonly UserDatatableService $userDatatableService,
        private readonly UserCommandService $userCommandService,
        private readonly UserQueryService $userQueryService
    ) {}

    public function renderIndex(Request $request): View
    {
        return view('admin.pages.user.index');
    }

    public function renderDatatable(Request $request): JsonResponse
    {
        return $this->userDatatableService->userDatatable(request: $request);
    }

    public function renderCreate(Request $request): View
    {
        if (Auth::user()->is_default && Auth::user()->is_super_admin) {
            return view('admin.pages.user.create');
        }
        abort(Response::HTTP_NOT_FOUND);
    }

    public function execStore(UserStoreRequest $request): User
    {
        if (Auth::user()->is_default && Auth::user()->is_super_admin) {
            return $this->userCommandService->storeUser(request: $request);
        }
        abort(Response::HTTP_NOT_FOUND);
    }

    public function renderEdit(Request $request, string $id): View
    {
        $user = $this->userQueryService->findUserById(id: $id);

        if (! isset($user)) {
            abort(Response::HTTP_NOT_FOUND);
        }

        if ($user->is_default || $user->id == Auth::user()->id) {
            abort(Response::HTTP_NOT_FOUND);
        }

        return view('admin.pages.user.edit', compact('user'));
    }

    public function execUpdate(UserUpdateRequest $request, string $id): User
    {
        $user = $this->userQueryService->findUserById(id: $id);

        if (! isset($user)) {
            throw new Exception(trans('response.error.update', ['data' => 'User', 'error' => 'data tidak ditemukan']));
        }

        if ($user->is_default || $user->id == Auth::user()->id) {
            throw new Exception(trans('response.error.update', ['data' => 'User', 'error' => 'data tidak ditemukan']));
        }

        return $this->userCommandService->updateUser(request: $request, user: $user);
    }

    public function execDelete(Request $request, string $id): bool
    {
        $user = $this->userQueryService->findUserById(id: $id);

        if (! isset($user)) {
            throw new Exception(trans('response.error.delete', ['data' => 'User', 'error' => 'data tidak ditemukan']));
        }

        if ($user->is_default || $user->id == Auth::user()->id || $user->guid_user !== null) {
            throw new Exception(trans('response.error.delete', ['data' => 'User', 'error' => 'data tidak ditemukan']));
        }

        if ($user->is_active) {
            throw new Exception(trans('response.error.delete', ['data' => 'User', 'error' => 'user masih aktif']));
        }

        return $this->userCommandService->deleteUser(user: $user);
    }

    public function execSetStatus(Request $request, string $id): bool
    {
        $user = $this->userQueryService->findUserById(id: $id);

        if (! isset($user)) {
            throw new Exception(trans('response.error.switch', ['data' => 'Perubahan Status User', 'error' => 'data tidak ditemukan']));
        }

        return $this->userCommandService->setStatus(user: $user);
    }
}
