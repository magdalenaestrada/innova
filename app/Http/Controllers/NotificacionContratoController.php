<?php

namespace App\Http\Controllers;

use App\Models\Contrato;
use App\Models\LqCliente;
use Illuminate\Http\JsonResponse;

class NotificacionContratoController extends Controller
{
    public function index(): JsonResponse
    {
        $clientesActivos = LqCliente::where('estado', '!=', 'I')->pluck('id');

        $porVencer = Contrato::whereNotNull('fecha_fin_contrato')
            ->whereIn('cliente_id', $clientesActivos)
            ->whereBetween('fecha_fin_contrato', [now(), now()->addMonth()])
            ->count();

        $vencidos = Contrato::whereNotNull('fecha_fin_contrato')
            ->whereIn('cliente_id', $clientesActivos)
            ->where('fecha_fin_contrato', '<', now())
            ->count();

        return response()->json([
            'por_vencer' => $porVencer,
            'vencidos' => $vencidos,
            'total' => $porVencer + $vencidos,
        ]);
    }
}
