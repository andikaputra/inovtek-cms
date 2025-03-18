<?php

namespace App\Services\RegionGallery;

use App\Http\Requests\Admin\HomeDesaGallery\HomeDesaGalleryUpdateRequest;
use App\Models\RegionGallery;

class RegionGalleryCommandService
{
    public function storeOrUpdate(HomeDesaGalleryUpdateRequest $request, string $id_provinsi): ?RegionGallery
    {
        $query = RegionGallery::where('region_id', $id_provinsi)->first();
        if (! isset($query)) {
            $query = new RegionGallery;
        }
        $query->region_id = $id_provinsi;
        $query->is_active = isset($request->is_active) ? true : false;
        $query->save();

        return $query;
    }
}
