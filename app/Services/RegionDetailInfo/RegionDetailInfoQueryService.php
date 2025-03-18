<?php

namespace App\Services\RegionDetailInfo;

use App\Models\RegionDetailInfo;

class RegionDetailInfoQueryService
{
    public function findDetailInfoByRegionId(string $id_provinsi): ?RegionDetailInfo
    {
        $query = RegionDetailInfo::query();

        $query->where('region_id', $id_provinsi);

        return $query->first();
    }
}
