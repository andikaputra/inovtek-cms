<?php

namespace App\Services\Asset;

use App\Models\Asset;
use Illuminate\Database\Eloquent\Collection;

class AssetQueryService
{
    public function loadAsset(string $pathType, string $pathId, ?string $assetKey = null): ?string
    {
        $query = Asset::query();

        $query->where('assetable_type', $pathType);
        $query->where('assetable_id', $pathId);

        if ($assetKey != null) {
            $query->where('asset_key', $assetKey);
        }

        $findAsset = $query->first();

        return $findAsset->asset_path ?? null;
    }

    public function findById(string $id): ?Asset
    {
        $query = Asset::query();

        $query->where('id', $id);

        $findAsset = $query->first();

        return $findAsset ?? null;
    }

    public function getAllAsset(string $pathType, string $pathId, ?string $exceptAssetKey = null, ?string $withAssetKey = null, bool $usingLike = false): Collection
    {
        $query = Asset::query();

        $query->where('assetable_type', $pathType);
        $query->where('assetable_id', $pathId);

        if ($exceptAssetKey != null) {
            if ($usingLike) {
                $query->whereRaw('asset_key NOT LIKE ?', ['%'.$exceptAssetKey.'%']);
            } else {
                $query->where('asset_key', '!=', $exceptAssetKey);
            }
        }

        if ($withAssetKey != null) {
            if ($usingLike) {
                $query->whereRaw('asset_key LIKE ?', ['%'.$withAssetKey.'%']);
            } else {
                $query->where('asset_key', $withAssetKey);
            }
        }

        return $query->get();
    }
}
