<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckUserRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (Auth::check()) {
            if (! Auth::user()->is_active) {
                return to_route('admin.account-disabled.index');
            }

            $userRole = Auth::user()->is_super_admin ? 'super_admin' : 'admin';
            $roles = explode('|', $role);

            if (in_array($userRole, $roles)) {
                return $next($request);
            }
        }

        throw new Exception('Access Denied, API only available for role '.str_replace('|', ',', $role), Response::HTTP_FORBIDDEN);
    }
}
