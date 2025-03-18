<?php

namespace App\Services\RegionDetailMapbox;

use App\Constants\AppConst;
use App\Models\RegionDetailMapbox;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class RegionDetailMapboxDatatableService
{
    public function mapboxDatatable(Request $request, string $id_desa): JsonResponse
    {
        $query = RegionDetailMapbox::query();
        $query->where('region_detail_id', $id_desa);

        // FILTER AND SEARCHING
        $this->_filterDatatable($request, $query);
        // END FILTER AND SEARCHING
        $totalActive = (clone $query)->where('is_active', true)->count();
        $totalInactive = (clone $query)->where('is_active', false)->count();

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('action', function ($item) {
                $element = '';
                $element .= '<form  id="delete-'.$item->id.'" action="'.route('admin.home.detail.desa.segmentasi-mapbox.delete', ['id_provinsi' => $item?->regionDetail?->region?->slug, 'id_desa' => $item?->regionDetail?->slug, 'id' => $item?->id]).'" method="POST"> ';
                $element .= csrf_field();
                $element .= method_field('DELETE');
                $element .= '<div class="d-flex inline-block">';
                $element .= '<a data-id ="'.$item->id.'"  data-bs-toggle="tooltip" data-bs-placement="top" href="'.route('admin.home.detail.desa.segmentasi-mapbox.edit', ['id_provinsi' => $item?->regionDetail?->region?->slug, 'id_desa' => $item?->regionDetail?->slug, 'id' => $item?->id]).'" type="button" class="btn btn-primary btn-sm mt-1 mx-1" title="Ubah Data">
                                <i class="bi bi-pencil-square" ></i>
                            </a>';
                $element .= '<a data-id ="'.$item->id.'"  data-bs-toggle="tooltip" data-bs-placement="top" href="'.route('admin.home.detail.desa.segmentasi-mapbox.jalur.index', ['id_provinsi' => $item?->regionDetail?->region?->slug, 'id_desa' => $item?->regionDetail?->slug, 'id_mapbox' => $item->id]).'" type="button" class="btn btn-warning text-white btn-sm mt-1 mx-1" title="Daftar Titik Jalur">
                               <i class="bi bi-map"></i>
                            </a>';
                $element .= '<button data-bs-toggle="tooltip" data-bs-placement="top" data-id ="'.$item->id.'" type="button" class="btn btn-danger btn-alert-confirm btn-sm ms-1 mt-1" title="Hapus Data">
                                <i class="bi bi-trash3-fill"></i>
                            </button>
                            </div>';
                $element .= '</form>';

                return $element;
            })
            ->addColumn('name', function ($item) {
                return $item->name;
            })
            ->addColumn('lat_long', function ($item) {
                return $item->latitude.', '.$item->longitude;
            })
            ->addColumn('type', function ($item) {
                return AppConst::POINT_TYPE_MAPPING[$item->type];
            })
            ->addColumn('map_url', function ($item) {
                return ! isset($item->map_url) ? null : '<a href="'.$item->map_url.'" target="_blank" rel="noopener noreferrer">'.$item->map_url.'</a>';
            })
            ->addColumn('vr_url', function ($item) {
                return ! isset($item->vr_url) ? null : '<a href="'.$item->vr_url.'" target="_blank" rel="noopener noreferrer">'.$item->vr_url.'</a>';
            })
            ->addColumn('vr_youtube_url', function ($item) {
                return ! isset($item->vr_youtube_url) ? null : '<a href="'.$item->vr_youtube_url.'" target="_blank" rel="noopener noreferrer">'.$item->vr_youtube_url.'</a>';
            })
            ->addColumn('status', function ($item) {
                $element = '';
                if ($item->is_active) {
                    $query = 'on';
                } else {
                    $query = ' ';
                }
                $element .= '<form id="change-'.$item->id.'" action="'.route('admin.home.detail.desa.segmentasi-mapbox.switch', ['id_provinsi' => $item?->regionDetail?->region?->slug, 'id_desa' => $item?->regionDetail?->slug, 'id' => $item?->id]).'" method="POST"> ';
                $element .= csrf_field();
                $element .= method_field('PATCH');
                $element .= '<button data-id ="'.$item->id.'" type="button"  class="btn-change-status main-toggle changeStatus '.$query.'"><span></span> </button>';
                $element .= '</form>';

                return $element;
            })
            ->addColumn('drone', function ($item) {
                $element = '';
                $status = $item->is_drone ? 'Aktif' : 'Nonaktif';
                $class = $item->is_drone ? 'bg-success' : 'bg-secondary';
                $element .= '<div class="badge '.$class.'">'.$status.'</div>';

                return $element;
            })
            ->addColumn('updated_at', function ($item) {
                return Carbon::parse($item->updated_at)->format('d F Y H:i');
            })
            ->with('totalActive', $totalActive)
            ->with('totalInactive', $totalInactive)
            ->rawColumns(['action', 'status', 'map_url', 'drone', 'vr_url', 'vr_youtube_url'])
            ->make(true);
    }

    private function _filterDatatable(Request $request, Builder $query): Builder
    {
        $filter = $request->all();
        $order_column_index = $filter['order'][0]['column'] ?? 0;
        $order_column_dir = $filter['order'][0]['dir'] ?? 'desc';

        $searchKeyword = $request->input('search.value');

        if (isset($searchKeyword)) {
            $query->where(function ($query) use ($searchKeyword) {
                $lowerKeyword = strtolower($searchKeyword);
                $query->whereRaw('LOWER(name) LIKE ?', ["%{$lowerKeyword}%"])
                    ->orWhereRaw('LOWER(latitude) LIKE ?', ["%{$lowerKeyword}%"])
                    ->orWhereRaw('LOWER(longitude) LIKE ?', ["%{$lowerKeyword}%"])
                    ->orWhereRaw('LOWER(map_url) LIKE ?', ["%{$lowerKeyword}%"])
                    ->orWhereRaw('LOWER(type) LIKE ?', ["%{$lowerKeyword}%"])
                    ->orWhere('is_active', 'like', "%$searchKeyword%")
                    ->orWhere('is_drone', 'like', "%$searchKeyword%")
                    ->orWhere('updated_at', 'like', "%$searchKeyword%");
            });
        }

        // Filtering and Ordering
        if ($order_column_index == 0) {
            $query->orderBy('order_point', 'ASC');
        }

        if ($order_column_index == 3) {
            $query->orderBy('name', $order_column_dir);
        }

        if ($order_column_index == 4) {
            $query->orderBy('is_active', $order_column_dir);
        }

        if ($order_column_index == 5) {
            $query->orderBy('updated_at', $order_column_dir);
        }

        return $query;
    }
}
