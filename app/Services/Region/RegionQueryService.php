<?php

namespace App\Services\Region;

use App\Models\Region;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class RegionQueryService
{
    public function findRegionById(string $id, bool $withActive = false): ?Region
    {
        $query = Region::query();
        $query->with(['assets', 'existingApps']);
        $query->where(function ($query) use ($id) {
            $query->where('id', $id)
                ->orWhere('slug', $id);
        });

        if ($withActive) {
            $query->where('is_active', true);
        }

        return $query->first();
    }

    public function findRegionByIdentifier(string $identifier): ?Region
    {
        $query = Region::with(['assets', 'existingApps', 'seos'])
            ->where('is_active', true)
            ->where(function ($query) use ($identifier) {
                $query->where('id', $identifier)
                    ->orWhere('slug', $identifier);
            });

        return $query->first();
    }

    public function getAllRegion(Request $request, bool $withActive = false): LengthAwarePaginator|Collection
    {
        $query = Region::query()
            ->with(['assets', 'existingApps'])
            ->selectRaw(
                '*,
            (SELECT COUNT(*) FROM regions WHERE is_active = true) as active_count,
            (SELECT COUNT(*) FROM regions WHERE is_active = false) as deactive_count'
            );

        // Filter berdasarkan is_active dengan kondisi default jika tidak ada filter
        $query->when($request->filled('is_active'), function ($query) use ($request) {
            if ($request->boolean('is_active') || in_array($request->is_active, ['aktif', 'nonaktif'])) {
                $status = $request->boolean('is_active') ? $request->boolean('is_active') : ($request->is_active == 'aktif' ? true : false);
                $query->where('is_active', $status);
            }
        }, function ($query) use ($withActive) {
            if ($withActive == true) {
                $query->where('is_active', true); // Kondisi default untuk is_active = true jika tidak ada filter
            }
        });

        // Filter berdasarkan kombinasi search, province, dan regency
        $query->when($request->filled('search'), function ($query) use ($request) {
            $lowerKeyword = strtolower($request->search);
            $query->where(function ($subQuery) use ($lowerKeyword) {
                $subQuery->whereRaw('LOWER(province) LIKE ?', ["%{$lowerKeyword}%"])
                    ->orWhereRaw('LOWER(regency) LIKE ?', ["%{$lowerKeyword}%"]);
            });
        });

        $query->when($request->filled('province'), function ($query) use ($request) {
            $query->where('province', 'LIKE', '%'.$request->province.'%');
        });

        $query->when($request->filled('regency'), function ($query) use ($request) {
            $query->where('regency', 'LIKE', '%'.$request->regency.'%');
        });

        // Filter berdasarkan existing_app
        $query->when($request->filled('existing_app'), function ($query) use ($request) {
            $query->whereHas('existingApps', function ($subQuery) use ($request) {
                $subQuery->where('code', $request->existing_app);
            });
        });

        // Urutan hasil berdasarkan created_at
        $query->orderBy('created_at', 'DESC');

        // Opsi paginate
        if ($request->boolean('paginate', false)) {
            return $query->paginate($request->input('per_page', 10))->appends($request->except('page'));
        }

        return $query->get();
    }
}
