<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        // Cambia email y password por valores seguros en tu entorno.
        User::updateOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'Admin',
                'password' => '123456', // será hasheado por el cast en el modelo
                'role' => 'admin',
            ]
        );
    }
}
