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
        Schema::create('material_requirements_plannings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('component_id')->constrained()->onDelete('cascade');
            $table->year('year');
            $table->unsignedTinyInteger('week');
            $table->unsignedTinyInteger('month');
            $table->integer('gross_requirements')->nullable()->default(null);
            $table->integer('schedule_receipts')->nullable()->default(null);
            $table->integer('projected_on_hand')->default(0);
            $table->integer('net_requirements')->nullable()->default(null);
            $table->integer('planned_order_receipts')->nullable()->default(null);
            $table->integer('planned_order_releases')->nullable()->default(null);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('material_requirements_plannings');
    }
};
