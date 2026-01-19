<?php
// database/migrations/2024_10_28_create_excel_data_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateExcelDataTable extends Migration
{
    public function up()
    {
        Schema::table('excel_data', function (Blueprint $table) {
            $table->string('file_identifier')->after('id')->nullable();
            $table->string('file_type')->after('file_identifier')->nullable();
            $table->string('SubCountyName')->nullable();
            $table->string('LocationName')->nullable();
            $table->string('SubLocationName')->nullable();
            $table->integer('Rejected')->nullable();
            $table->integer('PendingIPRS')->nullable();
            $table->integer('IPRSFailed')->nullable();
            $table->integer('ValidationCheck')->nullable();
            $table->integer('Review')->nullable();
            $table->integer('Dwelling')->nullable();
            $table->integer('Demographics')->nullable();
        });
    }

    public function down()
    {
        Schema::table('excel_data', function (Blueprint $table) {
            $table->dropColumn('file_identifier');
            $table->dropColumn('file_type');
            // Drop the additional columns
            $table->dropColumn([
                'SubCountyName', 'LocationName', 'SubLocationName',
                'Rejected', 'PendingIPRS', 'IPRSFailed', 'ValidationCheck',
                'Review', 'Dwelling', 'Demographics'
            ]);
        });
    }
}