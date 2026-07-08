<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('coach_requisitions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('requested_by');
            $table->string('position_title')->default('Part-Time Business Coach');
            $table->integer('number_of_coaches');
            $table->date('start_date');
            $table->date('end_date');
            $table->string('work_arrangement');
            $table->unsignedBigInteger('reporting_to')->nullable();
            $table->unsignedBigInteger('county_id');
            $table->text('justification')->nullable();
            $table->text('roles_responsibilities')->nullable();
            $table->string('budget_line')->nullable();
            $table->string('budget_code')->nullable();
            $table->decimal('monthly_cost', 12, 2)->nullable();
            $table->decimal('total_cost', 12, 2)->nullable();
            $table->string('status')->default('pending');
            $table->timestamps();
            $table->softDeletes();
        });
    }
    public function down() {
        Schema::dropIfExists('coach_requisitions');
    }
};
