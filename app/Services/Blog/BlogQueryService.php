<?php

namespace App\Services\Blog;

use App\Models\Blog;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class BlogQueryService
{
    public function getById(string $id, ?string $id_provinsi = null): ?Blog
    {
        $query = Blog::query();

        if ($id_provinsi != null) {
            $query->whereHas('regions', function ($query) use ($id_provinsi) {
                $query->where('region_id', $id_provinsi);
            });
        }

        $query->where('id', $id);

        return $query->first();
    }

    public function getByIdentifier(string $identifier): ?Blog
    {
        $query = Blog::with(['assets', 'seos'])
            ->where('is_active', true)
            ->where(function ($query) use ($identifier) {
                $query->where('id', $identifier)
                    ->orWhere('slug', $identifier);
            });

        return $query->first();
    }

    public function getAllBlog(Request $request, bool $onlyRegionData = true, ?string $region_id = null): Collection|LengthAwarePaginator
    {
        $query = Blog::query();

        if ($onlyRegionData) {
            $query->selectRaw(
                '*,
                (SELECT COUNT(*) FROM blogs
                    INNER JOIN blog_tags ON blogs.id = blog_tags.blog_id
                    WHERE blogs.is_active = true
                    AND blog_tags.region_id = ?) as active_count,
                (SELECT COUNT(*) FROM blogs
                    INNER JOIN blog_tags ON blogs.id = blog_tags.blog_id
                    WHERE blogs.is_active = false
                    AND blog_tags.region_id = ?) as deactive_count',
                [$region_id, $region_id] // Pass the region_id for both subqueries
            );

            $query->whereHas('regions', function ($query) use ($region_id) {
                $query->where('region_id', $region_id);
            });
        } else {
            $query->selectRaw(
                '*,
                (SELECT COUNT(*) FROM blogs WHERE is_active = true AND is_general_blog = true) as active_count,
                (SELECT COUNT(*) FROM blogs WHERE is_active = false AND is_general_blog = true) as deactive_count'
            );
            $query->where('is_general_blog', true);
        }

        $query->where('is_active', true);

        // Filter berdasarkan kombinasi search
        $query->when($request->filled('blog_search'), function ($query) use ($request) {
            $lowerKeyword = strtolower($request->blog_search);
            $query->where(function ($subQuery) use ($lowerKeyword) {
                $subQuery->whereRaw('LOWER(title) LIKE ?', ["%{$lowerKeyword}%"])
                    ->orWhereRaw('LOWER(sub_title) LIKE ?', ["%{$lowerKeyword}%"])
                    ->orWhereRaw('LOWER(content) LIKE ?', ["%{$lowerKeyword}%"]);
            });
        });

        $query->when($request->filled('blog_title'), function ($query) use ($request) {
            $query->where('title', 'LIKE', '%'.$request->blog_title.'%');
        });

        $query->when($request->filled('blog_sub_title'), function ($query) use ($request) {
            $query->where('sub_title', 'LIKE', '%'.$request->blog_sub_title.'%');
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
