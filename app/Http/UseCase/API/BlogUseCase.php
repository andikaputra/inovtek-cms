<?php

namespace App\Http\UseCase\API;

use App\Http\Interfaces\API\BlogInterface;
use App\Models\Blog;
use App\Services\Blog\BlogQueryService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

final class BlogUseCase implements BlogInterface
{
    public function __construct(private readonly BlogQueryService $blogQueryService) {}

    public function renderGetAllBlogData(Request $request): array
    {
        $blog = $this->blogQueryService->getAllBlog(request: $request, onlyRegionData: false);
        $active = $blog->first()?->active_count ?? 0;
        $deactive = $blog->first()?->deactive_count ?? 0;
        $countData = [
            'all' => $active + $deactive,
            'active' => $active,
            'deactive' => $deactive,
        ];

        $blog->each(function ($item) {
            $item->wallpaper = $this->_appendTokenImage($item->assets[0]?->asset_path);
            $item->assets = $this->_mapAssets($item->assets);
            $item->makeHidden(['active_count', 'deactive_count', 'assets']);
        });

        return [
            'pageInfo' => $countData,
            'nodes' => $blog,
        ];
    }

    public function renderGetDetailBlogData(Request $request, string $identifier): ?Blog
    {
        $findBlog = $this->blogQueryService->getByIdentifier(identifier: $identifier);
        if (! isset($findBlog)) {
            throw new Exception(
                trans('response.error.view', ['data' => 'Detail Blog', 'error' => 'Data Tidak Ditemukan']),
                Response::HTTP_NOT_FOUND
            );
        }
        $findBlog->wallpaper = $this->_appendTokenImage($findBlog->assets[0]?->asset_path);
        $findBlog->assets = $this->_mapAssets($findBlog->assets);

        $tag = [];
        if ($findBlog->is_general_blog) {
            array_push($tag, 'Umum');
        }

        foreach ($findBlog->regions as $item) {
            array_push($tag, $item->regency);
        }
        $findBlog->tag = $tag;
        $findBlog->makeHidden(['regions']);

        return $findBlog;
    }

    private function _appendTokenImage(?string $relativePath): ?string
    {
        return $relativePath ? route('api.file.preview', ['path' => $relativePath]) : null;
    }

    private function _mapAssets($assets)
    {
        return $assets->map(fn ($asset) => $asset->asset_url = $this->_appendTokenImage($asset->asset_path));
    }
}
