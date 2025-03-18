<?php

namespace App\Http\Interfaces\Admin;

use App\Http\Requests\Admin\AboutApp\AboutAppUpdateRequest;
use App\Models\ExistingAppInfo;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

/**
 * Interface AboutAppInterface
 */
interface AboutAppInterface
{
    /**
     * Renders the edit view for the About app.
     *
     * @param  Request  $request  The HTTP request instance.
     * @return View The view to render for editing the About app.
     */
    public function renderEdit(Request $request): View;

    /**
     * Executes the update operation for the About app.
     *
     * @param  AboutAppUpdateRequest  $request  The validated request instance containing update data.
     * @return ExistingAppInfo|null The updated instance of ExistingAppInfo or null if update fails.
     */
    public function execUpdate(AboutAppUpdateRequest $request): ?ExistingAppInfo;
}
