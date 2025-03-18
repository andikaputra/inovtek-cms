<?php

namespace App\Http\UseCase\Admin;

use App\Http\Interfaces\Admin\HomeDesaQuizLinkInterface;
use App\Http\Requests\Admin\HomeDesaQuiz\HomeDesaQuizLinkStoreRequest;
use App\Http\Requests\Admin\HomeDesaQuiz\HomeDesaQuizLinkUpdateRequest;
use App\Models\QuizLink;
use App\Services\QuizLink\QuizLinkCommandService;
use App\Services\QuizLink\QuizLinkDatatableService;
use App\Services\QuizLink\QuizLinkQueryService;
use App\Services\Region\RegionQueryService;
use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

final class HomeDesaQuizLinkUseCase implements HomeDesaQuizLinkInterface
{
    public function __construct(
        private readonly RegionQueryService $regionQueryService,
        private readonly QuizLinkDatatableService $quizLinkDatatableService,
        private readonly QuizLinkQueryService $quizLinkQueryService,
        private readonly QuizLinkCommandService $quizLinkCommandService,
    ) {}

    public function renderIndex(Request $request, string $id_provinsi): View
    {
        $findRegion = $this->regionQueryService->findRegionById(id: $id_provinsi);
        if (! isset($findRegion)) {
            abort(Response::HTTP_NOT_FOUND);
        }

        return view('admin.pages.home-desa-kuis.index', compact('findRegion'));
    }

    public function renderDatatable(Request $request, string $id_provinsi): JsonResponse
    {
        $findRegion = $this->regionQueryService->findRegionById(id: $id_provinsi);
        if (! isset($findRegion)) {
            throw new Exception(trans('response.error.view', ['data' => 'Kuis', 'error' => 'Data tidak ditemukan']));
        }

        return $this->quizLinkDatatableService->quizLinkDatatable(request: $request, id_provinsi: $findRegion->id);
    }

    public function renderCreate(Request $request, string $id_provinsi): View
    {
        $findRegion = $this->regionQueryService->findRegionById(id: $id_provinsi);
        if (! isset($findRegion)) {
            abort(Response::HTTP_NOT_FOUND);
        }

        $checkExistActiveQuiz = $this->quizLinkQueryService->checkExistActive(id_provinsi: $findRegion->id);

        return view('admin.pages.home-desa-kuis.create', compact('findRegion', 'checkExistActiveQuiz'));
    }

    public function execStore(HomeDesaQuizLinkStoreRequest $request, string $id_provinsi): ?QuizLink
    {
        $findRegion = $this->regionQueryService->findRegionById(id: $id_provinsi);
        if (! isset($findRegion)) {
            throw new Exception(trans('response.error.store', ['data' => 'Kuis', 'error' => 'Data tidak ditemukan']));
        }

        if (isset($request->is_active)) {
            $checkExistActiveQuiz = $this->quizLinkQueryService->checkExistActive(id_provinsi: $findRegion->id);
            if ($checkExistActiveQuiz) {
                throw new Exception(trans('response.error.store', ['data' => 'Kuis', 'error' => 'Sudah ada kuis yang sedang aktif']));
            }
        }

        $storeData = $this->quizLinkCommandService->store(request: $request, id_provinsi: $findRegion->id);
        if (! isset($storeData)) {
            throw new Exception(trans('response.error.store', ['data' => 'Kuis', 'error' => 'Data tidak ditemukan']));
        }

        return $storeData;
    }

    public function renderEdit(Request $request, string $id_provinsi, string $id): View
    {
        $findRegion = $this->regionQueryService->findRegionById(id: $id_provinsi);
        if (! isset($findRegion)) {
            abort(Response::HTTP_NOT_FOUND);
        }

        $findKuis = $this->quizLinkQueryService->findQuizLinkById(id_provinsi: $findRegion->id, id: $id);
        if (! isset($findKuis)) {
            abort(Response::HTTP_NOT_FOUND);
        }

        return view('admin.pages.home-desa-kuis.edit', compact('findRegion', 'findKuis'));
    }

    public function execUpdateActive(string $id_provinsi, string $id): bool
    {
        $findRegion = $this->regionQueryService->findRegionById(id: $id_provinsi);
        if (! isset($findRegion)) {
            throw new Exception(trans('response.error.switch', ['data' => 'Perubahan Status Kuis', 'error' => 'Data tidak ditemukan']));
        }

        $findKuis = $this->quizLinkQueryService->findQuizLinkById(id_provinsi: $findRegion->id, id: $id);
        if (! isset($findKuis)) {
            throw new Exception(trans('response.error.switch', ['data' => 'Perubahan Status Kuis', 'error' => 'Data tidak ditemukan']));
        }

        $countActive = $this->quizLinkQueryService->countActiveQuizLink(id_provinsi: $findRegion->id, except_id: $findKuis->id);
        if ($countActive > 0) {
            throw new Exception(trans('response.error.switch', ['data' => 'Perubahan Status Kuis', 'error' => 'Sudah ada kuis yang sedang aktif']));
        }

        return $this->quizLinkCommandService->updateActive(quizLink: $findKuis);
    }

    public function execUpdateContent(HomeDesaQuizLinkUpdateRequest $request, string $id_provinsi, string $id): ?QuizLink
    {
        $findRegion = $this->regionQueryService->findRegionById(id: $id_provinsi);
        if (! isset($findRegion)) {
            throw new Exception(trans('response.error.update', ['data' => 'Kuis', 'error' => 'Data tidak ditemukan']));
        }

        $findKuis = $this->quizLinkQueryService->findQuizLinkById(id_provinsi: $findRegion->id, id: $id);
        if (! isset($findKuis)) {
            throw new Exception(trans('response.error.update', ['data' => 'Kuis', 'error' => 'Data tidak ditemukan']));
        }

        $updateData = $this->quizLinkCommandService->updateContent(quizLink: $findKuis, request: $request);
        if (! isset($updateData)) {
            throw new Exception(trans('response.error.update', ['data' => 'Kuis', 'error' => 'Data tidak ditemukan']));
        }

        return $updateData;
    }

    public function execDelete(Request $request, string $id_provinsi, string $id): bool
    {
        $findRegion = $this->regionQueryService->findRegionById(id: $id_provinsi);
        if (! isset($findRegion)) {
            throw new Exception(trans('response.error.delete', ['data' => 'Kuis', 'error' => 'Data tidak ditemukan']));
        }

        $findKuis = $this->quizLinkQueryService->findQuizLinkById(id_provinsi: $findRegion->id, id: $id);
        if (! isset($findKuis)) {
            throw new Exception(trans('response.error.delete', ['data' => 'Kuis', 'error' => 'Data tidak ditemukan']));
        }

        if ($findKuis->is_active) {
            throw new Exception(trans('response.error.delete', ['data' => 'Kuis', 'error' => 'Mohon nonaktifkan kuis terlebih dahulu']));
        }

        return $this->quizLinkCommandService->delete(quizLink: $findKuis);
    }
}
