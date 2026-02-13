<?php

namespace App\Http\Controllers;

use App\Exports\PesosExport;
use App\Models\Peso;
use Illuminate\Http\Request;
use App\Models\RecepcionIngreso;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\User;

class PesoController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:ver pesos');

    }

    public function recepcionar(string $nro_salida)
    {
        $peso = Peso::where('NroSalida', $nro_salida)->firstOrFail();

        $prefill = [
            'nro_salida'      => $peso->NroSalida,
            'dni_conductor'   => $peso->DNIConductor ?? null,
            'datos_conductor' => $peso->Conductor ?? null,
        ];

        $pesoInfo = [
            'fechas'        => $peso->Fechas ?? null,
            'horas'         => $peso->Horas ?? null,
            'bruto'         => $peso->Bruto ?? null,
            'tara'          => $peso->Tara ?? null,
            'neto'          => $peso->Neto ?? null,
            'producto'      => $peso->Producto ?? null,
            'placa'         => $peso->Placa ?? null,
            'carreta'       => $peso->Carreta ?? null,
            'destino'       => $peso->destino ?? null,
            'origen'        => $peso->origen ?? null,
            'guia'          => $peso->guia ?? null,
            'guiat'         => $peso->guiat ?? null,
            'razon_social'  => $peso->RazonSocial ?? null,
            'conductor'     => $peso->Conductor ?? null,
        ];

        // ✅ REPRESENTANTES PARA EL SELECT
        $REP_IDS = [23, 24, 32, 34, 38];

        $representantes = User::query()
            ->whereIn('id', $REP_IDS)
            ->orderBy('name')
            ->get(['id', 'name']);

        return view('recepciones_ingreso.create', compact('prefill', 'pesoInfo', 'representantes'));
    }

    public function index(Request $request)
    {
        $pesos = Peso::query();

        if ($request->filled('fechai') && $request->filled('fechas')) {
            $pesos->whereBetween('Fechas', [
                Carbon::parse($request->fechai)->startOfDay(),
                Carbon::parse($request->fechas)->endOfDay(),
            ]);
        } elseif ($request->filled('fechai')) {
            $pesos->where('Fechas', '>=', Carbon::parse($request->fechai)->startOfDay());
        } elseif ($request->filled('fechas')) {
            $pesos->where('Fechas', '<=', Carbon::parse($request->fechas)->endOfDay());
        }


        if ($request->filled('ticket')) {
            $pesos->where('NroSalida', 'like', $request->ticket . '%');
        }

        if ($request->filled('razon')) {
            $pesos->where('RazonSocial', 'like',  $request->razon . '%');
        }

        if ($request->filled('producto')) {
            $pesos->where('Producto', 'like', '%' . $request->producto . '%');
        }

        if ($request->filled('destino')) {
            $pesos->where('destino', 'like',  $request->destino . '%');
        }

        if ($request->filled('origen')) {
            $pesos->where('origen', 'like', $request->origen . '%');
        }

        $pesos = $pesos->orderBy('NroSalida', 'desc')->simplePaginate(200);

        $nros = collect($pesos->items())
            ->pluck('NroSalida')
            ->filter()   // quita null/vacíos
            ->values()
            ->all();

        $recepcionesPorSalida = $nros
            ? RecepcionIngreso::whereIn('nro_salida', $nros)->pluck('id', 'nro_salida')->toArray()
            : [];

        if ($request->ajax()) {
            return view('pesos.partials.tabla', compact('pesos', 'recepcionesPorSalida'))->render();
        }

        return view('pesos.index', compact('pesos', 'recepcionesPorSalida'));
    }




    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $peso = Peso::where('nrosalida', $id)->firstOrFail();

        return view('pesos.show', compact('peso'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function exportExcel(Request $request)
    {
        return Excel::download(
            new PesosExport($request),
            'pesos_' . now()->format('Ymd_His') . '.xlsx'
        );
    }
}
