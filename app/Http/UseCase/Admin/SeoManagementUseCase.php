<?php

namespace App\Http\UseCase\Admin;

use App\Http\Interfaces\Admin\SeoManagementInterface;
use App\Http\Requests\Admin\SeoManagement\SeoManagementStoreUpdateRequest;
use App\Models\Seo;
use App\Services\Blog\BlogQueryService;
use App\Services\Region\RegionQueryService;
use App\Services\SeoManagement\SeoManagementCommandService;
use App\Services\SeoManagement\SeoManagementQueryService;
use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Response;

final class SeoManagementUseCase implements SeoManagementInterface
{
    public function __construct(
        private readonly SeoManagementQueryService $seoManagementQueryService,
        private readonly SeoManagementCommandService $seoManagementCommandService,
        private readonly RegionQueryService $regionQueryService,
        private readonly BlogQueryService $blogQueryService
    ) {}

    public function renderEdit(string $id_provinsi, string $type, string $id): View
    {
        $target = $this->_validateTargetSeo(type: $type, id_provinsi: $id_provinsi, id: $id);

        $seo = $this->seoManagementQueryService->getSeoByTypeAndId(seoType: $target['seoType'], seoId: $target['seoId']);

        $findRegion = $this->regionQueryService->findRegionById(id: $id_provinsi);
        if (! isset($findRegion)) {
            abort(Response::HTTP_NOT_FOUND);
        }

        return view('admin.pages.seo-management.edit', compact('seo', 'type', 'id', 'findRegion'));
    }

    public function execUpdate(SeoManagementStoreUpdateRequest $request, string $id_provinsi, string $type, string $id): Seo
    {
        $target = $this->_validateTargetSeo(type: $type, id_provinsi: $id_provinsi, id: $id);

        return $this->seoManagementCommandService->storeUpdate(request: $request, seoType: $target['seoType'], seoId: $target['seoId']);
    }

    public function renderEditUmum(string $type, string $id): View
    {
        $target = $this->_validateTargetSeo(type: $type, id: $id);

        $seo = $this->seoManagementQueryService->getSeoByTypeAndId(seoType: $target['seoType'], seoId: $target['seoId']);

        return view('admin.pages.seo-management.edit-umum', compact('seo', 'type', 'id'));
    }

    public function execUpdateUmum(SeoManagementStoreUpdateRequest $request, string $type, string $id): Seo
    {
        $target = $this->_validateTargetSeo(type: $type, id: $id);

        return $this->seoManagementCommandService->storeUpdate(request: $request, seoType: $target['seoType'], seoId: $target['seoId']);
    }

    // Private function here
    private function _validateTargetSeo(string $type, string $id, ?string $id_provinsi = null): array
    {
        switch ($type) {
            case 'wilayah':
                $data = $this->regionQueryService->findRegionById(id: $id);
                break;
            case 'blog':
                if ($id_provinsi != null) {
                    $findRegion = $this->regionQueryService->findRegionById(id: $id_provinsi);
                    $id_provinsi = $findRegion->id;
                }

                $data = $this->blogQueryService->getById(id: $id, id_provinsi: $id_provinsi);
                break;
            default:
                throw new Exception(trans('response.error.view', ['data' => 'SEO', 'error' => 'Data tidak ditemukan']));
                break;
        }

        if (! isset($data)) {
            throw new Exception(trans('response.error.view', ['data' => 'SEO', 'error' => 'Data tidak ditemukan']));
        }

        return [
            'seoType' => $data::class,
            'seoId' => $data->id,
        ];
    }
}
