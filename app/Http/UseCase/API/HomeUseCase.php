<?php

namespace App\Http\UseCase\API;

use App\Http\Interfaces\API\HomeInterface;
use App\Http\Requests\API\HomeDesaQuiz\HomeDesaQuizRegistrationRequest;
use App\Models\QuizRegistration;
use App\Models\Region;
use App\Notifications\RegisterQuizNotification;
use App\Services\Announcement\AnnouncementQueryService;
use App\Services\Blog\BlogQueryService;
use App\Services\LinkCollection\LinkCollectionQueryService;
use App\Services\QuizLink\QuizLinkQueryService;
use App\Services\QuizRegistration\QuizRegistrationCommandService;
use App\Services\QuizRegistration\QuizRegistrationQueryService;
use App\Services\Region\RegionQueryService;
use App\Services\RegionDetail\RegionDetailQueryService;
use App\Services\RegionGallery\RegionGalleryQueryService;
use App\Services\User\UserQueryService;
use Exception;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

final class HomeUseCase implements HomeInterface
{
    public function __construct(
        private readonly RegionQueryService $regionQueryService,
        private readonly AnnouncementQueryService $announcementQueryService,
        private readonly QuizLinkQueryService $quizLinkQueryService,
        private readonly BlogQueryService $blogQueryService,
        private readonly LinkCollectionQueryService $linkCollectionQueryService,
        private readonly RegionGalleryQueryService $regionGalleryQueryService,
        private readonly RegionDetailQueryService $regionDetailQueryService,
        private readonly QuizRegistrationCommandService $quizRegistrationCommandService,
        private readonly QuizRegistrationQueryService $quizRegistrationQueryService,
        private readonly UserQueryService $userQueryService
    ) {}

    public function renderGetAllWilayahData(Request $request): array
    {
        $getAllRegion = $this->regionQueryService->getAllRegion(request: $request, withActive: true);
        $active = $getAllRegion->first()?->active_count ?? 0;
        $deactive = $getAllRegion->first()?->deactive_count ?? 0;
        $countData = [
            'all' => $active + $deactive,
            'active' => $active,
            'deactive' => $deactive,
        ];

        // Mengatur data assets dan wallpaper, serta menghapus field yang tidak diperlukan
        $getAllRegion->each(function ($item) {
            $item->wallpaper = $this->_appendTokenImage(relativePath: $item->assets[0]?->asset_path);
            $item->assets = $item->assets->map(fn ($asset) => $asset->asset_url = $this->_appendTokenImage(relativePath: $asset->asset_path));
            $item->makeHidden(['active_count', 'deactive_count']);
        });

        return [
            'pageInfo' => $countData,
            'nodes' => $getAllRegion,
        ];
    }

