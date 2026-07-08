<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Vanguard\Role;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Role::create([
            'name' => 'Admin',
            'display_name' => 'Admin',
            'description' => 'System administrator.',
            'removable' => false,
        ]);

        Role::create([
            'name' => 'User',
            'display_name' => 'User',
            'description' => 'Default system user.',
            'removable' => false,
        ]);

        Role::create([
            'name' => 'Logsheet Officer',
            'display_name' => 'Logsheet Officer',
            'description' => 'Can add and manage field activity logsheets only.',
            'removable' => true,
        ]);
    }
}
