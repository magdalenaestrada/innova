<?php

namespace App\Http\Controllers;

use App\Models\LqCliente;
use Carbon\Carbon;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class LqClienteController extends Controller
{


    public function __construct()
    {
        $this->middleware('permission:use cuenta');
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $clientes = LqCliente::orderBy('created_at', 'desc')->paginate(30);
        return view('liquidaciones.clientes.index', compact('clientes'));
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

            $request->validate(
                [
                    'documento' => 'required|max:255|string|unique:lq_clientes,documento',
                    'nombre' => 'required|max:255|string|unique:lq_clientes,nombre',
                    'r_info' => 'required|max:255|string|unique:lq_clientes,r_info',
                    'fecha_inicio_contrato' => 'nullable|date|before_or_equal:fecha_fin_contrato',
                    'fecha_fin_contrato'    => 'nullable|date|after_or_equal:fecha_inicio_contrato',
                ],
                [
                    'fecha_inicio_contrato.before_or_equal' => 'La fecha de inicio no puede ser mayor que la fecha de fin.',
                    'fecha_fin_contrato.after_or_equal'     => 'La fecha de fin no puede ser menor que la fecha de inicio.',
                ]
            );

            $empleado = LqCliente::create([
                'documento' => $request->documento,
                'nombre' => $request->nombre,
                'r_info' => $request->r_info,
                'fecha_inicio_contrato' => $request->fecha_inicio_contrato,
                'fecha_fin_contrato' => $request->fecha_fin_contrato,
                'observacion' => $request->observacion,
                'r_info_prestado' => $request->r_info_prestado,
                'codigo' => $this->GenerarCodigo(),
                'creador_id' => auth()->id(),
            ]);


            DB::commit();

            return redirect()->route('lqclientes.index')->with('status', 'Cliente creado con éxito.');
        } catch (QueryException $e) {
            DB::rollBack();
            if ($e->getCode() == '23000') {
                return redirect()->back->withInput()->with('error', 'Ya existe un registro con este valor.');
            } else {
                return redirect()->back()->with('error', 'Error desconocido');
            }
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    public function GenerarCodigo()
    {
        $ultimo_cliente = LqCliente::orderBy('id', 'desc')->first();
        if ($ultimo_cliente) {
            $nuevo_codigo = str_pad(intval($ultimo_cliente->id) + 1, 4, '0', STR_PAD_LEFT);
            $anio = Carbon::now("America/Lima")->format("y");
            $codigo = 'AF' . $anio . '-' . $nuevo_codigo;
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


    /**
     * Update the specified resource in storage.
     */
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
                'r_info' => [
                    'required',
                    'string',
                    'max:255',
                    Rule::unique('lq_clientes', 'r_info')->ignore($cliente->id),
                ],
                'fecha_inicio_contrato' => 'nullable|date|before_or_equal:fecha_fin_contrato',
                'fecha_fin_contrato'    => 'nullable|date|after_or_equal:fecha_inicio_contrato',
            ],
            [
                'fecha_inicio_contrato.before_or_equal' =>
                'La fecha de inicio no puede ser mayor que la fecha de fin.',
                'fecha_fin_contrato.after_or_equal' =>
                'La fecha de fin no puede ser menor que la fecha de inicio.',
            ]
        );

        $cliente->update([
            'documento' => $request->documento,
            'nombre' => $request->nombre,
            'r_info' => $request->r_info,
            'r_info_prestado' => $request->r_info_prestado,
            'fecha_inicio_contrato' => $request->fecha_inicio_contrato,
            'fecha_fin_contrato' => $request->fecha_fin_contrato,
            'observacion' => $request->observacion,
        ]);

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


    public function activar(string $id)
    {
        $lq_cliente = LqCliente::findOrFail($id);
        $lq_cliente->update([
            'estado' => 'A',
        ]);
    }

    public function desactivar(string $id)
    {
        $lq_cliente = LqCliente::findOrFail($id);

        $lq_cliente->update([
            'estado' => 'I',
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
}
