<?php

namespace App\Services\QuizRegistration;

use App\Models\QuizRegistration;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class QuizRegistrationDatatableService
{
    public function quizRegistrationDatatable(Request $request, string $id_kuis): JsonResponse
    {
        $query = QuizRegistration::query();
        $query->where('quiz_link_id', $id_kuis);

        if (isset($request->search_general)) {
            $query->where(function ($query) use ($request) {
                $lowerKeyword = strtolower($request->search_general);
                $query->whereRaw('LOWER(email) LIKE ?', ["%{$lowerKeyword}%"])
                    ->orWhereRaw('LOWER(name) LIKE ?', ["%{$lowerKeyword}%"])
                    ->orWhereRaw('LOWER(age) LIKE ?', ["%{$lowerKeyword}%"])
                    ->orWhereRaw('LOWER(work) LIKE ?', ["%{$lowerKeyword}%"])
                    ->orWhereRaw('LOWER(quiz_code) LIKE ?', ["%{$lowerKeyword}%"])
                    ->orWhereRaw('LOWER(phone_no) LIKE ?', ["%{$lowerKeyword}%"]);
            });
        }

        if (isset($request->date_range)) {
            $dates = explode(' to ', $request->date_range);
            if (count($dates) === 2) {
                $start_date = $dates[0].' 00:00:00';
                $end_date = $dates[1].' 23:59:59';

                $query->whereBetween('created_at', [$start_date, $end_date]);
            } elseif (count($dates) === 1) {
                $date = $dates[0];

                $query->whereDate('created_at', $date);
            }
        }

        if (isset($request->village_id)) {
            if ($request->village_id != 'semua') {
                $query->where('region_detail_id', $request->village_id);
            }
        }
        // FILTER AND SEARCHING
        $this->_filterDatatable($request, $query);
        // END FILTER AND SEARCHING

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('name', function ($item) {
                return $item->name;
            })
            ->addColumn('email', function ($item) {
                return '<a href="mailto:'.$item->email.'" target="_blank" rel="noopener noreferrer">'.$item->email.'</a>';
            })
            ->addColumn('phone_no', function ($item) {
                return '<a href="https://wa.me/'.$item->phone_no.'" target="_blank" rel="noopener noreferrer">'.$item->phone_no.'</a>';
            })
            ->addColumn('sex_type', function ($item) {
                return $item->sex_type == 'L' ? 'Laki-Laki' : 'Perempuan';
            })
            ->addColumn('village', function ($item) {
                return $item->regionDetail?->village;
            })
            ->addColumn('created_at', function ($item) {
                return Carbon::parse($item->created_at)->format('d F Y H:i');
            })
            ->rawColumns(['action', 'status', 'email', 'phone_no'])
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
                $query->whereRaw('LOWER(email) LIKE ?', ["%{$lowerKeyword}%"])
                    ->orWhereRaw('LOWER(name) LIKE ?', ["%{$lowerKeyword}%"])
                    ->orWhereRaw('LOWER(phone_no) LIKE ?', ["%{$lowerKeyword}%"])
                    ->orWhereRaw('LOWER(age) LIKE ?', ["%{$lowerKeyword}%"])
                    ->orWhereRaw('LOWER(quiz_code) LIKE ?', ["%{$lowerKeyword}%"])
                    ->orWhereRaw('LOWER(work) LIKE ?', ["%{$lowerKeyword}%"])
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
            $query->orderBy('email', $order_column_dir);
        }

        if ($order_column_index == 4) {
            $query->orderBy('phone_no', $order_column_dir);
        }

        if ($order_column_index == 5) {
            $query->orderBy('region_detail_id', $order_column_dir);
        }

        if ($order_column_index == 6) {
            $query->orderBy('updated_at', $order_column_dir);
        }

        return $query;
    }
}
