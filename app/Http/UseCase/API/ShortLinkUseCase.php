<?php

namespace App\Http\UseCase\API;

use App\Constants\AppConst;
use App\Http\Interfaces\API\ShortLinkInterface;
use App\Models\ShortLink;
use App\Services\ShortLink\ShortLinkQueryService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

final class ShortLinkUseCase implements ShortLinkInterface
{
    public function __construct(private readonly ShortLinkQueryService $shortLinkQueryService) {}

    public function renderGetAllShortLinkData(Request $request): array
    {
        $shortLink = $this->shortLinkQueryService->getAllShortLink(request: $request);
        $active = $shortLink->first()?->active_count ?? 0;
        $deactive = $shortLink->first()?->deactive_count ?? 0;
        $countData = [
            'all' => $active + $deactive,
            'active' => $active,
            'deactive' => $deactive,
        ];

        // Path ke folder file QR Code
        $folderPath = 'short-link-qrcode';
        $shortLink->each(function ($item) use ($folderPath) {
            $fileName = 'short_url_'.$item->id.'.png';
            $filePath = $folderPath.'/'.$fileName;
            $item->short_url = 'https://'.AppConst::SHORT_LINK_BASE_URL.'/s/'.$item->short_url;
            $item->qr_code = $this->_appendTokenImage($filePath);
            $item->makeHidden(['active_count', 'deactive_count', 'assets']);
        });

        return [
            'pageInfo' => $countData,
            'nodes' => $shortLink,
        ];
    }

    public function renderGetAllDetailShortLinkData(Request $request, string $identifier): ?ShortLink
    {
        $findShortLink = $this->shortLinkQueryService->findById(id: $identifier);
        if (! isset($findShortLink)) {
            throw new Exception(
                trans('response.error.view', ['data' => 'Short Link', 'error' => 'Data Tidak Ditemukan']),
                Response::HTTP_NOT_FOUND
            );
        }
        // Path QR
        $folderPath = 'short-link-qrcode';
        $fileName = 'short_url_'.$findShortLink->id.'.png';
        $filePath = $folderPath.'/'.$fileName;

        // Short Link
        $findShortLink->short_url = 'https://'.AppConst::SHORT_LINK_BASE_URL.'/s/'.$findShortLink->short_url;

        $findShortLink->qr_code = $this->_appendTokenImage($filePath);

        return $findShortLink;
    }

    private function _appendTokenImage(?string $relativePath): ?string
    {
        return $relativePath ? route('api.file.preview', ['path' => $relativePath]) : null;
    }
}
