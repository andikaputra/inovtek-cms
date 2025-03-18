<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Interfaces\Admin\SecurityInterface;
use App\Http\Requests\Admin\Security\SecurityUpdateRequest;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Throwable;

final class SecurityController extends Controller
{
    public function __construct(private readonly SecurityInterface $securityInterface) {}

    public function index(Request $request): View
    {
        return $this->securityInterface->renderIndex(request: $request);
    }

    public function update(SecurityUpdateRequest $request): RedirectResponse
    {
        try {
            DB::beginTransaction();
            $this->securityInterface->execUpdate(request: $request);
            DB::commit();

            if (isset($request->password)) {
                Auth::logout();

                return to_route('login')->with('success', trans('response.success.update', ['data' => 'User Security']));
            }

            return to_route('admin.setting.security.index')->with('success', trans('response.success.update', ['data' => 'User Security']));
        } catch (Throwable $th) {
            DB::rollBack();

            return to_route('admin.setting.security.index')->with('error', $th->getMessage());
        }
    }
}
