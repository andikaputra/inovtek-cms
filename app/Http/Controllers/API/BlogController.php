<?php

namespace App\Http\Controllers\API;

use App\Helpers\Json;
use App\Http\Controllers\Controller;
use App\Http\Interfaces\API\BlogInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

final class BlogController extends Controller
{
    public function __construct(private readonly BlogInterface $blogInterface) {}

    public function getAllBlogData(Request $request): JsonResponse
    {
        try {
            DB::beginTransaction();
            $data = $this->blogInterface->renderGetAllBlogData(request: $request);
            DB::commit();

            return Json::success(data: $data);
        } catch (Throwable $th) {
            DB::rollback();

            return Json::error(error: $th->getMessage(), httpCode: $th->getCode());
        }
    }

    public function getAllDetailBlogData(Request $request, string $identifier): JsonResponse
    {
        try {
            DB::beginTransaction();
            $data = $this->blogInterface->renderGetDetailBlogData(request: $request, identifier: $identifier);
            DB::commit();

            return Json::success(data: $data);
        } catch (Throwable $th) {
            DB::rollback();

            return Json::error(error: $th->getMessage(), httpCode: $th->getCode());
        }
    }
}
