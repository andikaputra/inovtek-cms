<?php

namespace App\Http\UseCase\Admin;

use App\Http\Interfaces\Admin\FileManagerInterface;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

final class FileManagerUseCase implements FileManagerInterface
{
    public function renderIndex(Request $request): View
    {
        return view('admin.pages.file-manager.index');
    }
}
