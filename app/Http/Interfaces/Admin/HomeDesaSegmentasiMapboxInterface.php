<?php

namespace App\Http\Interfaces\Admin;

use App\Http\Requests\Admin\HomeDesaSegmentasiMapbox\HomeDesaSegmentasiMapboxOrderRequest;
use App\Http\Requests\Admin\HomeDesaSegmentasiMapbox\HomeDesaSegmentasiMapboxStoreRequest;
use App\Http\Requests\Admin\HomeDesaSegmentasiMapbox\HomeDesaSegmentasiMapboxUpdateRequest;
use App\Models\RegionDetailMapbox;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Interface HomeDesaSegmentasiMapboxInterface
 */
interface HomeDesaSegmentasiMapboxInterface
{
    /**
     * Render the index view for Desa Segmentasi Mapbox.
     *
     * @param  Request  $request  The HTTP request instance.
     * @param  string  $id_provinsi  The province identifier.
     * @param  string  $id_desa  The desa identifier.
     * @return View The view instance for the index page.
     */
    public function renderIndex(Request $request, string $id_provinsi, string $id_desa): View;

    /**
     * Render the list for Desa Segmentasi Mapbox.
     *
     * @param  Request  $request  The HTTP request instance.
     * @param  string  $id_provinsi  The province identifier.
     * @param  string  $id_desa  The desa identifier.
     * @return Collection The Collection for the list.
     */
    public function renderOrderList(Request $request, string $id_provinsi, string $id_desa): Collection;

    /**
     * Render the list for Desa Segmentasi Mapbox.
     *
     * @param  HomeDesaSegmentasiMapboxOrderRequest  $request  The request instance for storing data.
     * @param  string  $id_provinsi  The province identifier.
     * @param  string  $id_desa  The desa identifier.
     * @return RegionDetailMapbox|null The created RegionDetailMapbox instance or null.
     */
    public function execOrderUpdate(HomeDesaSegmentasiMapboxOrderRequest $request, string $id_provinsi, string $id_desa): bool;

    /**
     * Render the datatable for Desa Segmentasi Mapbox.
     *
     * @param  Request  $request  The HTTP request instance.
     * @param  string  $id_provinsi  The province identifier.
     * @param  string  $id_desa  The desa identifier.
     * @return JsonResponse The JSON response for the datatable.
     */
    public function renderDatatable(Request $request, string $id_provinsi, string $id_desa): JsonResponse;

    /**
     * Render the create view for Desa Segmentasi Mapbox.
     *
     * @param  Request  $request  The HTTP request instance.
     * @param  string  $id_provinsi  The province identifier.
     * @param  string  $id_desa  The desa identifier.
     * @return View The view instance for the create page.
     */
    public function renderCreate(Request $request, string $id_provinsi, string $id_desa): View;

    /**
     * Execute the store operation for Desa Segmentasi Mapbox.
     *
     * @param  HomeDesaSegmentasiMapboxStoreRequest  $request  The request instance for storing data.
     * @param  string  $id_provinsi  The province identifier.
     * @param  string  $id_desa  The desa identifier.
     * @return RegionDetailMapbox|null The created RegionDetailMapbox instance or null.
     */
    public function execStore(HomeDesaSegmentasiMapboxStoreRequest $request, string $id_provinsi, string $id_desa): ?RegionDetailMapbox;

    /**
     * Render the edit view for a specific Desa Segmentasi Mapbox.
     *
     * @param  Request  $request  The HTTP request instance.
     * @param  string  $id_provinsi  The province identifier.
     * @param  string  $id_desa  The desa identifier.
     * @param  string  $id  The specific identifier for the entity.
     * @return View The view instance for the edit page.
     */
    public function renderEdit(Request $request, string $id_provinsi, string $id_desa, string $id): View;

    /**
     * Execute the update operation for a specific Desa Segmentasi Mapbox.
     *
     * @param  HomeDesaSegmentasiMapboxUpdateRequest  $request  The request instance for updating data.
     * @param  string  $id_provinsi  The province identifier.
     * @param  string  $id_desa  The desa identifier.
     * @param  string  $id  The specific identifier for the entity.
     * @return RegionDetailMapbox|null The updated RegionDetailMapbox instance or null.
     */
    public function execUpdate(HomeDesaSegmentasiMapboxUpdateRequest $request, string $id_provinsi, string $id_desa, string $id): ?RegionDetailMapbox;

    /**
     * Execute the switch operation for a specific Desa Segmentasi Mapbox.
     *
     * @param  string  $id_provinsi  The province identifier.
     * @param  string  $id_desa  The desa identifier.
     * @param  string  $id  The specific identifier for the entity.
     * @return bool True if the operation was successful, false otherwise.
     */
    public function execSwitch(string $id_provinsi, string $id_desa, string $id): bool;

    /**
     * Execute the delete operation for a specific Desa Segmentasi Mapbox.
     *
     * @param  string  $id_provinsi  The province identifier.
     * @param  string  $id_desa  The desa identifier.
     * @param  string  $id  The specific identifier for the entity.
     * @return bool True if the deletion was successful, false otherwise.
     */
    public function execDelete(string $id_provinsi, string $id_desa, string $id): bool;
}
