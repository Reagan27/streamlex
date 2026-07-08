<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('requisition_proposed_coaches', function (Blueprint $table) {
            $table->text('responsibilities')->nullable()->after('sub_county_assigned');
            $table->json('reasons')->nullable()->after('responsibilities');
        });
    }

    public function down()
    {
        Schema::table('requisition_proposed_coaches', function (Blueprint $table) {
            $table->dropColumn(['responsibilities', 'reasons']);
        });
    }
};
