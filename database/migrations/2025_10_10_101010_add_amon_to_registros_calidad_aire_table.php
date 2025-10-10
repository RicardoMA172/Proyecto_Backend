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
        Schema::table('registros_calidad_aire', function (Blueprint $table) {
            // Añadir columna para amon (amoníaco)
            $table->float('amon')->nullable()->after('hum');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('registros_calidad_aire', function (Blueprint $table) {
            $table->dropColumn('amon');
        });
    }
};
