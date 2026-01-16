<?php

namespace App\Http\Controllers;

use App\Models\ControlGarita;
use App\Models\DetalleControlGarita;
use App\Models\Producto;
use App\Models\Etiqueta;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DetalleControlGaritaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        // $this->middleware('permission:view registro', ['only' => ['indexE', 'indexS']]);
        // $this->middleware('permission:edit registro', ['only' => ['update']]);
    }

    /**
     * Display a listing of the resource.
     */
    public function indexE()
    {
        $ultimoTurno = ControlGarita::where('usuario_id', Auth::id())
            ->orderBy('id', 'desc')
            ->with(['cargos'])
            ->first();

        $turnoActivo = null;
        if ($ultimoTurno && $ultimoTurno->estado === 'activo')
        {
            $turnoActivo = $ultimoTurno;
        }
        $detalles = DetalleControlGarita::whereHas('controlGarita', function ($query) {
                $query->where('usuario_id', Auth::id());
            })
            ->latest('id')
            ->get();
        $etiquetas = Etiqueta::latest('id')->get();
        $controlGarita = ControlGarita::orderBy('id', 'desc')->get();
        $productos = Producto::select('id', 'nombre_producto')->get();
        $users = User::select('id', 'name')
            ->whereHas('empleado.area', function ($q) {
                $q->where('nombre', 'like', 'garita%');
            })->get();
        
        return view('controlgarita.entrada.index', compact(
            'detalles',
            'controlGarita',
            'productos',
            'users',
            'turnoActivo',
            'ultimoTurno',
            'etiquetas'
        ));
    }

    public function indexS()
    {
        $detalles = DetalleControlGarita::orderBy('id', 'desc')
            ->where('tipo_movimiento', 'S')
            ->get();
        return view('controlgarita.salida.index', compact('detalles'));
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
        // dd($request->all());
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

        try {
            $user = Auth::id();

            $turnoActivo = ControlGarita::where('usuario_id', $user)
                ->where('estado', 'activo')
                ->latest('id')
                ->first();

            if (!$turnoActivo) {
                return redirect()->back()->with('error', 'No tienes un turno activo para registrar el detalle.');
            }

            DetalleControlGarita::create([
                'tipo_movimiento' => $request->tipo_movimiento,
                'tipo_entidad' => $request->tipo_entidad,
                'nombre' => Str::upper($request->nombre),
                'documento' => $request->documento,
                'tipo_documento' => $request->tipo_documento,
                'ocurrencias' => $request->ocurrencias ? Str::upper($request->ocurrencias) : null,
                'hora' => $request->hora,
                'destino' => $request->destino ? Str::upper($request->destino) : null,
                'placa' => $request->placa ? Str::upper($request->placa) : null,
                'tipo_carga' => $request->tipo_carga,
                'tipo_vehiculo' => $request->tipo_vehiculo,
                'etiqueta_id' => $request->etiqueta_id,
                'control_garita_id' => $turnoActivo->id,
                'usuario_id' => $user,
            ]);

            // $ruta = $request->tipo_movimiento === 'E' ? 'controlgarita.entradas.index' : 'controlgarita.salidas.index';
            // $mensaje = $request->tipo_movimiento === 'E' ? 'Entrada registrada exitosamente.' : 'Salida registrada exitosamente.';

            // return redirect()->back()->with('success', $mensaje);
            return redirect()->back()->with('success', 'Entrada registrada exitosamente');
        } catch (\Exception $e) {
            // return redirect()->back()->with('error', 'Error al registrar el detalle de control de garita: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al registrar registro: ' . $e->getMessage(),
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
                'nombre' => Str::upper($request->nombre),
                'documento' => $request->documento,
                'tipo_documento' => $request->tipo_documento,
                'ocurrencias' => $request->ocurrencias ? Str::upper($request->ocurrencias) : null,
                'hora' => $request->hora,
                'destino' => $request->destino ? Str::upper($request->destino) : null,
                'placa' => $request->placa ? Str::upper($request->placa) : null,
                'tipo_carga' => $request->tipo_carga ? Str::upper($request->tipo_carga) : null,
                'tipo_vehiculo' => $request->tipo_vehiculo ? Str::upper($request->tipo_vehiculo) : null,
                'etiqueta_id' => $request->etiqueta_id ? $request->etiqueta_id : null,
                'control_garita_id' => $turnoActivo->id,
                'usuario_id' => $user,
            ]); 

            return redirect()->back()->with('success', 'Entrada editada exitosamente');
        } catch (\Exception $e) {
            // return redirect()->back()->with('error', 'Error al editar el detalle de control de garita: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al editar registro: ' . $e->getMessage(),
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

    public function searchCodigo(Request $request)
    {
        $detalleSearch = DetalleControlGarita::where('nombre', 'like', '%' . $request->search_string . '%')
            ->orWhere('documento', 'like', '%' . $request->search_string . '%')
            ->orderBy('created_at', 'desc')->get();
        return view('controlgarita.entrada.index', compact('detalleSearch'));
    }
}
