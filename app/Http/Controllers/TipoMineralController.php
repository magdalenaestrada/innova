<?php

namespace App\Http\Controllers;

use App\Models\TipoMineral;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TipoMineralController extends Controller
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
        $mineral = TipoMineral::select('id', 'nombre')->get();
        return response()->json($mineral);
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
            $request->validate([
                'nombre' => 'required|string|unique:tipo_mineral,nombre',
            ]);
            TipoMineral::create([
                'nombre' => $request->nombre,
            ]);
    
            return response()->json([
                'success' => true,
                'message' => 'Tipo de mineral creado con éxito.',
            ], 201);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'error' => 'Error inesperado',
                'message' => 'Error al crear el tipo de mineral.' . $th->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(TipoMineral $tipoMineral)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TipoMineral $tipoMineral)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TipoMineral $tipoMineral)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TipoMineral $tipoMineral)
    {
        //
    }
}
