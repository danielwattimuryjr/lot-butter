<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('master_production_schedules', function (Blueprint $table) {
            // Drop the foreign key constraint on forecast_id
            $table->dropForeign(['forecast_id']);

            // Add product_id column as nullable first
            $table->foreignId('product_id')->nullable()->after('id');

            // Add forecast_value column
            $table->integer('forecast_value')->nullable()->after('month');
        });

        // Populate product_id from forecast relationship for existing records
        DB::statement('
            UPDATE master_production_schedules mps
            JOIN forecasts f ON mps.forecast_id = f.id
            SET mps.product_id = f.product_id
            WHERE mps.forecast_id IS NOT NULL
        ');

        // Now make product_id non-nullable and add constraint
        Schema::table('master_production_schedules', function (Blueprint $table) {
            $table->foreignId('product_id')->nullable(false)->change();
            $table->foreign('product_id')->references('id')->on('products')->cascadeOnDelete();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('master_production_schedules', function (Blueprint $table) {
            // Drop product_id foreign key and column
            $table->dropForeign(['product_id']);
            $table->dropColumn('product_id');

            // Drop forecast_value column
            $table->dropColumn('forecast_value');

            // Restore forecast_id foreign key
            $table->foreign('forecast_id')->references('id')->on('forecasts')->nullOnDelete();
        });
    }
};
