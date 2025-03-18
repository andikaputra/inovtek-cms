<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Interfaces\Admin\HomeDesaDetailInfoInterface;
use App\Http\Requests\Admin\HomeDesaDetailInfo\HomeDesaDetailInfoUpdateRequest;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

final class HomeDesaDetailInfoController extends Controller
{
    public function __construct(private readonly HomeDesaDetailInfoInterface $homeDesaDetailInfoInterface) {}

    public function edit(Request $request, string $id_provinsi): View
    {
        return $this->homeDesaDetailInfoInterface->renderEdit(request: $request, id_provinsi: $id_provinsi);
    }

    public function update(HomeDesaDetailInfoUpdateRequest $request, string $id_provinsi): RedirectResponse
    {
        try {
            DB::beginTransaction();
            $this->homeDesaDetailInfoInterface->execUpdate(request: $request, id_provinsi: $id_provinsi);
            DB::commit();

            return to_route('admin.home.detail.tentang-aplikasi.edit', ['id_provinsi' => $id_provinsi])->with('success', trans('response.success.update', ['data' => 'Informasi Produk']));
        } catch (Throwable $th) {
            DB::rollBack();

            return to_route('admin.home.detail.tentang-aplikasi.edit', ['id_provinsi' => $id_provinsi])->with('error', $th->getMessage());
        }
    }
}
