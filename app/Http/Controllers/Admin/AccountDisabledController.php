<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Interfaces\Admin\AccountDisabledInterface;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

final class AccountDisabledController extends Controller
{
    public function __construct(private readonly AccountDisabledInterface $accountDisabledInterface) {}

    public function index(Request $request): View
    {
        return $this->accountDisabledInterface->renderIndex(request: $request);
    }
}
