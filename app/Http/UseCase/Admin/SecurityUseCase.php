<?php

namespace App\Http\UseCase\Admin;

use App\Http\Interfaces\Admin\SecurityInterface;
use App\Http\Requests\Admin\Security\SecurityUpdateRequest;
use App\Services\User\UserCommandService;
use App\Services\User\UserQueryService;
use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

final class SecurityUseCase implements SecurityInterface
{
    public function __construct(
        private readonly UserCommandService $userCommandService,
        private readonly UserQueryService $userQueryService
    ) {}

    public function renderIndex(Request $request): View
    {
        return view('admin.pages.setting.security');
    }

    public function execUpdate(SecurityUpdateRequest $request): bool
    {
        $user = $this->userQueryService->findUserById(id: Auth::user()->id);
        if (! isset($user)) {
            throw new Exception(trans('response.error.update', ['data' => 'User', 'error' => 'User Tidak Ditemukan']));
        }

        return $this->userCommandService->updateUserSecurity(request: $request, user: $user);
    }
}