    public function execPostQuizRegistration(HomeDesaQuizRegistrationRequest $request, string $identifier): ?QuizRegistration
    {
        // Validate  Phone No
        $phone_no = $this->_checkAndValidatePhoneNo(phone_no: $request->phone_no);
        if (! isset($phone_no) || $phone_no == null) {
            throw new Exception(
                trans('response.error.store', ['data' => 'Pendaftaran Kuis', 'error' => 'Nomor Telepon Tidak Valid']),
                Response::HTTP_NOT_FOUND
            );
        }

        $request->merge([
            'phone_no' => '62'.$phone_no,
        ]);

        // Quiz Registration
        $region = $this->regionQueryService->findRegionByIdentifier(identifier: $identifier);
        if (! $region) {
            throw new Exception(
                trans('response.error.store', ['data' => 'Pendaftaran Kuis', 'error' => 'Data Tidak Ditemukan']),
                Response::HTTP_NOT_FOUND
            );
        }

        $regionDetail = $this->regionDetailQueryService->findDetailRegionById(id_provinsi: $region->id, id: $request->village_id);

        if (! $regionDetail) {
            throw new Exception(
                trans('response.error.store', ['data' => 'Pendaftaran Kuis', 'error' => 'Desa/Kelurahan Data Tidak Ditemukan']),
                Response::HTTP_NOT_FOUND
            );
        }

        if (! $regionDetail->is_active) {
            throw new Exception(
                trans('response.error.store', ['data' => 'Pendaftaran Kuis', 'error' => 'Desa/Kelurahan Data Tidak Aktif']),
                Response::HTTP_NOT_FOUND
            );
        }

        $quizLink = $this->quizLinkQueryService->findQuizActive(region_id: $region->id);

        if (! isset($quizLink)) {
            throw new Exception(
                trans('response.error.store', ['data' => 'Pendaftaran Kuis', 'error' => 'Tidak Ada Kuis Yang Sedang Aktif Saat Ini']),
                Response::HTTP_NOT_FOUND
            );
        }

        // Disabled for now
        // $checkExistRegistration = $this->quizRegistrationQueryService->checkExistRegistration(quiz_link_id: $quizLink->id, email: $request->email, phone_no: $request->phone_no);
        // if ($checkExistRegistration) {
        //     throw new Exception(
        //         trans('response.error.store', ['data' => 'Pendaftaran Kuis', 'error' => 'Akun Telah Terdaftar Pada Kuis Ini']),
        //         Response::HTTP_CONFLICT
        //     );
        // }

        $shortCode = $this->_generateCode();

        $storeData = $this->quizRegistrationCommandService->storeRegistration(request: $request, quiz_link_id: $quizLink->id, quiz_code: $shortCode);

        if (! isset($storeData)) {
            throw new Exception(
                trans('response.error.store', ['data' => 'Pendaftaran Kuis', 'error' => 'Gagal Menambahkan Pendaftaran']),
                Response::HTTP_CONFLICT
            );
        }

        $getAllUser = $this->userQueryService->getAllUser();
        foreach ($getAllUser as $user) {
            $user->notify(new RegisterQuizNotification(registrant: $storeData));
        }

        return $storeData;
    }

    public function renderGetQuizRegister(Request $request, string $identifier): Collection|LengthAwarePaginator
    {
        $region = $this->regionQueryService->findRegionByIdentifier(identifier: $identifier);
        if (! $region) {
            throw new Exception(
                trans('response.error.view', ['data' => 'Data Pendaftar Kuis', 'error' => 'Data Tidak Ditemukan']),
                Response::HTTP_NOT_FOUND
            );
        }

        $quizLink = $this->quizLinkQueryService->findQuizActive(region_id: $region->id);

        if (! isset($quizLink)) {
            throw new Exception(
                trans('response.error.view', ['data' => 'Data Pendaftar Kuis', 'error' => 'Tidak Ada Kuis Yang Sedang Aktif Saat Ini']),
                Response::HTTP_NOT_FOUND
            );
        }

        return $this->quizRegistrationQueryService->getAllQuizRegister(request: $request, quiz_link_id: $quizLink->id);
    }

    public function renderGetAllWilayahDetailData(Request $request, string $identifier): ?Region
    {
        $region = $this->regionQueryService->findRegionByIdentifier(identifier: $identifier);
        if (! $region) {
            throw new Exception(
                trans('response.error.view', ['data' => 'Wilayah', 'error' => 'Data Tidak Ditemukan']),
                Response::HTTP_NOT_FOUND
            );
        }

        // Wallpaper dan Assets
        $region->wallpaper = $this->_appendTokenImage($region->assets[0]?->asset_path);
        $region->assets = $this->_mapAssets($region->assets);

        // Pengumuman
        $region->announcement = $this->_getAnnouncementData($region->id);

        // Quiz
        $region->quiz = $this->_getQuizData($region->id);

        // Blog
        $region->blog = $this->_getBlogData($request, $region->id);

        // Link Tautan
        $region->useful_link = $this->_getUsefulLinks($region->id);

        // Social Media
        $region->social_media = $this->_getSocialMediaLinks($region->id);

        // Galeri
        $region->gallery = $this->_getGalleryData($region->id);

        // Village
        $region->village = $this->_getVillageData($region->id);

        return $region;
    }

