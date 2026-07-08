<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('field_activities', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->unsignedBigInteger('team_id');
            $table->unsignedBigInteger('created_by');
            $table->string('status')->default('draft');
            $table->date('start_date');
            $table->date('end_date');
            $table->string('location');
            $table->integer('progress_percent')->default(0);
            $table->decimal('budget', 15, 2)->default(0);
            $table->decimal('disbursed', 15, 2)->default(0);
            $table->decimal('actual_spent', 15, 2)->default(0);
            $table->timestamps();
        });

        Schema::create('field_activity_expenses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('activity_id');
            $table->string('description');
            $table->string('category');
            $table->decimal('amount', 15, 2);
            $table->timestamps();
        });

        Schema::create('field_activity_actual_expenses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('activity_id');
            $table->string('description');
            $table->date('date');
            $table->decimal('amount', 15, 2);
            $table->timestamps();
        });

        Schema::create('field_activity_logistics', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('activity_id');
            $table->string('from_location');
            $table->string('to_location');
            $table->string('mode');
            $table->decimal('cost', 15, 2);
            $table->timestamps();
        });

        Schema::create('field_activity_transport_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('activity_id');
            $table->string('from_location');
            $table->string('to_location');
            $table->string('mode');
            $table->decimal('cost', 15, 2);
            $table->decimal('gps_departure_lat', 10, 6)->nullable();
            $table->decimal('gps_departure_lon', 10, 6)->nullable();
            $table->timestamp('gps_departure_time')->nullable();
            $table->decimal('gps_arrival_lat', 10, 6)->nullable();
            $table->decimal('gps_arrival_lon', 10, 6)->nullable();
            $table->timestamp('gps_arrival_time')->nullable();
            $table->string('gps_status')->default('pending');
            $table->timestamps();
        });

        Schema::create('field_activity_timeline', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('activity_id');
            $table->string('text');
            $table->string('icon');
            $table->string('color');
            $table->unsignedBigInteger('created_by');
            $table->timestamps();
        });

        Schema::create('field_activity_documents', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('activity_id');
            $table->string('file_name');
            $table->string('file_path');
            $table->integer('file_size');
            $table->string('file_type');
            $table->string('category');
            $table->unsignedBigInteger('uploaded_by');
            $table->timestamps();
        });

        Schema::create('field_activity_approvals', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('activity_id');
            $table->string('step');
            $table->string('status')->default('pending');
            $table->unsignedBigInteger('reviewer_id');
            $table->text('comment')->nullable();
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('field_activity_approvals');
        Schema::dropIfExists('field_activity_documents');
        Schema::dropIfExists('field_activity_timeline');
        Schema::dropIfExists('field_activity_transport_logs');
        Schema::dropIfExists('field_activity_logistics');
        Schema::dropIfExists('field_activity_actual_expenses');
        Schema::dropIfExists('field_activity_expenses');
        Schema::dropIfExists('field_activities');
    }
};
