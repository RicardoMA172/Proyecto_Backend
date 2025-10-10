<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RegistroCalidadAire extends Model
{
    use HasFactory;

    protected $table = 'registros_calidad_aire';

    protected $fillable = [
        'id',
        'co',
        'nox',
        'sox',
        'pm10',
        'pm25',
        'fecha_hora',
        'payload'
    ];

    protected $casts = [
        'fecha_hora' => 'datetime',
        'payload' => 'array'
    ];
}
