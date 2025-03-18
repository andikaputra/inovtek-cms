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
        Schema::create('quiz_registrations', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('quiz_link_id')->references('id')->on('quiz_links');
            $table->foreignUlid('region_detail_id')->references('id')->on('region_details');
            $table->string('name');
            $table->string('email');
            $table->string('phone_no');
            $table->enum('sex_type', ['L', 'P']);
            $table->string('age');
            $table->text('work');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quiz_registrations');
    }
};
