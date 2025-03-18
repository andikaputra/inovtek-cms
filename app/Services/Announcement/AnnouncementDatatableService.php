<?php

namespace App\Services\Announcement;

use App\Models\AnnouncementLink;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class AnnouncementDatatableService
{
    public function __construct(
        private readonly AnnouncementQueryService $announcementQueryService
    ) {}

    public function announcementDatatable(Request $request, string $id_provinsi): JsonResponse
    {
        $query = AnnouncementLink::query();
        $query->where('region_id', $id_provinsi);

        // FILTER AND SEARCHING
        $this->_filterDatatable($request, $query);
        // END FILTER AND SEARCHING
        $totalActive = (clone $query)->where('is_active', true)->count();
        $totalInactive = (clone $query)->where('is_active', false)->count();

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('action', function ($item) {
                $element = '';

                $routeDelete = route('admin.home.detail.pengumuman.delete', ['id_provinsi' => $item->region->slug, 'id' => $item->id]);
                $routeEdit = route('admin.home.detail.pengumuman.edit', ['id_provinsi' => $item->region->slug, 'id' => $item->id]);

                $element .= '<form  id="delete-'.$item->id.'" action="'.$routeDelete.'" method="POST"> ';
                $element .= csrf_field();
                $element .= method_field('DELETE');
                $element .= '<div class="d-flex inline-block">';
                $element .= '<a data-id ="'.$item->id.'"  href="'.$routeEdit.'"  data-bs-toggle="tooltip" data-bs-placement="top" type="button" class="btn btn-primary btn-sm mt-1 mx-1" title="Ubah Data">
                                        <i class="bi bi-pencil-square" ></i>
                                    </a>';
                $element .= '<button data-id ="'.$item->id.'" data-bs-toggle="tooltip" data-bs-placement="top" type="button" class="btn btn-danger btn-alert-confirm btn-sm ms-1 mt-1" title="Hapus Data">
                                        <i class="bi bi-trash3-fill"></i>
                                    </button>
                                    </div>';
                $element .= '</form>';

                return $element;
            })
            ->addColumn('link', function ($item) {
                return isset($item->announcement_link) ? '<a href="'.$item->announcement_link.'" target="_blank" rel="noopener noreferrer">'.$item->announcement_link.'</a>' : null;
            })
            ->addColumn('name', function ($item) {
                return $item->name;
            })
            ->addColumn('status', function ($item) use ($id_provinsi) {
                $element = '';
                $checkExistActivePengumuman = $this->announcementQueryService->checkExistActive(id_provinsi: $id_provinsi);
                if ($item->is_active || ! $checkExistActivePengumuman) {
                    if ($item->is_active) {
                        $query = 'on';
                    } else {
                        $query = ' ';
                    }
                    $routeUpdate = route('admin.home.detail.pengumuman.update.active', ['id_provinsi' => $item->region->slug, 'id' => $item->id]);

                    $element .= '<form id="change-'.$item->id.'" action="'.$routeUpdate.'" method="POST"> ';
                    $element .= csrf_field();
                    $element .= method_field('PATCH');
                    $element .= '<button data-id ="'.$item->id.'" type="button"  class="main-toggle btn-change-status changeStatus '.$query.'"><span></span> </button>';
                    $element .= '</form>';
                } else {
                    $status = $item->is_active ? 'Aktif' : 'Nonaktif';
                    $class = $item->is_active ? 'bg-success' : 'bg-secondary';
                    $element .= '<div class="badge '.$class.'">'.$status.'</div>';
                }

                return $element;
            })
            ->addColumn('updated_at', function ($item) {
                return Carbon::parse($item->updated_at)->format('d F Y H:i');
            })
            ->with('totalActive', $totalActive)
            ->with('totalInactive', $totalInactive)
            ->rawColumns(['action', 'status', 'link'])
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
                $query->whereRaw('LOWER(announcement_link) LIKE ?', ["%{$lowerKeyword}%"])
                    ->orWhereRaw('LOWER(name) LIKE ?', ["%{$lowerKeyword}%"])
                    ->orWhere('is_active', 'like', "%$searchKeyword%")
                    ->orWhere('updated_at', 'like', "%$searchKeyword%");
            });
        }

        // Filtering and Ordering
        if ($order_column_index == 0) {
            $query->orderBy('created_at', 'ASC');
        }

        if ($order_column_index == 2) {
            $query->orderBy('name', $order_column_dir);
        }

        if ($order_column_index == 3) {
            $query->orderBy('announcement_link', $order_column_dir);
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
