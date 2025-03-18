<?php

namespace App\Http\Interfaces\Admin;

use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

/**
 * Interface HomeDesaQuizRegistrantInterface
 */
interface HomeDesaQuizRegistrantInterface
{
    /**
     * Render the index view for listing quiz registrants.
     *
     * @param  Request  $request  The current request instance.
     * @param  string  $id_provinsi  The province ID to display relevant quiz registrants.
     * @param  string  $id_kuis  The quiz ID to filter the registrants.
     * @return View The view used for displaying the list of quiz registrants.
     */
    public function renderIndex(Request $request, string $id_provinsi, string $id_kuis): View;

    /**
     * Render the data table view for listing quiz registrants.
     *
     * @param  Request  $request  The current request instance.
     * @param  string  $id_provinsi  The province ID to filter quiz registrants.
     * @param  string  $id_kuis  The quiz ID to filter the registrants.
     * @return JsonResponse The data table in JSON format.
     */
    public function renderDatatable(Request $request, string $id_provinsi, string $id_kuis): JsonResponse;

    /**
     * Export the quiz registrants' data to a downloadable file.
     *
     * @param  Request  $request  The current request instance.
     * @param  string  $id_provinsi  The province ID to filter quiz registrants.
     * @param  string  $id_kuis  The quiz ID to filter the registrants.
     * @return BinaryFileResponse The response containing the downloadable file.
     */
    public function execExport(Request $request, string $id_provinsi, string $id_kuis): BinaryFileResponse;
}
