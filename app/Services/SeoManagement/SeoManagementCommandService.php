<?php

namespace App\Services\SeoManagement;

use App\Http\Requests\Admin\SeoManagement\SeoManagementStoreUpdateRequest;
use App\Models\Seo;

class SeoManagementCommandService
{
    public function storeUpdate(SeoManagementStoreUpdateRequest $request, string $seoType, string $seoId, ?string $seoKey = null): Seo
    {
        $query = Seo::firstOrNew(['seotable_id' => $seoId, 'seotable_type' => $seoType]);
        $query->seotable_type = $seoType;
        $query->seotable_id = $seoId;
        $query->seo_key = $seoKey ?? 'default-seo-key-id';
        $query->meta_title = $request->meta_title;
        $query->meta_description = $request->meta_description;
        $query->meta_robot = strtolower($request->meta_robot);
        $query->meta_author = $request->meta_author;
        $query->meta_keyword = $request->meta_keyword;
        $query->meta_language = $request->meta_language;
        $query->meta_og_title = $request->meta_og_title ?? null;
        $query->meta_og_description = $request->meta_og_description ?? null;
        $query->meta_og_url = $request->meta_og_url ?? null;
        $query->meta_og_type = $request->meta_og_type ?? null;
        $query->save();

        return $query;
    }

    public function delete(string $seoType, string $seoId, ?string $seoKey = null): bool
    {
        $query = Seo::query();

        if (isset($seoKey)) {
            $query->where('seo_key', $seoKey);
        }

        $query->where('seotable_type', $seoType);
        $query->where('seotable_id', $seoId);

        return $query->delete();
    }
}
