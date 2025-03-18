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
        Schema::table('region_detail_infos', function (Blueprint $table) {
            $table->dropColumn('intro_video_url');
            $table->dropColumn('tutorial_video_url');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('region_detail_infos', function (Blueprint $table) {
            $table->text('intro_video_url');
            $table->text('tutorial_video_url');
        });
    }
};
