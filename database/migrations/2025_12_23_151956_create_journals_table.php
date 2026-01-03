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
        Schema::create('journals', function (Blueprint $table) {
            $table->id();
            $table->string('code', 10)->unique();
            $table->date('date');
            $table->string('description');
            $table->decimal('debit', 15, 2)->nullable();
            $table->decimal('credit', 15, 2)->nullable();
            $table->decimal('balance', 15, 2)->default(0);
            $table->enum('transaction_type', [
                'income',
                'purchase',
                'adjustment',
            ]);
            $table->string('reference_table')->nullable();
            $table->unsignedBigInteger('reference_id')->nullable();
            $table->foreignId('product_id')
                ->nullable()
                ->constrained('products')
                ->onDelete('set null');
            $table->timestamps();

            $table->index(['reference_table', 'reference_id']);
            $table->index('transaction_type');
            $table->index('date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('journals');
    }
};
