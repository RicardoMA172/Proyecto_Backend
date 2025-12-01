<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * IMPORTANT: Renaming columns in MySQL via the Schema builder requires
     * the `doctrine/dbal` package. Install it before running `php artisan migrate`:
     *   composer require doctrine/dbal
     *
     * This migration renames column `co` -> `co2` in `registros_calidad_aire`.
     */
    public function up(): void
    {
        Schema::table('registros_calidad_aire', function (Blueprint $table) {
            // renameColumn requires doctrine/dbal for MySQL
            if (Schema::hasColumn('registros_calidad_aire', 'co')) {
                $table->renameColumn('co', 'co2');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('registros_calidad_aire', function (Blueprint $table) {
            if (Schema::hasColumn('registros_calidad_aire', 'co2')) {
                $table->renameColumn('co2', 'co');
            }
        });
    }
};
