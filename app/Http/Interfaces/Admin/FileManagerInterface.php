<?php

namespace App\Http\Interfaces\Admin;

use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

/**
 * Interface for file manager related operations.
 */
interface FileManagerInterface
{
    /**
     * Render the index page for FileManager.
     *
     * @param  Request  $request  The request object containing necessary data.
     * @return View The view for the index page.
     */
    public function renderIndex(Request $request): View;
}
