<?php

namespace App\Services\Announcement;

use App\Models\AnnouncementLink;

class AnnouncementQueryService
{
    public function checkExistActive(string $id_provinsi): bool
    {
        return AnnouncementLink::where('region_id', $id_provinsi)
            ->where('is_active', true)
            ->exists();
    }

    public function countActiveAnnouncement(string $id_provinsi, string $except_id): bool
    {
        return AnnouncementLink::where('region_id', $id_provinsi)
            ->where('is_active', true)
            ->where('id', '!=', $except_id)
            ->count();
    }

    public function findAnnouncementById(string $id_provinsi, string $id): ?AnnouncementLink
    {
        return AnnouncementLink::where('region_id', $id_provinsi)->where('id', $id)->first();
    }

    public function findAnnouncementActive(string $region_id): ?AnnouncementLink
    {
        return AnnouncementLink::where('region_id', $region_id)->where('is_active', true)->first();
    }
}
