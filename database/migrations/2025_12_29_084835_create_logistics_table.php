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
        Schema::create('logistics', function (Blueprint $table) {
            $table->id();
            $table->string('code', 50)->unique();
            $table->foreignId('component_id')->constrained()->onDelete('cascade');
            $table->enum('transaction_type', ['in', 'out']);
            $table->decimal('quantity', 10, 2);
            $table->decimal('stock_total', 10, 2)->default(0);
            $table->date('date');
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index('component_id');
            $table->index('transaction_type');
            $table->index('date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('logistics');
    }
};
