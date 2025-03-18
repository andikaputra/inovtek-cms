<?php

namespace Database\Seeders;

use App\Constants\AppConst;
use App\Models\ExistingApp;
use Illuminate\Database\Seeder;

class CreateExistingApp extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (AppConst::MAP_EXISTING_APP as $index => $item) {
            $existingApp = new ExistingApp;
            $existingApp->code = $index;
            $existingApp->display = $item;
            $existingApp->save();
        }
    }
}
