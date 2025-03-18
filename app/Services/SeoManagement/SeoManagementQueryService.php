<?php

namespace App\Services\SeoManagement;

use App\Models\Seo;

class SeoManagementQueryService
{
    public function getSeoByTypeAndId(string $seoType, string $seoId): ?Seo
    {
        return Seo::where('seotable_type', $seoType)->where('seotable_id', $seoId)->first();
    }
}
