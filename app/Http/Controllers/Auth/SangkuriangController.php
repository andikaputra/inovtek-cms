<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Interfaces\Auth\SangkuriangInterface;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class SangkuriangController extends Controller
{
    public function __construct(private readonly SangkuriangInterface $sangkuriangInterface) {}

    public function handleLogin(Request $request): RedirectResponse
    {
        return $this->sangkuriangInterface->execHandleLogin(request: $request);
    }

    public function handleError(): View
    {
        return $this->sangkuriangInterface->renderHandleError();
    }
}
