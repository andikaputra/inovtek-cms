<?php

namespace App\Http\UseCase\Admin;

use App\Http\Interfaces\Admin\HomeDesaAnnouncementInterface;
use App\Http\Requests\Admin\HomeDesaAnnouncement\HomeDesaAnnouncementStoreRequest;
use App\Http\Requests\Admin\HomeDesaAnnouncement\HomeDesaAnnouncementUpdateRequest;
use App\Models\AnnouncementLink;
use App\Services\Announcement\AnnouncementCommandService;
use App\Services\Announcement\AnnouncementDatatableService;
use App\Services\Announcement\AnnouncementQueryService;
use App\Services\Region\RegionQueryService;
use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

final class HomeDesaAnnouncementUseCase implements HomeDesaAnnouncementInterface
{
    public function __construct(
        private readonly RegionQueryService $regionQueryService,
        private readonly AnnouncementDatatableService $announcementDatatableService,
        private readonly AnnouncementQueryService $announcementQueryService,
        private readonly AnnouncementCommandService $announcementCommandService
    ) {}

    public function renderIndex(Request $request, string $id_provinsi): View
    {
        $findRegion = $this->regionQueryService->findRegionById(id: $id_provinsi);
        if (! isset($findRegion)) {
            abort(Response::HTTP_NOT_FOUND);
        }

        return view('admin.pages.home-desa-announcement.index', compact('findRegion'));
    }

    public function renderDatatable(Request $request, string $id_provinsi): JsonResponse
    {
        $findRegion = $this->regionQueryService->findRegionById(id: $id_provinsi);
        if (! isset($findRegion)) {
            throw new Exception(trans('response.error.view', ['data' => 'Pengumuman', 'error' => 'Data tidak ditemukan']));
        }

        return $this->announcementDatatableService->announcementDatatable(request: $request, id_provinsi: $findRegion->id);
    }

    public function renderCreate(Request $request, string $id_provinsi): View
    {
        $findRegion = $this->regionQueryService->findRegionById(id: $id_provinsi);
        if (! isset($findRegion)) {
            abort(Response::HTTP_NOT_FOUND);
        }

        $checkExistActivePengumuman = $this->announcementQueryService->checkExistActive(id_provinsi: $findRegion->id);

        return view('admin.pages.home-desa-announcement.create', compact('findRegion', 'checkExistActivePengumuman'));
    }

    public function execStore(HomeDesaAnnouncementStoreRequest $request, string $id_provinsi): ?AnnouncementLink
    {
        $findRegion = $this->regionQueryService->findRegionById(id: $id_provinsi);
        if (! isset($findRegion)) {
            throw new Exception(trans('response.error.store', ['data' => 'Pengumuman', 'error' => 'Data tidak ditemukan']));
        }

        if (isset($request->is_active)) {
            $checkExistActivePengumuman = $this->announcementQueryService->checkExistActive(id_provinsi: $findRegion->id);
            if ($checkExistActivePengumuman) {
                throw new Exception(trans('response.error.store', ['data' => 'Pengumuman', 'error' => 'Sudah ada pengumuman yang sedang aktif']));
            }
        }

        $storeData = $this->announcementCommandService->store(request: $request, id_provinsi: $findRegion->id);
        if (! isset($storeData)) {
            throw new Exception(trans('response.error.store', ['data' => 'Pengumuman', 'error' => 'Data tidak ditemukan']));
        }

        return $storeData;
    }

    public function renderEdit(Request $request, string $id_provinsi, string $id): View
    {
        $findRegion = $this->regionQueryService->findRegionById(id: $id_provinsi);
        if (! isset($findRegion)) {
            abort(Response::HTTP_NOT_FOUND);
        }

        $findPengumuman = $this->announcementQueryService->findAnnouncementById(id_provinsi: $findRegion->id, id: $id);
        if (! isset($findPengumuman)) {
            abort(Response::HTTP_NOT_FOUND);
        }

        return view('admin.pages.home-desa-announcement.edit', compact('findRegion', 'findPengumuman'));
    }

    public function execUpdateActive(string $id_provinsi, string $id): bool
    {
        $findRegion = $this->regionQueryService->findRegionById(id: $id_provinsi);
        if (! isset($findRegion)) {
            throw new Exception(trans('response.error.switch', ['data' => 'Perubahan Status Pengumuman', 'error' => 'Data tidak ditemukan']));
        }

        $findPengumuman = $this->announcementQueryService->findAnnouncementById(id_provinsi: $findRegion->id, id: $id);
        if (! isset($findPengumuman)) {
            throw new Exception(trans('response.error.switch', ['data' => 'Perubahan Status Pengumuman', 'error' => 'Data tidak ditemukan']));
        }

        $countActive = $this->announcementQueryService->countActiveAnnouncement(id_provinsi: $findRegion->id, except_id: $findPengumuman->id);
        if ($countActive > 0) {
            throw new Exception(trans('response.error.switch', ['data' => 'Perubahan Status Pengumuman', 'error' => 'Sudah ada pengumuman yang sedang aktif']));
        }

        return $this->announcementCommandService->updateActive(announcementLink: $findPengumuman);
    }

    public function execUpdateContent(HomeDesaAnnouncementUpdateRequest $request, string $id_provinsi, string $id): ?AnnouncementLink
    {
        $findRegion = $this->regionQueryService->findRegionById(id: $id_provinsi);
        if (! isset($findRegion)) {
            throw new Exception(trans('response.error.update', ['data' => 'Pengumuman', 'error' => 'Data tidak ditemukan']));
        }

        $findPengumuman = $this->announcementQueryService->findAnnouncementById(id_provinsi: $findRegion->id, id: $id);
        if (! isset($findPengumuman)) {
            throw new Exception(trans('response.error.update', ['data' => 'Pengumuman', 'error' => 'Data tidak ditemukan']));
        }

        $updateData = $this->announcementCommandService->updateContent(announcementLink: $findPengumuman, request: $request);
        if (! isset($updateData)) {
            throw new Exception(trans('response.error.update', ['data' => 'Pengumuman', 'error' => 'Data tidak ditemukan']));
        }

        return $updateData;
    }

    public function execDelete(Request $request, string $id_provinsi, string $id): bool
    {
        $findRegion = $this->regionQueryService->findRegionById(id: $id_provinsi);
        if (! isset($findRegion)) {
            throw new Exception(trans('response.error.delete', ['data' => 'Pengumuman', 'error' => 'Data tidak ditemukan']));
        }

        $findPengumuman = $this->announcementQueryService->findAnnouncementById(id_provinsi: $findRegion->id, id: $id);
        if (! isset($findPengumuman)) {
            throw new Exception(trans('response.error.delete', ['data' => 'Pengumuman', 'error' => 'Data tidak ditemukan']));
        }

        if ($findPengumuman->is_active) {
            throw new Exception(trans('response.error.delete', ['data' => 'Pengumuman', 'error' => 'Mohon nonaktifkan pengumuman terlebih dahulu']));
        }

        return $this->announcementCommandService->delete(announcementLink: $findPengumuman);
    }
}
