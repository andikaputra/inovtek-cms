<?php

namespace App\Services\RegionDetailInfo;

use App\Http\Requests\Admin\HomeDesaDetailInfo\HomeDesaDetailInfoUpdateRequest;
use App\Models\RegionDetailInfo;

class RegionDetailInfoCommandService
{
    public function storeOrUpdate(HomeDesaDetailInfoUpdateRequest $request, string $id_provinsi): ?RegionDetailInfo
    {
        $query = RegionDetailInfo::where('region_id', $id_provinsi)->first();
        if (! isset($query)) {
            $query = new RegionDetailInfo;
        }

        $query->region_id = $id_provinsi;
        $query->mitigation = $request->mitigation;
        $query->save();

        return $query;
    }
}
