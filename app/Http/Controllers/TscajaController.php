<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TipoMoneda;
use App\Models\TsCaja;
use App\Models\Empleado;
use App\Models\TsEncargadocaja;
use App\Models\TsReposicioncaja;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TscajaController extends Controller
{

    public function __construct() {}

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $empleado = Auth::user()->empleado;

        // ✅ Cajas con su moneda (para mostrar SOLES/DÓLARES si lo necesitas en la tabla)
        $cajas = TsCaja::with('tipoMoneda')
            ->orderBy('created_at', 'asc')
            ->paginate(30);

        $empleados = Empleado::orderBy('created_at', 'asc')->paginate(30);

        // ✅ Para el select "MONEDA" del modal crear caja
        $tiposmonedas = TipoMoneda::orderBy('id', 'asc')->get();

        return view('tesoreria.cajas.index', compact('cajas', 'empleados', 'tiposmonedas'));
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
            DB::beginTransaction();

            $request->validate([
                'nombre' => 'required|max:255|string|unique:ts_cajas,nombre',
                'tipo_moneda_id' => 'required|exists:tipo_moneda,id',
                'encargados' => 'required|array|min:1',
                'encargados.*' => 'exists:empleados,id',
            ]);

            $caja = TsCaja::create([
                'nombre' => $request->nombre,
                'tipo_moneda_id' => $request->tipo_moneda_id, // ✅ GUARDA LA MONEDA
                'creador_id' => auth()->id(),
                'balance' => 0,
            ]);

            foreach ($request->input('encargados') as $encargadoId) {
                TsEncargadocaja::create([
                    'caja_id' => $caja->id,
                    'encargado_id' => $encargadoId,
                ]);
            }

            DB::commit();

            return redirect()->route('tscajas.index')->with('status', 'Caja creada con éxito.');
        } catch (QueryException $e) {
            DB::rollBack();

            if ($e->getCode() == '23000') {
                return redirect()->back()->withInput()->with('error', 'Ya existe un registro con este valor.');
            }

            return redirect()->back()->withInput()->with('error', 'Error de base de datos: ' . $e->getMessage());
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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

     public function getReposiciones(Request $request)
    {
        $reposiciones = TsReposicioncaja::where('caja_id', $request->cajaId)
            ->with('salidacuenta')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($reposiciones);
    }
}
