<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('emails')) {
            Schema::table('emails', function (Blueprint $table) {
                // Create a fulltext index on subject and message for MySQL / MariaDB
                // Laravel's Blueprint exposes fullText() in recent versions.
                try {
                    $table->fullText(['subject', 'message'], 'emails_subject_message_fulltext');
                } catch (\Exception $e) {
                    // Ignore on DBs that don't support fulltext via this method; user can add manually
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasTable('emails')) {
            Schema::table('emails', function (Blueprint $table) {
                try {
                    $table->dropFullText('emails_subject_message_fulltext');
                } catch (\Exception $e) {
                    // ignore if not present or unsupported
                }
            });
        }
    }
};
