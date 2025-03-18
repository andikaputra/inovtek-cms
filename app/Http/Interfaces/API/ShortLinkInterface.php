<?php

namespace App\Http\Interfaces\API;

use App\Models\ShortLink;
use Illuminate\Http\Request;

/**
 * Interface ShortLinkInterface
 */
interface ShortLinkInterface
{
    /**
     * Retrieves all short link data with filters or parameters provided in the request.
     *
     * @param  Request  $request  The HTTP request containing parameters for retrieving short link data.
     * @return array An array of short link data matching the provided criteria.
     */
    public function renderGetAllShortLinkData(Request $request): array;

    /**
     * Retrieves detailed short link data based on the provided identifier.
     *
     * @param  Request  $request  The HTTP request for retrieving blog details.
     * @param  string  $identifier  A unique identifier (e.g., slug or ID) for identifying the specific blog.
     * @return ShortLink|null The short link data if found, or null if the blog is not found.
     */
    public function renderGetAllDetailShortLinkData(Request $request, string $identifier): ?ShortLink;
}
