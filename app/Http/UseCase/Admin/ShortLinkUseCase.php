<?php

namespace App\Http\UseCase\Admin;

use App\Constants\AppConst;
use App\Http\Interfaces\Admin\ShortLinkInterface;
use App\Http\Requests\Admin\ShortLink\ShortLinkStoreRequest;
use App\Http\Requests\Admin\ShortLink\ShortLinkUpdateRequest;
use App\Models\ShortLink;
use App\Services\ShortLink\ShortLinkCommandService;
use App\Services\ShortLink\ShortLinkDatatableService;
use App\Services\ShortLink\ShortLinkQueryService;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

final class ShortLinkUseCase implements ShortLinkInterface
{
    public function __construct(
        private readonly ShortLinkQueryService $shortLinkQueryService,
        private readonly ShortLinkDatatableService $shortLinkDatatableService,
        private readonly ShortLinkCommandService $shortLinkCommandService

    ) {}

    public function renderIndex(Request $request): View
    {
        return view('admin.pages.short-link.index');
    }

    public function renderCreate(Request $request): View
    {
        return view('admin.pages.short-link.create');
    }

    public function renderDatatable(Request $request): JsonResponse
    {
        return $this->shortLinkDatatableService->shortLinkDatatable(request: $request);
    }

    public function execStore(ShortLinkStoreRequest $request): ?ShortLink
    {
        $request->merge([
            'short_url' => $this->_generateCode(),
        ]);

        $storeData = $this->shortLinkCommandService->store(request: $request);

        if (! isset($storeData)) {
            throw new Exception(trans('response.error.store', ['data' => 'Short Link', 'error' => 'data tidak ditemukan']));
        }

        $fullUrl = 'https://'.AppConst::SHORT_LINK_BASE_URL.'/s/'.$storeData->short_url;
        $qrCode = new QrCode($fullUrl);

        $writer = new PngWriter;
        $image = $writer->write($qrCode);

        $folderPath = 'short-link-qrcode'; // Nama folder di Storage::public
        $fileName = 'short_url_'.$storeData->id.'.png';
        $filePath = $folderPath.'/'.$fileName;

        if (! Storage::disk('public')->exists($folderPath)) {
            Storage::disk('public')->makeDirectory($folderPath);
        }

        $image->saveToFile(storage_path('app/public/'.$filePath));

        return $storeData;
    }

    public function renderEdit(Request $request, string $id): View
    {
        $findShortLink = $this->shortLinkQueryService->findById(id: $id);

        if (! isset($findShortLink)) {
            abort(Response::HTTP_NOT_FOUND);
        }

        return view('admin.pages.short-link.edit', compact('findShortLink'));
    }

    public function execUpdate(ShortLinkUpdateRequest $request, string $id): ?ShortLink
    {
        $findShortLink = $this->shortLinkQueryService->findById(id: $id);

        if (! isset($findShortLink)) {
            throw new Exception(trans('response.error.update', ['data' => 'Short Link', 'error' => 'data tidak ditemukan']));
        }

        $storeData = $this->shortLinkCommandService->update(request: $request, shortLink: $findShortLink);

        if (! isset($storeData)) {
            throw new Exception(trans('response.error.update', ['data' => 'Short Link', 'error' => 'data tidak ditemukan']));
        }

        return $storeData;
    }

    public function execSetStatus(Request $request, string $id): bool
    {
        $findShortLink = $this->shortLinkQueryService->findById(id: $id);

        if (! isset($findShortLink)) {
            throw new Exception(trans('response.error.switch', ['data' => 'Perubahan Status Short Link', 'error' => 'data tidak ditemukan']));
        }

        return $this->shortLinkCommandService->updateStatus(shortLink: $findShortLink);
    }

    public function execDelete(Request $request, string $id): bool
    {
        $findShortLink = $this->shortLinkQueryService->findById(id: $id);

        if (! isset($findShortLink)) {
            throw new Exception(trans('response.error.delete', ['data' => 'Short Link', 'error' => 'data tidak ditemukan']));
        }

        if ($findShortLink->is_active) {
            throw new Exception(trans('response.error.delete', ['data' => 'Short Link', 'error' => 'mohon nonaktifkan short link terlebih dahulu']));
        }
        // Path ke folder file QR Code
        $folderPath = 'short-link-qrcode';
        $fileName = 'short_url_'.$findShortLink->id.'.png';
        $filePath = $folderPath.'/'.$fileName;

        // Cek dan hapus file jika ada
        if (Storage::disk('public')->exists($filePath)) {
            Storage::disk('public')->delete($filePath);
        }

        return $this->shortLinkCommandService->delete(shortLink: $findShortLink);
    }

    public function execRedirect(Request $request, string $uniqueCode): RedirectResponse
    {
        $findShortLink = $this->shortLinkQueryService->findLinkByUniqueCode(uniqueCode: $uniqueCode);
        if (! isset($findShortLink)) {
            throw new Exception('Short Link yang dimasukkan tidak valid');
        }

        if (! $findShortLink->is_active) {
            throw new Exception('Short Link yang dimasukkan sedang tidak aktif');
        }

        $this->shortLinkCommandService->incrementClick(shortLink: $findShortLink);

        return redirect()->away($findShortLink->original_url);
    }

    // Private function here
    private function _generateCode(): string
    {
        return DB::transaction(function () {
            do {
                $shortCode = Str::random(6);

                $exists = $this->shortLinkQueryService->checkExistCode($shortCode);
            } while ($exists);

            return $shortCode;
        });
    }
}
