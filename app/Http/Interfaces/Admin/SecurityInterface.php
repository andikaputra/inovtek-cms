<?php

namespace App\Http\Interfaces\Admin;

use App\Http\Requests\Admin\Security\SecurityUpdateRequest;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

/**
 * Interface SecurityInterface
 */
interface SecurityInterface
{
    /**
     * Render the index view for the security settings.
     *
     * @param  Request  $request  The current HTTP request.
     * @return View The rendered security settings index view.
     */
    public function renderIndex(Request $request): View;

    /**
     * Execute the update action to save changes to the security settings.
     *
     * @param  SecurityUpdateRequest  $request  The request containing validated data for updating security settings.
     * @return bool True if the security settings were successfully updated, false otherwise.
     */
    public function execUpdate(SecurityUpdateRequest $request): bool;
}
