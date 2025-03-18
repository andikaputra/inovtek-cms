<?php

namespace App\Services\User;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

class UserDatatableService
{
    public function userDatatable(Request $request): JsonResponse
    {
        $query = User::query();
        if (Auth::user()->guid_user != null) {
            $query->whereNotNull('guid_user');
        }

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
                if ($item->guid_user != null) {
                    if ($item->id != Auth::user()->id) {
                        $element .= '<div class="d-flex inline-block">';
                        $element .= '<a data-id ="'.$item->id.'"  href="'.route('admin.user.edit', $item->id).'" type="button" class="btn btn-primary btn-sm mt-1 mx-1" title="Ubah Data">
                                        <i class="bi bi-pencil-square" ></i>
                                     </a>';
                        $element .= '</div>';
                    } else {
                        $element .= '<div class="d-flex inline-block" data-bs-toggle="tooltip" data-bs-placement="top" title="Anda tidak dapat mengubah dan menghapus data ini!">';
                        $element .= '<button type="button" disabled class="btn btn-primary btn-sm mt-1 mx-1" title="Ubah Data">
                                        <i class="bi bi-pencil-square"></i>
                                    </button>';
                        $element .= '</div>';
                    }
                } else {
                    if (! $item->is_default && $item->id != Auth::user()->id) {
                        $element .= '<form  id="delete-'.$item->id.'" action="'.route('admin.user.delete', $item->id).'" method="POST"> ';
                        $element .= csrf_field();
                        $element .= method_field('DELETE');
                        $element .= '<div class="d-flex inline-block">';
                        $element .= '<a data-id ="'.$item->id.'"  href="'.route('admin.user.edit', $item->id).'" type="button" class="btn btn-primary btn-sm mt-1 mx-1" title="Ubah Data">
                                        <i class="bi bi-pencil-square" ></i>
                                    </a>';
                        $element .= '<button data-id ="'.$item->id.'" type="button" class="btn btn-alert-confirm btn-danger btn-sm ms-1 mt-1" title="Hapus Data">
                                        <i class="bi bi-trash3-fill"></i>
                                    </button>
                                    </div>';
                        $element .= '</form>';
                    } else {
                        $element .= '<div class="d-flex inline-block" data-bs-toggle="tooltip" data-bs-placement="top" title="Anda tidak dapat mengubah dan menghapus data ini!">';
                        $element .= '<button type="button" disabled class="btn btn-primary btn-sm mt-1 mx-1" title="Ubah Data">
                                        <i class="bi bi-pencil-square" ></i>
                                    </button>';
                        $element .= '<button type="button" disabled class="btn btn-danger btn-sm ms-1 mt-1" title="Hapus Data">
                                        <i class="bi bi-trash3-fill"></i>
                                    </button>
                                    </div>';
                    }
                }

                return $element;
            })
            ->addColumn('username', function ($item) {
                return $item->username;
            })
            ->addColumn('name', function ($item) {
                return $item->name;
            })
            ->addColumn('email', function ($item) {
                return '<a href="mailto:'.$item->email.'" target="_blank" rel="noopener noreferrer">'.$item->email.'</a>';
            })
            ->addColumn('role_access', function ($item) {
                $element = '';
                $status = $item->is_super_admin ? 'Super Admin' : 'Admin';
                $class = $item->is_super_admin ? 'bg-success' : 'bg-info';
                $element .= '<div class="badge '.$class.'">'.$status.'</div>';

                return $element;
            })
            ->addColumn('status', function ($item) {
                $element = '';
                if (! $item->is_default && $item->id != Auth::user()->id) {
                    if ($item->is_active) {
                        $query = 'on';
                    } else {
                        $query = ' ';
                    }
                    $element .= '<form id="change-'.$item->id.'" action="'.route('admin.user.set-status', $item->id).'" method="POST"> ';
                    $element .= csrf_field();
                    $element .= method_field('PATCH');
                    $element .= '<button data-id ="'.$item->id.'" type="button"  class="btn-change-status main-toggle changeStatus '.$query.'"><span></span> </button>';
                    $element .= '</form>';
                } else {
                    $status = $item->is_active ? 'Aktif' : 'Nonaktif';
                    $class = $item->is_active ? 'bg-success' : 'bg-secondary';
                    $element .= '<div class="badge '.$class.'">'.$status.'</div>';
                }

                return $element;
            })
            ->addColumn('sinkronasi', function ($item) {
                $element = '';
                $status = $item->guid_user != null ? '<i class="bi bi-check2-circle"></i>' : '<i class="bi bi-x-circle"></i>';
                $class = $item->guid_user != null ? 'bg-success' : 'bg-danger';
                $element .= '<center><div class="rounded-circle m-auto sync-badge text-white '.$class.'">'.$status.'</div></center>';

                return $element;
            })
            ->addColumn('updated_at', function ($item) {
                return Carbon::parse($item->updated_at)->format('d F Y H:i');
            })
            ->with('totalActive', $totalActive)
            ->with('totalInactive', $totalInactive)
            ->rawColumns(['action', 'email', 'status', 'role_access', 'sinkronasi'])
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
                $query->whereRaw('LOWER(username) LIKE ?', ["%{$lowerKeyword}%"])
                    ->orWhereRaw('LOWER(name) LIKE ?', ["%{$lowerKeyword}%"])
                    ->orWhereRaw('LOWER(email) LIKE ?', ["%{$lowerKeyword}%"])
                    ->orWhereRaw('LOWER(guid_user) LIKE ?', ["%{$lowerKeyword}%"])
                    ->orWhere('is_active', 'like', "%$searchKeyword%")
                    ->orWhere('is_default', 'like', "%$searchKeyword%")
                    ->orWhere('is_super_admin', 'like', "%$searchKeyword%")
                    ->orWhere('updated_at', 'like', "%$searchKeyword%");
            });
        }

        // Filtering and Ordering
        if ($order_column_index == 0) {
            $query->orderBy('created_at', 'ASC');
        }

        if ($order_column_index == 2) {
            $query->orderBy('username', $order_column_dir);
        }

        if ($order_column_index == 3) {
            $query->orderBy('name', $order_column_dir);
        }

        if ($order_column_index == 4) {
            $query->orderBy('email', $order_column_dir);
        }

        if ($order_column_index == 5) {
            $query->orderBy('is_super_admin', $order_column_dir);
        }

        if ($order_column_index == 6) {
            $query->orderBy('is_active', $order_column_dir);
        }

        if ($order_column_index == 7) {
            $query->orderBy('guid_user', $order_column_dir);
        }

        if ($order_column_index == 8) {
            $query->orderBy('updated_at', $order_column_dir);
        }

        return $query;
    }
}
