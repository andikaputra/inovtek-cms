<?php

namespace App\Http\UseCase\Auth;

use App\Helpers\JwtHelper;
use App\Http\Interfaces\Auth\SangkuriangInterface;
use App\Services\Ext\BnpbExternalService;
use App\Services\User\UserCommandService;
use App\Services\User\UserQueryService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

final class SangkuriangUseCase implements SangkuriangInterface
{
    public function __construct(
        private readonly UserQueryService $userQueryService,
        private readonly BnpbExternalService $bnpbExternalService,
        private readonly UserCommandService $userCommandService
    ) {}

    public function execHandleLogin(Request $request): RedirectResponse
    {
        Session::forget('sso_error_msg');
        if (Auth::check()) {
            $token = $this->getTokenFromRequest($request);

            if ($token !== null) {
                Auth::logout();

                return $this->checkValidationToken(request: $request);
            }

            return to_route('admin.home.index');
        }

        if (! Auth::check()) {
            return $this->checkValidationToken(request: $request);
        }
    }

    public function renderHandleError(): View
    {
        $error = Session::get('sso_error_msg');
        if (! Session::has('sso_error_msg')) {
            abort(Response::HTTP_NOT_FOUND);
        }

        return view('errors.bnpb-error', compact('error'));
    }

    // Private function here
    private function checkValidationToken(Request $request): RedirectResponse
    {
        try {
            $token = $this->getTokenFromRequest($request);

            if ($token !== null) {
                $method = 'HS256';
                $key = config('services.bnpb.jwt_secret');
                $valid = JwtHelper::checkValidToken($token, $key, $method);

                if ($valid) {
                    $getUserByToken = $this->bnpbExternalService->findUserByToken(token: $token);
                    if (! isset($getUserByToken)) {
                        Session::put('sso_error_msg', 'User tidak ditemukan, silahkan cek data kembali');

                        return to_route('sangkuriang.error');
                    }

                    if ($getUserByToken['role']['name'] != 'Admin') {
                        Session::put('sso_error_msg', 'Hak akses milik user ini tidak diizinkan untuk mengakses Aplikasi ini');

                        return to_route('sangkuriang.error');
                    }

                    $findUserByEmail = $this->userQueryService->findUserByEmail(email: $getUserByToken['email']);

                    if (! isset($findUserByEmail)) {
                        $findUserByEmail = $this->userCommandService->storeUserByIntegration(data: $getUserByToken);
                    }

                    if ($findUserByEmail->is_default || $findUserByEmail->guid_user != $getUserByToken['id']) {
                        Session::put('sso_error_msg', 'Email telah terdaftar pada aplikasi ini, silahkan gunakan email lain');

                        return to_route('sangkuriang.error');
                    }

                    if (! $findUserByEmail->is_active) {
                        return to_route('admin.account-disabled.index');
                    }

                    Auth::login($findUserByEmail);

                    return to_route('admin.home.index');
                }
            }

            Log::error(__METHOD__, ['error' => 'Invalid Token']);

            return redirect(config('services.bnpb.login_url'));
        } catch (\Throwable $th) {
            Log::error(__METHOD__, ['error' => $th->getMessage()]);
            Session::put('sso_error_msg', $th->getMessage());

            return to_route('sangkuriang.error');
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
