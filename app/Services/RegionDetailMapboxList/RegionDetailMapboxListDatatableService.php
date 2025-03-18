<?php

namespace App\Services\RegionDetailMapboxList;

use App\Models\RegionDetailMapboxList;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class RegionDetailMapboxListDatatableService
{
    public function mapboxListDatatable(Request $request, string $id_mapbox): JsonResponse
    {
        $query = RegionDetailMapboxList::query();
        $query->where('region_detail_mapbox_id', $id_mapbox);

        // FILTER AND SEARCHING
        $this->_filterDatatable($request, $query);
        // END FILTER AND SEARCHING
        $totalActive = (clone $query)->where('is_active', true)->count();
        $totalInactive = (clone $query)->where('is_active', false)->count();

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('action', function ($item) {
                $element = '';
                $element .= '<form  id="delete-'.$item->id.'" action="'.route('admin.home.detail.desa.segmentasi-mapbox.jalur.delete', ['id_provinsi' => $item?->regionDetailMapbox?->regionDetail?->region?->slug, 'id_desa' => $item?->regionDetailMapbox?->regionDetail?->slug, 'id_mapbox' => $item?->regionDetailMapbox?->id, 'id' => $item?->id]).'" method="POST"> ';
                $element .= csrf_field();
                $element .= method_field('DELETE');
                $element .= '<div class="d-flex inline-block">';
                $element .= '<a data-id ="'.$item->id.'"  data-bs-toggle="tooltip" data-bs-placement="top" href="'.route('admin.home.detail.desa.segmentasi-mapbox.jalur.edit', ['id_provinsi' => $item?->regionDetailMapbox?->regionDetail?->region?->slug, 'id_desa' => $item?->regionDetailMapbox?->regionDetail?->slug, 'id_mapbox' => $item?->regionDetailMapbox?->id, 'id' => $item?->id]).'" type="button" class="btn btn-primary btn-sm mt-1 mx-1" title="Ubah Data">
                                <i class="bi bi-pencil-square" ></i>
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
            ->addColumn('status', function ($item) {
                $element = '';
                if ($item->is_active) {
                    $query = 'on';
                } else {
                    $query = ' ';
                }
                $element .= '<form id="change-'.$item->id.'" action="'.route('admin.home.detail.desa.segmentasi-mapbox.jalur.switch', ['id_provinsi' => $item?->regionDetailMapbox?->regionDetail?->region?->slug, 'id_desa' => $item?->regionDetailMapbox?->regionDetail?->slug, 'id_mapbox' => $item?->regionDetailMapbox?->id, 'id' => $item?->id]).'" method="POST"> ';
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
            ->rawColumns(['action', 'status'])
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
                    ->orWhere('is_active', 'like', "%$searchKeyword%")
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
            $query->orderBy('latitude', $order_column_dir);
        }

        if ($order_column_index == 5) {
            $query->orderBy('is_active', $order_column_dir);
        }

        if ($order_column_index == 6) {
            $query->orderBy('updated_at', $order_column_dir);
        }

        return $query;
    }
}
