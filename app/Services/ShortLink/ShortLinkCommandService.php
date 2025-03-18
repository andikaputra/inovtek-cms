<?php

namespace App\Services\ShortLink;

use App\Http\Requests\Admin\ShortLink\ShortLinkStoreRequest;
use App\Http\Requests\Admin\ShortLink\ShortLinkUpdateRequest;
use App\Models\ShortLink;

class ShortLinkCommandService
{
    public function store(ShortLinkStoreRequest $request): ?ShortLink
    {
        $query = new ShortLink;
        $query->original_url = $request->original_url;
        $query->short_url = $request->short_url;
        $query->is_active = isset($request->is_active) ? true : false;
        $query->save();

        return $query;
    }

    public function update(ShortLinkUpdateRequest $request, ShortLink $shortLink): ?ShortLink
    {
        $shortLink->original_url = $request->original_url;
        $shortLink->is_active = isset($request->is_active) ? true : false;
        $shortLink->save();

        return $shortLink;
    }

    public function incrementClick(ShortLink $shortLink): int
    {
        return $shortLink->increment('click_count');
    }

    public function updateStatus(ShortLink $shortLink): bool
    {
        $shortLink->is_active = $shortLink->is_active ? false : true;

        return $shortLink->save();
    }

    public function delete(ShortLink $shortLink): bool
    {
        return $shortLink->delete();
    }
}