    // Private function here
    // Private function here
    private function _generateCode(): string
    {
        return DB::transaction(function () {
            do {
                $shortCode = substr(str_shuffle('0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 4);

                $exists = $this->quizRegistrationQueryService->checkExistCode($shortCode);
            } while ($exists);

            return $shortCode;
        });
    }

    private function _checkAndValidatePhoneNo(string $phone_no): ?string
    {
        $phoneNumber = preg_replace('/\D/', '', $phone_no);

        if (preg_match('/^(?:\+62|62|0)?(0*\d{9,16})$/', $phoneNumber, $matches)) {
            $cleanedNumber = ltrim($matches[1], '0');

            if (substr($cleanedNumber, 0, 1) === '8') {
                return $cleanedNumber;
            }
        }

        return null;
    }

    private function _appendTokenImage(?string $relativePath): ?string
    {
        return $relativePath ? route('api.file.preview', ['path' => $relativePath]) : null;
    }

    private function _mapAssets($assets)
    {
        return $assets->map(fn ($asset) => $asset->asset_url = $this->_appendTokenImage($asset->asset_path));
    }

    private function _getAnnouncementData(string $regionId)
    {
        $announcement = $this->announcementQueryService->findAnnouncementActive(region_id: $regionId);

        return $announcement;
    }

    private function _getVillageData(string $regionId)
    {
        return $this->regionDetailQueryService->getAllRegionDetail(identifier: $regionId)->map(function ($item) {
            $item->makeHidden(['deleted_at', 'latitude', 'longitude', 'map_url']);

            return $item;
        });
    }

    private function _getQuizData(string $regionId)
    {
        $quiz = $this->quizLinkQueryService->findQuizActive(region_id: $regionId);

        return $quiz;
    }

    private function _getBlogData(Request $request, string $regionId)
    {
        $blog = $this->blogQueryService->getAllBlog(request: $request, onlyRegionData: true, region_id: $regionId);
        $active = $blog->first()?->active_count ?? 0;
        $deactive = $blog->first()?->deactive_count ?? 0;
        $countData = [
            'all' => $active + $deactive,
            'active' => $active,
            'deactive' => $deactive,
        ];

        $blog->each(function ($item) {
            $item->wallpaper = $this->_appendTokenImage($item->assets[0]?->asset_path);
            $item->assets = $this->_mapAssets($item->assets);
            $item->makeHidden(['active_count', 'deactive_count', 'is_general_blog', 'assets']);
        });

        return [
            'pageInfo' => $countData,
            'nodes' => $blog,
        ];
    }

    private function _getUsefulLinks(string $regionId)
    {
        return $this->linkCollectionQueryService
            ->getLinkByRegionId(region_id: $regionId)
            ->each(function ($item) {
                $item->icon = $this->_appendTokenImage($item->assets[0]?->asset_path);
                $item->makeHidden(['created_at', 'updated_at', 'assets', 'is_social_media']);
            });
    }

    private function _getSocialMediaLinks(string $regionId)
    {
        return $this->linkCollectionQueryService
            ->getLinkByRegionId(region_id: $regionId, isSocialMedia: true)
            ->each(function ($item) {
                $item->icon = $this->_appendTokenImage($item->assets[0]?->asset_path);
                $item->makeHidden(['created_at', 'updated_at', 'assets', 'is_social_media']);
            });
    }

    private function _getGalleryData(string $regionId)
    {
        $gallery = $this->regionGalleryQueryService->findGalleryByRegionId(id_provinsi: $regionId, withActive: true);
        if ($gallery) {
            $gallery->assets = $this->_mapAssets($gallery->assets);
            $gallery->makeHidden(['created_at', 'updated_at']);
        }

        return $gallery;
    }
}
