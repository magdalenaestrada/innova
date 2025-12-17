<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\DocumentacionLegal;
use Spatie\Permission\Models\Permission;

class DocumentacionLegalController extends Controller
{
    public function __construct()
    { 
        $this->middleware('permission:view doclegal', ['only' => ['index']]);
        $this->middleware('permission:create doclegal', ['only' => ['create', 'store']]);
        $this->middleware('permission:print doclegal', ['only' => ['prnpriview']]);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $DocumentacionLegal = DocumentacionLegal::all()->orderBy('created_at', 'desc')->paginate(20);
        return view('DocumentacionLegal.index', compact('DocumentacionLegal'));
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
        //
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
        $registro = DocumentacionLegal::find($id);

        if (!$registro) {
            return response()->json(['mensaje' => 'No encontrado'], 404);
        }

        $registro->delete();

        return response()->json(['mensaje' => 'Eliminado correctamente']);
    }

    /**
     * Generate code from identification.
     */
    private function generarCodigoUnico()
    {
        do {
            $letras = substr(str_shuffle("ABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 3);

            $numeros = str_pad(rand(0, 999), 3, '0', STR_PAD_LEFT);

            $codigo = $letras . $numeros;

            // Verificacion
        } while (DocumentacionLegal::where('codigo_documento', $codigo)->exists());

        return $codigo;
    }
}
