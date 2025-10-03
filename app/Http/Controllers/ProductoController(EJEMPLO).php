<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProductoController extends Controller
{
    public function index()
    {
        return response()->json([
            ['id'=>1, 'nombre'=>'Producto A'],
            ['id'=>2, 'nombre'=>'Producto B']
        ]);
    }

    public function store(Request $request)
    {
        $nuevoProducto = $request->all();
        return response()->json($nuevoProducto, 201);
    }
}
