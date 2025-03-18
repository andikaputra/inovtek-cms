<?php

namespace App\Http\Interfaces\Admin;

use App\Http\Requests\Admin\Blog\BlogStoreRequest;
use App\Http\Requests\Admin\Blog\BlogUpdateRequest;
use App\Models\Blog;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Interface BlogInterface
 */
interface BlogInterface
{
    /**
     * Render the index view for blog posts.
     *
     * @param  Request  $request  The current request instance.
     * @return View The view for displaying blog posts.
     */
    public function renderIndex(Request $request): View;

    /**
     * Render the datatable for blog posts.
     *
     * @param  Request  $request  The current request instance.
     * @return JsonResponse The JSON response containing the datatable data.
     */
    public function renderDatatable(Request $request): JsonResponse;

    /**
     * Render the view for creating a new blog post.
     *
     * @param  Request  $request  The current request instance.
     * @return View The view for creating a new blog post.
     */
    public function renderCreate(Request $request): View;

    /**
     * Store a new blog post.
     *
     * @param  BlogStoreRequest  $request  The request containing data for the new blog post.
     * @return Blog|null The created blog post, or null if the operation failed.
     */
    public function execStore(BlogStoreRequest $request): ?Blog;

    /**
     * Render the view for editing an existing blog post.
     *
     * @param  Request  $request  The current request instance.
     * @param  string  $id  The ID of the blog post to edit.
     * @return View The view for editing the specified blog post.
     */
    public function renderEdit(Request $request, string $id): View;

    /**
     * Update the content of an existing blog post.
     *
     * @param  BlogUpdateRequest  $request  The request containing updated data for the blog post.
     * @param  string  $id  The ID of the blog post to update.
     * @return Blog|null The updated blog post, or null if the operation failed.
     */
    public function execUpdateContent(BlogUpdateRequest $request, string $id): ?Blog;

    /**
     * Update the active status of a blog post.
     *
     * @param  string  $id  The ID of the blog post to update.
     * @return bool True if the active status was successfully updated, false otherwise.
     */
    public function execUpdateActive(string $id): bool;

    /**
     * Delete an existing blog post.
     *
     * @param  Request  $request  The current request instance.
     * @param  string  $id  The ID of the blog post to delete.
     * @return bool True if the blog post was successfully deleted, false otherwise.
     */
    public function execDelete(Request $request, string $id): bool;
}
