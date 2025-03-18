<?php

namespace App\Http\Middleware;

use App\Constants\AppConst;
use App\Helpers\Json;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ValidateHostRequest
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->expectsJson()) {
            if ($request->header('Origin')) {
                $originHostWithPort = parse_url($request->header('Origin'), PHP_URL_HOST) . (parse_url($request->header('Origin'), PHP_URL_PORT) ? ':' . parse_url($request->header('Origin'), PHP_URL_PORT) : '');
                if (! in_array($originHostWithPort, AppConst::ALLOW_HOST)) {
                    logger('__HOST__', [
                        'host' => $originHostWithPort,
                        'allow' => AppConst::ALLOW_HOST
                    ]);
                    return Json::error('Invalid Host Header', Response::HTTP_FORBIDDEN);
                }
            } else {
                if (! in_array($request->header('Host'), AppConst::ALLOW_HOST)) {
                    logger('__HOST__', [
                        'host' => $request->header('Host'),
                        'allow' => AppConst::ALLOW_HOST
                    ]);
                    return Json::error('Invalid Host Header', Response::HTTP_FORBIDDEN);
                }
            }
        } else {
            if (! in_array($request->header('Host'), AppConst::ALLOW_HOST)) {
                logger('__HOST__', [
                    'host' => $request->header('Host'),
                    'allow' => AppConst::ALLOW_HOST
                ]);
                abort(403, 'Invalid Host header');
            }
        }

        return $next($request);
    }
}
