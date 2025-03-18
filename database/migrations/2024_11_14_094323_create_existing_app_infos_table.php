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
        Schema::create('existing_app_infos', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('existing_app_id')->references('id')->on('existing_apps');
            $table->text('intro_video_url');
            $table->text('tutorial_video_url');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('existing_app_infos');
    }
};
