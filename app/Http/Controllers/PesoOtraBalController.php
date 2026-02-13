<?php

namespace App\Http\Controllers;

use App\Exports\OtrosPesosExport;
use App\Http\Controllers\Controller;
use App\Models\Lote;
use App\Models\PesoOtraBal;
use App\Models\PlProgramacion;
use App\Models\Proceso;
use App\Models\Estado;
use App\Models\LqCliente;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Facades\Excel;

class PesoOtraBalController extends Controller
{

    public function __construct()
    {
        $this->middleware('permission:ver otros pesos')
            ->only(['index', 'show', 'pesos', 'pesosOtrasLote']);

        $this->middleware('permission:crear otros pesos')
            ->only(['guardar', 'store']);

        $this->middleware('permission:editar otros pesos')
            ->only(['update']);

        $this->middleware('permission:eliminar otros pesos')
            ->only(['destroy']);

        $this->middleware('permission:guardar molienda otros pesos')
            ->only(['guardar_molienda']);

        $this->middleware('permission:gestionar otros pesos')
            ->except(['index', 'show', 'pesos', 'pesosOtrasLote']);
    }
    public function index()
    {
        $estados = Estado::all();
        $clientes = LqCliente::all();
        return view('otros-pesos.index', compact('estados', 'clientes'));
    }

    public function pesos(Request $request)
    {
        $query = PesoOtraBal::query();

        if ($request->filled('id')) {
            $query->where('id', 'like', $request->id . '%');
        }
        if ($request->filled('producto')) {
            $query->where('producto', 'like', '%' . $request->producto . '%');
        }
        if ($request->filled('placa')) {
            $query->where('placa', 'like', '%' . $request->placa . '%');
        }
        if ($request->filled('conductor')) {
            $query->where('conductor', 'like', '%' . $request->conductor . '%');
        }
        if ($request->filled('razon')) {
            $query->whereHas('cliente', function ($q) use ($request) {
                $q->where('nombre', 'like', '%' . $request->razon . '%');
            });
        }
        if ($request->filled('origen')) {
            $query->where('origen', 'like', '%' . $request->origen . '%');
        }
        if ($request->filled('destino')) {
            $query->where('destino', 'like', '%' . $request->destino . '%');
        }
        if ($request->filled('neto')) {
            $query->where('neto', $request->neto);
        }
        if ($request->filled('observacion')) {
            $query->where('observacion', 'like', '%' . $request->observacion . '%');
        }
        if ($request->filled('desde')) {
            $query->whereDate('fechai', '>=', $request->desde);
        }
        if ($request->filled('hasta')) {
            $query->whereDate('fechas', '<=', $request->hasta);
        }

        if ($request->filled('estado_id')) {
            $query->where('estado_id', $request->estado_id);
        }

        $sumaNetos = $query->sum('Neto');

        $pesos = $query->orderBy('id', 'desc')->paginate(100);

        $estados = Estado::all();

        if ($request->ajax()) {
            return view('otros-pesos.partials.tabla', compact('pesos', 'estados', 'sumaNetos'))->render();
        }

        return view('otros-pesos.index', compact('pesos', 'estados', 'sumaNetos'));
    }

