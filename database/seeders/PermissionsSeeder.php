<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Vanguard\Permission;
use Vanguard\Role;

class PermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $adminRole = Role::where('name', 'Admin')->first();

        $permissions[] = Permission::create([
            'name' => 'users.manage',
            'display_name' => 'Manage Users',
            'description' => 'Manage users and their sessions.',
            'removable' => false,
        ]);

        $permissions[] = Permission::create([
            'name' => 'users.create',
            'display_name' => 'Create Users',
            'description' => 'Create new users in the system.',
            'removable' => false,
        ]);

        $permissions[] = Permission::create([
            'name' => 'users.activity',
            'display_name' => 'View System Activity Log',
            'description' => 'View activity log for all system users.',
            'removable' => false,
        ]);

        $permissions[] = Permission::create([
            'name' => 'roles.manage',
            'display_name' => 'Manage Roles',
            'description' => 'Manage system roles.',
            'removable' => false,
        ]);

        $permissions[] = Permission::create([
            'name' => 'permissions.manage',
            'display_name' => 'Manage Permissions',
            'description' => 'Manage role permissions.',
            'removable' => false,
        ]);

        $permissions[] = Permission::create([
            'name' => 'settings.general',
            'display_name' => 'Update General System Settings',
            'description' => '',
            'removable' => false,
        ]);

        $permissions[] = Permission::create([
            'name' => 'settings.auth',
            'display_name' => 'Update Authentication Settings',
            'description' => 'Update authentication and registration system settings.',
            'removable' => false,
        ]);

        $permissions[] = Permission::create([
            'name' => 'settings.notifications',
            'display_name' => 'Update Notifications Settings',
            'description' => '',
            'removable' => false,
        ]);

        // Assets permissions
        $permissions[] = Permission::create([
            'name' => 'assets.view',
            'display_name' => 'Manage Assets',
            'description' => 'View the list of assets.',
            'removable' => false,
        ]);

        $permissions[] = Permission::create([
            'name' => 'assets.assign',
            'display_name' => 'Assign Assets',
            'description' => 'Assign assets to users.',
            'removable' => false,
        ]);

        $permissions[] = Permission::create([
            'name' => 'assets.bulk_assign',
            'display_name' => 'Bulk Assign Assets',
            'description' => 'Assign assets to users in bulk.',
            'removable' => false,
        ]);

        $permissions[] = Permission::create([
            'name' => 'assets.my',
            'display_name' => 'My Assets',
            'description' => 'View the list of assets assigned to the logged-in user.',
            'removable' => false,
        ]);        

        $permissions[] = Permission::create([
            'name' => 'assets.create',
            'display_name' => 'Create Assets',
            'description' => 'Create new assets.',
            'removable' => false,
        ]);

        $permissions[] = Permission::create([
            'name' => 'assets.edit',
            'display_name' => 'Edit Assets',
            'description' => 'Edit existing assets.',
            'removable' => false,
        ]);

        $permissions[] = Permission::create([
            'name' => 'assets.delete',
            'display_name' => 'Delete Assets',
            'description' => 'Delete assets from the system.',
            'removable' => false,
        ]);

        $permissions[] = Permission::create([
            'name' => 'messages.manage',
            'display_name' => 'Manage Messages',
            'description' => 'Send bulk, single, and select SMS messages, and manage message templates.',
            'removable' => false,
        ]);

        $permissions[] = Permission::create([
            'name' => 'groups.manage',
            'display_name' => 'Manage Groups',
            'description' => 'Manage user groups in the system.',
            'removable' => false,
        ]);

        $permissions[] = Permission::create([
            'name' => 'emails.send',
            'display_name' => 'Send Emails',
            'description' => 'Send email messages to users.',
            'removable' => false,
        ]);

        $permissions[] = Permission::create([
            'name' => 'emails.manage',
            'display_name' => 'Manage Emails',
            'description' => 'Manage email-related settings and templates.',
            'removable' => false,
        ]);

        $permissions[] = Permission::create([
            'name' => 'support.view',
            'display_name' => 'View Support',
            'description' => 'View own support-related issues.',
            'removable' => false,
        ]);

        $permissions[] = Permission::create([
            'name' => 'support.manage',
            'display_name' => 'Manage Support',
            'description' => 'Manage all support-related settings, issues, and categories.',
            'removable' => false,
        ]);  

        $adminRole->attachPermissions($permissions);
    }
}
