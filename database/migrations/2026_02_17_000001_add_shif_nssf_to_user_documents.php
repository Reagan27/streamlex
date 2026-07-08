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
        Schema::table('user_documents', function (Blueprint $table) {
            $table->string('shif_number')->nullable()->after('kra_certificate_path');
            $table->string('shif_document_path')->nullable()->after('shif_number');
            $table->string('nssf_number')->nullable()->after('shif_document_path');
            $table->string('nssf_document_path')->nullable()->after('nssf_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_documents', function (Blueprint $table) {
            $table->dropColumn(['shif_number', 'shif_document_path', 'nssf_number', 'nssf_document_path']);
        });
    }
};
