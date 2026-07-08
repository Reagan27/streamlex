<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('requisition_proposed_coaches', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('coach_requisition_id');
            $table->string('full_name');
            $table->string('phone_number');
            $table->string('email_address');
            $table->string('sub_county_assigned')->nullable();
            $table->text('justification')->nullable();
            $table->text('roles_responsibilities')->nullable();
            $table->timestamps();
            $table->foreign('coach_requisition_id')->references('id')->on('coach_requisitions')->onDelete('cascade');
        });
    }
    public function down() {
        Schema::dropIfExists('requisition_proposed_coaches');
    }
};
