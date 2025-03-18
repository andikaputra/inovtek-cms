<?php

namespace App\Services\QuizRegistration;

use App\Models\QuizRegistration;
use Carbon\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class QuizRegistrationQueryService
{
    public function checkExistCode(string $code): bool
    {
        return QuizRegistration::where('quiz_code', $code)->lockForUpdate()->exists();
    }

    public function checkExistRegistration(string $quiz_link_id, string $email, string $phone_no): bool
    {
        $query = QuizRegistration::query();
        $query->where('quiz_link_id', $quiz_link_id);
        $query->where(function ($query) use ($email, $phone_no) {
            $query->where('email', $email)
                ->orWhere('phone_no', $phone_no);
        });

        return $query->exists();
    }

    public function getAllQuizRegister(Request $request, string $quiz_link_id): Collection|LengthAwarePaginator
    {
        $query = QuizRegistration::query()
            ->where('quiz_link_id', $quiz_link_id);

        $query->when($request->filled('search'), function ($query) use ($request) {
            $lowerKeyword = strtolower($request->search);
            $query->where(function ($subQuery) use ($lowerKeyword) {
                $subQuery->whereRaw('LOWER(email) LIKE ?', ["%{$lowerKeyword}%"])
                    ->orWhereRaw('LOWER(name) LIKE ?', ["%{$lowerKeyword}%"])
                    ->orWhereRaw('LOWER(age) LIKE ?', ["%{$lowerKeyword}%"])
                    ->orWhereRaw('LOWER(work) LIKE ?', ["%{$lowerKeyword}%"])
                    ->orWhereRaw('LOWER(phone_no) LIKE ?', ["%{$lowerKeyword}%"]);
            });
        });

        if (isset($request->start_date) || isset($request->end_date)) {
            $start_date = $request->start_date ? Carbon::parse($request->start_date)->format('Y-m-d').' 00:00:00' : null;
            $end_date = $request->end_date ? Carbon::parse($request->end_date)->format('Y-m-d').' 23:59:59' : null;

            if ($start_date && $end_date) {
                $query->whereBetween('created_at', [$start_date, $end_date]);
            } elseif ($start_date) {
                $query->whereDate('created_at', '>=', $start_date);
            } elseif ($end_date) {
                $query->whereDate('created_at', '<=', $end_date);
            }
        }

        $query->when($request->filled('email'), function ($query) use ($request) {
            $query->where('email', 'LIKE', '%'.$request->email.'%');
        });

        $query->when($request->filled('name'), function ($query) use ($request) {
            $query->where('name', 'LIKE', '%'.$request->name.'%');
        });

        $query->when($request->filled('phone_no'), function ($query) use ($request) {
            $query->where('phone_no', 'LIKE', '%'.$request->phone_no.'%');
        });

        $query->when($request->filled('age'), function ($query) use ($request) {
            $query->where('age', 'LIKE', '%'.$request->age.'%');
        });

        $query->when($request->filled('work'), function ($query) use ($request) {
            $query->where('work', 'LIKE', '%'.$request->work.'%');
        });

        $query->when($request->filled('village_id'), function ($query) use ($request) {
            $query->where('region_detail_id', $request->village_id);
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
