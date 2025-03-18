<?php

namespace App\Services\RegionDetailMapboxList;

use App\Http\Requests\Admin\HomeDesaMapboxJalur\HomeDesaMapboxJalurStoreRequest;
use App\Http\Requests\Admin\HomeDesaMapboxJalur\HomeDesaMapboxJalurUpdateRequest;
use App\Models\RegionDetailMapboxList;

class RegionDetailMapboxListCommandService
{
    public function store(HomeDesaMapboxJalurStoreRequest $request, string $id_mapbox): ?RegionDetailMapboxList
    {
        $query = new RegionDetailMapboxList;
        $query->region_detail_mapbox_id = $id_mapbox;
        $query->name = $request->name;
        $query->latitude = $request->latitude;
        $query->longitude = $request->longitude;
        $query->is_active = isset($request->is_active) ? true : false;
        $query->order_point = $request->order_point;
        $query->save();

        return $query;
    }

    public function update(HomeDesaMapboxJalurUpdateRequest $request, RegionDetailMapboxList $regionDetailMapboxList): ?RegionDetailMapboxList
    {
        $regionDetailMapboxList->name = $request->name;
        $regionDetailMapboxList->latitude = $request->latitude;
        $regionDetailMapboxList->longitude = $request->longitude;
        $regionDetailMapboxList->is_active = isset($request->is_active) ? true : false;
        $regionDetailMapboxList->save();

        return $regionDetailMapboxList;
    }

    public function updateSwitch(RegionDetailMapboxList $regionDetailMapboxList): bool
    {
        $regionDetailMapboxList->is_active = $regionDetailMapboxList->is_active == false ? true : false;

        return $regionDetailMapboxList->save();
    }

    public function delete(RegionDetailMapboxList $regionDetailMapboxList): bool
    {
        return $regionDetailMapboxList->delete();
    }

    public function updateOrder(RegionDetailMapboxList $regionDetailMapboxList, int $index): ?RegionDetailMapboxList
    {
        $regionDetailMapboxList->order_point = $index;
        $regionDetailMapboxList->save();

        return $regionDetailMapboxList;
    }
}
