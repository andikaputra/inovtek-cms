<?php

namespace App\Http\Interfaces\Admin;

use App\Http\Requests\Admin\HomeDesaDetailInfo\HomeDesaDetailInfoUpdateRequest;
use App\Models\RegionDetailInfo;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

/**
 * Interface HomeDesaDetailInfoInterface
 */
interface HomeDesaDetailInfoInterface
{
    /**
     * Renders the edit view for the Home Desa Detail Info.
     *
     * @param  Request  $request  The HTTP request instance.
     * @param  string  $id_provinsi  The ID of the province to filter the information.
     * @return View The view to render for editing Home Desa Detail Info.
     */
    public function renderEdit(Request $request, string $id_provinsi): View;

    /**
     * Executes the update operation for the Home Desa Detail Info.
     *
     * @param  HomeDesaDetailInfoUpdateRequest  $request  The validated request instance containing update data.
     * @param  string  $id_provinsi  The ID of the province to be updated.
     * @return RegionDetailInfo|null The updated instance of RegionDetailInfo or null if update fails.
     */
    public function execUpdate(HomeDesaDetailInfoUpdateRequest $request, string $id_provinsi): ?RegionDetailInfo;
}
