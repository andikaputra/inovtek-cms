<?php

namespace App\Http\UseCase\Admin;

use App\Constants\AppConst;
use App\Http\Interfaces\Admin\AboutAppInterface;
use App\Http\Requests\Admin\AboutApp\AboutAppUpdateRequest;
use App\Models\ExistingAppInfo;
use App\Services\ExistingApp\ExistingAppQueryService;
use App\Services\ExistingAppInfo\ExistingAppInfoCommandService;
use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

final class AboutAppUseCase implements AboutAppInterface
{
    public function __construct(
        private readonly ExistingAppQueryService $existingAppQueryService,
        private readonly ExistingAppInfoCommandService $existingAppInfoCommandService
    ) {}

    public function renderEdit(Request $request): View
    {
        $vr360Tour = $this->existingAppQueryService->findByCode(code: AppConst::CODE_EXISTING_APP['02']);

        return view('admin.pages.about-app.edit', compact('vr360Tour'));
    }

    public function execUpdate(AboutAppUpdateRequest $request): ?ExistingAppInfo
    {
        $existingApp = $this->existingAppQueryService->findByCode(code: AppConst::CODE_EXISTING_APP['02']);
        if (! isset($existingApp)) {
            throw new Exception(trans('response.error.update', ['data' => 'Informasi Produk', 'error' => 'Data tidak ditemukan']));
        }

        return $this->existingAppInfoCommandService->storeOrUpdate(request: $request, existingAppId: $existingApp->id);
    }
}
