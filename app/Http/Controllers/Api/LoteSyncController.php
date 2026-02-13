<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Lote;
use App\Models\LqCliente;

class LoteSyncController extends Controller
{
    public function sync(Request $request)
    {
        if ($request->header('X-SYNC-KEY') !== config('services.sync.key')) {
            abort(401);
        }

        $data = $request->validate([
            'codigo'       => 'required|string',
            'nombre'       => 'required|string',
            'activo'       => 'required|boolean',
            'cliente_ruc'  => 'required|string',
            'usuario_id'   => 'required|integer',
            'origen'       => 'required|in:A,B',
        ]);

        $cliente = LqCliente::where('documento', $data['cliente_ruc'])->first();

        if (!$cliente) {
            return response()->json([
                'ok'  => false,
                'msg' => 'Cliente no existe en servidor C'
            ], 422);
        }

        $loteExistente = Lote::where('codigo', $data['codigo'])->first();

        if ($loteExistente && $loteExistente->origen === 'C') {
            return response()->json([
                'ok'  => true,
                'msg' => 'Lote creado manualmente en C, no se sobrescribe'
            ]);
        }

        $lote = Lote::updateOrCreate(
            [
                'codigo' => $data['codigo'],
                'origen' => $data['origen'],
            ],
            [
                'nombre'        => strtoupper($data['nombre']),
                'activo'        => $data['activo'],
                'lq_cliente_id' => $cliente->id,
                'usuario_id'    => $data['usuario_id'],
                'sync_at'       => now(),
            ]
        );

        return response()->json([
            'ok' => true,
            'id' => $lote->id,
        ]);
    }
}
