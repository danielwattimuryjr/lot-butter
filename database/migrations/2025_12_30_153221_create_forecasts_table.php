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
            $table->unsignedSmallInteger('week'); // Continuous week number (1, 2, 3, ...)
            $table->unsignedTinyInteger('month'); // Month (1-12, based on 4 weeks per month)
            $table->year('year'); // Year (calculated from week)
            $table->decimal('intercept', 15, 4)->nullable(); // Konstanta dari linear regression
            $table->decimal('slope', 15, 4)->nullable(); // Kemiringan dari linear regression
            $table->decimal('trend', 15, 4)->nullable(); // Trend value untuk week ini
            $table->decimal('seasonal_index', 15, 4)->nullable(); // Seasonal index (1.0 atau 1.5)
            $table->decimal('forecast_value', 15, 2); // Hasil forecast
            $table->timestamps();

            $table->unique(['product_id', 'week']);
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
