<?php

namespace App\Http\Controllers;

use App\Models\CgAgentes;
use App\Models\CgCargos;
use App\Models\ControlGarita;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ControlGaritaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $controlGarita = ControlGarita::orderBy('id', 'desc')->get();
        $cg_agente = CgAgentes::all();
        $cg_cargo = CgCargos::all();
        return view('controlgarita.index', compact('controlGarita', 'cg_agente', 'cg_cargo'));
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
        // $request->validate([
        //     'documento' => 'required|string|max:20',
        //     'hora' => 'required|date_format:H:i',
        // ]);
        
        try {
            // DB::beginTransaction();
            
            ControlGarita::create([
                'turno' => $request->turno,
                'fecha' => $request->fecha,
            ]);
            // 'tipo_movimiento' => $request->tipo_movimiento,
            // 'tipo_entidad' => $request->tipo_entidad,
            // 'nombre' => Str::upper($request->nombre),
            // 'documento' => $request->documento,
            // 'tipo_documento' => $request->tipo_documento,
            // 'ocurrencias' => $request->ocurrencias ? Str::upper($request->ocurrencias) : null,
            // 'hora' => $request->hora,
            // 'destino' => $request->destino ? Str::upper($request->destino) : null,
            // 'placa' => $request->placa ? Str::upper($request->placa) : null,
            // 'tipo_vehiculo' => $request->tipo_vehiculo ? Str::upper($request->tipo_vehiculo) : null,
            // 'vehiculo_id' => $request->vehiculo_id === 'V' ? $request->vehiculo_id : null,
            // 'trabajador_id' => $request->trabajador_id,
            // 'etiqueta_id' => $request->etiqueta_id ? Str::upper($request->etiqueta_id) : null,
            // 'control_garita_id' => $request->control_garita_id ? Str::upper($request->control_garita_id) : null,

            // DB::commit();

            // $ruta = $request->tipo_movimiento === 'E' ? 'controlgarita.entradas.index' : 'controlgarita.salidas.index';
            // $mensaje = $request->tipo_movimiento === 'E' ? 'Entrada registrada exitosamente.' : 'Salida registrada exitosamente.';

            // return redirect()->back()->with('success', $mensaje);
            return redirect()->back()->with('success', 'Entrada registrada exitosamente');
        } catch (\Exception $e) {
            // DB::rollBack();
            return redirect()->back()->with('error', 'Error al registrar el detalle de control de garita: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(ControlGarita $controlGarita)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ControlGarita $controlGarita)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ControlGarita $controlGarita)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ControlGarita $controlGarita)
    {
        //
    }
}
