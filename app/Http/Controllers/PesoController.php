<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Peso;
use App\Models\PesoKilate; // <- crea/importa este modelo
use App\Exports\PesoExport;
use Excel;

class PesoController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:ver balanza', ['only' => ['export_excel', 'index']]);
    }

 public function index(Request $request)
{
    $balanza = $request->get('balanza'); // pesos | kilate | null

    $pesos = collect();
    $pesos_kilate = collect();

    if ($balanza === 'pesos' || $balanza === null) {
        $pesos = Peso::orderBy('NroSalida','desc')->paginate(200, ['*'], 'pesos_page');
    }

    if ($balanza === 'kilate' || $balanza === null) {
        $pesos_kilate = PesoKilate::orderBy('NroSalida','desc')->paginate(200, ['*'], 'kilate_page');
    }

    // ... (tus pluck distinct para el modal y KPIs)
}

    public function export_excel(Request $request)
    {
        $balanza     = $request->input('balanza'); // pesos | kilate
        $observacion = $request->input('Observacion');
        $producto    = $request->input('Producto');
        $startDate   = $request->input('start_date');
        $endDate     = $request->input('end_date');

        $filename = $balanza === 'kilate' ? 'pesos_kilate.xlsx' : 'pesos.xlsx';

        return Excel::download(
            new PesoExport($balanza, $observacion, $producto, $startDate, $endDate),
            $filename
        );
    }
}
