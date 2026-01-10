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
        Schema::create('mismatched_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('payment_cycle_id')->constrained()->onDelete('cascade');
            $table->string('id_number')->nullable();
            $table->string('imported_name')->nullable();
            $table->decimal('productivity', 8, 2)->default(0);
            $table->decimal('amount_payable', 15, 2)->default(0);
            $table->string('status')->default('Pending');
            $table->decimal('tax', 15, 2)->default(0);
            $table->text('resolution_notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mismatched_payments');
    }
};
