<?php

namespace App\Services\Announcement;

use App\Http\Requests\Admin\HomeDesaAnnouncement\HomeDesaAnnouncementStoreRequest;
use App\Http\Requests\Admin\HomeDesaAnnouncement\HomeDesaAnnouncementUpdateRequest;
use App\Models\AnnouncementLink;

class AnnouncementCommandService
{
    public function store(HomeDesaAnnouncementStoreRequest $request, string $id_provinsi): ?AnnouncementLink
    {
        $query = new AnnouncementLink;
        $query->region_id = $id_provinsi;
        $query->name = $request->name;
        $query->announcement_link = $request->announcement_link;
        $query->is_active = isset($request->is_active) ? true : false;
        $query->save();

        return $query;
    }

    public function updateContent(HomeDesaAnnouncementUpdateRequest $request, AnnouncementLink $announcementLink): ?AnnouncementLink
    {
        $announcementLink->name = $request->name;
        $announcementLink->announcement_link = $request->announcement_link;
        $announcementLink->save();

        return $announcementLink;
    }

    public function updateActive(AnnouncementLink $announcementLink): bool
    {
        $announcementLink->is_active = $announcementLink->is_active ? false : true;

        return $announcementLink->save();
    }

    public function delete(AnnouncementLink $announcementLink): bool
    {
        return $announcementLink->delete();
    }
}
