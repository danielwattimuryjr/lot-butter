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
            $table->foreignId('product_variant_id')->constrained()->cascadeOnDelete();
            $table->year('year');
            $table->unsignedTinyInteger('month');
            $table->unsignedTinyInteger('week')->default(0);
            $table->integer('beginning_inventory')->default(0);
            $table->integer('projected_on_hand')->nullable();
            $table->integer('available')->nullable();
            $table->boolean('is_edited')->default(false);
            $table->timestamps();

            $table->unique(['product_variant_id', 'year', 'week']);
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
