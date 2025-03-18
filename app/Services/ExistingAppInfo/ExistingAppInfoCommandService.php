<?php

namespace App\Services\ExistingAppInfo;

use App\Http\Requests\Admin\AboutApp\AboutAppUpdateRequest;
use App\Models\ExistingAppInfo;

class ExistingAppInfoCommandService
{
    public function storeOrUpdate(AboutAppUpdateRequest $request, string $existingAppId): ?ExistingAppInfo
    {
        $query = ExistingAppInfo::where('existing_app_id', $existingAppId)->first();
        if (! isset($query)) {
            $query = new ExistingAppInfo;
        }
        $query->existing_app_id = $existingAppId;
        $query->intro_video_url = $request->intro_video_url;
        $query->tutorial_video_url = $request->tutorial_video_url;
        $query->save();

        return $query;
    }
}
