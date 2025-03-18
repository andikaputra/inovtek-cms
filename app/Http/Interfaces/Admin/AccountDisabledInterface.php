<?php

namespace App\Http\Interfaces\Admin;

use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

/**
 * Interface AccountDisabledInterface
 */
interface AccountDisabledInterface
{
    /**
     * Render the index view for an account that has been disabled.
     *
     * @param  Request  $request  The current HTTP request.
     * @return View The rendered view indicating the account is disabled.
     */
    public function renderIndex(Request $request): View;
}
