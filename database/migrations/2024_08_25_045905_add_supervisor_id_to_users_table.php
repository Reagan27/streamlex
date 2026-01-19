<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        $idColumnType = $this->getIdColumnType();

        Schema::table('users', function (Blueprint $table) use ($idColumnType) {
            if (!Schema::hasColumn('users', 'supervisor_id')) {
                $table->$idColumnType('supervisor_id')->nullable();
            } else {
                // If the column exists, modify it to match the id column type
                $table->$idColumnType('supervisor_id')->nullable()->change();
            }

            // Attempt to add the foreign key constraint
            if (!$this->hasForeignKey('users', 'users_supervisor_id_foreign')) {
                $table->foreign('supervisor_id')
                    ->references('id')
                    ->on('users')
                    ->onDelete('set null');
            }
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            if ($this->hasForeignKey('users', 'users_supervisor_id_foreign')) {
                $table->dropForeign(['supervisor_id']);
            }
            
            if (Schema::hasColumn('users', 'supervisor_id')) {
                $table->dropColumn('supervisor_id');
            }
        });
    }

    private function hasForeignKey($table, $foreignKey)
    {
        $schema = DB::connection()->getDatabaseName();

        $foreignKeys = DB::table('INFORMATION_SCHEMA.KEY_COLUMN_USAGE')
            ->where('REFERENCED_TABLE_SCHEMA', $schema)
            ->where('REFERENCED_TABLE_NAME', $table)
            ->where('CONSTRAINT_NAME', $foreignKey)
            ->get();

        return $foreignKeys->isNotEmpty();
    }

    private function getIdColumnType()
    {
        $idColumn = DB::table('INFORMATION_SCHEMA.COLUMNS')
            ->where('TABLE_SCHEMA', DB::connection()->getDatabaseName())
            ->where('TABLE_NAME', 'users')
            ->where('COLUMN_NAME', 'id')
            ->first();

        $type = strtolower($idColumn->DATA_TYPE);

        if ($type === 'bigint') {
            return 'unsignedBigInteger';
        } elseif ($type === 'int') {
            return 'unsignedInteger';
        }

        // Default to bigInteger if we can't determine the type
        return 'unsignedBigInteger';
    }
};