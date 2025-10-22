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
            'temp' => [
                'promedio' => DB::table($tabla)->avg('temp'),
                'max'     => DB::table($tabla)->max('temp'),
                'min'     => DB::table($tabla)->min('temp'),
            ],
            'hum' => [
                'promedio' => DB::table($tabla)->avg('hum'),
                'max'     => DB::table($tabla)->max('hum'),
                'min'     => DB::table($tabla)->min('hum'),
            ],
        ]);
    }

    // Endpoints por contaminante (devuelven historial)
    public function co()  { return DB::table('registros_calidad_aire')->select('fecha_hora','co')->orderBy('fecha_hora')->get(); }
    public function nox() { return DB::table('registros_calidad_aire')->select('fecha_hora','nox')->orderBy('fecha_hora')->get(); }
    public function sox() { return DB::table('registros_calidad_aire')->select('fecha_hora','sox')->orderBy('fecha_hora')->get(); }
    public function pm10(){ return DB::table('registros_calidad_aire')->select('fecha_hora','pm10')->orderBy('fecha_hora')->get(); }
    public function pm25(){ return DB::table('registros_calidad_aire')->select('fecha_hora','pm25')->orderBy('fecha_hora')->get(); }
    public function temp(){ return DB::table('registros_calidad_aire')->select('fecha_hora','temp')->orderBy('fecha_hora')->get(); }
    public function hum(){ return DB::table('registros_calidad_aire')->select('fecha_hora','hum')->orderBy('fecha_hora')->get(); }


    // -----------------------------------------------------------------
    // Recibir datos desde ESP32 (POST /api/device/data)
    // -----------------------------------------------------------------
    public function storeDeviceData(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'fecha_hora' => 'sometimes|date',
            'temp'       => 'nullable|numeric',
            'hum'        => 'nullable|numeric',
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
            $data['fecha_hora'] = Carbon::now()->toDateTimeString();
        }

        // Insertar sin especificar `id` (autoincrement)
        $insertId = DB::table('registros_calidad_aire')->insertGetId([
            'fecha_hora' => $data['fecha_hora'],
            'temp'       => $data['temp'] ?? null,
            'hum'        => $data['hum'] ?? null,
            'co'         => $data['co'] ?? null,
            'nox'        => $data['nox'] ?? null,
            'sox'        => $data['sox'] ?? null,
            'pm10'       => $data['pm10'] ?? null,
            'pm25'       => $data['pm25'] ?? null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json(['success' => true, 'id' => $insertId], 201);
    }

    // -----------------------------------------------------------------
    // Otros métodos existentes...
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


    // Obtener los últimos N registros (por defecto 5)
    public function latest(Request $request)
    {
        $limit = intval($request->query('limit', 5));
        $datos = DB::table('registros_calidad_aire')
            ->orderBy('fecha_hora', 'desc')
            ->limit($limit)
            ->get();

        return response()->json($datos);
    }


    // Obtener todos los registros
    public function allRecords()
    {
        $datos = DB::table('registros_calidad_aire')
            ->orderBy('fecha_hora', 'asc')
            ->get();

        return response()->json($datos);
    }
    

    // Obtener registros desde una fecha/hora específica
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


    // Obtener registros de un día específico
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


    // Obtener los últimos N registros de un día específico
    public function LatestByDate(Request $request) {
        $date = $request->query('date'); // YYYY-MM-DD
        $limit = $request->query('limit', 10);

        $start = $date . ' 00:00:00';
        $end = $date . ' 23:59:59';

        $records = DB::table('registros_calidad_aire')
            ->whereBetween('fecha_hora', [$start, $end])
            ->orderBy('fecha_hora', 'desc')
            ->limit($limit)
            ->get();

        return response()->json($records);
    }

    // Obtener promedio de los datos del día actual
    public function todayAverage(Request $request)
    {
        // Usamos la zona horaria configurada en la app (config/app.php)
        $start = Carbon::today()->startOfDay()->toDateTimeString();
        $end = Carbon::today()->endOfDay()->toDateTimeString();

        $row = DB::table('registros_calidad_aire')
            ->whereBetween('fecha_hora', [$start, $end])
            ->selectRaw(
                'avg(co) as co, avg(nox) as nox, avg(sox) as sox, avg(pm10) as pm10, avg(pm25) as pm25, avg(temp) as temp, avg(hum) as hum'
            )
            ->first();

        // Formatear valores (null si no hay registros, o float con 2 decimales)
        $format = function ($v) {
            return is_null($v) ? null : round((float) $v, 2);
        };

        $result = [
            'co'   => $format($row->co ?? null),
            'nox'  => $format($row->nox ?? null),
            'sox'  => $format($row->sox ?? null),
            'pm10' => $format($row->pm10 ?? null),
            'pm25' => $format($row->pm25 ?? null),
            'temp' => $format($row->temp ?? null),
            'hum'  => $format($row->hum ?? null),
            'start' => $start,
            'end' => $end,
        ];

        return response()->json($result);
    }
    


    // Exportar datos a CSV (soporta ?date=YYYY-MM-DD para exportar un día)
    public function exportCsv(Request $request){
        $date = $request->query('date');
        if ($date) {
            $start = $date . ' 00:00:00';
            $end = $date . ' 23:59:59';
            $records = DB::table('registros_calidad_aire')
                ->whereBetween('fecha_hora', [$start, $end])
                ->orderBy('fecha_hora')
                ->get();
        } else {
            $records = DB::table('registros_calidad_aire')->orderBy('fecha_hora')->get();
        }

        $filename = 'registros_calidad_aire_' . ($date ?? date('Ymd')) . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            // Forzar descarga con nombre
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            // Asegurar CORS para el frontend (también es recomendable configurar FRONTEND_URL en env de producción)
            'Access-Control-Allow-Origin' => env('FRONTEND_URL', '*'),
            'Access-Control-Allow-Credentials' => 'true',
        ];

        return response()->streamDownload(function() use ($records) {
            $handle = fopen('php://output', 'w');
            // Cabecera del CSV
            fputcsv($handle, ['ID','Fecha_Hora','CO','NOX','SOX','PM10','PM25','Temp','Hum','Created_At','Updated_At']);
            foreach ($records as $row) {
                fputcsv($handle, [
                    $row->id,
                    $row->fecha_hora,
                    $row->co,
                    $row->nox,
                    $row->sox,
                    $row->pm10,
                    $row->pm25,
                    $row->temp,
                    $row->hum,
                    $row->created_at,
                    $row->updated_at,
                ]);
            }
            fclose($handle);
        }, $filename, $headers);
    }


}
