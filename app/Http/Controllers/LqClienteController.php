<?php

namespace App\Http\Controllers;

use App\Exports\ClientesExport;
use App\Models\LqCliente;
use App\Models\Ubigeo;
use Carbon\Carbon;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;

class LqClienteController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:gestionar clientes')
            ->except(['index', 'getLqClienteByNombre']);

        $this->middleware('permission:ver cliente')
            ->only(['index', 'getLqClienteByNombre']);
    }


    public function index(Request $request)
    {
        $search = $request->get('search');
        $estado = $request->get('estado');

        $clientes = LqCliente::query()

            ->when(!auth()->user()->can('use cuenta'), function ($query) {
                $query->where('estado', 'A');
            })

            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('documento', 'like', "%$search%")
                        ->orWhere('nombre', 'like', "%$search%")
                        ->orWhere('r_info', 'like', "%$search%")
                        ->orWhere('nombre_r_info', 'like', "%$search%");
                });
            })

            ->when($estado, function ($q) use ($estado) {

                $hoy = Carbon::today();
                $limite = Carbon::today()->addDays(30);

                if ($estado === 'vigente') {
                    $q->whereHas('contrato', function ($c) use ($limite) {
                        $c->whereNotNull('fecha_fin_contrato')
                            ->where('fecha_fin_contrato', '>', $limite);
                    });
                }

                if ($estado === 'por_vencer') {
                    $q->whereHas('contrato', function ($c) use ($hoy, $limite) {
                        $c->whereBetween('fecha_fin_contrato', [$hoy, $limite]);
                    });
                }

                if ($estado === 'vencido') {
                    $q->whereHas('contrato', function ($c) use ($hoy) {
                        $c->whereNotNull('fecha_fin_contrato')
                            ->where('fecha_fin_contrato', '<', $hoy);
                    });
                }

                if ($estado === 'sin_fecha') {
                    $q->where(function ($x) {
                        $x->whereDoesntHave('contrato')
                            ->orWhereHas('contrato', fn($c) => $c->whereNull('fecha_fin_contrato'));
                    });
                }
            })



            ->orderBy('estado', 'asc')
            ->orderBy('created_at', 'desc')
            ->paginate(30);

        if ($request->ajax()) {
            return view('liquidaciones.clientes.partials.tabla', compact('clientes'))->render();
        }

        return view('liquidaciones.clientes.index', compact('clientes'));
    }

    public function export(Request $request)
    {
        return Excel::download(
            new ClientesExport(
                $request->search,
                $request->estado,
                auth()->user()
            ),
            'clientes_completo.xlsx'
        );
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
        DB::beginTransaction();

        try {

            $request->validate([
                'documento' => 'required|string|max:255|unique:lq_clientes,documento',
                'nombre'    => 'required|string|max:255|unique:lq_clientes,nombre',
                'nombre_r_info' => 'nullable|string|max:255',
                'observacion' => 'nullable|string',
            ]);

            $rInfoPrestado = $request->boolean('r_info_prestado');
            $auth = Auth::id();
            $cliente = LqCliente::create([
                'documento' => $request->documento,
                'nombre' => $request->nombre,
                'creador_id' => $auth,
                'r_info_prestado' => $rInfoPrestado,
                'r_info' => $rInfoPrestado ? $request->r_info : $request->documento,
                'nombre_r_info' => $rInfoPrestado ? $request->nombre_r_info : $request->nombre,
                'observacion' => $request->observacion,
                'codigo' => $this->GenerarCodigo(),
            ]);

            DB::commit();

            return redirect()
                ->route('lqclientes.index')
                ->with('status', 'Cliente creado con éxito.');
        } catch (QueryException $e) {

            DB::rollBack();

            if ($e->getCode() == '23000') {
                return redirect()
                    ->back()
                    ->withInput()
                    ->with('error', 'Ya existe un registro con este valor.');
            }

            return redirect()
                ->back()
                ->with('error', 'Error en base de datos');
        } catch (\Exception $e) {

            DB::rollBack();

            return redirect()
                ->back()
                ->with('error', 'Error: ' . $e->getMessage());
        }
    }

    public function GenerarCodigo()
    {
        $ultimo_cliente = LqCliente::orderBy('id', 'desc')->first();
        if ($ultimo_cliente) {
            $nuevo_codigo = str_pad(intval($ultimo_cliente->id) + 1, 4, '0', STR_PAD_LEFT);
            $anio = Carbon::now("America/Lima")->format("y");
            $codigo = 'IC' . $anio . '-' . $nuevo_codigo;
        } else {
            $anio = Carbon::now("America/Lima")->format("y");
            $codigo = 'IC' . $anio . '-0001';
        }
        return $codigo;
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
        $cliente = LqCliente::findOrFail($id);
        return response()->json($cliente);
    }
    public function update(Request $request, string $id)
    {
        $cliente = LqCliente::findOrFail($id);

        $request->validate(
            [
                'documento' => [
                    'required',
                    'string',
                    'max:255',
                    Rule::unique('lq_clientes', 'documento')->ignore($cliente->id),
                ],
                'nombre' => [
                    'required',
                    'string',
                    'max:255',
                    Rule::unique('lq_clientes', 'nombre')->ignore($cliente->id),
                ],

            ]
        );

        if ($request->r_info_prestado == true) {
            $cliente->update([
                'documento' => $request->documento,
                'nombre' => $request->nombre,
                'creador_id' => auth()->id(),
                'nombre_r_info' => $request->nombre_r_info,
                'r_info_prestado' => $request->r_info_prestado,
                'r_info' => $request->r_info,
                'observacion' => $request->observacion,
                'codigo' => $request->codigo,
            ]);
        } else {
            $cliente->update([
                'documento' => $request->documento,
                'nombre' => $request->nombre,
                'creador_id' => auth()->id(),
                'nombre_r_info' => $request->nombre,
                'r_info_prestado' => $request->r_info_prestado,
                'r_info' => $request->documento,
                'observacion' => $request->observacion,
                'codigo' => $request->codigo,
            ]);
        }

        return redirect()
            ->route('lqclientes.index')
            ->with('status', 'Cliente actualizado correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }


    public function activar($id)
    {
        $cliente = LqCliente::findOrFail($id);
        $cliente->estado = 'A';
        $cliente->save();

        return response()->json([
            'success' => true,
            'message' => 'Cliente activado'
        ]);
    }

    public function desactivar($id)
    {
        $cliente = LqCliente::findOrFail($id);
        $cliente->estado = 'I';
        $cliente->save();

        return response()->json([
            'success' => true,
            'message' => 'Cliente desactivado'
        ]);
    }


    //FOR AJAX
    public function getLqClienteByNombre($cliente)
    {
        $cliente = LqCliente::find($cliente);


        if ($cliente) {
            return response()->json(['success' => true, 'cliente' => $cliente]);
        } else {
            return response()->json(['success' => false, 'message' => 'Lote no encontrado']);
        }
    }
    
    /**
     * DATOS REINFO
     */
    public function getReinfo($id)
    {
        $cliente = LqCliente::with('ubigeo.parent.parent')->find($id);

        if (!$cliente) {
            return response()->json(['success' => false, 'message' => 'Cliente no encontrado'], 404);
        }

        $data = [
            'id'            => $cliente->id,
            'codigo_minero' => $cliente->codigo_minero,
            'nombre_minero' => $cliente->nombre_minero,
            'estado_reinfo' => $cliente->estado_reinfo,
            'ubigeo_id'     => $cliente->ubigeo_id,
        ];

        if ($cliente->ubigeo) {
            $data['distrito_id']     = $cliente->ubigeo->id;
            $data['provincia_id']    = $cliente->ubigeo->parent_id;
            $data['departamento_id'] = $cliente->ubigeo->parent->parent_id ?? null;
            $data['ubicacion_texto'] = $cliente->ubigeo->parent->parent->nombre . ' / ' . 
                                       $cliente->ubigeo->parent->nombre . ' / ' . 
                                       $cliente->ubigeo->nombre;
        } else {
            $data['distrito_id']     = null;
            $data['provincia_id']    = null;
            $data['departamento_id'] = null;
        }

        return response()->json([
            'success' => true,
            'data'    => $data
        ]);
    }

    public function saveReinfo(Request $request)
    {
        try {
            $request->validate([
                'cliente_id'    => 'required|exists:lq_clientes,id',
                'codigo_minero' => [
                    'nullable', 
                    'string', 
                    'max:255', 
                    Rule::unique('lq_clientes', 'codigo_minero')->ignore($request->cliente_id)
                ],
                'nombre_minero' => 'nullable|string|max:255',
                'ubigeo_id'     => 'nullable|exists:ubigeo,id',
                'estado_reinfo' => 'nullable|boolean',
            ]);

            $cliente = LqCliente::findOrFail($request->cliente_id);

            $cliente->update([
                'codigo_minero' => $request->codigo_minero,
                'nombre_minero' => $request->nombre_minero,
                'ubigeo_id'     => $request->ubigeo_id,
                'estado_reinfo' => $request->estado_reinfo,
            ]);

            // return response()->json([
            //     'success' => true,
            //     'message' => 'Información REINFO actualizada correctamente.',
            //     'ubicacion_nombre' => $cliente->ubigeo ? $cliente->ubigeo->nombre : '-'
            // ]);

            return redirect()
                ->route('lqclientes.index')
                ->with('status', 'Información REINFO actualizada correctamente.');
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Ocurrió un error al guardar: ' . $th->getMessage()
            ], 500);
        }
    }

    public function getDepartamentos()
    {
        $departamentos = Ubigeo::where('nivel', 1)->orderBy('nombre')->get(['id', 'nombre']);
        return response()->json($departamentos);
    }

    public function getProvincias($departamento_id)
    {
        $provincias = Ubigeo::where('parent_id', $departamento_id)
                            ->where('nivel', 2)
                            ->orderBy('nombre')
                            ->get(['id', 'nombre']);
        return response()->json($provincias);
    }

    public function getDistritos($provincia_id)
    {
        $distritos = Ubigeo::where('parent_id', $provincia_id)
                           ->where('nivel', 3)
                           ->orderBy('nombre')
                           ->get(['id', 'nombre']);
        return response()->json($distritos);
    }
}
