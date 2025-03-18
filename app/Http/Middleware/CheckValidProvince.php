<?php

namespace App\Http\Middleware;

use App\Models\Region;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckValidProvince
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Gunakan route model binding untuk memvalidasi id_provinsi
        $id_province = $request->route('id_provinsi');

        // Cek apakah provinsi valid, jika tidak valid, langsung return 404
        if (! $this->isValidProvinsi($id_province)) {
            return abort(Response::HTTP_NOT_FOUND);
        }

        return $next($request);
    }

    /**
     * Validasi apakah ID Provinsi valid.
     *
     * @param  string  $id_province
     */
    protected function isValidProvinsi($id_province): bool
    {
        return Region::where('id', $id_province)->orWhere('slug', $id_province)->exists();
    }
}
