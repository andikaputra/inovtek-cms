<?php

namespace App\Http\Controllers\API;

use App\Helpers\Json;
use App\Http\Controllers\Controller;
use App\Http\Interfaces\API\HomeDesaInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

class HomeDesaController extends Controller
{
    public function __construct(private readonly HomeDesaInterface $homeDesaInterface) {}

    public function getAllDesaProductData(Request $request, string $identifier): JsonResponse
    {
        try {
            DB::beginTransaction();
            $data = $this->homeDesaInterface->renderGetAllDesaProductData(request: $request, identifier: $identifier);
            DB::commit();

            return Json::success(data: $data);
        } catch (Throwable $th) {
            DB::rollback();

            return Json::error(error: $th->getMessage(), httpCode: $th->getCode());
        }
    }

    public function getAllVillageData(Request $request, string $identifier): JsonResponse
    {
        try {
            DB::beginTransaction();
            $data = $this->homeDesaInterface->renderGetAllVillageData(request: $request, identifier: $identifier);
            DB::commit();

            return Json::success(data: $data);
        } catch (Throwable $th) {
            DB::rollback();

            return Json::error(error: $th->getMessage(), httpCode: $th->getCode());
        }
    }

    public function getDetailMapboxData(Request $request, string $identifier, string $id): JsonResponse
    {
        try {
            DB::beginTransaction();
            $data = $this->homeDesaInterface->renderGetDetailMapboxData(request: $request, identifier: $identifier, id: $id);
            DB::commit();

            return Json::success(data: $data);
        } catch (Throwable $th) {
            DB::rollback();

            return Json::error(error: $th->getMessage(), httpCode: $th->getCode());
        }
    }
}
