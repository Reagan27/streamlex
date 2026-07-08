<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Vanguard\ComplianceCategory;
use Vanguard\ComplianceDocument;

class ComplianceDocumentTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_calculates_status_based_on_expiry_date(): void
    {
        $category = ComplianceCategory::create(['name' => 'Insurance']);

        $active = ComplianceDocument::create([
            'name' => 'Public Liability Insurance',
            'category_id' => $category->id,
            'regulatory_authority' => 'Registrar',
            'department' => 'Operations',
            'responsible_officer' => 'Jane',
            'reference_number' => 'POL-001',
            'issue_date' => now()->subDays(50)->toDateString(),
            'expiry_date' => now()->addDays(20)->toDateString(),
            'renewal_frequency' => 'annual',
            'reminder_period' => 30,
            'status' => 'active',
            'description' => 'Test policy',
        ]);

        $expired = ComplianceDocument::create([
            'name' => 'Expired Permit',
            'category_id' => $category->id,
            'regulatory_authority' => 'Registrar',
            'department' => 'Operations',
            'responsible_officer' => 'Jane',
            'reference_number' => 'PER-001',
            'issue_date' => now()->subDays(120)->toDateString(),
            'expiry_date' => now()->subDays(2)->toDateString(),
            'renewal_frequency' => 'annual',
            'reminder_period' => 30,
            'status' => 'active',
            'description' => 'Expired test permit',
        ]);

        $this->assertEquals('active', $active->fresh()->status_for_display);
        $this->assertEquals('expired', $expired->fresh()->status_for_display);
    }
}
