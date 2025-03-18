<?php

namespace App\Http\Interfaces\Admin;

use App\Http\Requests\Admin\Profile\ProfileUpdateRequest;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

/**
 * Interface ProfileInterface
 */
interface ProfileInterface
{
    /**
     * Render the index view for the user profile.
     *
     * @param  Request  $request  The current HTTP request.
     * @return View The rendered profile index view.
     */
    public function renderIndex(Request $request): View;

    /**
     * Execute the update action to save changes to the user profile.
     *
     * @param  ProfileUpdateRequest  $request  The request containing validated data for updating the profile.
     * @return bool True if the profile was successfully updated, false otherwise.
     */
    public function execUpdate(ProfileUpdateRequest $request): bool;
}
