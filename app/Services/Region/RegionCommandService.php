<?php

namespace App\Services\Region;

use App\Http\Requests\Admin\Home\HomeStoreRequest;
use App\Http\Requests\Admin\Home\HomeUpdateRequest;
use App\Models\Region;
use App\Models\RegionExistingApp;
use Cviebrock\EloquentSluggable\Services\SlugService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RegionCommandService
{
    public function store(HomeStoreRequest $request): ?Region
    {
        $query = new Region;
        $query->slug = SlugService::createSlug(Region::class, 'slug', $request->province.'-'.$request->regency);
        $query->province = $request->province;
        $query->regency = $request->regency;
        $query->is_active = isset($request->is_active) ? true : false;
        $query->save();

        return $query;
    }

    public function update(HomeUpdateRequest $request, Region $region): ?Region
    {
        $region->slug = strtolower($region->province) == strtolower($request->province) && strtolower($region->regency) == strtolower($request->regency) ? $region->slug : SlugService::createSlug(Region::class, 'slug', $request->province.'-'.$request->regency);
        $region->province = $request->province;
        $region->regency = $request->regency;
        $region->save();

        return $region;
    }

    public function switch(Region $region): ?Region
    {
        $region->is_active = $region->is_active == false ? true : false;
        $region->save();

        return $region;
    }

    public function delete(Region $region): bool
    {
        return $region->delete();
    }

    public function regionExistingApp(Region $region, Request $request): bool
    {
        // Delete First
        RegionExistingApp::where('region_id', $region->id)->delete();

        // Generate Again
        foreach ($request->product as $item) {
            $query = new RegionExistingApp;
            $query->region_id = $region->id;
            $query->existing_app_id = $item;
            $statusUpdate = $query->save();

            if ($statusUpdate != true) {
                return false;
            }
        }

        return true;
    }

    public function readNotification(Request $request): bool
    {
        $user = Auth::user();
        $user->unreadNotifications->markAsRead();

        return true;
    }
}
