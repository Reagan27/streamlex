<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAssignmentLogsTable extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('assignment_logs')) {
            Schema::create('assignment_logs', function (Blueprint $table) {
                $table->id();
                $table->unsignedInteger('assigned_by');
                $table->unsignedInteger('assigned_to');
                $table->unsignedBigInteger('asset_id');
                $table->integer('quantity_assigned');
                $table->timestamps();

                // Foreign key constraints
                $table->foreign('assigned_by')->references('id')->on('users')->onDelete('cascade');
                $table->foreign('assigned_to')->references('id')->on('users')->onDelete('cascade');
                $table->foreign('asset_id')->references('id')->on('assets')->onDelete('cascade');
            });
        } else {
            Schema::table('assignment_logs', function (Blueprint $table) {
                if (!Schema::hasColumn('assignment_logs', 'assigned_by')) {
                    $table->unsignedInteger('assigned_by');
                    $table->foreign('assigned_by')->references('id')->on('users')->onDelete('cascade');
                }
                if (!Schema::hasColumn('assignment_logs', 'assigned_to')) {
                    $table->unsignedInteger('assigned_to');
                    $table->foreign('assigned_to')->references('id')->on('users')->onDelete('cascade');
                }
                if (!Schema::hasColumn('assignment_logs', 'asset_id')) {
                    $table->unsignedBigInteger('asset_id');
                    $table->foreign('asset_id')->references('id')->on('assets')->onDelete('cascade');
                }
                if (!Schema::hasColumn('assignment_logs', 'quantity_assigned')) {
                    $table->integer('quantity_assigned');
                }
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('assignment_logs');
    }
}
