<?php

namespace App\Http\Interfaces\Admin;

use App\Http\Requests\Admin\ShortLink\ShortLinkStoreRequest;
use App\Http\Requests\Admin\ShortLink\ShortLinkUpdateRequest;
use App\Models\ShortLink;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

/**
 * Interface ShortLinkInterface
 */
interface ShortLinkInterface
{
    /**
     * Render the index view for short link management.
     *
     * @param  Request  $request  The current HTTP request.
     * @return View The rendered short link management index view.
     */
    public function renderIndex(Request $request): View;

    /**
     * Render the create short link form view.
     *
     * @param  Request  $request  The current HTTP request.
     * @return View The rendered create short link form view.
     */
    public function renderCreate(Request $request): View;

    /**
     * Render the short link data as a JSON response for a datatable.
     *
     * @param  Request  $request  The current HTTP request.
     * @return JsonResponse The JSON response with short link data for the datatable.
     */
    public function renderDatatable(Request $request): JsonResponse;

    /**
     * Store a new short link with the provided data.
     *
     * @param  ShortLinkStoreRequest  $request  The request containing validated data for creating a new short link.
     * @return ShortLink The created short link instance.
     */
    public function execStore(ShortLinkStoreRequest $request): ?ShortLink;

    /**
     * Render the edit view for a specific short link.
     *
     * @param  Request  $request  The current HTTP request.
     * @param  string  $id  The ID of the short link to edit.
     * @return View The rendered edit view for the short link.
     */
    public function renderEdit(Request $request, string $id): View;

    /**
     * Update the specified short link with the provided data.
     *
     * @param  ShortLinkUpdateRequest  $request  The request containing validated data for updating the short link.
     * @param  string  $id  The ID of the short link to update.
     * @return ShortLink The updated short link instance.
     */
    public function execUpdate(ShortLinkUpdateRequest $request, string $id): ?ShortLink;

    /**
     * Delete the specified short link.
     *
     * @param  Request  $request  The current HTTP request.
     * @param  string  $id  The ID of the short link to delete.
     * @return bool True if the shirt link was successfully deleted, false otherwise.
     */
    public function execDelete(Request $request, string $id): bool;

    /**
     * Set the status (e.g., active/inactive) of the specified short link.
     *
     * @param  Request  $request  The current HTTP request.
     * @param  string  $id  The ID of the short link whose status is to be updated.
     * @return bool True if the status was successfully updated, false otherwise.
     */
    public function execSetStatus(Request $request, string $id): bool;

    /**
     * Redirect to original link
     *
     * @param  Request  $request  The request received from the user.
     * @param  RedirectResponse  Redirect response for the short link.
     * @return View The rendered view for the short link.
     */
    public function execRedirect(Request $request, string $uniqueCode): RedirectResponse;
}
