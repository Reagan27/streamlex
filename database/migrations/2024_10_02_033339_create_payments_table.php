<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
          Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('user_id');
            $table->unsignedBigInteger('payment_cycle_id');
            $table->decimal('amount_payable', 10, 2);
            $table->decimal('tax', 10, 2);
            $table->decimal('productivity', 5, 2);
            $table->string('status');
            $table->string('invoice_number')->nullable();
            $table->string('invoice_file')->nullable();
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
            
            $table->foreign('payment_cycle_id')
                ->references('id')
                ->on('payment_cycles')
                ->onDelete('cascade');
          });
    }

    public function down()
    {
        Schema::dropIfExists('payments');
    }
};
