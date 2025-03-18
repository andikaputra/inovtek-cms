<?php

namespace App\Services\RegionDetail;

use App\Http\Requests\Admin\HomeDesa\HomeDesaStoreRequest;
use App\Http\Requests\Admin\HomeDesa\HomeDesaUpdateRequest;
use App\Models\RegionDetail;
use Cviebrock\EloquentSluggable\Services\SlugService;

class RegionDetailCommandService
{
    public function store(HomeDesaStoreRequest $request, string $id_provinsi): ?RegionDetail
    {
        $query = new RegionDetail;
        $query->slug = SlugService::createSlug(RegionDetail::class, 'slug', $request->village);
        $query->region_id = $id_provinsi;
        $query->village = $request->village;
        $query->latitude = $request->latitude;
        $query->longitude = $request->longitude;
        $query->map_url = $request->map_url;
        $query->is_active = isset($request->is_active) ? true : false;
        $query->save();

        return $query;
    }

    public function update(HomeDesaUpdateRequest $request, RegionDetail $regionDetail): ?RegionDetail
    {
        $regionDetail->slug = strtolower($regionDetail->village) == strtolower($request->village) ? $regionDetail->slug : SlugService::createSlug(RegionDetail::class, 'slug', $request->village);
        $regionDetail->village = $request->village;
        $regionDetail->latitude = $request->latitude;
        $regionDetail->longitude = $request->longitude;
        $regionDetail->map_url = $request->map_url;
        $regionDetail->is_active = isset($request->is_active) ? true : false;
        $regionDetail->save();

        return $regionDetail;
    }

    public function setStatus(RegionDetail $regionDetail): bool
    {
        $regionDetail->is_active = $regionDetail->is_active == true ? false : true;

        return $regionDetail->save();
    }

    public function delete(RegionDetail $regionDetail): bool
    {
        return $regionDetail->delete();
    }
}
