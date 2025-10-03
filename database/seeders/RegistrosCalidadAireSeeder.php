<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RegistrosCalidadAireSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('registros_calidad_aire')->insert([
            ['id'=>1,'co'=>20.5,'nox'=>10.2,'sox'=>5.1,'pm10'=>50.3,'pm25'=>25.7,'fecha_hora'=>'2025-09-23 08:00:00','created_at'=>'2025-09-23 06:13:38','updated_at'=>'2025-09-23 06:13:38'],
            ['id'=>2,'co'=>22.1,'nox'=>11,'sox'=>5.5,'pm10'=>45.2,'pm25'=>20.4,'fecha_hora'=>'2025-09-23 09:00:00','created_at'=>'2025-09-23 06:13:38','updated_at'=>'2025-09-23 06:13:38'],
            ['id'=>3,'co'=>18.7,'nox'=>9.5,'sox'=>4.8,'pm10'=>55.1,'pm25'=>30.2,'fecha_hora'=>'2025-09-23 10:00:00','created_at'=>'2025-09-23 06:13:38','updated_at'=>'2025-09-23 06:13:38'],
            ['id'=>4,'co'=>20.5,'nox'=>11,'sox'=>5.5,'pm10'=>45.2,'pm25'=>20.4,'fecha_hora'=>'2025-09-27 21:42:00','created_at'=>'2025-09-29 11:52:10','updated_at'=>'2025-09-29 11:52:10'],
            ['id'=>5,'co'=>11.5,'nox'=>11,'sox'=>1.5,'pm10'=>11.2,'pm25'=>11.4,'fecha_hora'=>'2025-09-29 21:49:00','created_at'=>'2025-09-29 12:04:28','updated_at'=>'2025-09-29 12:04:28'],
            ['id'=>6,'co'=>10.5,'nox'=>10,'sox'=>10.5,'pm10'=>10.2,'pm25'=>10.4,'fecha_hora'=>'2025-12-29 05:49:00','created_at'=>'2025-09-29 13:58:47','updated_at'=>'2025-09-29 13:58:47'],
            ['id'=>7,'co'=>20.5,'nox'=>20,'sox'=>20.5,'pm10'=>20.2,'pm25'=>20.4,'fecha_hora'=>'2025-12-30 09:49:00','created_at'=>'2025-10-01 06:55:33','updated_at'=>'2025-10-01 06:55:33'],
        ]);
    }
}
