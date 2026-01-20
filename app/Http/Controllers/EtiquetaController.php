<?php

namespace App\Http\Controllers;

use App\Models\Etiqueta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class EtiquetaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:create cg-etiqueta', ['only' => ['store']]);
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
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'color' => 'nullable|string|max:7',
        ]);

        try {
            $etiqueta = Etiqueta::create([
                'nombre' => $request->nombre,
                'descripcion' => $request->descripcion,
                'color' => $request->color,
            ]);
            return response()->json([
                'success' => true,
                'etiqueta' => $etiqueta,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al crear etiqueta: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Etiqueta $etiqueta)
    {
        // $etiqueta = Etiqueta::all();
        // return response()->json($etiqueta);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Etiqueta $etiqueta)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $etiqueta = Etiqueta::findOrFail($id);

        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'color' => 'nullable|string|max:7',
        ]);

        try {
            $etiqueta->update([
                'nombre' => $request->nombre,
                'descripcion' => $request->descripcion,
                'color' => $request->color,
            ]);
            return redirect()->back()->with('success', 'Etiqueta actualizada exitosamente');
        } catch (\Exception $e) {
            // return redirect()->back()->with('error', 'Error al actualizar etiqueta: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar etiqueta: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Etiqueta $etiqueta)
    {
        //
    }
}
