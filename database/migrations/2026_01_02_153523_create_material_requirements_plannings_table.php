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
            $table->enum('level', ['0', '1', '2']);
            $table->foreignId('product_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('product_variant_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('component_id')->nullable()->constrained()->onDelete('cascade');
            $table->year('year');
            $table->unsignedTinyInteger('week');
            $table->integer('scheduled_receipts')->default(0);
            $table->integer('projected_on_hand')->default(0);
            $table->integer('planned_order_receipts')->default(0);
            $table->integer('planned_order_releases')->default(0);
            $table->boolean('is_edited')->default(false);
            $table->timestamps();

            // Unique constraints per level/entity/year/week
            // Level 0: product_variant_id must be set
            $table->unique(['level', 'product_variant_id', 'year', 'week'], 'mrp_level0_unique');

            // Level 1 has two types:
            // - Product-level: product_id + component_id
            $table->unique(['level', 'product_id', 'component_id', 'year', 'week'], 'mrp_level1_product_unique');
            // - Variant-level: product_variant_id + component_id
            $table->unique(['level', 'product_variant_id', 'component_id', 'year', 'week'], 'mrp_level1_variant_unique');

            // Level 2: product_id + component_id
            $table->unique(['level', 'product_id', 'component_id', 'year', 'week'], 'mrp_level2_unique');
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
