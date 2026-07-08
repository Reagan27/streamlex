<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('field_activity_approvals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('field_activity_id')->constrained()->onDelete('cascade');
            $table->integer('approval_level');
            $table->string('role');
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('comments')->nullable();
            $table->string('approver_name')->nullable();
            $table->foreignId('approver_user_id')->nullable()->constrained('users');
            $table->dateTime('approved_at')->nullable();
            $table->timestamps();
        });
    }
    public function down()
    {
        Schema::dropIfExists('field_activity_approvals');
    }
};