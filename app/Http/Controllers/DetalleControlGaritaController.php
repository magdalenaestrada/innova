<?php

namespace App\Http\Controllers;

use App\Exports\DetalleControlGaritaExport;
use App\Models\ControlGarita;
use App\Models\DetalleControlGarita;
use App\Models\Producto;
use App\Models\Etiqueta;
use App\Models\Lote;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;

class DetalleControlGaritaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:create cg-register', ['only' => ['store']]);
        $this->middleware('permission:edit cg-register', ['only' => ['update']]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // $ultimoTurno = ControlGarita::where('usuario_id', Auth::id())
        //     ->orderBy('id', 'desc')
        //     ->with(['cargos'])
        //     ->first();

        // $turnoActivo = null;
        // if ($ultimoTurno && $ultimoTurno->estado === 'activo')
        // {
        //     $turnoActivo = $ultimoTurno;
        // }
        // $controlGarita = ControlGarita::orderBy('id', 'desc')->get();
        $user = Auth::user();
        $turnoActivo = ControlGarita::where('estado', 'activo')->where(function ($query) use ($user) {
            $query->where('usuario_id', $user->id)
                  ->orWhereHas('agentes', function ($q) use ($user) {
                      $q->where('usuarios_id', $user->id);
                  });
            })
            ->with(['cargos'])
            ->latest('id')
            ->first();
        $detalles = $turnoActivo ? $turnoActivo->detalles()->latest('id')->limit(100)->get() : collect();
        $etiquetas = Etiqueta::latest('id')->get();
        $productos = Producto::select('id', 'nombre_producto')->get();
        $lotes = Lote::select('id', 'nombre')->get();
        $users = User::select('id', 'name')
            ->whereHas('empleado.area', function ($q) {
                $q->where('nombre', 'like', 'garita%');
            })->get();
        
        return view('controlgarita.controlregistro.index', compact(
            'detalles',
            'productos',
            'lotes',
            'users',
            'turnoActivo',
            // 'ultimoTurno',
            'etiquetas'
        ));
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
        try {
            $request->validate([
                'tipo_movimiento' => 'required|in:E,S',
                'tipo_entidad' => 'required|in:P,V',
                'nombre' => 'required|string|max:255',
                'documento' => 'required|string|max:11',
                'tipo_documento' => 'required|in:1,2',
                'ocurrencias' => 'nullable|string',
                'hora' => 'required|date_format:H:i',
                'destino' => 'nullable|string',
                'placa' => 'nullable|string',
            ]);
            $user = Auth::id();

            $turnoActivo = ControlGarita::where('usuario_id', $user)
                ->where('estado', 'activo')
                ->latest('id')
                ->first();
                
            // if ($turnoActivo) {
            //     $this->authorize('create', $turnoActivo);
            // } else {
            //     return redirect()->back()->with('error', 'No tienes un turno activo para registrar el detalle.');
            // }

            if (!$turnoActivo) {
                return redirect()->back()->with('error', 'No tienes un turno activo para registrar el detalle.');
            }

            DetalleControlGarita::create([
                'tipo_movimiento' => $request->tipo_movimiento,
                'tipo_entidad' => $request->tipo_entidad,
                'nombre' => $request->nombre,
                'documento' => $request->documento,
                'tipo_documento' => $request->tipo_documento,
                'ocurrencias' => $request->ocurrencias,
                'hora' => $request->hora,
                'destino' => $request->destino,
                'placa' => $request->placa,
                'tipo_mineral_id' => $request->tipo_mineral,
                'tipo_vehiculo_id' => $request->tipo_vehiculo,
                'etiqueta_id' => $request->etiqueta_id,
                'control_garita_id' => $turnoActivo->id,
                'usuario_id' => $user,
            ]);

            // $ruta = $request->tipo_movimiento === 'E' ? 'controlgarita.index' : 'controlgarita.salidas.index';
            // $mensaje = $request->tipo_movimiento === 'E' ? 'Entrada registrada exitosamente.' : 'Salida registrada exitosamente.';

            // return redirect()->back()->with('success', $mensaje);
            return redirect()->back()->with('success', 'Entrada registrada exitosamente');
        } catch (\Throwable $th) {
            // return redirect()->back()->with('error', 'Error al registrar el detalle de control de garita: ' . $th->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al registrar: ' . $th->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(DetalleControlGarita $detalleControlGarita)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(DetalleControlGarita $detalleControlGarita)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $detalle = DetalleControlGarita::findOrFail($id);

        try {
            $user = Auth::id();

            $turnoActivo = ControlGarita::where('usuario_id', $user)
                ->where('estado', 'activo')
                ->latest('id')
                ->first();

            if (!$turnoActivo) {
                return redirect()->back()->with('error', 'No tienes un turno activo para editar el detalle.');
            }

            $detalle->update([
                'tipo_movimiento' => $request->tipo_movimiento,
                'tipo_entidad' => $request->tipo_entidad,
                'nombre' => $request->nombre,
                'documento' => $request->documento,
                'tipo_documento' => $request->tipo_documento,
                'ocurrencias' => $request->ocurrencias,
                'hora' => $request->hora,
                'destino' => $request->destino,
                'placa' => $request->placa,
                'tipo_mineral_id' => $request->tipo_mineral,
                'tipo_vehiculo_id' => $request->tipo_vehiculo,
                'etiqueta_id' => $request->etiqueta_id,
                'control_garita_id' => $turnoActivo->id,
                'usuario_id' => $user,
            ]);

            return redirect()->back()->with('success', 'Entrada editada exitosamente');
        } catch (\Throwable $th) {
            // return redirect()->back()->with('error', 'Error al editar el detalle de control de garita: ' . $th->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al editar registro: ' . $th->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DetalleControlGarita $detalleControlGarita)
    {
        //
    }

    public function exportExcelCustom(Request $request)
    {
        $filtros = [
            'tipo_movimiento' => $request->tipo_movimiento,
            'tipo_entidad'    => $request->tipo_entidad,
            'fecha_inicio'    => $request->fecha_inicio,
            'fecha_fin'       => $request->fecha_fin,
            'hora_inicio'     => $request->hora_inicio,
            'hora_fin'        => $request->hora_fin,
            'usuario_id'      => $request->usuario_id,
        ];

        $nombreArchivo = 'reporte_garita_' . now()->format('d_m_Y_His') . '.xlsx';

        return Excel::download(new DetalleControlGaritaExport($filtros), $nombreArchivo);
    }

    public function searchCodigo(Request $request)
    {
        $detalleSearch = DetalleControlGarita::where('nombre', 'like', '%' . $request->search_string . '%')
            ->orWhere('documento', 'like', '%' . $request->search_string . '%')
            ->orderBy('created_at', 'desc')->get();
        return view('controlgarita.controlregistro.index', compact('detalleSearch'));
    }
}
