<?php

namespace App\Http\Interfaces\Admin;

use App\Http\Requests\Admin\User\UserStoreRequest;
use App\Http\Requests\Admin\User\UserUpdateRequest;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Interface UserInterface
 */
interface UserInterface
{
    /**
     * Render the index view for user management.
     *
     * @param  Request  $request  The current HTTP request.
     * @return View The rendered user management index view.
     */
    public function renderIndex(Request $request): View;

    /**
     * Render the create user form view.
     *
     * @param  Request  $request  The current HTTP request.
     * @return View The rendered create user form view.
     */
    public function renderCreate(Request $request): View;

    /**
     * Render the user data as a JSON response for a datatable.
     *
     * @param  Request  $request  The current HTTP request.
     * @return JsonResponse The JSON response with user data for the datatable.
     */
    public function renderDatatable(Request $request): JsonResponse;

    /**
     * Store a new user with the provided data.
     *
     * @param  UserStoreRequest  $request  The request containing validated data for creating a new user.
     * @return User The created user instance.
     */
    public function execStore(UserStoreRequest $request): User;

    /**
     * Render the edit view for a specific user.
     *
     * @param  Request  $request  The current HTTP request.
     * @param  string  $id  The ID of the user to edit.
     * @return View The rendered edit view for the user.
     */
    public function renderEdit(Request $request, string $id): View;

    /**
     * Update the specified user with the provided data.
     *
     * @param  UserUpdateRequest  $request  The request containing validated data for updating the user.
     * @param  string  $id  The ID of the user to update.
     * @return User The updated user instance.
     */
    public function execUpdate(UserUpdateRequest $request, string $id): User;

    /**
     * Delete the specified user.
     *
     * @param  Request  $request  The current HTTP request.
     * @param  string  $id  The ID of the user to delete.
     * @return bool True if the user was successfully deleted, false otherwise.
     */
    public function execDelete(Request $request, string $id): bool;

    /**
     * Set the status (e.g., active/inactive) of the specified user.
     *
     * @param  Request  $request  The current HTTP request.
     * @param  string  $id  The ID of the user whose status is to be updated.
     * @return bool True if the status was successfully updated, false otherwise.
     */
    public function execSetStatus(Request $request, string $id): bool;
}
