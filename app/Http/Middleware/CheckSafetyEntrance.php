<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class CheckSafetyEntrance
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Mengambil `safety_entrance` dari request URI
        $safetyEntrance = $request->route('safety_entrance');

        // Validasi atau logika lain menggunakan nilai `safety_entrance`
        if (! $this->isValidSafetyEntrance($safetyEntrance)) {
            return abort(Response::HTTP_NOT_FOUND);
        }

        return $next($request);
    }

    // Contoh fungsi validasi
    protected function isValidSafetyEntrance($safetyEntrance)
    {
        // Logika validasi `safety_entrance`
        $validator = Validator::make(['safety_entrance' => $safetyEntrance], [
            'safety_entrance' => 'required|uuid',
        ]);

        // Jika validasi gagal, redirect atau return response error
        if ($validator->fails() || $safetyEntrance != config('app.safety_entrance')) {
            return false;
        }

        return true;
    }
}
