<?php

namespace App\Services\RegionDetail;

use App\Constants\AppConst;
use App\Models\RegionDetail;
use App\Services\Region\RegionQueryService;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class RegionDetailDatatableService
{
    public function __construct(
        private readonly RegionQueryService $regionQueryService,
    ) {}

    public function regionDetailDatatable(Request $request, string $id_provinsi): JsonResponse
    {
        $query = RegionDetail::query();
        $query->where('region_id', $id_provinsi);

        // FILTER AND SEARCHING
        $this->_filterDatatable($request, $query, $id_provinsi);
        // END FILTER AND SEARCHING
        $totalActive = (clone $query)->where('is_active', true)->count();
        $totalInactive = (clone $query)->where('is_active', false)->count();

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('action', function ($item) {
                $element = '';
                $element .= '<form  id="delete-'.$item->id.'" action="'.route('admin.home.detail.desa.delete', ['id_provinsi' => $item->region->slug, 'id' => $item->slug]).'" method="POST"> ';
                $element .= csrf_field();
                $element .= method_field('DELETE');
                $element .= '<div class="d-flex inline-block">';
                $element .= '<a data-id ="'.$item->id.'"  data-bs-toggle="tooltip" data-bs-placement="top" href="'.route('admin.home.detail.desa.edit', ['id_provinsi' => $item->region->slug, 'id' => $item->slug]).'" type="button" class="btn btn-primary btn-sm mt-1 mx-1" title="Ubah Data">
                                <i class="bi bi-pencil-square" ></i>
                            </a>';
                if ($item->region->existingApps && $item->region->existingApps->isNotEmpty()) {
                    $arr_existing_app = $item->region->existingApps->pluck('code')->toArray();
                    if (in_array(AppConst::CODE_EXISTING_APP['02'], $arr_existing_app)) {
                        $element .= '<a data-id ="'.$item->id.'"  data-bs-toggle="tooltip" data-bs-placement="top" href="'.route('admin.home.detail.desa.segmentasi-mapbox.index', ['id_provinsi' => $item->region->slug, 'id_desa' => $item->slug]).'" type="button" class="btn btn-warning text-white btn-sm mt-1 mx-1" title="Segmentasi Mapbox">
                                        <i class="bi bi-geo-alt"></i>
                                    </a>';
                    }
                }
                $element .= '<button data-bs-toggle="tooltip" data-bs-placement="top" data-id ="'.$item->id.'" type="button" class="btn btn-danger btn-alert-confirm btn-sm ms-1 mt-1" title="Hapus Data">
                                <i class="bi bi-trash3-fill"></i>
                            </button>
                            </div>';
                $element .= '</form>';

                return $element;
            })
            ->addColumn('village', function ($item) {
                return $item->village;
            })
            ->addColumn('lat_long', function ($item) {
                return $item->latitude.', '.$item->longitude;
            })
            ->addColumn('map_url', function ($item) {
                return ! isset($item->map_url) ? null : '<a href="'.$item->map_url.'" target="_blank" rel="noopener noreferrer">'.$item->map_url.'</a>';
            })
            ->addColumn('status', function ($item) {
                $element = '';
                if ($item->is_active) {
                    $query = 'on';
                } else {
                    $query = ' ';
                }
                $element .= '<form id="change-'.$item->id.'" action="'.route('admin.home.detail.desa.switch', ['id_provinsi' => $item->region->slug, 'id' => $item->slug]).'" method="POST"> ';
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
            ->rawColumns(['action', 'status', 'map_url'])
            ->make(true);
    }

    private function _filterDatatable(Request $request, Builder $query, string $id_provinsi): Builder
    {
        $filter = $request->all();
        $order_column_index = $filter['order'][0]['column'] ?? 0;
        $order_column_dir = $filter['order'][0]['dir'] ?? 'desc';

        $searchKeyword = $request->input('search.value');

        if (isset($searchKeyword)) {
            $query->where(function ($query) use ($searchKeyword) {
                $lowerKeyword = strtolower($searchKeyword);
                $query->whereRaw('LOWER(village) LIKE ?', ["%{$lowerKeyword}%"])
                    ->orWhereRaw('LOWER(latitude) LIKE ?', ["%{$lowerKeyword}%"])
                    ->orWhereRaw('LOWER(longitude) LIKE ?', ["%{$lowerKeyword}%"])
                    ->orWhereRaw('LOWER(map_url) LIKE ?', ["%{$lowerKeyword}%"])
                    ->orWhere('is_active', 'like', "%$searchKeyword%")
                    ->orWhere('updated_at', 'like', "%$searchKeyword%");
            });
        }

        // Filtering and Ordering
        $findRegion = $this->regionQueryService->findRegionById(id: $id_provinsi);
        if ($findRegion->existingApps && $findRegion->existingApps->isNotEmpty()) {
            $arr_existing_app = $findRegion->existingApps->pluck('code')->toArray();
            if (! in_array(AppConst::CODE_EXISTING_APP['02'], $arr_existing_app)) {
                if ($order_column_index == 0) {
                    $query->orderBy('created_at', 'ASC');
                }

                if ($order_column_index == 2) {
                    $query->orderBy('village', $order_column_dir);
                }

                if ($order_column_index == 3) {
                    $query->orderBy('is_active', $order_column_dir);
                }

                if ($order_column_index == 4) {
                    $query->orderBy('updated_at', $order_column_dir);
                }
            } else {
                if ($order_column_index == 0) {
                    $query->orderBy('created_at', 'ASC');
                }

                if ($order_column_index == 2) {
                    $query->orderBy('village', $order_column_dir);
                }

                if ($order_column_index == 3) {
                    $query->orderBy('latitude', $order_column_dir);
                }

                if ($order_column_index == 4) {
                    $query->orderBy('map_url', $order_column_dir);
                }

                if ($order_column_index == 5) {
                    $query->orderBy('is_active', $order_column_dir);
                }

                if ($order_column_index == 6) {
                    $query->orderBy('updated_at', $order_column_dir);
                }
            }
        }

        return $query;
    }
}
