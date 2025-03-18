<?php

namespace App\Services\User;

use App\Http\Requests\Admin\Profile\ProfileUpdateRequest;
use App\Http\Requests\Admin\Security\SecurityUpdateRequest;
use App\Http\Requests\Admin\User\UserStoreRequest;
use App\Http\Requests\Admin\User\UserUpdateRequest;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

class UserCommandService
{
    public function storeUser(UserStoreRequest $request): User
    {
        $user = new User;
        $user->username = $user->generateUsername($request->name, Carbon::now()->format('Y-m-d'));
        $user->email = strtolower($request->email);
        $user->email_verified_at = Carbon::now()->format('Y-m-d H:i:s');
        $user->name = $request->name;
        $user->password = Hash::make($request->password);
        $user->is_default = false;
        $user->guid_user = null;
        $user->is_super_admin = $request->role_access == 'super_admin' ? true : false;
        $user->is_active = isset($request->is_active) ? true : false;
        $user->save();

        return $user;
    }

    public function storeUserByIntegration(array $data): User
    {
        $user = new User;
        $user->username = $user->generateUsername($data['name'], Carbon::now()->format('Y-m-d'));
        $user->email = strtolower($data['email']);
        $user->email_verified_at = Carbon::now()->format('Y-m-d H:i:s');
        $user->name = $data['name'];
        $user->password = Hash::make('@Dmin1234|'.$data['name']);
        $user->is_default = false;
        $user->guid_user = $data['id'];
        $user->is_super_admin = true;
        $user->is_active = true;
        $user->save();

        return $user;
    }

    public function updateUser(UserUpdateRequest $request, User $user): User
    {
        $user->email = strtolower($request->email);
        $user->name = $request->name;

        if (isset($request->password)) {
            $user->password = Hash::make($request->password);
        }

        $user->is_super_admin = $request->role_access == 'super_admin' ? true : false;
        $user->is_active = isset($request->is_active) ? true : false;
        $user->save();

        return $user;
    }

    public function deleteUser(User $user): bool
    {
        return $user->delete();
    }

    public function setStatus(User $user): bool
    {
        return $user->update(['is_active' => $user->is_active ? false : true]);
    }

    public function updateUserProfile(ProfileUpdateRequest $request, User $user): bool
    {
        return $user->update(['name' => $request->name]);
    }

    public function updateUserSecurity(SecurityUpdateRequest $request, User $user): bool
    {
        $user->email = $request->email;
        if (isset($request->password)) {
            $user->password = Hash::make($request->password);
        }

        return $user->save();
    }
}
