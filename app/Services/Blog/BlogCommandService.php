<?php

namespace App\Services\Blog;

use App\Models\Blog;
use App\Models\BlogTag;
use Cviebrock\EloquentSluggable\Services\SlugService;
use Illuminate\Http\Request;

class BlogCommandService
{
    public function store(Request $request, ?string $id_provinsi = null): ?Blog
    {
        $query = new Blog;
        $query->slug = SlugService::createSlug(Blog::class, 'slug', $request->title);
        $query->title = $request->title;
        $query->sub_title = $request->sub_title;
        $query->content = $request->content;
        $query->is_active = isset($request->is_active) ? true : false;
        $query->is_general_blog = $id_provinsi == null ? true : (isset($request->is_general_blog) ? true : false);
        $query->save();

        if ($id_provinsi != null) {
            $tags = new BlogTag;
            $tags->region_id = $id_provinsi;
            $tags->blog_id = $query->id;
            $tags->save();
        }

        if (! isset($id_provinsi) && $request->region_id != null) {
            foreach ($request->region_id as $item) {
                $tags = new BlogTag;
                $tags->region_id = $item;
                $tags->blog_id = $query->id;
                $tags->save();
            }
        }

        return $query;
    }

    public function updateContent(Request $request, Blog $query, bool $isArtikelUmum = false): ?Blog
    {
        $query->slug = strtolower($request->title) == strtolower($query->title) ? $query->slug : SlugService::createSlug(Blog::class, 'slug', $request->title);
        $query->title = $request->title;
        $query->sub_title = $request->sub_title;
        $query->content = $request->content;
        $query->is_active = isset($request->is_active) ? true : false;
        $query->is_general_blog = $isArtikelUmum ? true : (isset($request->is_general_blog) ? true : false);
        $query->save();

        if ($isArtikelUmum && $request->region_id != null) {
            BlogTag::where('blog_id', $query->id)->delete();

            foreach ($request->region_id as $item) {
                $tags = new BlogTag;
                $tags->region_id = $item;
                $tags->blog_id = $query->id;
                $tags->save();
            }
        }

        return $query;
    }

    public function updateActive(Blog $query): bool
    {
        $query->is_active = $query->is_active == false ? true : false;

        return $query->save();
    }

    public function updateGeneralBlog(Blog $query): bool
    {
        $query->is_general_blog = $query->is_general_blog == false ? true : false;

        return $query->save();
    }

    public function delete(Blog $query): bool
    {
        BlogTag::where('blog_id', $query->id)->delete();

        return $query->delete();
    }
}
