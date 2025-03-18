<?php

namespace App\Services\Blog;

use App\Models\Blog;
use App\Services\Region\RegionQueryService;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class BlogDatatableService
{
    public function __construct(
        private readonly RegionQueryService $regionQueryService
    ) {}

    public function blogDatatable(Request $request, bool $onlyRegionData = false, ?string $id_provinsi = null): JsonResponse
    {
        $query = Blog::query();
        if ($onlyRegionData) {
            $query->whereHas('regions', function ($query) use ($id_provinsi) {
                $query->where('region_id', $id_provinsi);
            });
        }

        if (! $onlyRegionData) {
            $query->where('is_general_blog', true);
        }

        // FILTER AND SEARCHING
        $this->_filterDatatable($request, $query);
        // END FILTER AND SEARCHING

        // Count total is_active true and false
        $totalActive = (clone $query)->where('is_active', true)->count();
        $totalInactive = (clone $query)->where('is_active', false)->count();

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('action', function ($item) use ($onlyRegionData, $id_provinsi) {
                if ($onlyRegionData == true) {
                    $findRegion = $this->regionQueryService->findRegionById(id: $id_provinsi);
                }
                $routeDelete = $onlyRegionData == true ? route('admin.home.detail.blog.delete', ['id_provinsi' => $findRegion->slug, 'id' => $item->id]) : route('admin.blog.delete', ['id' => $item->id]);
                $routeEdit = $onlyRegionData == true ? route('admin.home.detail.blog.edit', ['id_provinsi' => $findRegion->slug, 'id' => $item->id]) : route('admin.blog.edit', ['id' => $item->id]);
                $routeSeo = $onlyRegionData == true ? route('admin.home.detail.seo-wilayah.edit', ['id_provinsi' => $findRegion->slug, 'type' => 'blog', 'id_key' => $item->id]) : route('admin.seo-artikel-umum.edit', ['type' => 'blog', 'id_key' => $item->id]);

                $element = '';
                $totalWilayah = $item->regions->count();
                if ($totalWilayah <= 1 || ! $onlyRegionData) {
                    $element .= '<form  id="delete-'.$item->id.'" action="'.$routeDelete.'" method="POST"> ';
                    $element .= csrf_field();
                    $element .= method_field('DELETE');
                    $element .= '<div class="d-flex inline-block">';
                    $element .= '<a data-id ="'.$item->id.'" data-bs-toggle="tooltip" data-bs-placement="top"  href="'.$routeEdit.'" type="button" class="btn btn-primary btn-sm mt-1 mx-1" title="Ubah Data">
                                        <i class="bi bi-pencil-square" ></i>
                                    </a>';
                    $element .= '<a data-id ="'.$item->id.'" data-bs-toggle="tooltip" data-bs-placement="top"  href="'.$routeSeo.'" type="button" class="btn btn-success btn-sm mt-1 mx-1" title="Ubah SEO">
                                    <i class="bi bi-lightning-charge"></i>
                                </a>';
                    $element .= '<button data-bs-toggle="tooltip" data-bs-placement="top" data-id ="'.$item->id.'" type="button" class="btn btn-danger btn-alert-confirm btn-sm ms-1 mt-1" title="Hapus Data">
                                        <i class="bi bi-trash3-fill"></i>
                                    </button>
                                    </div>';
                    $element .= '</form>';
                } else {
                    $element .= '<div class="d-flex inline-block" data-bs-toggle="tooltip" data-bs-placement="top" title="Artikel ini diposting ke '.$totalWilayah.' wilayah berbeda, harap melakukan perubahan melalui fitur Artikel Umum!">';
                    $element .= '<button type="button" disabled class="btn btn-primary btn-sm mt-1 mx-1" title="Ubah Data">
                                    <i class="bi bi-pencil-square" ></i>
                                </button>';
                    $element .= '<button type="button" disabled class="btn btn-success btn-sm mt-1 mx-1" title="Ubah SEO">
                                    <i class="bi bi-lightning-charge"></i>
                                </button>';
                    $element .= '<button type="button" disabled class="btn btn-danger btn-sm ms-1 mt-1" title="Hapus Data">
                                    <i class="bi bi-trash3-fill"></i>
                                </button>
                                </div>';
                }

                return $element;
            })
            ->addColumn('title', function ($item) {
                return $item->title;
            })
            ->addColumn('sub_title', function ($item) {
                return $item->sub_title;
            })
            ->addColumn('content', function ($item) {
                $content = strip_tags($item->content);
                if (strlen($content) > 20) {
                    $content = Str::limit($content, 20, '...');
                }

                return $content;
            })
            ->addColumn('tag', function ($item) use ($onlyRegionData) {
                $element = '';
                if ($onlyRegionData) {
                    if ($item->is_general_blog) {
                        $element .= '<div class="badge text-bg-secondary">Umum</div> ';
                    }

                    foreach ($item->regions as $tag) {
                        $element .= '<div class="badge text-bg-secondary">'.$tag->regency.'</div> ';
                    }
                }

                return $element;
            })
            ->addColumn('posting_to', function ($item) use ($onlyRegionData) {
                $element = '';
                if (! $onlyRegionData) {
                    if ($item->is_general_blog) {
                        $element .= '<div class="badge text-bg-secondary">Tentang Inovtek</div> ';
                    }
                    foreach ($item->regions as $region) {
                        $element .= '<div class="badge text-bg-secondary">Wilayah '.$region->regency.'</div> ';
                    }
                }

                return $element;
            })
            ->addColumn('is_active', function ($item) use ($onlyRegionData, $id_provinsi) {
                $element = '';
                if ($item->regions->count() <= 1 || ! $onlyRegionData) {
                    if ($item->is_active) {
                        $query = 'on';
                    } else {
                        $query = ' ';
                    }
                    if ($onlyRegionData) {
                        $findRegion = $this->regionQueryService->findRegionById(id: $id_provinsi);
                    }
                    $routeUpdate = $onlyRegionData == true ? route('admin.home.detail.blog.update.active', ['id_provinsi' => $findRegion->slug, 'id' => $item->id]) : route('admin.blog.update.active', ['id' => $item->id]);
                    $element .= '<form id="change-'.$item->id.'-active" action="'.$routeUpdate.'" method="POST"> ';
                    $element .= csrf_field();
                    $element .= method_field('PATCH');
                    $element .= '<button data-id ="'.$item->id.'-active" type="button"  class="btn-change-status main-toggle changeStatus '.$query.'"><span></span> </button>';
                    $element .= '</form>';
                } else {
                    $status = $item->is_active ? 'Aktif' : 'Nonaktif';
                    $class = $item->is_active ? 'bg-success' : 'bg-secondary';
                    $element .= '<div class="badge '.$class.'">'.$status.'</div>';
                }

                return $element;
            })
            ->addColumn('is_general_blog', function ($item) use ($onlyRegionData, $id_provinsi) {
                $element = '';
                if ($item->regions->count() <= 1 || ! $onlyRegionData) {
                    if ($item->is_general_blog) {
                        $query = 'on';
                    } else {
                        $query = ' ';
                    }
                    if ($onlyRegionData) {
                        $findRegion = $this->regionQueryService->findRegionById(id: $id_provinsi);
                    }
                    $routeUpdate = $onlyRegionData == true ? route('admin.home.detail.blog.update.general-blog', ['id_provinsi' => $findRegion->slug, 'id' => $item->id]) : null;
                    $element .= '<form id="change-'.$item->id.'-general-blog" action="'.$routeUpdate.'" method="POST"> ';
                    $element .= csrf_field();
                    $element .= method_field('PATCH');
                    $element .= '<button data-id ="'.$item->id.'-general-blog" type="button"  class="main-toggle btn-change-status changeStatus '.$query.'"><span></span> </button>';
                    $element .= '</form>';
                } else {
                    $status = $item->is_general_blog ? 'Aktif' : 'Nonaktif';
                    $class = $item->is_general_blog ? 'bg-success' : 'bg-secondary';
                    $element .= '<div class="badge '.$class.'">'.$status.'</div>';
                }

                return $element;
            })
            ->addColumn('updated_at', function ($item) {
                return Carbon::parse($item->updated_at)->format('d F Y H:i');
            })
            ->with('totalActive', $totalActive)
            ->with('totalInactive', $totalInactive)
            ->rawColumns(['action', 'is_active', 'is_general_blog', 'tag', 'posting_to'])
            ->make(true);
    }

    private function _filterDatatable(Request $request, Builder $query): Builder
    {
        $filter = $request->all();
        $order_column_index = $filter['order'][0]['column'] ?? 0;
        $order_column_dir = $filter['order'][0]['dir'] ?? 'desc';

        $searchKeyword = $request->input('search.value');

        if (isset($searchKeyword)) {
            $lowerKeyword = strtolower($searchKeyword);
            $query->where(function ($query) use ($lowerKeyword) {
                $query->whereRaw('LOWER(blogs.title) LIKE ?', ["%{$lowerKeyword}%"])
                    ->orWhereRaw('LOWER(blogs.sub_title) LIKE ?', ["%{$lowerKeyword}%"])
                    ->orWhereRaw('LOWER(blogs.content) LIKE ?', ["%{$lowerKeyword}%"]);
            })
                ->orWhere(function ($query) use ($searchKeyword) {
                    $query->where('blogs.is_active', 'like', "%$searchKeyword%")
                        ->orWhere('blogs.is_general_blog', 'like', "%$searchKeyword%")
                        ->orWhere('blogs.updated_at', 'like', "%$searchKeyword%");
                });
        }

        // Filtering and Ordering
        if ($order_column_index == 0) {
            $query->orderBy('blogs.created_at', 'DESC');
        }

        if ($order_column_index == 2) {
            $query->orderBy('blogs.title', $order_column_dir);
        }

        if ($order_column_index == 3) {
            $query->orderBy('blogs.sub_title', $order_column_dir);
        }

        if ($order_column_index == 4) {
            $query->orderBy('blogs.content', $order_column_dir);
        }

        if ($order_column_index == 5) {
            $query->orderBy('blogs.is_active', $order_column_dir);
        }

        if ($order_column_index == 6) {
            $query->orderBy('blogs.updated_at', $order_column_dir);
        }

        return $query;
    }
}
