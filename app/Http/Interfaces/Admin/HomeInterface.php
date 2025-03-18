<?php

namespace App\Http\Interfaces\Admin;

use App\Http\Requests\Admin\Home\HomeStoreRequest;
use App\Http\Requests\Admin\Home\HomeUpdateRequest;
use App\Models\Region;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

/**
 * Interface HomeInterface
 */
interface HomeInterface
{
    /**
     * Render the index view for regions.
     *
     * @param  Request  $request  The current HTTP request.
     * @return View The rendered index view.
     */
    public function renderIndex(Request $request): View;

    /**
     * Render the create form view for adding a new region.
     *
     * @param  Request  $request  The current HTTP request.
     * @return View The rendered create view.
     */
    public function renderCreate(Request $request): View;

    /**
     * Execute the store action to save a new region.
     *
     * @param  HomeStoreRequest  $request  The request containing validated data for creating a region.
     * @return Region|null The saved Region model instance, or null on failure.
     */
    public function execStore(HomeStoreRequest $request): ?Region;

    /**
     * Render the edit form view for an existing region.
     *
     * @param  Request  $request  The current HTTP request.
     * @param  string  $id  The ID of the region to edit.
     * @return View The rendered edit view.
     */
    public function renderEdit(Request $request, string $id): View;

    /**
     * Execute the update action for an existing region.
     *
     * @param  HomeUpdateRequest  $request  The request containing validated data for updating the region.
     * @param  string  $id  The ID of the region to update.
     * @return Region|null The updated Region model instance, or null on failure.
     */
    public function execUpdate(HomeUpdateRequest $request, string $id): ?Region;

    /**
     * Toggle the active status of a region.
     *
     * @param  string  $id  The ID of the region to toggle.
     * @return Region|null The Region model instance with updated status, or null on failure.
     */
    public function execSwitch(string $id): ?Region;

    /**
     * Execute the delete action for a region.
     *
     * @param  string  $id  The ID of the region to delete.
     * @return bool True if the deletion was successful, false otherwise.
     */
    public function execDelete(string $id): bool;

    /**
     * Read notification for general
     *
     * @param  Request  $request  The current HTTP request.
     * @return bool The return data
     */
    public function execReadNotification(Request $request): bool;
}
