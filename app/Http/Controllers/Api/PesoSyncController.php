<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Peso;
use Illuminate\Http\Request;

class PesoSyncController extends Controller
{
    public function sync(Request $request)
    {
        if ($request->header('X-SYNC-KEY') !== config('services.sync.key')) {
            abort(401);
        }

        $data = $request->validate([
            'origen' => 'required|in:A,B',
            'nro_salida_origen' => 'required|numeric',
        ]);

        $peso = Peso::updateOrCreate(
            [
                'origen' => $data['origen'],
                'nro_salida_origen' => $data['nro_salida_origen'],
            ],
            array_merge($request->except(['id']), [
                'sync_at' => now(),
            ])
        );

        return response()->json([
            'ok' => true,
            'id' => $peso->id,
        ]);
    }
}
