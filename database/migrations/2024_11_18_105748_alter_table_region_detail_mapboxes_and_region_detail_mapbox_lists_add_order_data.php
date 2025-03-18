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
            $table->text('order_point')->nullable();
        });

        Schema::table('region_detail_mapbox_lists', function (Blueprint $table) {
            $table->text('order_point')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('region_detail_mapboxes', function (Blueprint $table) {
            $table->dropColumn('order_point');
        });

        Schema::table('region_detail_mapbox_lists', function (Blueprint $table) {
            $table->dropColumn('order_point');
        });
    }
};
