<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Carbon\Carbon;

class NormalizeFechaHora
{
    /**
     * Handle an incoming request.
     * Busca y ajusta campos de fecha/hora restando 6 horas.
     * Campos soportados: fecha_hora, timestamp, timestamp_tx, timestamp_tx_original
     * Reemplaza los valores en el request.
     */
    public function handle(Request $request, Closure $next)
    {
        // Añadimos keys comunes utilizadas en queries: start, end, since
        $keys = ['fecha_hora', 'timestamp', 'timestamp_tx', 'timestamp_tx_original', 'timestamp_tx_minus6', 'start', 'end', 'since'];

        $data = [];
        foreach ($keys as $k) {
            if ($request->has($k)) {
                $val = $request->input($k);
                if (!is_null($val) && $val !== '') {
                    try {
                        $dt = Carbon::parse($val);
                        $dt->subHours(6);
                        // Guardamos en el mismo campo el valor ajustado en formato Y-m-d H:i:s
                        $data[$k] = $dt->toDateTimeString();
                    } catch (\Exception $e) {
                        // intento de parseo fallido => dejamos valor tal cual
                        // (no queremos bloquear petición por formato inesperado)
                    }
                }
            }
        }

        if (!empty($data)) {
            // Mantenemos los valores originales en campos *_original si no existen
            foreach ($data as $k => $v) {
                $origKey = $k . '_original_from_request';
                if (!$request->has($origKey)) {
                    $origVal = $request->input($k);
                    $request->merge([$origKey => $origVal]);
                }
            }
            // Merge los valores ajustados
            $request->merge($data);
        }

        return $next($request);
    }
}
