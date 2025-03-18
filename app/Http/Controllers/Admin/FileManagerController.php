<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Interfaces\Admin\FileManagerInterface;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

final class FileManagerController extends Controller
{
    public function __construct(private readonly FileManagerInterface $fileManagerInterface) {}

    public function index(Request $request): View
    {
        return $this->fileManagerInterface->renderIndex(request: $request);
    }
}
