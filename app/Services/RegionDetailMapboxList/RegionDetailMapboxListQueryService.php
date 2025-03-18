<?php

namespace App\Services\RegionDetailMapboxList;

use App\Constants\AppConst;
use App\Models\RegionDetailMapboxList;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;

class RegionDetailMapboxListQueryService
{
    public function getAllCoordinate(string $id_mapbox): Collection
    {
        return Cache::remember("coordinates_{$id_mapbox}", AppConst::MAP_REFRESH_LOAD_SECOND, function () use ($id_mapbox) {
            Cache::put("coordinates_{$id_mapbox}_last_update", now(), AppConst::MAP_REFRESH_LOAD_SECOND);

            return RegionDetailMapboxList::query()
                ->select(['name', 'latitude', 'longitude'])
                ->where('region_detail_mapbox_id', $id_mapbox)
                ->where('is_active', true)
                ->orderBy('created_at', 'ASC')
                ->get();
        });
    }

    public function findMapboxListById(string $id_mapbox, string $id): ?RegionDetailMapboxList
    {
        $query = RegionDetailMapboxList::query();
        $query->where('region_detail_mapbox_id', $id_mapbox);
        $query->where('id', $id);

        return $query->first();
    }

    public function getAllMapboxList(string $id_mapbox, bool $isActive = true): Collection
    {
        $query = RegionDetailMapboxList::query();
        $query->where('region_detail_mapbox_id', $id_mapbox);
        if ($isActive) {
            $query->where('is_active', true);
        }
        $query->orderBy('order_point', 'ASC');

        return $query->get();
    }
}
