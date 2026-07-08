<?php
$app = require __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;

$pendingProblematicMigrations = [
    '2024_08_28_194836_create_admin_contract_county_table',
    '2024_09_03_091056_create_appraisals_table',
    '2024_09_09_143314_create_messages_table',
    '2024_09_13_095947_create_jobs_table',
    '2024_09_13_161606_create_failed_jobs_table',
    '2024_09_17_110828_create_groups_table',
    '2024_09_26_082525_create_support_issues_table',
    '2024_09_28_064842_create_comments_table',
    '2024_10_02_033339_create_payments_table',
    '2024_10_09_113713_create_bank_branches_table',
    '2024_10_09_174806_create_map_users_table',
    '2024_10_17_163137_create_settings_table',
];

echo "\nMarking migrations as completed in database...\n";

foreach ($pendingProblematicMigrations as $migration) {
    DB::table('migrations')->updateOrInsert(
        ['migration' => $migration],
        ['batch' => 1, 'migration' => $migration]
    );
    echo "✓ $migration\n";
}

echo "\nDone! These migrations are now marked as completed.\n";
echo "\nNow the system will only run truly pending migrations.\n";
