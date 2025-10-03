<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('registros_calidad_aire', function (Blueprint $table) {
            $table->id(); // id AUTO_INCREMENT
            $table->float('co')->nullable();
            $table->float('nox')->nullable();
            $table->float('sox')->nullable();
            $table->float('pm10')->nullable();
            $table->float('pm25')->nullable();
            $table->dateTime('fecha_hora')->nullable();
            $table->timestamps(); // created_at y updated_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('registros_calidad_aire');
    }
};

