<?php

namespace App\Http\UseCase\Admin;

use App\Exports\RegistrasiKuisExport;
use App\Http\Interfaces\Admin\HomeDesaQuizRegistrantInterface;
use App\Services\QuizLink\QuizLinkQueryService;
use App\Services\QuizRegistration\QuizRegistrationDatatableService;
use App\Services\Region\RegionQueryService;
use App\Services\RegionDetail\RegionDetailQueryService;
use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

final class HomeDesaQuizRegistrantUseCase implements HomeDesaQuizRegistrantInterface
{
    public function __construct(
        private readonly RegionQueryService $regionQueryService,
        private readonly RegionDetailQueryService $regionDetailQueryService,
        private readonly QuizLinkQueryService $quizLinkQueryService,
        private readonly QuizRegistrationDatatableService $quizRegistrationDatatableService
    ) {}

    public function renderIndex(Request $request, string $id_provinsi, string $id_kuis): View
    {
        $findRegion = $this->regionQueryService->findRegionById(id: $id_provinsi);
        if (! isset($findRegion)) {
            abort(Response::HTTP_NOT_FOUND);
        }

        $findKuis = $this->quizLinkQueryService->findQuizLinkById(id_provinsi: $findRegion->id, id: $id_kuis);
        if (! isset($findKuis)) {
            abort(Response::HTTP_NOT_FOUND);
        }

        $findAllVillage = $this->regionDetailQueryService->getAllRegionDetail(identifier: $findRegion->id);

        return view('admin.pages.home-desa-registrasi.index', compact('findRegion', 'findKuis', 'findAllVillage'));
    }

    public function renderDatatable(Request $request, string $id_provinsi, string $id_kuis): JsonResponse
    {
        $findRegion = $this->regionQueryService->findRegionById(id: $id_provinsi);
        if (! isset($findRegion)) {
            throw new Exception(trans('response.error.view', ['data' => 'Registrasi Kuis', 'error' => 'Data tidak ditemukan']));
        }

        $findKuis = $this->quizLinkQueryService->findQuizLinkById(id_provinsi: $findRegion->id, id: $id_kuis);
        if (! isset($findKuis)) {
            throw new Exception(trans('response.error.view', ['data' => 'Registrasi Kuis', 'error' => 'Data tidak ditemukan']));
        }

        return $this->quizRegistrationDatatableService->quizRegistrationDatatable(request: $request, id_kuis: $findKuis->id);
    }

    public function execExport(Request $request, string $id_provinsi, string $id_kuis): BinaryFileResponse
    {
        $findRegion = $this->regionQueryService->findRegionById(id: $id_provinsi);
        if (! isset($findRegion)) {
            throw new Exception(trans('response.error.export', ['data' => 'Registrasi Kuis', 'error' => 'Data tidak ditemukan']));
        }

        $findKuis = $this->quizLinkQueryService->findQuizLinkById(id_provinsi: $findRegion->id, id: $id_kuis);
        if (! isset($findKuis)) {
            throw new Exception(trans('response.error.export', ['data' => 'Registrasi Kuis', 'error' => 'Data tidak ditemukan']));
        }

        return Excel::download(new RegistrasiKuisExport(id_kuis: $findKuis->id, range_date: $request->export_date_range, village_id: $request->export_village_id, search: $request->export_search), date('Ymd').'-daftar-registrasi-kuis.xlsx');
    }
}
