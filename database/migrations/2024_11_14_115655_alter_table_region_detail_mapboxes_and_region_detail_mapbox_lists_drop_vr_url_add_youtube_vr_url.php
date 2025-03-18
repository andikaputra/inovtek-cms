<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('region_detail_mapboxes', function (Blueprint $table) {
            $table->text('vr_youtube_url')->nullable();
        });

        Schema::table('region_detail_mapbox_lists', function (Blueprint $table) {
            $table->dropColumn('vr_url');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('region_detail_mapboxes', function (Blueprint $table) {
            $table->dropColumn('vr_youtube_url');
        });

        Schema::table('region_detail_mapbox_lists', function (Blueprint $table) {
            $table->text('vr_url')->nullable();
        });
    }
};
