<?php
$app = require __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\Schema;

$table = 'admin_contracts';

echo "\n=== TABLE STRUCTURE: $table ===\n\n";

$columns = Schema::getColumnListing($table);
echo "Columns in $table:\n";
foreach ($columns as $col) {
    echo "  - $col\n";
}

echo "\n";

// Check specific columns we need
$requiredColumns = ['project_id', 'end_date', 'active_for_onboarding'];
echo "\nRequired columns check:\n";
foreach ($requiredColumns as $col) {
    $exists = Schema::hasColumn($table, $col);
    $status = $exists ? "✓ EXISTS" : "✗ MISSING";
    echo "  $col: $status\n";
}

echo "\n";     
