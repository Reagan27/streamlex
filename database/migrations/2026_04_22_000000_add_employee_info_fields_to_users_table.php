<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('nok_full_name')->nullable();
            $table->string('nok_relationship')->nullable();
            $table->string('nok_mobile')->nullable();
            $table->string('nok_alt_phone')->nullable();
            $table->string('nok_email')->nullable();
            $table->string('nok_address')->nullable();
            $table->string('marital_status')->nullable();
            $table->string('spouse_name')->nullable();
            $table->string('spouse_contact')->nullable();
            $table->integer('dependents')->nullable();
            $table->string('employee_number')->nullable();
            $table->string('department')->nullable();
            $table->string('job_title')->nullable();
            $table->string('employment_type')->nullable();
            $table->date('employment_date')->nullable();
            $table->string('work_station')->nullable();
            $table->string('supervisor_name')->nullable();
            $table->string('supervisor_title')->nullable();
            $table->string('blood_group')->nullable();
            $table->string('medical_conditions')->nullable();
            $table->string('allergies')->nullable();
            $table->string('medical_facility')->nullable();
            $table->string('disability')->nullable();
            $table->string('disability_details')->nullable();
            $table->string('workplace_adjustments')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'nok_full_name',
                'nok_relationship',
                'nok_mobile',
                'nok_alt_phone',
                'nok_email',
                'nok_address',
                'marital_status',
                'spouse_name',
                'spouse_contact',
                'dependents',
                'employee_number',
                'department',
                'job_title',
                'employment_type',
                'employment_date',
                'work_station',
                'supervisor_name',
                'supervisor_title',
                'blood_group',
                'medical_conditions',
                'allergies',
                'medical_facility',
                'disability',
                'disability_details',
                'workplace_adjustments',
            ]);
        });
    }
};
