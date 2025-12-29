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
        Schema::create('incomes', function (Blueprint $table) {
            $table->id();
            $table->string('code', 10)->unique();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->text('description');
            $table->integer('quantity');
            $table->decimal('unit_price', 15, 2);
            $table->decimal('amount', 15, 2);
            $table->date('date_received');
            $table->integer('week');
            $table->timestamps();

            $table->index('date_received');
            $table->index('week');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('incomes');
    }
};
