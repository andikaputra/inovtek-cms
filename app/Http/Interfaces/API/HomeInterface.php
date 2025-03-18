<?php

namespace App\Http\Interfaces\API;

use App\Http\Requests\API\HomeDesaQuiz\HomeDesaQuizRegistrationRequest;
use App\Models\QuizRegistration;
use App\Models\Region;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

/**
 * Interface HomeInterface
 */
interface HomeInterface
{
    /**
     * Retrieve and render all regional data.
     *
     * @param  Request  $request  The HTTP request instance.
     * @return array An array containing all regional data.
     */
    public function renderGetAllWilayahData(Request $request): array;

    /**
     * Retrieve and render detailed data for a specific region.
     *
     * @param  Request  $request  The HTTP request instance.
     * @param  string  $identifier  A unique identifier for the region.
     * @return Region|null The detailed data for the specified region, or null if not found.
     */
    public function renderGetAllWilayahDetailData(Request $request, string $identifier): ?Region;

    /**
     * Handle the quiz registration for a specific region.
     *
     * @param  HomeDesaQuizRegistrationRequest  $request  The quiz registration request instance.
     * @param  string  $identifier  A unique identifier for the region where the quiz registration applies.
     * @return QuizRegistration|null The created QuizRegistration instance or null if registration fails.
     */
    public function execPostQuizRegistration(HomeDesaQuizRegistrationRequest $request, string $identifier): ?QuizRegistration;

    /**
     * Retrieve and render quiz register data for a specific region.
     *
     * @param  Request  $request  The HTTP request instance.
     * @param  string  $identifier  The identifier for the Desa.
     * @return Collection The quiz register data for the specified region.
     */
    public function renderGetQuizRegister(Request $request, string $identifier): Collection|LengthAwarePaginator;
}
