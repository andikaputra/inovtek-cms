<?php

namespace App\Services\QuizRegistration;

use App\Http\Requests\API\HomeDesaQuiz\HomeDesaQuizRegistrationRequest;
use App\Models\QuizRegistration;

class QuizRegistrationCommandService
{
    public function storeRegistration(HomeDesaQuizRegistrationRequest $request, string $quiz_link_id, string $quiz_code): ?QuizRegistration
    {
        $query = new QuizRegistration;
        $query->quiz_link_id = $quiz_link_id;
        $query->name = $request->name;
        $query->email = $request->email;
        $query->phone_no = $request->phone_no;
        $query->sex_type = $request->sex_type;
        $query->quiz_code = $quiz_code;
        $query->age = $request->age;
        $query->region_detail_id = $request->village_id;
        $query->work = $request->work;
        $query->save();

        return $query;
    }
}
