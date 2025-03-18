<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\Json;
use App\Http\Controllers\Controller;
use App\Http\Interfaces\Admin\BlogInterface;
use App\Http\Requests\Admin\Blog\BlogStoreRequest;
use App\Http\Requests\Admin\Blog\BlogUpdateRequest;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

final class BlogController extends Controller
{
    public function __construct(private readonly BlogInterface $blogInterface) {}

    public function index(Request $request): View
    {
        return $this->blogInterface->renderIndex(request: $request);
    }

    public function datatable(Request $request): JsonResponse
    {
        try {
            DB::beginTransaction();
            $data = $this->blogInterface->renderDatatable(request: $request);
            DB::commit();

            return $data;
        } catch (Throwable $th) {
            DB::rollBack();

            return Json::error(error: $th->getMessage());
        }
    }

    public function create(Request $request): View
    {
        return $this->blogInterface->renderCreate(request: $request);
    }

    public function store(BlogStoreRequest $request): RedirectResponse
    {
        try {
            DB::beginTransaction();
            $this->blogInterface->execStore(request: $request);
            DB::commit();

            return to_route('admin.blog.index')->with('success', trans('response.success.store', ['data' => 'Artikel Umum']));
        } catch (Throwable $th) {
            DB::rollBack();

            return to_route('admin.blog.create')->with('error', $th->getMessage());
        }
    }

    public function edit(Request $request, string $id): View
    {
        return $this->blogInterface->renderEdit(request: $request, id: $id);
    }

    public function updateContent(BlogUpdateRequest $request, string $id): RedirectResponse
    {
        try {
            DB::beginTransaction();
            $this->blogInterface->execUpdateContent(request: $request, id: $id);
            DB::commit();

            return to_route('admin.blog.index')->with('success', trans('response.success.update', ['data' => 'Artikel Umum']));
        } catch (Throwable $th) {
            DB::rollBack();

            return to_route('admin.blog.edit', ['id' => $id])->with('error', $th->getMessage());
        }
    }

    public function updateActive(string $id): RedirectResponse
    {
        try {
            DB::beginTransaction();
            $this->blogInterface->execUpdateActive(id: $id);
            DB::commit();

            return to_route('admin.blog.index')->with('success', trans('response.success.switch', ['data' => 'Perubahan Status Artikel Umum']));
        } catch (Throwable $th) {
            DB::rollBack();

            return to_route('admin.blog.index')->with('error', $th->getMessage());
        }
    }

    public function delete(Request $request, string $id): RedirectResponse
    {
        try {
            DB::beginTransaction();
            $this->blogInterface->execDelete(request: $request, id: $id);
            DB::commit();

            return to_route('admin.blog.index')->with('success', trans('response.success.delete', ['data' => 'Artikel Umum']));
        } catch (Throwable $th) {
            DB::rollBack();

            return to_route('admin.blog.index')->with('error', $th->getMessage());
        }
    }
}
