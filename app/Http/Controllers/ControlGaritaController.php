<?php

namespace App\Http\Controllers;

use App\Models\CgAgentes;
use App\Models\CgCargos;
use App\Models\ControlGarita;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ControlGaritaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
        $now = Carbon::now('America/Lima')->format('H:i:s');
        $user = Auth::id();
        $turnoExistente = ControlGarita::where('usuario_id', $user)
            ->where('estado', 'activo')
            ->orderBy('id', 'desc')
            ->first();
        if ($turnoExistente && $turnoExistente->estado === 'activo') {
            return redirect()->back()->with('error', 'Ya tienes un turno activo. Debes finalizar tu turno actual primero.');
        }

        $request->validate([
            'turno' => 'required|in:0,1',
            'fecha' => 'required|date',
            'unidad' => 'required|string|max:255',

            // 'usuarios_id' => 'required|array|min:1',
            // 'usuarios_id.*' => 'exists:users,id',

            'productos' => 'required|array|min:1',
            'productos.*.cantidad' => 'required|integer|min:1',
            'productos.*.productos_id' => 'required|exists:productos,id',
        ], [
            'turno.required' => 'Debe seleccionar un turno',
            'fecha.required' => 'La fecha es obligatoria',
            'unidad.required' => 'La unidad es obligatoria',
            // 'usuarios_id.required' => 'Debe seleccionar al menos un usuario',
            // 'usuarios_id.min' => 'Debe seleccionar al menos un usuario',
            'productos.required' => 'Debe agregar al menos un elemento',
            'productos.min' => 'Debe agregar al menos un elemento',
            'productos.*.cantidad.required' => 'La cantidad es obligatoria',
            'productos.*.cantidad.min' => 'La cantidad debe ser mayor a 0',
            'productos.*.productos_id.required' => 'Debe seleccionar un producto',
        ]);

        try {
            DB::beginTransaction();

            $controlGarita = ControlGarita::create([
                'turno' => $request->turno,
                'fecha' => $request->fecha,
                'unidad' => Str::upper($request->unidad),
                'estado' => 'activo',
                'hora_inicio' => $now,
                'usuario_id' => $user,
            ]);

            // foreach ($request->usuarios_id as $usuario_id) {
            //     CgAgentes::create([
            //         'control_garita_id' => $controlGarita->id,
            //         'usuarios_id' => $usuario_id,
            //     ]);
            // };

            foreach ($request->productos as $index) {
                CgCargos::create([
                    'control_garita_id' => $controlGarita->id,
                    'productos_id' => $index['productos_id'],
                    'cantidad' => $index['cantidad'],
                ]);
            };

            DB::commit();

            return redirect()->back()->with('success', 'Turno iniciado exitosamente');
        } catch (\Exception $e) {
            // dd($e->getMessage(), $e->getTrace());
            // return redirect()->back()->with('error', 'Error al iniciar turno: ' . $e->getMessage());
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error al iniciar turno: ' . $e->getMessage(),
            ], 500);
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

    public function endTurn()
    {
        $ultimoTurno = ControlGarita::where('usuario_id', Auth::id())
            ->orderBy('id', 'desc')
            ->first();

        if (!$ultimoTurno || $ultimoTurno->estado === ' finalizado') {
            return redirect()->back()->with('error', 'No tienes un turno activo para finalizar.');
        }

        $ultimoTurno->update([
            'estado' => 'finalizado',
            'hora_fin' => now(),
        ]);

        return redirect()->back()->with('success', 'Turno finalizado exitosamente');
    }

    public function getTurnoActivo()
    {
        $turnoActivo = ControlGarita::where('usuario_id', Auth::id())
            ->where('estado', 'activo')
            ->with(['cargos.producto', 'usuario'])
            ->latest()
            ->first();

        if (!$turnoActivo) {
            return response()->json(['activo' => false]);
        }

        return response()->json([
            'activo' => true,
            'turno' => [
                'id' => $turnoActivo->id,
                'turno' => $turnoActivo->turno,
                'turno_texto' => $turnoActivo->turno == 0 ? 'Día (7:00 - 19:00)' : 'Noche (19:00 - 7:00)',
                'fecha' => $turnoActivo->fecha->format('d/m/Y'),
                'unidad' => $turnoActivo->unidad,
                'hora_inicio' => $turnoActivo->hora_inicio->format('H:i:s'),
                'usuario' => $turnoActivo->usuario->name,
                'cargos' => $turnoActivo->cargos->map(function ($cargo) {
                    return [
                        'producto' => $cargo->producto->nombre_producto,
                        'cantidad' => $cargo->cantidad
                    ];
                })
            ]
        ]);
    }
}
