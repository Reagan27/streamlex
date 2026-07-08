<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $permissions = [
            ['name' => 'compliance.view', 'display_name' => 'View Compliance', 'description' => 'View compliance documents'],
            ['name' => 'compliance.create', 'display_name' => 'Create Compliance', 'description' => 'Create compliance documents'],
            ['name' => 'compliance.edit', 'display_name' => 'Edit Compliance', 'description' => 'Edit compliance documents'],
            ['name' => 'compliance.delete', 'display_name' => 'Delete Compliance', 'description' => 'Delete compliance documents'],
            ['name' => 'compliance.renew', 'display_name' => 'Renew Compliance', 'description' => 'Renew compliance documents'],
        ];

        foreach ($permissions as $permission) {
            DB::table('permissions')->updateOrInsert(['name' => $permission['name']], $permission + ['removable' => true]);
        }

        $permissionIds = DB::table('permissions')->whereIn('name', array_column($permissions, 'name'))->pluck('id');
        $roles = DB::table('roles')->whereIn('name', ['Admin', 'Manager', 'Finance'])->get();

        foreach ($roles as $role) {
            foreach ($permissionIds as $permissionId) {
                DB::table('permission_role')->updateOrInsert([
                    'permission_id' => $permissionId,
                    'role_id' => $role->id,
                ], []);
            }
        }
    }

    public function down(): void
    {
        DB::table('permissions')->whereIn('name', [
            'compliance.view',
            'compliance.create',
            'compliance.edit',
            'compliance.delete',
            'compliance.renew',
        ])->delete();
    }
};
