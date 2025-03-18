<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('region_detail_mapboxes', function (Blueprint $table) {
            DB::statement('ALTER TABLE region_detail_mapboxes ALTER COLUMN order_point TYPE INTEGER USING order_point::integer');
        });

        Schema::table('region_detail_mapbox_lists', function (Blueprint $table) {
            DB::statement('ALTER TABLE region_detail_mapbox_lists ALTER COLUMN order_point TYPE INTEGER USING order_point::integer');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('region_detail_mapboxes', function (Blueprint $table) {
            DB::statement('ALTER TABLE region_detail_mapboxes ALTER COLUMN order_point TYPE TEXT');
        });

        Schema::table('region_detail_mapbox_lists', function (Blueprint $table) {
            DB::statement('ALTER TABLE region_detail_mapbox_lists ALTER COLUMN order_point TYPE TEXT');
        });
    }
};
