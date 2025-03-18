<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Interfaces\Admin\SeoManagementInterface;
use App\Http\Requests\Admin\SeoManagement\SeoManagementStoreUpdateRequest;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Throwable;

final class SeoManagementController extends Controller
{
    public function __construct(private readonly SeoManagementInterface $seoManagementInterface) {}

    public function edit(string $id_provinsi, string $type, string $id): View
    {
        return $this->seoManagementInterface->renderEdit(id_provinsi: $id_provinsi, type: $type, id: $id);
    }

    public function update(SeoManagementStoreUpdateRequest $request, string $id_provinsi, string $type, string $id): RedirectResponse
    {
        try {
            DB::beginTransaction();
            $this->seoManagementInterface->execUpdate(request: $request, id_provinsi: $id_provinsi, type: $type, id: $id);
            DB::commit();

            return to_route('admin.home.detail.seo-wilayah.edit', ['id_provinsi' => $id_provinsi, 'type' => $type, 'id_key' => $id])->with('success', trans('response.success.update', ['data' => 'SEO Configuration']));
        } catch (Throwable $th) {
            DB::rollBack();

            return to_route('admin.home.detail.seo-wilayah.edit', ['id_provinsi' => $id_provinsi, 'type' => $type, 'id_key' => $id])->with('error', $th->getMessage());
        }
    }

    public function editUmum(string $type, string $id): View
    {
        return $this->seoManagementInterface->renderEditUmum(type: $type, id: $id);
    }

    public function updateUmum(SeoManagementStoreUpdateRequest $request, string $type, string $id): RedirectResponse
    {
        try {
            DB::beginTransaction();
            $this->seoManagementInterface->execUpdateUmum(request: $request, type: $type, id: $id);
            DB::commit();

            return to_route('admin.seo-artikel-umum.edit', ['type' => $type, 'id_key' => $id])->with('success', trans('response.success.update', ['data' => 'SEO Configuration']));
        } catch (Throwable $th) {
            DB::rollBack();

            return to_route('admin.seo-artikel-umum.edit', ['type' => $type, 'id_key' => $id])->with('error', $th->getMessage());
        }
    }
}
