<?php

namespace App\Services\Asset;

use App\Models\Asset;
use Illuminate\Support\Str;

class AssetCommandService
{
    public function storeAsset(string $pathType, string $pathId, string $pathName, ?string $assetKey = null, bool $is_image = true): Asset
    {
        $query = Asset::query();

        $query->where('assetable_type', $pathType)->where('assetable_id', $pathId);

        if ($assetKey != null) {
            $query->where('asset_key', $assetKey);
        }
        $findAsset = $query->firstOrNew();
        $findAsset->assetable_type = $pathType;
        $findAsset->assetable_id = $pathId;
        $findAsset->asset_path = $pathName;
        $findAsset->asset_key = $assetKey ?? Str::slug((strtolower($pathType.'-'.$pathId)));
        $findAsset->is_image = $is_image;
        $findAsset->save();

        return $findAsset;
    }

    public function deleteAsset(string $pathType, string $pathId, ?string $assetKey = null): Asset|bool
    {
        $query = Asset::query();

        $query->where('assetable_type', $pathType);
        $query->where('assetable_id', $pathId);

        if ($assetKey != null) {
            $query->where('asset_key', $assetKey);
        }

        $findAsset = $query->first();

        return $findAsset->delete();
    }

    public function deleteAllAsset(string $pathType, string $pathId, ?string $assetKey = null, bool $usingLike = false): Asset|bool
    {
        $query = Asset::query();

        $query->where('assetable_type', $pathType);
        $query->where('assetable_id', $pathId);

        if ($assetKey != null) {
            if (! $usingLike) {
                $query->where('asset_key', $assetKey);
            } else {
                $query->where('asset_key', 'LIKE', $assetKey.'%');
            }
        }

        return $query->delete();
    }
}
