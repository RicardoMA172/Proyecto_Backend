<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('registros_calidad_aire', function (Blueprint $table) {
            $table->float('temp')->nullable()->after('pm25'); // temperatura
            $table->float('hum')->nullable()->after('temp');   // humedad
        });
    }

    public function down(): void
    {
        Schema::table('registros_calidad_aire', function (Blueprint $table) {
            $table->dropColumn(['temp', 'hum']);
        });
    }
};
