<?php

namespace App\Services\LinkCollection;

use App\Models\LinkCollection;
use App\Services\Asset\AssetQueryService;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class LinkCollectionDatatableService
{
    public function __construct(
        private readonly AssetQueryService $assetQueryService
    ) {}

    public function linkSosmedDatatable(Request $request, string $id_provinsi, bool $isSocialMedia = false): JsonResponse
    {
        $query = LinkCollection::query();
        $query->where('region_id', $id_provinsi);

        if ($isSocialMedia) {
            $query->where('is_social_media', true);
        } else {
            $query->where('is_social_media', false);
        }

        // FILTER AND SEARCHING
        $this->_filterDatatable($request, $query, $isSocialMedia);
        // END FILTER AND SEARCHING
        $totalActive = (clone $query)->where('is_active', true)->count();
        $totalInactive = (clone $query)->where('is_active', false)->count();

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('action', function ($item) use ($isSocialMedia) {
                $element = '';

                $routeDelete = $isSocialMedia ? route('admin.home.detail.sosial-media.delete', ['id_provinsi' => $item->region->slug, 'id' => $item->id]) : route('admin.home.detail.link.delete', ['id_provinsi' => $item->region->slug, 'id' => $item->id]);
                $routeEdit = $isSocialMedia ? route('admin.home.detail.sosial-media.edit', ['id_provinsi' => $item->region->slug, 'id' => $item->id]) : route('admin.home.detail.link.edit', ['id_provinsi' => $item->region->slug, 'id' => $item->id]);

                $element .= '<form  id="delete-'.$item->id.'" action="'.$routeDelete.'" method="POST"> ';
                $element .= csrf_field();
                $element .= method_field('DELETE');
                $element .= '<div class="d-flex inline-block">';
                $element .= '<a data-id ="'.$item->id.'"  href="'.$routeEdit.'" data-bs-toggle="tooltip" data-bs-placement="top" type="button" class="btn btn-primary btn-sm mt-1 mx-1" title="Ubah Data">
                                        <i class="bi bi-pencil-square" ></i>
                                    </a>';
                $element .= '<button data-bs-toggle="tooltip" data-bs-placement="top" data-id ="'.$item->id.'" type="button" class="btn btn-danger btn-alert-confirm btn-sm ms-1 mt-1" title="Hapus Data">
                                        <i class="bi bi-trash3-fill"></i>
                                    </button>
                                    </div>';
                $element .= '</form>';

                return $element;
            })
            ->addColumn('icon', function ($item) {
                $icon = $this->assetQueryService->loadAsset(pathType: LinkCollection::class, pathId: $item->id);
                $iconImg = $icon && Storage::disk('public')->exists($icon)
                    ? asset('storage/'.$icon)
                    : asset('assets/images/default/no-image.jpg');

                return isset($icon) ? '<img loading="lazy" src="'.$iconImg.'" alt="icon" class="img img-fluid img-responsive" width="50px" /></img>' : null;
            })
            ->addColumn('url', function ($item) {
                return '<a href="'.$item->url.'" target="_blank" rel="noopener noreferrer">'.$item->url.'</a>';
            })
            ->addColumn('display', function ($item) {
                return $item->display;
            })
            ->addColumn('status', function ($item) use ($isSocialMedia) {
                $element = '';
                if ($item->is_active) {
                    $query = 'on';
                } else {
                    $query = ' ';
                }
                $routeUpdate = $isSocialMedia ? route('admin.home.detail.sosial-media.update.active', ['id_provinsi' => $item->region->slug, 'id' => $item->id]) : route('admin.home.detail.link.update.active', ['id_provinsi' => $item->region->slug, 'id' => $item->id]);

                $element .= '<form id="change-'.$item->id.'" action="'.$routeUpdate.'" method="POST"> ';
                $element .= csrf_field();
                $element .= method_field('PATCH');
                $element .= '<button data-id ="'.$item->id.'" type="button"  class="btn-change-status main-toggle changeStatus '.$query.'"><span></span> </button>';
                $element .= '</form>';

                return $element;
            })
            ->addColumn('updated_at', function ($item) {
                return Carbon::parse($item->updated_at)->format('d F Y H:i');
            })
            ->with('totalActive', $totalActive)
            ->with('totalInactive', $totalInactive)
            ->rawColumns(['action', 'status', 'url', 'icon'])
            ->make(true);
    }

    private function _filterDatatable(Request $request, Builder $query, bool $isSocialMedia): Builder
    {
        $filter = $request->all();
        $order_column_index = $filter['order'][0]['column'] ?? 0;
        $order_column_dir = $filter['order'][0]['dir'] ?? 'desc';

        $searchKeyword = $request->input('search.value');

        if (isset($searchKeyword)) {
            $query->where(function ($query) use ($searchKeyword) {
                $lowerKeyword = strtolower($searchKeyword);
                $query->whereRaw('LOWER(url) LIKE ?', ["%{$lowerKeyword}%"])
                    ->orWhereRaw('LOWER(display) LIKE ?', ["%{$lowerKeyword}%"])
                    ->orWhere('is_active', 'like', "%$searchKeyword%")
                    ->orWhere('updated_at', 'like', "%$searchKeyword%");
            });
        }

        // Filtering and Ordering
        if ($isSocialMedia) {
            if ($order_column_index == 0) {
                $query->orderBy('created_at', 'ASC');
            }

            if ($order_column_index == 2) {
                $query->orderBy('id', $order_column_dir);
            }

            if ($order_column_index == 3) {
                $query->orderBy('url', $order_column_dir);
            }

            if ($order_column_index == 4) {
                $query->orderBy('display', $order_column_dir);
            }

            if ($order_column_index == 5) {
                $query->orderBy('is_active', $order_column_dir);
            }

            if ($order_column_index == 6) {
                $query->orderBy('updated_at', $order_column_dir);
            }
        } else {
            if ($order_column_index == 0) {
                $query->orderBy('created_at', 'ASC');
            }

            if ($order_column_index == 2) {
                $query->orderBy('url', $order_column_dir);
            }

            if ($order_column_index == 3) {
                $query->orderBy('display', $order_column_dir);
            }

            if ($order_column_index == 4) {
                $query->orderBy('is_active', $order_column_dir);
            }

            if ($order_column_index == 5) {
                $query->orderBy('updated_at', $order_column_dir);
            }
        }

        return $query;
    }
}
