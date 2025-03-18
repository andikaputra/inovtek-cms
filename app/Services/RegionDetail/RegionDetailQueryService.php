<?php

namespace App\Services\RegionDetail;

use App\Models\RegionDetail;
use Illuminate\Database\Eloquent\Collection;

class RegionDetailQueryService
{
    public function findDetailRegionById(string $id_provinsi, string $id): ?RegionDetail
    {
        $query = RegionDetail::query();
        $query->where('region_id', $id_provinsi);
        $query->where(function ($query) use ($id) {
            $query->where('id', $id)
                ->orWhere('slug', $id);
        });

        return $query->first();
    }

    public function getAllRegionDetail(string $identifier): Collection
    {
        $query = RegionDetail::query();

        $query->where(function ($query) use ($identifier) {
            $query->where('region_id', $identifier)
                ->orWhereHas('region', function ($subQuery) use ($identifier) {
                    $subQuery->where('slug', $identifier);
                });
        });

        $query->where('is_active', true);

        $query->orderBy('created_at', 'DESC');

        return $query->get();
    }
}
