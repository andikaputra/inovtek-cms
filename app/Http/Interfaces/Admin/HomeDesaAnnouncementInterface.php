<?php

namespace App\Http\Interfaces\Admin;

use App\Http\Requests\Admin\HomeDesaAnnouncement\HomeDesaAnnouncementStoreRequest;
use App\Http\Requests\Admin\HomeDesaAnnouncement\HomeDesaAnnouncementUpdateRequest;
use App\Models\AnnouncementLink;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Interface HomeDesaAnnouncementInterface
 */
interface HomeDesaAnnouncementInterface
{
    /**
     * Render the index view for the list of announcements.
     *
     * @param  Request  $request  The current request instance.
     * @param  string  $id_provinsi  The province ID to display related announcements.
     * @return View The view used to display the list of announcements.
     */
    public function renderIndex(Request $request, string $id_provinsi): View;

    /**
     * Render the datatable for the list of announcements.
     *
     * @param  Request  $request  The current request instance.
     * @param  string  $id_provinsi  The province ID to retrieve related announcement data.
     * @return JsonResponse JSON data for the datatable.
     */
    public function renderDatatable(Request $request, string $id_provinsi): JsonResponse;

    /**
     * Render the view for creating a new announcement.
     *
     * @param  Request  $request  The current request instance.
     * @param  string  $id_provinsi  The province ID to display the creation form for the announcement.
     * @return View The view used to display the creation form for a new announcement.
     */
    public function renderCreate(Request $request, string $id_provinsi): View;

    /**
     * Store a new announcement.
     *
     * @param  HomeDesaAnnouncementStoreRequest  $request  The request containing data to be stored.
     * @param  string  $id_provinsi  The province ID to save the related announcement.
     * @return AnnouncementLink|null The stored announcement link instance, or null if the storage fails.
     */
    public function execStore(HomeDesaAnnouncementStoreRequest $request, string $id_provinsi): ?AnnouncementLink;

    /**
     * Render the view for editing an existing announcement.
     *
     * @param  Request  $request  The current request instance.
     * @param  string  $id_provinsi  The province ID to display the related announcement data.
     * @param  string  $id  The ID of the announcement to be edited.
     * @return View The view used to display the edit form for the announcement.
     */
    public function renderEdit(Request $request, string $id_provinsi, string $id): View;

    /**
     * Update the content of an existing announcement.
     *
     * @param  HomeDesaAnnouncementUpdateRequest  $request  The request containing updated data for the announcement.
     * @param  string  $id_provinsi  The province ID to update the related announcement.
     * @param  string  $id  The ID of the announcement to be updated.
     * @return AnnouncementLink|null The updated announcement link instance, or null if the update fails.
     */
    public function execUpdateContent(HomeDesaAnnouncementUpdateRequest $request, string $id_provinsi, string $id): ?AnnouncementLink;

    /**
     * Update the active status of an announcement.
     *
     * @param  string  $id_provinsi  The province ID to update the status of the related announcement.
     * @param  string  $id  The ID of the announcement whose active status will be updated.
     * @return bool Returns true if the active status was successfully updated, false otherwise.
     */
    public function execUpdateActive(string $id_provinsi, string $id): bool;

    /**
     * Delete an announcement.
     *
     * @param  Request  $request  The current request instance.
     * @param  string  $id_provinsi  The province ID to delete the related announcement.
     * @param  string  $id  The ID of the announcement to be deleted.
     * @return bool Returns true if the announcement was successfully deleted, false otherwise.
     */
    public function execDelete(Request $request, string $id_provinsi, string $id): bool;
}
