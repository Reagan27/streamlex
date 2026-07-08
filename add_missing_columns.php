<?php
require __DIR__ . '/bootstrap/app.php';

use Illuminate\Support\Facades\DB;

echo "\n=== ADDING MISSING COLUMNS TO admin_contracts ===\n\n";

$dbname = env('DB_DATABASE');

// Check for project_id
$result = DB::select("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = 'admin_contracts' AND COLUMN_NAME = 'project_id' AND TABLE_SCHEMA = ?", [$dbname]);

if (empty($result)) {
    echo "Adding project_id column...\n";
    DB::statement('ALTER TABLE admin_contracts ADD COLUMN project_id BIGINT UNSIGNED NULL AFTER id');
    DB::statement('ALTER TABLE admin_contracts ADD FOREIGN KEY (project_id) REFERENCES projects(id) ON DELETE SET NULL');
    echo "✓ project_id column added\n";
} else {
    echo "✓ project_id column already exists\n";
}

// Check for end_date
$result = DB::select("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = 'admin_contracts' AND COLUMN_NAME = 'end_date' AND TABLE_SCHEMA = ?", [$dbname]);

if (empty($result)) {
    echo "Adding end_date column...\n";
    DB::statement('ALTER TABLE admin_contracts ADD COLUMN end_date DATE NULL AFTER start_date');
    echo "✓ end_date column added\n";
} else {
    echo "✓ end_date column already exists\n";
}

// Check for active_for_onboarding
$result = DB::select("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = 'admin_contracts' AND COLUMN_NAME = 'active_for_onboarding' AND TABLE_SCHEMA = ?", [$dbname]);

if (empty($result)) {
    echo "Adding active_for_onboarding column...\n";
    DB::statement('ALTER TABLE admin_contracts ADD COLUMN active_for_onboarding TINYINT(1) NOT NULL DEFAULT 0');
    echo "✓ active_for_onboarding column added\n";
} else {
    echo "✓ active_for_onboarding column already exists\n";
}

// Verify all columns now exist
echo "\n=== VERIFICATION ===\n";
$columns = DB::select("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = 'admin_contracts' AND TABLE_SCHEMA = ?", [$dbname]);
$columnNames = array_map(fn($c) => $c->COLUMN_NAME, $columns);

foreach (['project_id', 'end_date', 'active_for_onboarding'] as $col) {
    $status = in_array($col, $columnNames) ? "✓" : "✗";
    echo "$status $col\n";
}

echo "\n✓ Database schema update complete!\n";
