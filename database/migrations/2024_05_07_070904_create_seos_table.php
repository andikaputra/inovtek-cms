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
        Schema::create('seos', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->ulidMorphs('seotable');
            $table->ulid('seo_key');
            $table->string('meta_title');
            $table->text('meta_description');
            $table->string('meta_robot');
            $table->string('meta_author');
            $table->text('meta_keyword');
            $table->string('meta_language');
            $table->string('meta_og_title')->nullable();
            $table->text('meta_og_description')->nullable();
            $table->string('meta_og_url')->nullable();
            $table->string('meta_og_type');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('seos');
    }
};
