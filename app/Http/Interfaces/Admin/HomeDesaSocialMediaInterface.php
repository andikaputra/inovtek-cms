<?php

namespace App\Http\Interfaces\Admin;

use App\Http\Requests\Admin\HomeDesaSocialMedia\HomeDesaSocialMediaStoreRequest;
use App\Http\Requests\Admin\HomeDesaSocialMedia\HomeDesaSocialMediaUpdateRequest;
use App\Models\LinkCollection;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Interface HomeDesaSocialMediaInterface
 */
interface HomeDesaSocialMediaInterface
{
    /**
     * Render the index view for the list of social media links.
     *
     * @param  Request  $request  The request received from the user.
     * @param  string  $id_provinsi  The province ID for displaying related social media links.
     * @return View The view used to display the list of social media links.
     */
    public function renderIndex(Request $request, string $id_provinsi): View;

    /**
     * Render the datatable for the list of social media links.
     *
     * @param  Request  $request  The request received from the user.
     * @param  string  $id_provinsi  The province ID for fetching related social media link data.
     * @return JsonResponse The datatable data in JSON format.
     */
    public function renderDatatable(Request $request, string $id_provinsi): JsonResponse;

    /**
     * Render the view for creating a new social media link.
     *
     * @param  Request  $request  The request received from the user.
     * @param  string  $id_provinsi  The province ID for displaying the create social media link form.
     * @return View The view used for the new social media link creation form.
     */
    public function renderCreate(Request $request, string $id_provinsi): View;

    /**
     * Store a new social media link.
     *
     * @param  HomeDesaSocialMediaStoreRequest  $request  The request containing the data to be saved.
     * @param  string  $id_provinsi  The province ID to store the related social media link.
     * @return LinkCollection|null The collection of stored social media links, or null if failed.
     */
    public function execStore(HomeDesaSocialMediaStoreRequest $request, string $id_provinsi): ?LinkCollection;

    /**
     * Render the view to edit an existing social media link.
     *
     * @param  Request  $request  The request received from the user.
     * @param  string  $id_provinsi  The province ID for displaying related social media link data.
     * @param  string  $id  The ID of the social media link to be edited.
     * @return View The view used for the social media link edit form.
     */
    public function renderEdit(Request $request, string $id_provinsi, string $id): View;

    /**
     * Update the content of an existing social media link.
     *
     * @param  HomeDesaSocialMediaUpdateRequest  $request  The request containing the updated data.
     * @param  string  $id_provinsi  The province ID for updating the related social media link.
     * @param  string  $id  The ID of the social media link to be updated.
     * @return LinkCollection|null The collection of updated social media links, or null if failed.
     */
    public function execUpdateContent(HomeDesaSocialMediaUpdateRequest $request, string $id_provinsi, string $id): ?LinkCollection;

    /**
     * Update the active status of a social media link.
     *
     * @param  string  $id_provinsi  The province ID for updating the related social media link's status.
     * @param  string  $id  The ID of the social media link whose status will be updated.
     * @return bool Returns true if the status update is successful, false if failed.
     */
    public function execUpdateActive(string $id_provinsi, string $id): bool;

    /**
     * Delete a social media link.
     *
     * @param  Request  $request  The request received from the user.
     * @param  string  $id_provinsi  The province ID for deleting the related social media link.
     * @param  string  $id  The ID of the social media link to be deleted.
     * @return bool Returns true if the deletion is successful, false if failed.
     */
    public function execDelete(Request $request, string $id_provinsi, string $id): bool;
}
