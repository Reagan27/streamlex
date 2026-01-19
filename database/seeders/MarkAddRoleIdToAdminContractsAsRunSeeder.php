<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MarkAddRoleIdToAdminContractsAsRunSeeder extends Seeder
{
    public function run()
    {
        if (!DB::table('migrations')->where('migration', '2024_08_09_222644_add_role_id_to_admin_contracts_table')->exists()) {
            DB::table('migrations')->insert([
                'migration' => '2024_08_09_222644_add_role_id_to_admin_contracts_table',
                'batch' => (DB::table('migrations')->max('batch') ?? 0) + 1,
            ]);
        }
    }
}