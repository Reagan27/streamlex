<?php
require __DIR__ . '/bootstrap/app.php';

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

// Get database info
$dbname = env('DB_DATABASE');

echo "\n=== CHECKING admin_contracts TABLE ===\n";
echo "Database: $dbname\n";

// Get all columns
$columns = DB::select("SELECT COLUMN_NAME, DATA_TYPE FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = 'admin_contracts' AND TABLE_SCHEMA = ?", [$dbname]);

echo "\nCurrent columns:\n";
foreach ($columns as $col) {
    echo "  - {$col->COLUMN_NAME} ({$col->DATA_TYPE})\n";
}

// Check for required columns
$required = ['project_id', 'end_date', 'active_for_onboarding'];
echo "\nRequired columns status:\n";
foreach ($required as $req) {
    $exists = in_array($req, array_map(fn($c) => $c->COLUMN_NAME, $columns));
    $status = $exists ? "✓ YES" : "✗ NO";
    echo "  - $req: $status\n";
}

echo "\n";
