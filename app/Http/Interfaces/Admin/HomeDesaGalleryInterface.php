<?php

namespace App\Http\Interfaces\Admin;

use App\Http\Requests\Admin\HomeDesaGallery\HomeDesaGalleryUpdateRequest;
use App\Models\RegionGallery;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

/**
 * Interface HomeDesaGalleryInterface
 */
interface HomeDesaGalleryInterface
{
    /**
     * Render the view for editing a gallery item.
     *
     * @param  Request  $request  The current request instance.
     * @param  string  $id_provinsi  The ID of the province associated with the gallery item.
     * @return View The view for editing the gallery item.
     */
    public function renderEdit(Request $request, string $id_provinsi): View;

    /**
     * Update an existing gallery item.
     *
     * @param  HomeDesaGalleryUpdateRequest  $request  The request containing updated data for the gallery item.
     * @param  string  $id_provinsi  The ID of the province associated with the gallery item.
     * @return RegionGallery The updated gallery item instance.
     */
    public function execUpdate(HomeDesaGalleryUpdateRequest $request, string $id_provinsi): RegionGallery;
}
