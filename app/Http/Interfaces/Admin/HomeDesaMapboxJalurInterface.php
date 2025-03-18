<?php

namespace App\Http\Interfaces\Admin;

use App\Http\Requests\Admin\HomeDesaMapboxJalur\HomeDesaMapboxJalurOrderRequest;
use App\Http\Requests\Admin\HomeDesaMapboxJalur\HomeDesaMapboxJalurStoreRequest;
use App\Http\Requests\Admin\HomeDesaMapboxJalur\HomeDesaMapboxJalurUpdateRequest;
use App\Models\RegionDetailMapboxList;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Interface HomeDesaMapboxJalurInterface
 */
interface HomeDesaMapboxJalurInterface
{
    /**
     * Render the index view for Desa Mapbox Jalur.
     *
     * @param  Request  $request  The HTTP request instance.
     * @param  string  $id_provinsi  The province identifier.
     * @param  string  $id_desa  The desa identifier.
     * @param  string  $id_mapbox  The mapbox identifier.
     * @return View The view instance for the index page.
     */
    public function renderIndex(Request $request, string $id_provinsi, string $id_desa, string $id_mapbox): View;

    /**
     * Render the list for Desa Segmentasi Mapbox.
     *
     * @param  Request  $request  The HTTP request instance.
     * @param  string  $id_provinsi  The province identifier.
     * @param  string  $id_desa  The desa identifier.
     * @param  string  $id_mapbox  The mapbox identifier.
     * @return Collection The Collection for the list.
     */
    public function renderOrderList(Request $request, string $id_provinsi, string $id_desa, string $id_mapbox): Collection;

    /**
     * Render the list for Desa Segmentasi Mapbox.
     *
     * @param  HomeDesaSegmentasiMapboxOrderRequest  $request  The request instance for storing data.
     * @param  string  $id_provinsi  The province identifier.
     * @param  string  $id_desa  The desa identifier.
     * @param  string  $id_mapbox  The mapbox identifier.
     * @return RegionDetailMapboxList|null The created RegionDetailMapboxList instance or null.
     */
    public function execOrderUpdate(HomeDesaMapboxJalurOrderRequest $request, string $id_provinsi, string $id_desa, string $id_mapbox): bool;

    /**
     * Render the datatable for Desa Mapbox Jalur.
     *
     * @param  Request  $request  The HTTP request instance.
     * @param  string  $id_provinsi  The province identifier.
     * @param  string  $id_desa  The desa identifier.
     * @param  string  $id_mapbox  The mapbox identifier.
     * @return JsonResponse The JSON response for the datatable.
     */
    public function renderDatatable(Request $request, string $id_provinsi, string $id_desa, string $id_mapbox): JsonResponse;

    /**
     * Render the create view for Desa Mapbox Jalur.
     *
     * @param  Request  $request  The HTTP request instance.
     * @param  string  $id_provinsi  The province identifier.
     * @param  string  $id_desa  The desa identifier.
     * @param  string  $id_mapbox  The mapbox identifier.
     * @return View The view instance for the create page.
     */
    public function renderCreate(Request $request, string $id_provinsi, string $id_desa, string $id_mapbox): View;

    /**
     * Execute the store operation for Desa Mapbox Jalur.
     *
     * @param  HomeDesaMapboxJalurStoreRequest  $request  The request instance for storing data.
     * @param  string  $id_provinsi  The province identifier.
     * @param  string  $id_desa  The desa identifier.
     * @param  string  $id_mapbox  The mapbox identifier.
     * @return RegionDetailMapboxList|null The created RegionDetailMapboxList instance or null.
     */
    public function execStore(HomeDesaMapboxJalurStoreRequest $request, string $id_provinsi, string $id_desa, string $id_mapbox): ?RegionDetailMapboxList;

    /**
     * Render the edit view for a specific Desa Mapbox Jalur.
     *
     * @param  Request  $request  The HTTP request instance.
     * @param  string  $id_provinsi  The province identifier.
     * @param  string  $id_desa  The desa identifier.
     * @param  string  $id_mapbox  The mapbox identifier.
     * @param  string  $id  The specific identifier for the entity.
     * @return View The view instance for the edit page.
     */
    public function renderEdit(Request $request, string $id_provinsi, string $id_desa, string $id_mapbox, string $id): View;

    /**
     * Execute the update operation for a specific Desa Mapbox Jalur.
     *
     * @param  HomeDesaMapboxJalurUpdateRequest  $request  The request instance for updating data.
     * @param  string  $id_provinsi  The province identifier.
     * @param  string  $id_desa  The desa identifier.
     * @param  string  $id_mapbox  The mapbox identifier.
     * @param  string  $id  The specific identifier for the entity.
     * @return RegionDetailMapboxList|null The updated RegionDetailMapboxList instance or null.
     */
    public function execUpdate(HomeDesaMapboxJalurUpdateRequest $request, string $id_provinsi, string $id_desa, string $id_mapbox, string $id): ?RegionDetailMapboxList;

    /**
     * Execute the switch operation for a specific Desa Mapbox Jalur.
     *
     * @param  string  $id_provinsi  The province identifier.
     * @param  string  $id_desa  The desa identifier.
     * @param  string  $id_mapbox  The mapbox identifier.
     * @param  string  $id  The specific identifier for the entity.
     * @return bool True if the operation was successful, false otherwise.
     */
    public function execSwitch(string $id_provinsi, string $id_desa, string $id_mapbox, string $id): bool;

    /**
     * Execute the delete operation for a specific Desa Mapbox Jalur.
     *
     * @param  string  $id_provinsi  The province identifier.
     * @param  string  $id_desa  The desa identifier.
     * @param  string  $id_mapbox  The mapbox identifier.
     * @param  string  $id  The specific identifier for the entity.
     * @return bool True if the deletion was successful, false otherwise.
     */
    public function execDelete(string $id_provinsi, string $id_desa, string $id_mapbox, string $id): bool;
}
