<?php

namespace App\Http\Controllers;

use App\Models\DiaLibre;
use Illuminate\Http\Request;

class DiaLibreController extends Controller
{
    public function __construct()
    {
        // $this->middleware('permission:crear dia libre', ['only' => ['store']]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $diaLibre = DiaLibre::all();
        return view('controlgarita.diaslibres.index' ,compact('diaLibre'));
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
    public function show(DiaLibre $DiaLibre)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(DiaLibre $DiaLibre)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, DiaLibre $DiaLibre)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DiaLibre $DiaLibre)
    {
        //
    }
}
