<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('back_to_office_reports', function (Blueprint $table) {
            $table->text('approval_comments')->nullable()->after('approved_at');
        });
    }

    public function down(): void
    {
        Schema::table('back_to_office_reports', function (Blueprint $table) {
            $table->dropColumn('approval_comments');
        });
    }
};
