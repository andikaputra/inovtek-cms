<?php

namespace App\Http\Interfaces\Admin;

use App\Http\Requests\Admin\SeoManagement\SeoManagementStoreUpdateRequest;
use App\Models\Seo;
use Illuminate\Contracts\View\View;

/**
 * Interface for SEO management operations.
 */
interface SeoManagementInterface
{
    /**
     * Render the edit page for SEO management.
     *
     * @param  string  $type  The type of SEO management.
     * @param  string  $id  The unique identifier of the SEO management.
     * @return View The view for the edit page.
     */
    public function renderEdit(string $id_provinsi, string $type, string $id): View;

    /**
     * Execute updating SEO management.
     *
     * @param  SeoManagementStoreUpdateRequest  $request  The request object containing necessary data for updating SEO management.
     * @param  string  $type  The type of SEO management.
     * @param  string  $id  The unique identifier of the SEO management.
     * @return Seo The updated SEO management.
     */
    public function execUpdate(SeoManagementStoreUpdateRequest $request, string $id_provinsi, string $type, string $id): Seo;

    /**
     * Render the edit page for SEO management.
     *
     * @param  string  $type  The type of SEO management.
     * @param  string  $id  The unique identifier of the SEO management.
     * @return View The view for the edit page.
     */
    public function renderEditUmum(string $type, string $id): View;

    /**
     * Execute updating SEO management.
     *
     * @param  SeoManagementStoreUpdateRequest  $request  The request object containing necessary data for updating SEO management.
     * @param  string  $type  The type of SEO management.
     * @param  string  $id  The unique identifier of the SEO management.
     * @return Seo The updated SEO management.
     */
    public function execUpdateUmum(SeoManagementStoreUpdateRequest $request, string $type, string $id): Seo;
}
