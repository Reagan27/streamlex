<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('admin_contracts', function (Blueprint $table) {
            if (!Schema::hasColumn('admin_contracts', 'role_id')) {
                $table->unsignedBigInteger('role_id')->nullable()->after('status');
            }
            
            // Add foreign key if the roles table exists and the foreign key doesn't already exist
            if (Schema::hasTable('roles') && !$this->hasForeignKey('admin_contracts', 'role_id')) {
                $table->foreign('role_id')->references('id')->on('roles')->onDelete('set null');
            }
        });
    }

    public function down()
    {
        Schema::table('admin_contracts', function (Blueprint $table) {
            if ($this->hasForeignKey('admin_contracts', 'role_id')) {
                $table->dropForeign(['role_id']);
            }
            if (Schema::hasColumn('admin_contracts', 'role_id')) {
                $table->dropColumn('role_id');
            }
        });
    }

    private function hasForeignKey($table, $column)
    {
        $conn = Schema::getConnection()->getDoctrineSchemaManager();
        $foreignKeys = $conn->listTableForeignKeys($table);
        foreach ($foreignKeys as $foreignKey) {
            if ($foreignKey->getLocalColumns() == [$column]) {
                return true;
            }
        }
        return false;
    }
};