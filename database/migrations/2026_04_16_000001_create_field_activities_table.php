<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('field_activities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('coach_requisition_id')->nullable()->constrained('coach_requisitions')->nullOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('team')->nullable();
            $table->string('location')->nullable();
            $table->date('start_date');
            $table->date('end_date');
            $table->enum('status', ['draft', 'submitted', 'supervisor_review', 'approved', 'funded', 'in_progress', 'reconciling', 'closed', 'rejected'])->default('draft');
            $table->integer('progress_pct')->default(0);
            $table->decimal('budget_programme', 12, 2)->nullable();
            $table->decimal('budget_transport', 12, 2)->nullable();
            $table->decimal('actual_spent', 12, 2)->default(0);
            $table->decimal('disbursed', 12, 2)->default(0);
            $table->unsignedInteger('created_by');
            $table->foreign('created_by')->references('id')->on('users');
            $table->timestamps();
        });
    }
    public function down()
    {
        Schema::dropIfExists('field_activities');
    }
};