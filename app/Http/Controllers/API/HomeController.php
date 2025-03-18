<?php

namespace App\Http\Controllers\API;

use App\Helpers\Json;
use App\Http\Controllers\Controller;
use App\Http\Interfaces\API\HomeInterface;
use App\Http\Requests\API\HomeDesaQuiz\HomeDesaQuizRegistrationRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

final class HomeController extends Controller
{
    public function __construct(private readonly HomeInterface $homeInterface) {}

    public function getAllWilayahData(Request $request): JsonResponse
    {
        try {
            DB::beginTransaction();
            $data = $this->homeInterface->renderGetAllWilayahData(request: $request);
            DB::commit();

            return Json::success(data: $data);
        } catch (Throwable $th) {
            DB::rollback();

            return Json::error(error: $th->getMessage(), httpCode: $th->getCode());
        }
    }

    public function getAllWilayahDetailData(Request $request, string $identifier): JsonResponse
    {
        try {
            DB::beginTransaction();
            $data = $this->homeInterface->renderGetAllWilayahDetailData(request: $request, identifier: $identifier);
            DB::commit();

            return Json::success(data: $data);
        } catch (Throwable $th) {
            DB::rollback();

            return Json::error(error: $th->getMessage(), httpCode: $th->getCode());
        }
    }

    public function postQuizRegistration(HomeDesaQuizRegistrationRequest $request, string $identifier): JsonResponse
    {
        try {
            DB::beginTransaction();
            $data = $this->homeInterface->execPostQuizRegistration(request: $request, identifier: $identifier);
            DB::commit();

            return Json::success(data: $data);
        } catch (Throwable $th) {
            DB::rollback();

            return Json::error(error: $th->getMessage(), httpCode: $th->getCode());
        }
    }

    public function getQuizRegister(Request $request, string $identifier): JsonResponse
    {
        try {
            DB::beginTransaction();
            $data = $this->homeInterface->renderGetQuizRegister(request: $request, identifier: $identifier);
            DB::commit();

            return Json::success(data: $data);
        } catch (Throwable $th) {
            DB::rollback();

            return Json::error(error: $th->getMessage(), httpCode: $th->getCode());
        }
    }
}
