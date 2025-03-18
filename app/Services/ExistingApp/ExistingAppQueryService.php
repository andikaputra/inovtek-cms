<?php

namespace App\Services\ExistingApp;

use App\Models\ExistingApp;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class ExistingAppQueryService
{
    public function getAllExistingApp(Request $request): Collection
    {
        return ExistingApp::all();
    }

    public function findByCode(string $code): ?ExistingApp
    {
        return ExistingApp::where('code', $code)->first();
    }
}
