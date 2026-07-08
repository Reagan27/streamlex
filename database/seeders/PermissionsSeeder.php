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

        // Field Activities permissions (only real actions)
        $permissions[] = Permission::firstOrCreate([
            'name' => 'field-activities.view',
        ], [
            'display_name' => 'View Field Activities',
            'description' => 'View field activities.',
            'removable' => false,
        ]);
        $permissions[] = Permission::firstOrCreate([
            'name' => 'field-activities.edit',
        ], [
            'display_name' => 'Edit Field Activities',
            'description' => 'Edit field activities.',
            'removable' => false,
        ]);
        $permissions[] = Permission::firstOrCreate([
            'name' => 'field-activities.delete',
        ], [
            'display_name' => 'Delete Field Activities',
            'description' => 'Delete field activities.',
            'removable' => false,
        ]);
        $permissions[] = Permission::firstOrCreate([
            'name' => 'field-activities.logsheet.create',
        ], [
            'display_name' => 'Create Field Activity Logsheets',
            'description' => 'Add field activity logsheets and tasks without creating requisitions.',
            'removable' => true,
        ]);

        // Coach Requisition permissions (standardized names)
        $coachReqPermissions = [
            [
                'name' => 'coach requisition view',
                'display_name' => 'View Coach Requisition',
                'description' => 'View coach requisitions.',
            ],
            [
                'name' => 'coach requisition approve',
                'display_name' => 'Approve Coach Requisition',
                'description' => 'Approve coach requisitions.',
            ],
            [
                'name' => 'coach requisition accept',
                'display_name' => 'Accept Coach Requisition',
                'description' => 'Accept coach requisitions.',
            ],
            [
                'name' => 'coach requisition reject',
                'display_name' => 'Reject Coach Requisition',
                'description' => 'Reject coach requisitions.',
            ],
        ];
        foreach ($coachReqPermissions as $perm) {
            $permissions[] = Permission::firstOrCreate([
                'name' => $perm['name'],
            ], [
                'display_name' => $perm['display_name'],
                'description' => $perm['description'],
                'removable' => false,
            ]);
        }

        // Meeting Management permissions
        $meetingPermissions = [
            [
                'name' => 'meetings.view',
                'display_name' => 'View Meetings',
                'description' => 'View meetings and meeting details.',
            ],
            [
                'name' => 'meetings.create',
                'display_name' => 'Create Meetings',
                'description' => 'Create new meetings.',
            ],
            [
                'name' => 'meetings.edit',
                'display_name' => 'Edit Meetings',
                'description' => 'Edit meetings, add participants, upload documents, manage action items.',
            ],
            [
                'name' => 'meetings.delete',
                'display_name' => 'Delete Meetings',
                'description' => 'Delete meetings.',
            ],
        ];
        foreach ($meetingPermissions as $perm) {
            $permissions[] = Permission::firstOrCreate([
                'name' => $perm['name'],
            ], [
                'display_name' => $perm['display_name'],
                'description' => $perm['description'],
                'removable' => false,
            ]);
        }

        // Attach all permissions to Admin
        $adminRole->attachPermissions($permissions);

        // Attach logsheet-only permission to Logsheet Officer role
        $logsheetOfficerRole = Role::where('name', 'Logsheet Officer')->first();
        if ($logsheetOfficerRole) {
            $logsheetPerm = Permission::where('name', 'field-activities.logsheet.create')->first();
            if ($logsheetPerm) {
                $logsheetOfficerRole->attachPermission($logsheetPerm);
            }
        }
    }
}
