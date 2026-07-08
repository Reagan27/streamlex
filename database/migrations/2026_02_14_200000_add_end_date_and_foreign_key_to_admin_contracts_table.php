<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('admin_contracts', function (Blueprint $table) {
            if (!Schema::hasColumn('admin_contracts', 'end_date')) {
                $table->date('end_date')->nullable()->after('start_date');
            }
            
            // Add foreign key constraint for project_id if it doesn't exist
            if (!$this->hasForeignKey('admin_contracts', 'project_id')) {
                $table->foreign('project_id')
                    ->references('id')
                    ->on('projects')
                    ->onDelete('set null');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('admin_contracts', function (Blueprint $table) {
            if ($this->hasForeignKey('admin_contracts', 'project_id')) {
                $table->dropForeign(['project_id']);
            }
            if (Schema::hasColumn('admin_contracts', 'end_date')) {
                $table->dropColumn('end_date');
            }
        });
    }
    
    private function hasForeignKey($table, $column)
    {
        try {
            $keyName = 'admin_contracts_' . $column . '_foreign';
            $indexes = \DB::select("SELECT CONSTRAINT_NAME FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE WHERE TABLE_NAME = ? AND COLUMN_NAME = ? AND CONSTRAINT_NAME LIKE 'fk_%'", [$table, $column]);
            return count($indexes) > 0;
        } catch (\Exception $e) {
            return false;
        }
    }
};
