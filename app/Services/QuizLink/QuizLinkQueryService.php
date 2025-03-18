<?php

namespace App\Services\QuizLink;

use App\Models\QuizLink;

class QuizLinkQueryService
{
    public function checkExistActive(string $id_provinsi): bool
    {
        return QuizLink::where('region_id', $id_provinsi)
            ->where('is_active', true)
            ->exists();
    }

    public function countActiveQuizLink(string $id_provinsi, string $except_id): bool
    {
        return QuizLink::where('region_id', $id_provinsi)
            ->where('is_active', true)
            ->where('id', '!=', $except_id)
            ->count();
    }

    public function findQuizLinkById(string $id_provinsi, string $id): ?QuizLink
    {
        return QuizLink::where('region_id', $id_provinsi)->where('id', $id)->first();
    }

    public function findQuizActive(string $region_id): ?QuizLink
    {
        return QuizLink::where('region_id', $region_id)->where('is_active', true)->first();
    }
}
