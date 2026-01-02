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
        Schema::create('forecasts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->year('year');
            $table->unsignedTinyInteger('week');

            $table->decimal('trend_component', 15, 4)->nullable();
            $table->decimal('seasonal_component', 15, 4)->nullable();
            $table->decimal('irregular_component', 15, 4)->nullable();
            $table->decimal('forecast_value', 15, 2);

            $table->timestamps();

            $table->unique(['product_id', 'year', 'week']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('forecasts');
    }
};
