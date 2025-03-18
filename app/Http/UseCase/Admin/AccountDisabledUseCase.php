<?php

namespace App\Http\UseCase\Admin;

use App\Http\Interfaces\Admin\AccountDisabledInterface;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

final class AccountDisabledUseCase implements AccountDisabledInterface
{
    public function renderIndex(Request $request): View
    {
        if (! Auth::check() || Auth::user()->is_active) {
            abort(Response::HTTP_NOT_FOUND);
        }

        return view('errors.disable-account');
    }
}
