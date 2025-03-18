<?php

namespace App\Services\LinkCollection;

use App\Models\LinkCollection;
use Illuminate\Http\Request;

class LinkCollectionCommandService
{
    public function store(Request $request, string $id_provinsi, bool $isSocialMedia = false): ?LinkCollection
    {
        $query = new LinkCollection;
        $query->region_id = $id_provinsi;
        $query->url = $request->url;
        $query->display = $request->display;
        $query->is_active = isset($request->is_active) ? true : false;
        $query->is_social_media = $isSocialMedia;
        $query->save();

        return $query;
    }

    public function update(LinkCollection $linkCollection, Request $request): ?LinkCollection
    {
        $linkCollection->url = $request->url;
        $linkCollection->display = $request->display;
        $linkCollection->is_active = isset($request->is_active) ? true : false;
        $linkCollection->save();

        return $linkCollection;
    }

    public function updateActive(LinkCollection $linkCollection): bool
    {
        $linkCollection->is_active = $linkCollection->is_active == false ? true : false;

        return $linkCollection->save();
    }

    public function delete(LinkCollection $linkCollection): bool
    {
        return $linkCollection->delete();
    }
}
