<?php

namespace App\Http\Middleware;

use App\Helpers\Json;
use App\Helpers\JwtHelper;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class EnsureJwtIsValid
{
    public function handle(Request $request, Closure $next)
    {
        try {
            $token = $this->getTokenFromRequest($request);

            if ($token !== null) {
                $method = 'RS256';
                $key = config('passport.public_key');
                $valid = JwtHelper::checkValidToken($token, $key, $method);

                if ($valid) {
                    return $next($request);
                }
            }

            $message = 'Invalid Token.';

            return Json::error(error: $message, httpCode: Response::HTTP_UNAUTHORIZED);
        } catch (\Throwable $th) {
            $message = $th->getMessage();

            return Json::error(error: $message, httpCode: Response::HTTP_UNAUTHORIZED);
        }
    }

    private function getTokenFromRequest(Request $request): ?string
    {
        $token = $request->input('token');

        if ($token === null) {
            $token = $request->bearerToken();
        }

        return $token;
    }
}
