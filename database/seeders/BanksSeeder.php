<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BanksSeeder extends Seeder
{
    public function run()
    {
        DB::table('banks')->insert([
            [
                'id' => 1,
                'name' => 'Default Bank',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
