<?php

namespace App\Services\User;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class UserQueryService
{
    public function findUserById(string $id): ?User
    {
        $query = User::query();
        $query->where('id', $id);

        $user = $query->first();

        return $user;
    }

    public function findUserByEmail(string $email): ?User
    {
        $query = User::query();
        $query->where('email', $email);

        $user = $query->first();

        return $user;
    }

    public function getAllUser(): Collection
    {
        return User::where('is_active', true)->get();
    }
}
