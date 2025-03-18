<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Interfaces\Admin\HomeDesaGalleryInterface;
use App\Http\Requests\Admin\HomeDesaGallery\HomeDesaGalleryUpdateRequest;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

final class HomeDesaGalleryController extends Controller
{
    public function __construct(private readonly HomeDesaGalleryInterface $homeDesaGalleryInterface) {}

    public function edit(Request $request, string $id_provinsi): View
    {
        return $this->homeDesaGalleryInterface->renderEdit(request: $request, id_provinsi: $id_provinsi);
    }

    public function update(HomeDesaGalleryUpdateRequest $request, string $id_provinsi): RedirectResponse
    {
        try {
            DB::beginTransaction();
            $this->homeDesaGalleryInterface->execUpdate(request: $request, id_provinsi: $id_provinsi);
            DB::commit();

            return to_route('admin.home.detail.galeri-wilayah.edit', ['id_provinsi' => $id_provinsi])->with('success', trans('response.success.update', ['data' => 'Galeri Wilayah']));
        } catch (Throwable $th) {
            DB::rollBack();

            return to_route('admin.home.detail.galeri-wilayah.edit', ['id_provinsi' => $id_provinsi])->with('error', $th->getMessage());
        }
    }
}
