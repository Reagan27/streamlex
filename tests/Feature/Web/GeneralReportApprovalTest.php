<?php

namespace Tests\Feature\Web;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Vanguard\County;
use Vanguard\GeneralReport;
use Vanguard\Permission;
use Vanguard\Role;
use Vanguard\User;
use Tests\TestCase;

class GeneralReportApprovalTest extends TestCase
{
    use RefreshDatabase;

    public function test_general_report_requires_approval_comments_before_approval(): void
    {
        $county = County::create(['name' => 'Test County']);

        $role = Role::create([
            'name' => 'Manager',
            'display_name' => 'Manager',
            'description' => 'Manager role',
        ]);

        $managePermission = Permission::create([
            'name' => 'general-reports.manage',
            'display_name' => 'Manage General Reports',
            'description' => 'Manage general reports',
        ]);

        $approvePermission = Permission::create([
            'name' => 'general-reports.approve',
            'display_name' => 'Approve General Reports',
            'description' => 'Approve general reports',
        ]);

        $role->attachPermissions([$managePermission, $approvePermission]);

        $user = User::factory()->create([
            'role_id' => $role->id,
            'status' => 'Active',
        ]);

        $report = GeneralReport::create([
            'title' => 'Quarterly Summary',
            'category' => 'monthly',
            'subcategory' => 'monthly',
            'county_id' => $county->id,
            'status' => 'submitted',
            'created_by' => $user->id,
            'content' => 'Test content',
        ]);

        $response = $this->actingAs($user)
            ->post(route('general-reports.approve', $report), [
                'approval_comments' => '',
            ]);

        $response->assertSessionHasErrors('approval_comments');
        $this->assertSame('submitted', $report->fresh()->status);
    }

    public function test_general_report_can_be_rejected_with_comments(): void
    {
        $county = County::create(['name' => 'Test County']);

        $role = Role::create([
            'name' => 'Manager',
            'display_name' => 'Manager',
            'description' => 'Manager role',
        ]);

        $managePermission = Permission::create([
            'name' => 'general-reports.manage',
            'display_name' => 'Manage General Reports',
            'description' => 'Manage general reports',
        ]);

        $approvePermission = Permission::create([
            'name' => 'general-reports.approve',
            'display_name' => 'Approve General Reports',
            'description' => 'Approve general reports',
        ]);

        $role->attachPermissions([$managePermission, $approvePermission]);

        $user = User::factory()->create([
            'role_id' => $role->id,
            'status' => 'Active',
        ]);

        $report = GeneralReport::create([
            'title' => 'Quarterly Summary',
            'category' => 'monthly',
            'subcategory' => 'monthly',
            'county_id' => $county->id,
            'status' => 'submitted',
            'created_by' => $user->id,
            'content' => 'Test content',
        ]);

        $response = $this->actingAs($user)
            ->post(route('general-reports.approve', $report), [
                'decision' => 'rejected',
                'approval_comments' => 'Needs revision',
            ]);

        $response->assertSessionHas('success', 'Report rejected successfully.');
        $this->assertSame('rejected', $report->fresh()->status);
        $this->assertSame('Needs revision', $report->fresh()->approval_comments);
    }
}
