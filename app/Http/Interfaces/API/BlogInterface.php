<?php

namespace App\Http\Interfaces\API;

use App\Models\Blog;
use Illuminate\Http\Request;

/**
 * Interface BlogInterface
 */
interface BlogInterface
{
    /**
     * Retrieves all blog data with filters or parameters provided in the request.
     *
     * @param  Request  $request  The HTTP request containing parameters for retrieving blog data.
     * @return array An array of blog data matching the provided criteria.
     */
    public function renderGetAllBlogData(Request $request): array;

    /**
     * Retrieves detailed blog data based on the provided identifier.
     *
     * @param  Request  $request  The HTTP request for retrieving blog details.
     * @param  string  $identifier  A unique identifier (e.g., slug or ID) for identifying the specific blog.
     * @return Blog|null The blog data if found, or null if the blog is not found.
     */
    public function renderGetDetailBlogData(Request $request, string $identifier): ?Blog;
}
