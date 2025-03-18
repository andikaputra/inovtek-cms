<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Interfaces\Admin\ProfileInterface;
use App\Http\Requests\Admin\Profile\ProfileUpdateRequest;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

final class ProfileController extends Controller
{
    public function __construct(private readonly ProfileInterface $profileInterface) {}

    public function index(Request $request): View
    {
        return $this->profileInterface->renderIndex(request: $request);
    }

    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        try {
            DB::beginTransaction();
            $this->profileInterface->execUpdate(request: $request);
            DB::commit();

            return to_route('admin.setting.profile.index')->with('success', trans('response.success.update', ['data' => 'User Profile']));
        } catch (Throwable $th) {
            DB::rollBack();

            return to_route('admin.setting.profile.index')->with('error', $th->getMessage());
        }
    }
}
