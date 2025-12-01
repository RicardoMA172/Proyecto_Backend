<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

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
        try {
            Log::info('NormalizeFechaHora middleware start', ['path' => $request->path(), 'method' => $request->method(), 'payload_keys' => array_keys($request->all())]);
        } catch (\Exception $e) {}
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
                            try {
                                Log::info('NormalizeFechaHora adjusted', ['key' => $k, 'original' => $val, 'adjusted' => $data[$k]]);
                            } catch (\Exception $e) {}
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
            try { Log::info('NormalizeFechaHora merging adjusted keys', $data); } catch (\Exception $e) {}
            $request->merge($data);
        }
        try { Log::info('NormalizeFechaHora middleware end', ['path' => $request->path()]); } catch (\Exception $e) {}
        return $next($request);
    }
}
