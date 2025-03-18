<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Interfaces\Admin\AboutAppInterface;
use App\Http\Requests\Admin\AboutApp\AboutAppUpdateRequest;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

final class AboutAppController extends Controller
{
    public function __construct(private readonly AboutAppInterface $aboutAppInterface) {}

    public function edit(Request $request): View
    {
        return $this->aboutAppInterface->renderEdit(request: $request);
    }

    public function update(AboutAppUpdateRequest $request): RedirectResponse
    {
        try {
            DB::beginTransaction();
            $this->aboutAppInterface->execUpdate(request: $request);
            DB::commit();

            return to_route('admin.tentang-aplikasi.edit')->with('success', trans('response.success.update', ['data' => 'Informasi Produk']));
        } catch (Throwable $th) {
            DB::rollBack();

            return to_route('admin.tentang-aplikasi.edit')->with('error', $th->getMessage());
        }
    }
}
