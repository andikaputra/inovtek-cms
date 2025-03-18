<?php

namespace App\Http\Controllers\API;

use App\Helpers\Json;
use App\Http\Controllers\Controller;
use App\Http\Interfaces\API\ShortLinkInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

final class ShortLinkController extends Controller
{
    public function __construct(private readonly ShortLinkInterface $shortLinkInterface) {}

    public function getAllShortLinkData(Request $request): JsonResponse
    {
        try {
            DB::beginTransaction();
            $data = $this->shortLinkInterface->renderGetAllShortLinkData(request: $request);
            DB::commit();

            return Json::success(data: $data);
        } catch (Throwable $th) {
            DB::rollback();

            return Json::error(error: $th->getMessage(), httpCode: $th->getCode());
        }
    }

    public function getAllDetailShortLinkData(Request $request, string $identifier): JsonResponse
    {
        try {
            DB::beginTransaction();
            $data = $this->shortLinkInterface->renderGetAllDetailShortLinkData(request: $request, identifier: $identifier);
            DB::commit();

            return Json::success(data: $data);
        } catch (Throwable $th) {
            DB::rollback();

            return Json::error(error: $th->getMessage(), httpCode: $th->getCode());
        }
    }
}
