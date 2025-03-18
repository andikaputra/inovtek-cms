<?php

namespace App\Services\RegionDetailMapbox;

use App\Http\Requests\Admin\HomeDesaSegmentasiMapbox\HomeDesaSegmentasiMapboxStoreRequest;
use App\Http\Requests\Admin\HomeDesaSegmentasiMapbox\HomeDesaSegmentasiMapboxUpdateRequest;
use App\Models\RegionDetailMapbox;
use App\Models\RegionDetailMapboxList;

class RegionDetailMapboxCommandService
{
    public function store(HomeDesaSegmentasiMapboxStoreRequest $request, string $id_desa): ?RegionDetailMapbox
    {
        $query = new RegionDetailMapbox;
        $query->region_detail_id = $id_desa;
        $query->name = $request->name;
        $query->latitude = $request->latitude;
        $query->longitude = $request->longitude;
        $query->map_url = $request->map_url;
        $query->vr_url = $request->vr_url;
        $query->vr_youtube_url = $request->vr_youtube_url;
        $query->type = strtolower($request->type);
        $query->is_drone = isset($request->is_drone) ? true : false;
        $query->is_active = isset($request->is_active) ? true : false;
        $query->order_point = $request->order_point;
        $query->save();

        return $query;
    }

    public function update(HomeDesaSegmentasiMapboxUpdateRequest $request, RegionDetailMapbox $regionDetailMapbox): ?RegionDetailMapbox
    {
        $regionDetailMapbox->name = $request->name;
        $regionDetailMapbox->latitude = $request->latitude;
        $regionDetailMapbox->longitude = $request->longitude;
        $regionDetailMapbox->map_url = $request->map_url;
        $regionDetailMapbox->vr_url = $request->vr_url;
        $regionDetailMapbox->vr_youtube_url = $request->vr_youtube_url;
        $regionDetailMapbox->type = strtolower($request->type);
        $regionDetailMapbox->is_drone = isset($request->is_drone) ? true : false;
        $regionDetailMapbox->is_active = isset($request->is_active) ? true : false;
        $regionDetailMapbox->save();

        return $regionDetailMapbox;
    }

    public function updateOrder(RegionDetailMapbox $regionDetailMapbox, int $index): ?RegionDetailMapbox
    {
        $regionDetailMapbox->order_point = $index;
        $regionDetailMapbox->save();

        return $regionDetailMapbox;
    }

    public function updateSwitch(RegionDetailMapbox $regionDetailMapbox): bool
    {
        $regionDetailMapbox->is_active = $regionDetailMapbox->is_active == true ? false : true;

        return $regionDetailMapbox->save();
    }

    public function delete(RegionDetailMapbox $regionDetailMapbox): bool
    {
        RegionDetailMapboxList::where('region_detail_mapbox_id', $regionDetailMapbox->id)->delete();

        return $regionDetailMapbox->delete();
    }
}
