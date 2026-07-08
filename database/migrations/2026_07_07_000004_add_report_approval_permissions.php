<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $permissions = [
            [
                'name' => 'general-reports.approve',
                'display_name' => 'Approve General Reports',
                'description' => 'Approve submitted general reports',
            ],
            [
                'name' => 'back-to-office-reports.approve',
                'display_name' => 'Approve Back To Office Reports',
                'description' => 'Approve submitted back to office reports',
            ],
        ];

        foreach ($permissions as $permission) {
            DB::table('permissions')->updateOrInsert(
                ['name' => $permission['name']],
                $permission + ['removable' => true]
            );
        }

        $permissionIds = DB::table('permissions')
            ->whereIn('name', array_column($permissions, 'name'))
            ->pluck('id');

        $roles = DB::table('roles')->whereIn('name', ['Admin', 'Manager', 'Regional_Coordinator', 'County_Coordinator'])->get();

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
            'general-reports.approve',
            'back-to-office-reports.approve',
        ])->delete();
    }
};
