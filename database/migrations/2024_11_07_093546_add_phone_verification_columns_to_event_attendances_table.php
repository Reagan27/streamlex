<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPhoneVerificationColumnsToEventAttendances extends Migration
{
    public function up()
    {
        Schema::table('event_attendances', function (Blueprint $table) {
            if (!Schema::hasColumn('event_attendances', 'phone_verified')) {
                $table->boolean('phone_verified')->default(false)->after('phone_number');
            }
            if (!Schema::hasColumn('event_attendances', 'phone_verification_date')) {
                $table->timestamp('phone_verification_date')->nullable()->after('phone_verified');
            }
            if (!Schema::hasColumn('event_attendances', 'title')) {
                $table->string('title', 100)->nullable()->after('name');
            }
            if (!Schema::hasColumn('event_attendances', 'designation')) {
                $table->string('designation', 100)->nullable()->after('title');
            }
        });
    }

    public function down()
    {
        Schema::table('event_attendances', function (Blueprint $table) {
            $table->dropColumn(['phone_verified', 'phone_verification_date', 'title', 'designation']);
        });
    }
}