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
        Schema::create('master_production_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('forecast_id')->nullable()->constrained()->nullOnDelete();
            $table->year('year');
            $table->unsignedTinyInteger('week');
            $table->unsignedTinyInteger('month');
            $table->integer('mps_value')->nullable()->default(null);
            $table->integer('available')->nullable()->default(null);
            $table->integer('projected_on_hand')->default(0);
            $table->boolean('is_edited')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('master_production_schedules');
    }
};
