<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;


class CalidadAireController extends Controller
{
    public function dashboard()
    {
        $tabla = 'registros_calidad_aire';

        return response()->json([
            'co' => [
                'promedio' => DB::table($tabla)->avg('co'),
                'max'     => DB::table($tabla)->max('co'),
                'min'     => DB::table($tabla)->min('co'),
            ],
            'nox' => [
                'promedio' => DB::table($tabla)->avg('nox'),
                'max'     => DB::table($tabla)->max('nox'),
                'min'     => DB::table($tabla)->min('nox'),
            ],
            'sox' => [
                'promedio' => DB::table($tabla)->avg('sox'),
                'max'     => DB::table($tabla)->max('sox'),
                'min'     => DB::table($tabla)->min('sox'),
            ],
            'pm10' => [
                'promedio' => DB::table($tabla)->avg('pm10'),
                'max'     => DB::table($tabla)->max('pm10'),
                'min'     => DB::table($tabla)->min('pm10'),
            ],
            'pm25' => [
                'promedio' => DB::table($tabla)->avg('pm25'),
                'max'     => DB::table($tabla)->max('pm25'),
                'min'     => DB::table($tabla)->min('pm25'),
            ],
        ]);
    }

    // Endpoints por contaminante (devuelven historial)
    public function co()  { return DB::table('registros_calidad_aire')->select('fecha_hora','co')->orderBy('fecha_hora')->get(); }
    public function nox() { return DB::table('registros_calidad_aire')->select('fecha_hora','nox')->orderBy('fecha_hora')->get(); }
    public function sox() { return DB::table('registros_calidad_aire')->select('fecha_hora','sox')->orderBy('fecha_hora')->get(); }
    public function pm10(){ return DB::table('registros_calidad_aire')->select('fecha_hora','pm10')->orderBy('fecha_hora')->get(); }
    public function pm25(){ return DB::table('registros_calidad_aire')->select('fecha_hora','pm25')->orderBy('fecha_hora')->get(); }

    // -----------------------------------------------------------------
    // NUEVO: Recibir datos desde ESP32 (POST /api/device/data)
    // -----------------------------------------------------------------
    public function storeDeviceData(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id'  => 'sometimes|string|max:100',
            'fecha_hora' => 'sometimes|date',
            'co'         => 'nullable|numeric',
            'nox'        => 'nullable|numeric',
            'sox'        => 'nullable|numeric',
            'pm10'       => 'nullable|numeric',
            'pm25'       => 'nullable|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $data = $validator->validated();

        // Si no mandan fecha_hora, usar now()
        if (empty($data['fecha_hora'])) {
            $data['fecha_hora'] = Carbon::now();
        }

        DB::table('registros_calidad_aire')->insert([
            'id'  => $data['id'] ?? 'esp32',
            'fecha_hora' => $data['fecha_hora'],
            'co'         => $data['co'] ?? null,
            'nox'        => $data['nox'] ?? null,
            'sox'        => $data['sox'] ?? null,
            'pm10'       => $data['pm10'] ?? null,
            'pm25'       => $data['pm25'] ?? null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json(['success' => true], 201);
    }

    // -----------------------------------------------------------------
    // NUEVO: Obtener CO entre fechas (GET /api/device/co?start=...&end=...)
    // -----------------------------------------------------------------
    public function coBetween(Request $request)
    {
        $start = $request->query('start');
        $end   = $request->query('end');

        if (!$start || !$end) {
            return response()->json(['message' => 'start y end son requeridos (YYYY-MM-DD HH:MM:SS)'], 400);
        }

        $datos = DB::table('registros_calidad_aire')
            ->whereBetween('fecha_hora', [$start, $end])
            ->select('fecha_hora', 'co')
            ->orderBy('fecha_hora', 'asc')
            ->get();

        return response()->json($datos);
    }

    // -----------------------------------------------------------------
    // NUEVO: Obtener últimos registros (GET /api/device/latest?limit=50)
    // -----------------------------------------------------------------
    public function latest(Request $request)
    {
        $limit = intval($request->query('limit', 5));
        $datos = DB::table('registros_calidad_aire')
            ->orderBy('fecha_hora', 'desc')
            ->limit($limit)
            ->get();

        return response()->json($datos);
    }

    // Todos los registros (para la gráfica)
    public function allRecords()
    {
        $datos = DB::table('registros_calidad_aire')
            ->orderBy('fecha_hora', 'asc') // de más antiguo a más reciente
            ->get();

        return response()->json($datos);
    }
    
    public function since(Request $request)
    {
        $since = $request->query('since');
        if (!$since) {
            return $this->allRecords();
        }

        $datos = DB::table('registros_calidad_aire')
            ->where('fecha_hora', '>', $since)
            ->orderBy('fecha_hora', 'asc')
            ->get();

        return response()->json($datos);
    }



    public function ByDate(Request $request)
{
    $date = $request->query('date'); // YYYY-MM-DD
    $start = $date . ' 00:00:00';
    $end   = $date . ' 23:59:59';

    $records = DB::table('registros_calidad_aire')
        ->whereBetween('fecha_hora', [$start, $end])
        ->orderBy('fecha_hora', 'asc')
        ->get();

    return response()->json($records);
}

      
    public function LatestByDate(Request $request) {
    $date = $request->query('date'); // YYYY-MM-DD
    $limit = $request->query('limit', 10);

    $start = $date . ' 00:00:00';
    $end = $date . ' 23:59:59';

    $records = DB::table('registros_calidad_aire') // ✅ tabla correcta
        ->whereBetween('fecha_hora', [$start, $end])
        ->orderBy('fecha_hora', 'desc')
        ->limit($limit)
        ->get();

    return response()->json($records);
}




}


/*
public function store (Request $request)
{
    // Obtener los datos enviados desde el ESP32
    $neighborId = $request-›input ('neighbor_id');
    $alarmId = $request-›input( 'alarm_id');

    // Crear el registro en la base de datos
    Activation::create([
        'neighbor_id' => $neighborId,
        'alarm_id' => $alarmId,
    ]);

    // Responder al ESP32 con una confirmación
    //return response() ->json(['message' => 'Registro creado correctamente' ]);
    
    return response() ->json([
    'message' => 'Registro creado correctamente',
    'reload' => true,
    ]);
}*/