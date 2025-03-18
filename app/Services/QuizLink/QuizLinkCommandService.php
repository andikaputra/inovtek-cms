<?php

namespace App\Services\QuizLink;

use App\Http\Requests\Admin\HomeDesaQuiz\HomeDesaQuizLinkStoreRequest;
use App\Http\Requests\Admin\HomeDesaQuiz\HomeDesaQuizLinkUpdateRequest;
use App\Models\QuizLink;

class QuizLinkCommandService
{
    public function store(HomeDesaQuizLinkStoreRequest $request, string $id_provinsi): ?QuizLink
    {
        $query = new QuizLink;
        $query->region_id = $id_provinsi;
        $query->name = $request->name;
        $query->quiz_link = $request->quiz_link;
        $query->is_active = isset($request->is_active) ? true : false;
        $query->save();

        return $query;
    }

    public function updateContent(HomeDesaQuizLinkUpdateRequest $request, QuizLink $quizLink): ?QuizLink
    {
        $quizLink->name = $request->name;
        $quizLink->quiz_link = $request->quiz_link;
        $quizLink->save();

        return $quizLink;
    }

    public function updateActive(QuizLink $quizLink): bool
    {
        $quizLink->is_active = $quizLink->is_active ? false : true;

        return $quizLink->save();
    }

    public function delete(QuizLink $quizLink): bool
    {
        return $quizLink->delete();
    }
}
