<?php
// Properly load Composer and Laravel
require __DIR__ . '/vendor/autoload.php';

$app = require __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

echo "\n=== ADDING MISSING COLUMNS TO admin_contracts ===\n\n";

$dbname = env('DB_DATABASE');

// Columns to add
$columnsToAdd = [
    'project_id' => [
        'check' => "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = 'admin_contracts' AND COLUMN_NAME = 'project_id' AND TABLE_SCHEMA = ?",
        'add' => [
            "ALTER TABLE admin_contracts ADD COLUMN project_id BIGINT UNSIGNED NULL AFTER id",
        ],
        'msg' => 'project_id'
    ],
    'end_date' => [
        'check' => "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = 'admin_contracts' AND COLUMN_NAME = 'end_date' AND TABLE_SCHEMA = ?",
        'add' => [
            "ALTER TABLE admin_contracts ADD COLUMN end_date DATE NULL AFTER start_date",
        ],
        'msg' => 'end_date'
    ],
    'active_for_onboarding' => [
        'check' => "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = 'admin_contracts' AND COLUMN_NAME = 'active_for_onboarding' AND TABLE_SCHEMA = ?",
        'add' => [
            "ALTER TABLE admin_contracts ADD COLUMN active_for_onboarding TINYINT(1) NOT NULL DEFAULT 0",
        ],  'msg' => 'active_for_onboarding'
    ],
];

foreach ($columnsToAdd as $colName => $config) {
    $result = DB::select($config['check'], [$dbname]);
    
    if (empty($result)) {
        echo "Adding {$config['msg']} column...\n";
        foreach ($config['add'] as $sql) {
            DB::statement($sql);
        }
        echo "✓ {$config['msg']} column added\n";
    } else {
        echo "✓ {$config['msg']} column already exists\n";
    }
}

// Add foreign key for project_id if it doesn't exist
echo "\nChecking foreign keys...\n";
$fkResult = DB::select("SELECT CONSTRAINT_NAME FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE WHERE TABLE_NAME = 'admin_contracts' AND COLUMN_NAME = 'project_id' AND TABLE_SCHEMA = ? AND CONSTRAINT_NAME != 'PRIMARY'", [$dbname]);

if (empty($fkResult)) {
    echo "Adding foreign key constraint for project_id...\n";
    try {
        DB::statement('ALTER TABLE admin_contracts ADD FOREIGN KEY (project_id) REFERENCES projects(id) ON DELETE SET NULL');
        echo "✓ Foreign key added\n";
    } catch (\Exception $e) {
        echo "⚠ Foreign key might already exist or error: " . $e->getMessage() . "\n";
    }
} else {
    echo "✓ Foreign key already exists\n";
}

echo "\n✓ Database schema update complete!\n\n";
