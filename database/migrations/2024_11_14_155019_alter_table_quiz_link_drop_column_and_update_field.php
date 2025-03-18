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
        Schema::table('quiz_links', function (Blueprint $table) {
            $table->dropColumn('description');
            $table->dropColumn('start_registration');
            $table->dropColumn('end_registration');
            $table->dropColumn('start_quiz');
            $table->dropColumn('end_quiz');
            $table->text('quiz_link')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('quiz_links', function (Blueprint $table) {
            $table->text('description');
            $table->date('start_registration');
            $table->date('end_registration')->nullable();
            $table->date('start_quiz')->nullable();
            $table->date('end_quiz')->nullable();
            $table->text('quiz_link')->nullable();
        });
    }
};
