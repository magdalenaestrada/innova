<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use Illuminate\Validation\Rule;

use App\Models\ClienteCodigo;
use Illuminate\Support\Str;

class ClienteCodigoController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware('permission:view clientecod', ['only' => ['index']]);
    //     $this->middleware('permission:create clientecod', ['only' => ['create', 'store']]);
    // }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $codigos = ClienteCodigo::orderBy('id', 'desc')->get();
        return view("clientescodigo.index", compact('codigos'));
    }

    /**d
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
        ClienteCodigo::create([
            'codigo' => $this->generarCodigoUnico(),
            'documento' => $request->documento,
            'nombre' => Str::upper($request->nombre),
            'tipo_documento' => $request->tipo_documento,
            'creador_id' => auth()->id(),
        ]);

        return redirect()->route('clientescodigo.index')->with('success', 'Código de cliente creado exitosamente.');
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
        $codigo = ClienteCodigo::findOrFail($id);

        $codigo->tipo_documento = $request->tipo_documento;
        $codigo->documento = strtoupper($request->documento);
        $codigo->nombre = strtoupper($request->nombre);

        $codigo->save();

        return redirect()
            ->route('clientescodigo.index')
            ->with('success', 'Código actualizado correctamente');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $codigoId)
    {
        ClienteCodigo::findOrFail($codigoId)->delete();

        return response()->json([
            'success' => true,
            'message' => 'Cliente eliminado correctamente'
        ]);
    }


    /**
     * Generate a unique code for the client.
     */
    public function generarCodigoUnico()
    {
        do {
            $letras = substr(str_shuffle("ABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 3);

            $numeros = str_pad(rand(0, 999), 3, '0', STR_PAD_LEFT);

            $codigo = $letras . $numeros;

            // Verificacion
        } while (ClienteCodigo::where('codigo', $codigo)->exists());

        return $codigo;
    }

    /**
     * Search for a code by name or code.
     */
    public function searchCodigo(Request $request)
    {
        $codigos = ClienteCodigo::where('nombre', 'like', '%' . $request->search_string . '%')
            ->orWhere('documento', 'like', '%' . $request->search_string . '%')
            ->orderBy('created_at', 'desc')->get();
        return view('clientescodigo.search-results', compact(var_name: 'codigos'));
    }
}
