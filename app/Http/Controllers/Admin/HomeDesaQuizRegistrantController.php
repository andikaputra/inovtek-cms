<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\Json;
use App\Http\Controllers\Controller;
use App\Http\Interfaces\Admin\HomeDesaQuizRegistrantInterface;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Throwable;

final class HomeDesaQuizRegistrantController extends Controller
{
    public function __construct(private readonly HomeDesaQuizRegistrantInterface $homeDesaQuizRegistrantInterface) {}

    public function index(Request $request, string $id_provinsi, string $id_kuis): View
    {
        return $this->homeDesaQuizRegistrantInterface->renderIndex(request: $request, id_provinsi: $id_provinsi, id_kuis: $id_kuis);
    }

    public function datatable(Request $request, string $id_provinsi, string $id_kuis): JsonResponse
    {
        try {
            DB::beginTransaction();
            $data = $this->homeDesaQuizRegistrantInterface->renderDatatable(request: $request, id_provinsi: $id_provinsi, id_kuis: $id_kuis);
            DB::commit();

            return $data;
        } catch (Throwable $th) {
            DB::rollBack();

            return Json::error(error: $th->getMessage());
        }
    }

    public function export(Request $request, string $id_provinsi, string $id_kuis): BinaryFileResponse|RedirectResponse
    {
        try {
            DB::beginTransaction();
            $data = $this->homeDesaQuizRegistrantInterface->execExport(request: $request, id_provinsi: $id_provinsi, id_kuis: $id_kuis);
            DB::commit();

            return $data;
        } catch (\Throwable $th) {
            DB::rollBack();

            return redirect()->back()->with('error', $th->getMessage());
        }
    }
}
