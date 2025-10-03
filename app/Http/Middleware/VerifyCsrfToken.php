<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * Rutas que no usarán CSRF
     */
    protected $except = [
        '/api/guardar-dato', // tu ruta POST sin CSRF
    ];
}
