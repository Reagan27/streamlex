<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Vanguard\AdminContract;

class AdminContractSeeder extends Seeder
{
    public function run()
    {
        AdminContract::create([
            'start_date' => now(),
            'number_of_days' => 30,
            'title' => 'Standard Employment Contract',
            'description' => 'This is a standard employment contract...',
            'status' => AdminContract::STATUS_PUBLISHED,
            'authority_signature' => null
        ]);
    }
}