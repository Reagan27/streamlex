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
        Schema::table('training_events', function (Blueprint $table) {
            $table->decimal('venue_latitude', 10, 8)->nullable()->after('venue_name');
            $table->decimal('venue_longitude', 11, 8)->nullable()->after('venue_latitude');
            $table->integer('location_radius')->nullable()->after('venue_longitude'); // radius in meters
            $table->boolean('enforce_location')->default(false)->after('location_radius');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('training_events', function (Blueprint $table) {
            $table->dropColumn([
                'venue_latitude',
                'venue_longitude',
                'location_radius',
                'enforce_location'
            ]);
        });
    }
};