<?php

namespace App\Services\RegionGallery;

use App\Models\RegionGallery;

class RegionGalleryQueryService
{
    public function findGalleryByRegionId(string $id_provinsi, bool $withActive = false): ?RegionGallery
    {
        $query = RegionGallery::query();

        $query->where('region_id', $id_provinsi);

        if ($withActive) {
            $query->where('is_active', true);
        }

        return $query->first();
    }
}
