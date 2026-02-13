<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Peso;
use App\Models\PsEstado;
use App\Models\PsEstadoPeso;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SyncEstadoPesoController extends Controller
{
    public function store(Request $request)
    {
        if ($request->header('X-SYNC-KEY') !== config('services.sync.key')) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $request->validate([
            'peso_origen_id' => 'required',
            'estado_codigo'  => 'required|string',
            'origen'         => 'required|in:A,B',
        ]);

        DB::transaction(function () use ($request) {

            $peso = Peso::where('id_origen', $request->peso_origen_id)->first();

            if (!$peso) {
                Log::warning('Peso no encontrado en sync estado', $request->all());
                return;
            }

            $estado = PsEstado::where('codigo', $request->estado_codigo)->first();

            if (!$estado) {
                Log::warning('Estado no encontrado en sync estado', $request->all());
                return;
            }

            PsEstadoPeso::updateOrCreate(
                [
                    'peso_id'   => $peso->id,
                ],
                [
                    'estado_id' => $estado->id,
                    'origen'    => $request->origen,
                    'sync_at'   => now(),
                ]
            );
        });

        return response()->json(['ok' => true]);
    }
}
