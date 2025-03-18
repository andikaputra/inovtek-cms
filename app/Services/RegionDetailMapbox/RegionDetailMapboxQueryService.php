<?php

namespace App\Services\RegionDetailMapbox;

use App\Constants\AppConst;
use App\Models\RegionDetailMapbox;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;

class RegionDetailMapboxQueryService
{
    public function findMapboxById(string $id, ?string $id_desa = null, bool $api = false): ?RegionDetailMapbox
    {
        $query = RegionDetailMapbox::query();
        if (! $api) {
            $query->where('region_detail_id', $id_desa);
        }
        $query->where('id', $id);

        return $query->first();
    }

    public function getAllCoordinate(string $id_desa): Collection
    {
        return Cache::remember("coordinates_{$id_desa}", AppConst::MAP_REFRESH_LOAD_SECOND, function () use ($id_desa) {

            Cache::put("coordinates_{$id_desa}_last_update", now(), AppConst::MAP_REFRESH_LOAD_SECOND);

            return RegionDetailMapbox::with(['regionDetailMapboxList' => function ($query) {
                $query->where('is_active', true) // Hanya ambil relasi yang aktif
                    ->orderBy('order_point', 'ASC');
            }])
                ->where('region_detail_id', $id_desa)
                ->where('is_active', true)
                ->orderBy('order_point', 'ASC')
                ->get();
        });
    }

    public function getAllMapboxList(string $id_desa, bool $isActive = true): Collection
    {
        $query = RegionDetailMapbox::query();
        $query->where('region_detail_id', $id_desa);
        if ($isActive) {
            $query->where('is_active', true);
        }
        $query->orderBy('order_point', 'ASC');

        return $query->get();
    }
}
