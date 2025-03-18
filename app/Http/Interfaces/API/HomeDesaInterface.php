<?php

namespace App\Http\Interfaces\API;

use App\Models\RegionDetailMapbox;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

/**
 * Interface HomeDesaInterface
 */
interface HomeDesaInterface
{
    /**
     * Retrieve and render all product data for a specific Desa.
     *
     * @param  Request  $request  The HTTP request instance.
     * @param  string  $identifier  The identifier for the Desa.
     * @return array The product data for the specified Desa.
     */
    public function renderGetAllDesaProductData(Request $request, string $identifier): array;

    /**
     * Retrieve and render detailed Mapbox data for a specific region within the Desa.
     *
     * @param  Request  $request  The HTTP request instance.
     * @param  string  $identifier  The identifier for the Desa.
     * @param  string  $id  The specific ID of the region within the Desa.
     * @return RegionDetailMapbox|null The detailed Mapbox data for the specified region, or null if not found.
     */
    public function renderGetDetailMapboxData(Request $request, string $identifier, string $id): ?RegionDetailMapbox;

    /**
     * Retrieve and render all village data for a specific region.
     *
     * @param  Request  $request  The HTTP request instance.
     * @param  string  $identifier  The identifier for the Desa.
     * @return Collection The all village data for the specified region.
     */
    public function renderGetAllVillageData(Request $request, string $identifier): Collection;
}
