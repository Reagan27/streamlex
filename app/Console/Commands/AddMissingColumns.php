<?php
namespace Vanguard\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class AddMissingColumns extends Command
{
    protected $signature = 'db:add-missing-columns';
    protected $description = 'Add missing columns to admin_contracts table';

    public function handle()
    {
        $this->info('Adding missing columns to admin_contracts table...');

        $dbname = env('DB_DATABASE');

        // Check for project_id
        $result = DB::select("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = 'admin_contracts' AND COLUMN_NAME = 'project_id' AND TABLE_SCHEMA = ?", [$dbname]);

        if (empty($result)) {
            $this->line('Adding project_id column...');
            DB::statement('ALTER TABLE admin_contracts ADD COLUMN project_id BIGINT UNSIGNED NULL AFTER id');
            DB::statement('ALTER TABLE admin_contracts ADD FOREIGN KEY (project_id) REFERENCES projects(id) ON DELETE SET NULL');
            $this->info('✓ project_id column added');
        } else {
            $this->info('✓ project_id column already exists');
        }

        // Check for end_date
        $result = DB::select("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = 'admin_contracts' AND COLUMN_NAME = 'end_date' AND TABLE_SCHEMA = ?", [$dbname]);

        if (empty($result)) {
            $this->line('Adding end_date column...');
            DB::statement('ALTER TABLE admin_contracts ADD COLUMN end_date DATE NULL AFTER start_date');
            $this->info('✓ end_date column added');
        } else {
            $this->info('✓ end_date column already exists');
        }

        // Check for active_for_onboarding
        $result = DB::select("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = 'admin_contracts' AND COLUMN_NAME = 'active_for_onboarding' AND TABLE_SCHEMA = ?", [$dbname]);

        if (empty($result)) {
            $this->line('Adding active_for_onboarding column...');
            DB::statement('ALTER TABLE admin_contracts ADD COLUMN active_for_onboarding TINYINT(1) NOT NULL DEFAULT 0');
            $this->info('✓ active_for_onboarding column added');
        } else {
            $this->info('✓ active_for_onboarding column already exists');
        }

        $this->info('\n✓ Database schema update complete!');
    }
}
