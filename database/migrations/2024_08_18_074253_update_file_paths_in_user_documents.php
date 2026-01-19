<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        DB::table('user_documents')->update([
            'id_photo_path' => DB::raw("REPLACE(id_photo_path, 'public/', '')"),
            'kra_certificate_path' => DB::raw("REPLACE(kra_certificate_path, 'public/', '')"),
        ]);
    }

    public function down()
    {
        // If needed, you can write a reverse operation here
    }
};
