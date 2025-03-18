<?php

namespace App\Services\LinkCollection;

use App\Models\LinkCollection;
use Illuminate\Database\Eloquent\Collection;

class LinkCollectionQueryService
{
    public function findLinkById(string $id_provinsi, string $id): ?LinkCollection
    {
        $query = LinkCollection::query();
        $query->where('region_id', $id_provinsi);
        $query->where('id', $id);

        return $query->first();
    }

    public function getLinkByRegionId(string $region_id, bool $isSocialMedia = false): Collection
    {
        $query = LinkCollection::query();
        $query->with(['assets']);
        $query->where('is_social_media', $isSocialMedia);
        $query->where('region_id', $region_id);
        $query->where('is_active', true);

        return $query->get();
    }
}
