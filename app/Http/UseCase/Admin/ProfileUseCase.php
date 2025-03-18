<?php

namespace App\Http\UseCase\Admin;

use App\Http\Interfaces\Admin\ProfileInterface;
use App\Http\Requests\Admin\Profile\ProfileUpdateRequest;
use App\Models\User;
use App\Services\Asset\AssetCommandService;
use App\Services\User\UserCommandService;
use App\Services\User\UserQueryService;
use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

final class ProfileUseCase implements ProfileInterface
{
    public function __construct(
        private readonly UserCommandService $userCommandService,
        private readonly UserQueryService $userQueryService,
        private readonly AssetCommandService $assetCommandService
    ) {}

    public function renderIndex(Request $request): View
    {
        return view('admin.pages.setting.profile');
    }

    public function execUpdate(ProfileUpdateRequest $request): bool
    {
        $user = $this->userQueryService->findUserById(id: Auth::user()->id);
        if (! isset($user)) {
            throw new Exception(trans('response.error.update', ['data' => 'User', 'error' => 'User tidak ditemukan']));
        }

        if (isset($request->image)) {

            $explodeFilePath = explode(','.config('app.url'), $request->image);
            if (count($explodeFilePath) > 1) {
                throw new Exception(trans('response.error.update', ['data' => 'User', 'error' => 'Tidak dapat menambahkan lebih dari 1 gambar']));
            }

            $this->assetCommandService->deleteAllAsset(pathType: User::class, pathId: $user->id);

            $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'svg', 'webp'];
            foreach ($explodeFilePath as $index => $filePath) {
                $explodePath = explode('storage/', $filePath);

                $fileUrl = trim($explodePath[1]); // Trim spaces
                $extension = strtolower(pathinfo($fileUrl, PATHINFO_EXTENSION));

                if (! in_array($extension, $allowedExtensions)) {
                    throw new Exception(trans('response.error.update', ['data' => 'User', 'error' => 'File yang diunggah bukan gambar yang valid']));
                }

                $this->assetCommandService->storeAsset(pathType: User::class, pathId: $user->id, pathName: $explodePath[1]);
            }
        }

        return $this->userCommandService->updateUserProfile(request: $request, user: $user);
    }
}
