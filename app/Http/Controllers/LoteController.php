<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Lote;
use App\Models\LqCliente;
use App\Models\Peso;
use Carbon\Carbon;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class LoteController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:ver lotes')
            ->only(['index', 'show', 'pesosEnCancha']);

        $this->middleware('permission:crear lotes')
            ->only(['create', 'store']);

        $this->middleware('permission:editar lotes')
            ->only(['update']);

        $this->middleware('permission:eliminar lotes')
            ->only(['destroy']);
    }

    public function index()
    {
        $lotes = Lote::with('cliente')
            ->orderBy('id', 'desc')
            ->where('codigo', 'NOT LIKE', 'COM-%')
            ->paginate(10);

        $clientes = LqCliente::with('lotes')->get()->map(function ($cliente) {

        $ultimoCorrelativo = Lote::withTrashed()
                ->where('lq_cliente_id', $cliente->id)
                ->where('codigo', 'NOT LIKE', 'COM-%')
                ->selectRaw('MAX(CAST(SUBSTRING_INDEX(codigo, "-", -1) AS UNSIGNED)) as ultimo')
                ->value('ultimo') ?? 0;

            $cliente->ultimo_correlativo = $ultimoCorrelativo;
            return $cliente;
        });

        return view('lotes.index', compact('lotes', 'clientes'));
    }

    public function create() {}
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'lq_cliente_id' => ['required', 'exists:lq_clientes,id'],

                'codigo' => [
                    'required',
                    'string',
                    Rule::unique('lotes', 'codigo')
                        ->where(function ($query) use ($request) {
                            $query->where('lq_cliente_id', $request->lq_cliente_id)
                                ->whereNull('deleted_at'); // <-- ignorar soft deleted
                        }),
                ],

                'nombre' => [
                    'required',
                    'string',
                    Rule::unique('lotes', 'nombre')
                        ->where(function ($query) use ($request) {
                            $query->where('lq_cliente_id', $request->lq_cliente_id)
                                ->whereNull('deleted_at'); // <-- ignorar soft deleted
                        }),
                ],

            ]);

            $lote = Lote::create([
                "lq_cliente_id" => $request->lq_cliente_id,
                'codigo' => strtoupper($request->codigo),
                'origen' => "C",
                'nombre' => strtoupper($request->nombre),
                'usuario_id' => auth()->id(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Lote creado exitosamente.'
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Ocurrió un error inesperado: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id) {}

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    public function search(Request $request)
    {
        $search = $request->search_string;

        $lotes = Lote::with('cliente')
            ->where('codigo', 'NOT LIKE', 'COM-%') // ← Filtrar COM-
            ->where(function ($q) use ($search) {
                $q->where('codigo', 'LIKE', "%{$search}%")
                    ->orWhere('nombre', 'LIKE', "%{$search}%")
                    ->orWhereHas('cliente', function ($c) use ($search) {
                        $c->where('nombre', 'LIKE', "%{$search}%");
                    });
            })
            ->orderBy('created_at', 'desc')
            ->get();

        return view('lotes.partials', compact('lotes'));
    }

    public function update(Request $request, $id)
    {
        try {
            $lote = Lote::findOrFail($id);

            $validated = $request->validate([
                'lq_cliente_id' => ['required', 'exists:lq_clientes,id'],

                'codigo' => [
                    'required',
                    'string',
                    Rule::unique('lotes')
                        ->ignore($id)
                        ->where(fn($q) => $q->where('lq_cliente_id', $request->lq_cliente_id))
                        ->whereNull('deleted_at'),
                ],

                'nombre' => [
                    'required',
                    'string',
                    Rule::unique('lotes')
                        ->ignore($id)
                        ->where(fn($q) => $q->where('lq_cliente_id', $request->lq_cliente_id))
                        ->whereNull('deleted_at'), // ← Agregar esta línea
                ],
            ]);

            $lote->update([
                'codigo' => strtoupper($request->codigo),
                'nombre' => strtoupper($request->nombre),
                'lq_cliente_id' => $request->lq_cliente_id,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Lote actualizado correctamente.'
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error inesperado.'
            ], 500);
        }
    }

    public function destroy(Request $request)
    {
        try {
            $lote = Lote::findOrFail($request->loteId);

            if ($lote->pesos()->exists() || $lote->procesos()->exists()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No se puede eliminar el lote porque tiene registros asociados (pesos o procesos).'
                ]);
            }

            $lote->delete();

            return response()->json([
                'success' => true,
                'message' => 'Lote eliminado correctamente.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar el lote: ' . $e->getMessage()
            ], 500);
        }
    }

    public function pesosEnCancha($loteId)
    {
        $pesos = Peso::whereHas('estado', function ($q) {
            $q->where('ps_estados_pesos.estado_id', 1);
        })
            ->whereHas('lote', function ($q) use ($loteId) {
                $q->where('lotes.id', $loteId);
            })
            ->with(['estado', 'lote'])
            ->distinct()
            ->get();

        return response()->json($pesos);
    }
    public function findLote(Request $request)
    {
        return Lote::where('codigo', 'LIKE', 'COM -%')
            ->where('nombre', 'LIKE', '%' . $request->term . '%')
            ->limit(10)
            ->get(['id', 'nombre']);
    }
}
