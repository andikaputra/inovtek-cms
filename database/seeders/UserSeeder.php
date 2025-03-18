<?php

namespace Database\Seeders;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = new User;
        $user->username = $user->generateUsername(nama: 'Super Admin', tglDaftar: date('Y-m-d'));
        $user->name = 'Super Admin';
        $user->email = 'super.admin@gmail.com';
        $user->password = Hash::make(value: '@Dmin1234');
        $user->email_verified_at = Carbon::now();
        $user->is_default = true;
        $user->is_active = true;
        $user->is_super_admin = true;
        $user->save();
    }
}
