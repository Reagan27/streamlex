<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('requisition_approvals', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('coach_requisition_id');
            $table->string('approval_level');
            $table->string('role');
            $table->string('status')->default('pending');
            $table->text('comments')->nullable();
            $table->string('approver_name')->nullable();
            $table->unsignedBigInteger('approver_user_id')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();
            $table->foreign('coach_requisition_id')->references('id')->on('coach_requisitions')->onDelete('cascade');
        });
    }
    public function down() {
        Schema::dropIfExists('requisition_approvals');
    }
};
