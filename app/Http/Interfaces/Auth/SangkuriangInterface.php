<?php

namespace App\Http\Interfaces\Auth;

use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

/**
 * Interface SangkuriangInterface
 */
interface SangkuriangInterface
{
    /**
     * Executes the login handling process.
     *
     * @param  Request  $request  The HTTP request instance containing login credentials.
     * @return RedirectResponse A redirect response upon successful login or failure.
     */
    public function execHandleLogin(Request $request): RedirectResponse;

    /**
     * Renders the error view for failed login or other issues.
     *
     * @return View The view to display when an error occurs during the login process.
     */
    public function renderHandleError(): View;
}
