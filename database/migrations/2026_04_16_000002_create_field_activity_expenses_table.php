<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('field_activity_expenses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('field_activity_id')->constrained()->onDelete('cascade');
            $table->string('description');
            $table->string('category');
            $table->decimal('amount', 12, 2);
            $table->decimal('actual_amount', 12, 2)->nullable();
            $table->date('expense_date')->nullable();
            $table->timestamps();
        });
    }
    public function down()
    {
        Schema::dropIfExists('field_activity_expenses');
    }
};