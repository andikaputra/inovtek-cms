<?php

namespace App\Services\ShortLink;

use App\Models\ShortLink;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class ShortLinkQueryService
{
    public function checkExistCode(string $code): bool
    {
        return ShortLink::where('short_url', $code)->lockForUpdate()->exists();
    }

    public function findLinkByUniqueCode(string $uniqueCode): ?ShortLink
    {
        $query = ShortLink::query();
        $query->where('short_url', $uniqueCode);

        return $query->first();
    }

    public function findById(string $id): ?ShortLink
    {
        return ShortLink::where('id', $id)->first();
    }

    public function getAllShortLink(Request $request): Collection|LengthAwarePaginator
    {
        $query = ShortLink::query();

        $query->selectRaw(
            '*,
                (SELECT COUNT(*) FROM short_links WHERE is_active = true AND is_active = true) as active_count,
                (SELECT COUNT(*) FROM short_links WHERE is_active = false AND is_active = true) as deactive_count'
        );

        // Filter by status active atau nggak, kalau nggak dikirim atau null maka nggak difilter semua ditampilkan
        $query->when($request->filled('is_active'), function ($query) use ($request) {
            if (in_array($request->is_active, [true, false])) {
                $query->where('is_active', $request->is_active);
            }
        });

        // Filter berdasarkan kombinasi search original url dan short url
        $query->when($request->filled('search'), function ($query) use ($request) {
            $lowerKeyword = strtolower($request->search);
            $query->where(function ($subQuery) use ($lowerKeyword) {
                $subQuery->whereRaw('LOWER(original_url) LIKE ?', ["%{$lowerKeyword}%"])
                    ->orWhereRaw('LOWER(short_url) LIKE ?', ["%{$lowerKeyword}%"]);
            });
        });

        // Original Url
        $query->when($request->filled('original_url'), function ($query) use ($request) {
            $query->where('original_url', 'LIKE', '%'.$request->original_url.'%');
        });

        // Short Url
        $query->when($request->filled('short_url'), function ($query) use ($request) {
            $query->where('short_url', 'LIKE', '%'.$request->short_url.'%');
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
