<?php

namespace App\Http\Interfaces\Admin;

use App\Http\Requests\Admin\HomeDesa\HomeDesaStoreRequest;
use App\Http\Requests\Admin\HomeDesa\HomeDesaUpdateRequest;
use App\Models\RegionDetail;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

/**
 * Interface HomeDesaInterface
 */
interface HomeDesaInterface
{
    /**
     * Render the index view for villages within a specified province.
     *
     * @param  Request  $request  The current HTTP request.
     * @param  string  $id_provinsi  The ID of the province.
     * @return View The rendered index view.
     */
    public function renderIndex(Request $request, string $id_provinsi): View|RedirectResponse;

    /**
     * Render the create form view for adding a new village to a specified province.
     *
     * @param  Request  $request  The current HTTP request.
     * @param  string  $id_provinsi  The ID of the province.
     * @return View The rendered create view.
     */
    public function renderCreate(Request $request, string $id_provinsi): View;

    /**
     * Execute the store action to save a new village within a specified province.
     *
     * @param  HomeDesaStoreRequest  $request  The request containing validated data for creating a village.
     * @param  string  $id_provinsi  The ID of the province.
     * @return RegionDetail|null The saved RegionDetail model instance, or null on failure.
     */
    public function execStore(HomeDesaStoreRequest $request, string $id_provinsi): ?RegionDetail;

    /**
     * Render the data for a datatable of villages in a specified province.
     *
     * @param  Request  $request  The current HTTP request.
     * @param  string  $id_provinsi  The ID of the province.
     * @return JsonResponse The JSON response containing datatable data.
     */
    public function renderDatatable(Request $request, string $id_provinsi): JsonResponse;

    /**
     * Toggle the active status of a village within a specified province.
     *
     * @param  string  $id_provinsi  The ID of the province.
     * @param  string  $id  The ID of the village to toggle.
     * @return bool True if the status was toggled successfully, false otherwise.
     */
    public function execSwitch(string $id_provinsi, string $id): bool;

    /**
     * Execute the delete action for a village in a specified province.
     *
     * @param  string  $id_provinsi  The ID of the province.
     * @param  string  $id  The ID of the village to delete.
     * @return bool True if the deletion was successful, false otherwise.
     */
    public function execDelete(string $id_provinsi, string $id): bool;

    /**
     * Render the edit form view for an existing village in a specified province.
     *
     * @param  string  $id_provinsi  The ID of the province.
     * @param  string  $id  The ID of the village to edit.
     * @return View The rendered edit view.
     */
    public function renderEdit(string $id_provinsi, string $id): View;

    /**
     * Execute the update action for an existing village within a specified province.
     *
     * @param  HomeDesaUpdateRequest  $request  The request containing validated data for updating the village.
     * @param  string  $id_provinsi  The ID of the province.
     * @param  string  $id  The ID of the village to update.
     * @return RegionDetail|null The updated RegionDetail model instance, or null on failure.
     */
    public function execUpdate(HomeDesaUpdateRequest $request, string $id_provinsi, string $id): ?RegionDetail;
}
