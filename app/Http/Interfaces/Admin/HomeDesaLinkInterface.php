<?php

namespace App\Http\Interfaces\Admin;

use App\Http\Requests\Admin\HomeDesaLink\HomeDesaLinkStoreRequest;
use App\Http\Requests\Admin\HomeDesaLink\HomeDesaLinkUpdateRequest;
use App\Models\LinkCollection;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Interface HomeDesaLinkInterface
 */
interface HomeDesaLinkInterface
{
    /**
     * Render the index view for the list of regional links.
     *
     * @param  Request  $request  The request received from the user.
     * @param  string  $id_provinsi  The province ID for displaying related links.
     * @return View The view used to display the list of regional links.
     */
    public function renderIndex(Request $request, string $id_provinsi): View;

    /**
     * Render the datatable for the list of regional links.
     *
     * @param  Request  $request  The request received from the user.
     * @param  string  $id_provinsi  The province ID for fetching the related link data.
     * @return JsonResponse The datatable data in JSON format.
     */
    public function renderDatatable(Request $request, string $id_provinsi): JsonResponse;

    /**
     * Render the view for creating a new regional link.
     *
     * @param  Request  $request  The request received from the user.
     * @param  string  $id_provinsi  The province ID for displaying the create link form.
     * @return View The view used for the new regional link creation form.
     */
    public function renderCreate(Request $request, string $id_provinsi): View;

    /**
     * Store a new regional link.
     *
     * @param  HomeDesaLinkStoreRequest  $request  The request containing the data to be saved.
     * @param  string  $id_provinsi  The province ID to store the related link.
     * @return LinkCollection|null The collection of stored regional links, or null if failed.
     */
    public function execStore(HomeDesaLinkStoreRequest $request, string $id_provinsi): ?LinkCollection;

    /**
     * Render the view to edit an existing regional link.
     *
     * @param  Request  $request  The request received from the user.
     * @param  string  $id_provinsi  The province ID for displaying related link data.
     * @param  string  $id  The ID of the regional link to be edited.
     * @return View The view used for the regional link edit form.
     */
    public function renderEdit(Request $request, string $id_provinsi, string $id): View;

    /**
     * Update the content of an existing regional link.
     *
     * @param  HomeDesaLinkUpdateRequest  $request  The request containing the updated data.
     * @param  string  $id_provinsi  The province ID for updating the related regional link.
     * @param  string  $id  The ID of the regional link to be updated.
     * @return LinkCollection|null The collection of updated regional links, or null if failed.
     */
    public function execUpdateContent(HomeDesaLinkUpdateRequest $request, string $id_provinsi, string $id): ?LinkCollection;

    /**
     * Update the active status of a regional link.
     *
     * @param  string  $id_provinsi  The province ID for updating the related regional link's status.
     * @param  string  $id  The ID of the regional link whose status will be updated.
     * @return bool Returns true if the status update is successful, false if failed.
     */
    public function execUpdateActive(string $id_provinsi, string $id): bool;

    /**
     * Delete a regional link.
     *
     * @param  Request  $request  The request received from the user.
     * @param  string  $id_provinsi  The province ID for deleting the related regional link.
     * @param  string  $id  The ID of the regional link to be deleted.
     * @return bool Returns true if the deletion is successful, false if failed.
     */
    public function execDelete(Request $request, string $id_provinsi, string $id): bool;
}
