<?php

namespace App\Http\Interfaces\Admin;

use App\Http\Requests\Admin\HomeDesaQuiz\HomeDesaQuizLinkStoreRequest;
use App\Http\Requests\Admin\HomeDesaQuiz\HomeDesaQuizLinkUpdateRequest;
use App\Models\QuizLink;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Interface HomeDesaQuizLinkInterface
 */
interface HomeDesaQuizLinkInterface
{
    /**
     * Render the index view for listing quiz links.
     *
     * @param  Request  $request  The current request instance.
     * @param  string  $id_provinsi  The province ID to display relevant quiz links.
     * @return View The view used for displaying the list of quiz links.
     */
    public function renderIndex(Request $request, string $id_provinsi): View;

    /**
     * Render the data table view for listing quiz links.
     *
     * @param  Request  $request  The current request instance.
     * @param  string  $id_provinsi  The province ID to retrieve related quiz links.
     * @return JsonResponse The data table in JSON format.
     */
    public function renderDatatable(Request $request, string $id_provinsi): JsonResponse;

    /**
     * Render the view for creating a new quiz link.
     *
     * @param  Request  $request  The current request instance.
     * @param  string  $id_provinsi  The province ID to display the quiz link creation form.
     * @return View The view used for the new quiz link creation form.
     */
    public function renderCreate(Request $request, string $id_provinsi): View;

    /**
     * Store a new quiz link.
     *
     * @param  HomeDesaQuizLinkStoreRequest  $request  The request containing data to be stored.
     * @param  string  $id_provinsi  The province ID to associate with the new quiz link.
     * @return QuizLink|null The saved quiz link instance or null if saving fails.
     */
    public function execStore(HomeDesaQuizLinkStoreRequest $request, string $id_provinsi): ?QuizLink;

    /**
     * Render the view for editing an existing quiz link.
     *
     * @param  Request  $request  The current request instance.
     * @param  string  $id_provinsi  The province ID to display the quiz link to be edited.
     * @param  string  $id  The ID of the quiz link to be edited.
     * @return View The view used for editing the quiz link.
     */
    public function renderEdit(Request $request, string $id_provinsi, string $id): View;

    /**
     * Update the content of an existing quiz link.
     *
     * @param  HomeDesaQuizLinkUpdateRequest  $request  The request containing updated data.
     * @param  string  $id_provinsi  The province ID associated with the quiz link.
     * @param  string  $id  The ID of the quiz link to be updated.
     * @return QuizLink|null The updated quiz link instance or null if update fails.
     */
    public function execUpdateContent(HomeDesaQuizLinkUpdateRequest $request, string $id_provinsi, string $id): ?QuizLink;

    /**
     * Update the active status of a quiz link.
     *
     * @param  string  $id_provinsi  The province ID associated with the quiz link.
     * @param  string  $id  The ID of the quiz link whose status will be updated.
     * @return bool Returns true if the active status was successfully updated, false otherwise.
     */
    public function execUpdateActive(string $id_provinsi, string $id): bool;

    /**
     * Delete a quiz link.
     *
     * @param  Request  $request  The current request instance.
     * @param  string  $id_provinsi  The province ID associated with the quiz link.
     * @param  string  $id  The ID of the quiz link to be deleted.
     * @return bool Returns true if the quiz link was successfully deleted, false otherwise.
     */
    public function execDelete(Request $request, string $id_provinsi, string $id): bool;
}