    public function guardar(Request $request)
    {
        try {

            $request->validate([
                'cliente_id' => 'required|exists:lq_clientes,id',
                'fechai'    => 'required',
                'fechas'    => 'required',
                'producto'  => 'required',
                'conductor' => 'required',
                'placa'     => 'required',
                'origen'    => 'required',
                'destino'   => 'required',
                'balanza'   => 'required',
                'bruto'     => 'required|numeric',
                'tara'      => 'required|numeric',
                'neto'      => 'required|numeric',
                'guia'      => 'required',
                'guiat'     => 'required',
            ]);

            PesoOtraBal::create([
                'fechai' => str_replace('T', ' ', $request->fechai),
                'fechas' => str_replace('T', ' ', $request->fechas),
                'bruto' => $request->bruto,
                'tara' => $request->tara,
                'neto' => $request->neto,
                'placa' => $request->placa,
                'observacion' => Str::upper($request->observacion),
                'producto' => Str::upper($request->producto),
                'conductor' => Str::upper($request->conductor),
                'guia' => Str::upper($request->guia),
                'guiat' => Str::upper($request->guiat),
                'origen' => Str::upper($request->origen),
                'destino' => Str::upper($request->destino),
                'balanza' => Str::upper($request->balanza),
                'cliente_id' => $request->cliente_id,
                'estado_id' => 1,
                'usuario_id' => auth()->id(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Peso registrado correctamente'
            ]);
        } catch (ValidationException $e) {

            return response()->json([
                'success' => false,
                'message' => 'Complete todos los campos obligatorios',
                'errors'  => $e->errors()
            ], 422);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'line'    => $e->getLine(),
                'file'    => $e->getFile(),
            ], 500);
        }
    }
    public function show($id)
    {
        return response()->json(
            PesoOtraBal::select(
                'id',
                'cliente_id',
                'fechai',
                'fechas',
                'producto',
                'conductor',
                'placa',
                'origen',
                'destino',
                'balanza',
                'bruto',
                'tara',
                'neto',
                'guia',
                'guiat',
                'observacion'
            )->with('cliente')->findOrFail($id)
        );
    }
    public function update(Request $request, $id)
    {
        $peso = PesoOtraBal::findOrFail($id);

        if ($peso->estado_id != 1) {
            return response()->json([
                'success' => false,
                'message' => 'Solo se puede editar un peso en estado CANCHA'
            ], 403);
        }

        $request->validate([
            'neto' => 'required|numeric',
        ]);

        $peso->update([
            'cliente_id'     => $request->cliente_id,
            'fechai'      => $request->fechai ? str_replace('T', ' ', $request->fechai) : null,
            'fechas'      => $request->fechas ? str_replace('T', ' ', $request->fechas) : null,
            'bruto'       => $request->bruto ?? 0,
            'tara'        => $request->tara ?? 0,
            'neto'        => $request->neto,
            'placa'       => $request->placa,
            'observacion' => $request->observacion,
            'producto'    => $request->producto,
            'conductor'   => $request->conductor,
            'guia'        => $request->guia,
            'guiat'       => $request->guiat,
            'origen'      => $request->origen,
            'destino'     => $request->destino,
            'balanza'     => $request->balanza,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Peso actualizado correctamente'
        ]);
    }


    public function destroy($id)
    {
        $pesoOtraBal = PesoOtraBal::findOrFail($id);
        $pesoOtraBal->delete();

        return response()->json([
            'success' => true,
            'message' => 'Registro eliminado correctamente'
        ]);
    }

    public function pesosOtrasLote($cliente_id)
    {
        return PesoOtraBal::with('estado')
            ->where('cliente_id', $cliente_id)
            ->whereNull('programacion_id')
            ->where('estado_id', 1)
            ->get()
            ->map(function ($otra_balanza) {
                return [
                    'id' => $otra_balanza->id,
                    'fechai' => $otra_balanza->fechai,
                    'fechas' => $otra_balanza->fechas,
                    'bruto' => $otra_balanza->bruto,
                    'tara' => $otra_balanza->tara,
                    'neto' => $otra_balanza->neto,
                    'conductor' => $otra_balanza->conductor,
                    'estado_nombre' => $otra_balanza->estado->nombre ?? null,
                    'estado_id' => $otra_balanza->estado->id ?? null,
                    'balanza' => $otra_balanza->balanza,
                    'producto' => $otra_balanza->producto,
                    'placa' => $otra_balanza->placa,
                ];
            });
    }

    public function export(Request $request)
    {
        $query = PesoOtraBal::query()->with(['estado', 'cliente']);

        if ($request->filled('desde')) {
            $query->whereDate('Fechai', '>=', $request->desde);
        }

        if ($request->filled('hasta')) {
            $query->whereDate('Fechas', '<=', $request->hasta);
        }

        if ($request->filled('RazonSocial')) {
            $query->whereHas('cliente', function ($q) use ($request) {
                $q->where('RazonSocial', 'like', "%{$request->RazonSocial}%");
            });
        }

        if ($request->filled('NroSalida')) {
            $query->where('NroSalida', 'like', "%{$request->NroSalida}%");
        }

        if ($request->filled('origen')) {
            $query->where('origen', 'like', "%{$request->origen}%");
        }

        if ($request->filled('destino')) {
            $query->where('destino', 'like', "%{$request->destino}%");
        }

        if ($request->filled('estado_id')) {

            if ($request->estado_id === 'sin_estado') {

                $query->whereNull('estado_id');
            } else {

                $query->where('estado_id', $request->estado_id);
            }
        }

        if ($request->filled('Producto')) {
            $query->where('Producto', 'like', "%{$request->Producto}%");
        }

        if ($request->filled('Conductor')) {
            $query->where('Conductor', 'like', "%{$request->Conductor}%");
        }

        if ($request->filled('Placa')) {
            $query->where('Placa', 'like', "%{$request->Placa}%");
        }

        if ($request->filled('Observacion')) {
            $query->where('Observacion', 'like', "%{$request->Observacion}%");
        }

        $pesos = $query->get();

        return Excel::download(new OtrosPesosExport($pesos), 'pesos_' . date('Y-m-d_His') . '.xlsx');
    }
}
