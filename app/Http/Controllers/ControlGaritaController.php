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
        $this->middleware('permission:start cg-turn', ['only' => ['store']]);
        $this->middleware('permission:end cg-turn', ['only' => ['endTurn']]);
        // $this->middleware('auth');
        // $this->middleware('permission:gestionar turnos garita', ['only' => ['store', 'endTurn']]);
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

        try {
            $request->validate([
                'turno' => 'required|in:0,1',
                'fecha' => 'required|date',
                'unidad' => 'required|string|max:255',

                // 'usuarios_id' => 'required|array|min:1',
                // 'usuarios_id.*' => 'exists:users,id',

                'productos' => 'required|array|min:1',
                'productos.*.cantidad' => 'required|integer|min:1',
                'productos.*.productos_id' => 'required|exists:productos,id',
            ]);

            DB::beginTransaction();

            $controlGarita = ControlGarita::create([
                'turno' => $request->turno,
                'fecha' => $request->fecha,
                'unidad' => $request->unidad,
                'estado' => 'activo',
                'hora_inicio' => $now,
                'usuario_id' => $user,
            ]);

            foreach ($request->usuarios_id as $usuario_id) {
                CgAgentes::create([
                    'control_garita_id' => $controlGarita->id,
                    'usuarios_id' => $usuario_id,
                ]);
            };

            foreach ($request->productos as $index) {
                CgCargos::create([
                    'control_garita_id' => $controlGarita->id,
                    'productos_id' => $index['productos_id'],
                    'cantidad' => $index['cantidad'],
                ]);
            };

            DB::commit();

            return redirect()->back()->with('success', 'Turno iniciado exitosamente');
        } catch (\Throwable $th) {
            // dd($e->getMessage(), $e->getTrace());
            // return redirect()->back()->with('error', 'Error al iniciar turno: ' . $e->getMessage());
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error al iniciar turno: ' . $th->getMessage(),
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
        $user = Auth::user();

        $ultimoTurno = ControlGarita::where('usuario_id', $user->id)
            ->where('estado', 'activo')
            ->orderBy('id', 'desc')
            ->first();

        if (!$ultimoTurno || $ultimoTurno->estado === 'finalizado') {
            return redirect()->back()->with('error', 'No tienes un turno activo para finalizar.');
        }

        $now = Carbon::now('America/Lima');
        $fechaTurno = Carbon::parse($ultimoTurno->fecha);

        if ($ultimoTurno->turno == 0) {
            // TURNO DÍA (07:00 AM - 19:00 PM del mismo día)
            $horaSalidaProgramada = $fechaTurno->copy()->setTime(19, 0, 0);
        } else {
            // TURNO NOCHE (19:00 PM - 07:00 AM del día SIGUIENTE)
            $horaSalidaProgramada = $fechaTurno->copy()->addDay()->setTime(7, 0, 0);
        }

        if ($now->lessThan($horaSalidaProgramada) && !$user->can('end cg-turn early')) {
 
            $horaFormateada = $horaSalidaProgramada->format('d/m/Y H:i');
            $tiempoRestante = $now->diffForHumans($horaSalidaProgramada, [
                'parts' => 2,
                'join' => true,
                'syntax' => Carbon::DIFF_ABSOLUTE
            ]);

            return redirect()->back()->with('error', "Aún no puedes finalizar. Tu turno acaba a las {$horaFormateada}. Faltan: {$tiempoRestante}.");
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
