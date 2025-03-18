<?php

namespace App\Services\ShortLink;

use App\Constants\AppConst;
use App\Models\ShortLink;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class ShortLinkDatatableService
{
    public function shortLinkDatatable(Request $request): JsonResponse
    {
        $query = ShortLink::query();

        // FILTER AND SEARCHING
        $this->_filterDatatable($request, $query);
        // END FILTER AND SEARCHING
        // Count total is_active true and false
        $totalActive = (clone $query)->where('is_active', true)->count();
        $totalInactive = (clone $query)->where('is_active', false)->count();

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('action', function ($item) {
                $element = '';
                $element .= '<form  id="delete-'.$item->id.'" action="'.route('admin.short-link.delete', $item->id).'" method="POST"> ';
                $element .= csrf_field();
                $element .= method_field('DELETE');
                $element .= '<div class="d-flex inline-block">';
                $element .= '<a data-id ="'.$item->id.'"  href="'.route('admin.short-link.edit', $item->id).'" type="button" class="btn btn-primary btn-sm mt-1 mx-1" title="Ubah Data">
                                        <i class="bi bi-pencil-square" ></i>
                                    </a>';
                $element .= '<button data-id ="'.$item->id.'" type="button" class="btn btn-danger btn-alert-confirm btn-sm ms-1 mt-1" title="Hapus Data">
                                        <i class="bi bi-trash3-fill"></i>
                                    </button>
                                    </div>';
                $element .= '</form>';

                return $element;
            })
            ->addColumn('original_url', function ($item) {
                return '<a href="'.$item->original_url.'" target="_blank" rel="noopener noreferrer">'.$item->original_url.'</a>';
            })
            ->addColumn('short_url', function ($item) {
                return '<a href="'.'https://'.AppConst::SHORT_LINK_BASE_URL.'/s/'.$item->short_url.'" target="_blank" rel="noopener noreferrer">'.'https://'.AppConst::SHORT_LINK_BASE_URL.'/s/'.$item->short_url.'</a>';
            })
            ->addColumn('click_count', function ($item) {
                return $item->click_count;
            })
            ->addColumn('qr_code', function ($item) {
                $folderPath = 'short-link-qrcode';
                $fileName = 'short_url_'.$item->id.'.png';
                $filePath = $folderPath.'/'.$fileName;

                $findImg = $filePath && Storage::disk('public')->exists($filePath)
                    ? asset('storage/'.$filePath)
                    : asset('assets/images/default/no-image.jpg');

                return '<img loading="lazy" src="'.$findImg.'" width="150px" class="img img-responsive"></img>';
            })
            ->addColumn('status', function ($item) {
                $element = '';
                if ($item->is_active) {
                    $query = 'on';
                } else {
                    $query = ' ';
                }
                $element .= '<form id="change-'.$item->id.'" action="'.route('admin.short-link.set-status', $item->id).'" method="POST"> ';
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
            ->rawColumns(['action', 'original_url', 'short_url', 'status', 'qr_code'])
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
                $query->whereRaw('LOWER(original_url) LIKE ?', ["%{$lowerKeyword}%"])
                    ->orWhereRaw('LOWER(short_url) LIKE ?', ["%{$lowerKeyword}%"])
                    ->orWhere('is_active', 'like', "%$searchKeyword%")
                    ->orWhere('click_count', 'like', "%$searchKeyword%")
                    ->orWhere('updated_at', 'like', "%$searchKeyword%");
            });
        }

        // Filtering and Ordering
        if ($order_column_index == 0) {
            $query->orderBy('created_at', 'ASC');
        }

        if ($order_column_index == 3) {
            $query->orderBy('short_url', $order_column_dir);
        }

        if ($order_column_index == 4) {
            $query->orderBy('original_url', $order_column_dir);
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
