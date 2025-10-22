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
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin',
                'password' => 'password', // será hasheado por el cast en el modelo
                'role' => 'admin',
            ]
        );
    }
}
