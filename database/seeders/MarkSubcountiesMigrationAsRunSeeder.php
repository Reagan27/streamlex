<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MarkSubcountiesMigrationAsRunSeeder extends Seeder
{
    public function run()
    {
        if (!DB::table('migrations')->where('migration', '2024_08_07_211224_create_subcounties_table')->exists()) {
            DB::table('migrations')->insert([
                'migration' => '2024_08_07_211224_create_subcounties_table',
                'batch' => (DB::table('migrations')->max('batch') ?? 0) + 1,
            ]);
        }
    }
}