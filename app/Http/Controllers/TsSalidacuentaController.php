<?php

namespace App\Http\Controllers;

use App\Exports\TsSalidaCuentaExport;
use Illuminate\Http\Request;

use App\Models\TsSalidaCuenta;
use Yajra\DataTables\Facades\DataTables;

use App\Models\TsCuenta;
use App\Models\TsMotivo;
use App\Models\Chat;
use App\Models\TipoComprobante;
use App\Models\Tsdetsalidacuentarep;
use App\Http\Controllers\TsIngresocuentaController;
use App\Models\TsBeneficiario;
use App\Models\TsDepositoCuenta;
use Carbon\Carbon;
use Excel;

class TsSalidacuentaController extends Controller
{

    //variable que almacenará al controlador externo
    protected $ingresoCuentaController;

    //Asignando el controlador a la variable
    public function __construct(TsIngresocuentaController $ingresoCuentaController)
    {
        $this->ingresoCuentaController = $ingresoCuentaController;
        $this->middleware('permission:use cuenta');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {


        $chats = Chat::where(function ($query) {
            $query->where('user_id', auth()->id())
                ->orWhere('recipient_id', auth()->id());
        })
            ->with(['messages' => function ($query) {
                // Get the messages, ordered by the latest
                $query->orderBy('created_at', 'desc');
            }])
            ->get();

        $startOfToday = \Carbon\Carbon::today()->startOfDay(); // Equivalent to 12:00 AM today
        $endOfToday = \Carbon\Carbon::today()->endOfDay(); // Equivalent to 12:00 AM today





        $salidascuentas = TsSalidaCuenta::whereBetween('fecha', [$startOfToday, $endOfToday])->orderByRaw('fecha IS NULL, fecha DESC')
            ->orderBy('created_at', 'desc')
            ->get();
        $tiposcomprobantes = TipoComprobante::orderBy('nombre', 'asc')->get();
        $cuentas = TsCuenta::orderBy('created_at', 'desc')->paginate(30);
        $motivos = TsMotivo::orderBy('nombre', 'asc')->get();

        return view('tesoreria.salidascuentas.index', compact('salidascuentas', 'tiposcomprobantes', 'cuentas', 'motivos', 'chats'));
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
                'monto' => 'required|numeric|min:0',
                'tipo_comprobante_id' => 'nullable',
                'comprobante_correlativo' => 'nullable',
                'descripcion' => 'nullable',
                'cuenta_id' => 'required',
                'motivo_id' => 'required',
                'documento' =>  'nullable', //DOCUMENTO DEL BENEFICIARIO 
                'nombre' =>  'nullable', //NOMBRE DEL BENEFICIARIO  
            ]);

            //SE CREA UN OBJETO LLAMANDO A LA CUENTA SOBRE LA QUE SE VA A TRABAJAR
            $cuenta = TsCuenta::findOrFail($request->cuenta_id);

            //VALIDAR SI DESPUÉS DE LA SALIDA EL BALANCE TODAVÍA ES POSITIVO


            $salida_cuenta = TsSalidaCuenta::create([
                'monto' => $request->monto,
                'tipo_comprobante_id' => $request->tipo_comprobante_id,
                'comprobante_correlativo' => $request->comprobante_correlativo,
                'descripcion' => $request->descripcion,
                'cuenta_id' => $request->cuenta_id,
                'motivo_id' => $request->motivo_id,
                'creador_id' => auth()->id(),
                'nro_operacion' => $request->nro_operacion,
            ]);

            //ACTUALIZAR EL BALANCE DE LA CUENTA DESPUÉS DE LA SALIDA
            $cuenta->balance -= $request->monto;
            $cuenta->save();


            if ($request->nombre && $request->documento) {
                $beneficiario = TsBeneficiario::updateOrCreate(
                    ['nombre' => $request->nombre],
                    [
                        'documento' => $request->documento,
                    ]
                );

                $salida_cuenta->beneficiario_id = $beneficiario->id;
                $salida_cuenta->save();
            }


            if ($request->fecha) {
                $salida_cuenta->fecha = $request->fecha;
            } else {
                $salida_cuenta->fecha = $salida_cuenta->created_at;
            }
            $salida_cuenta->save();




            return redirect()->route('tssalidascuentas.index')->with('status', 'Salida de la cuenta efectuada con éxito.');
        } catch (QueryException $e) {

            return redirect()->back()->with('error', 'Error desconocido');
        } catch (\Exception $e) {

            return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
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

        $tiposcomprobantes = TipoComprobante::orderBy('nombre', 'asc')->get();

        $salidacuenta = TsSalidaCuenta::findOrFail($id);
        $cuentas = TsCuenta::orderBy('created_at', 'desc')->paginate(30);
        $motivos = TsMotivo::orderBy('nombre', 'asc')->get();

        return view('tesoreria.salidascuentas.edit', compact('salidacuenta', 'tiposcomprobantes','cuentas', 'motivos'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $request->validate([
                'descripcion' => 'nullable|string|max:255',
                'monto' => 'nullable|numeric|min:0',

            ]);

            $salidacuenta = TsSalidaCuenta::findOrFail($id);

            $salidacuenta->descripcion = $request->descripcion;
            $salidacuenta->comprobante_correlativo = $request->comprobante_correlativo;
            $salidacuenta->fecha = $request->fecha;





            //UPDATE THE BALANCE

            $cuenta = TsCuenta::findOrFail($salidacuenta->cuenta->id);
            $cuenta->balance = $cuenta->balance - ($request->monto - $salidacuenta->monto);
            $cuenta->save();

            $salidacuenta->monto = $request->monto;
            $salidacuenta->cuenta_id = $request->cuenta_id;
            $salidacuenta->motivo_id = $request->motivo_id;
            $salidacuenta->tipo_comprobante_id = $request->tipocomprobante_id;



            $salidacuenta->save();


            return redirect()->route('tssalidascuentas.index')->with('status', 'Salida de la cuenta actualizada con éxito.');
        } catch (QueryException $e) {

            return redirect()->back()->with('error', 'Error desconocido');
        } catch (\Exception $e) {

            return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $salidacuenta = TsSalidaCuenta::findOrFail($id);
            $salidacuenta->cuenta->balance += $salidacuenta->monto;
            $salidacuenta->cuenta->save();
            $salidacuenta->delete();
            return redirect()->route('tssalidascuentas.index')->with('status', 'Salida de la cuenta eliminada con éxito.');
        } catch (QueryException $e) {
            return redirect()->back()->with('error', 'Error desconocido');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
        }
    }







    public function depositar(Request $request)
    {

        try {

            $request->validate([
                'monto' => 'required|numeric|min:0',
                'tipo_comprobante_id' => 'nullable',
                'comprobante_correlativo' => 'nullable',
                'cuenta_id' => 'required',
                'cuenta_beneficiaria_id' => 'required',
                'motivo_id' => 'required',
                'tipo_cambio' => 'nullable',
                'descripcion' => 'nullable',
            ]);





            //SE CREA UN OBJETO LLAMANDO A LA CUENTA DE ORIGEN
            $cuenta = TsCuenta::findOrFail($request->cuenta_id);

            //VALIDAR QUE EL BALANCE NO QUEDE NEGATIVO EN LA CUENTA DE ORIGEN ANTES DE LA TRANSFERENCIA
            if ($cuenta->balance < $request->monto) {
                throw new \Exception('No tienes suficiente saldo para ejecutar este depósito.');
            }



            $salida_cuenta = TsSalidaCuenta::create([
                'monto' => $request->monto,
                'tipo_comprobante_id' => $request->tipo_comprobante_id,
                'comprobante_correlativo' => $request->comprobante_correlativo,
                'descripcion' => $request->descripcion,
                'cuenta_id' => $request->cuenta_id,
                'motivo_id' => $request->motivo_id,
                'creador_id' => auth()->id(),
            ]);






            //ACTUALIZAR EL BALANCE DE LA CUENTA DESPUÉS DE LA SALIDA COMO DEPÓSITO
            $cuenta->balance -= $request->monto;
            $cuenta->save();

            if ($request->fecha) {
                $salida_cuenta->fecha = $request->fecha;
            } else {
                $salida_cuenta->fecha = $salida_cuenta->created_at;
            }
            $salida_cuenta->save();


            //CREAR EL INGRESO DEL DEPÓSITO EN LA CUENTA DESTINO
            $this->ingresoCuentaController->recepcionardeposito($request, $salida_cuenta->id);

            return redirect()->route('tssalidascuentas.index')->with('status', 'Salida de la cuenta efectuada con éxito.');
        } catch (QueryException $e) {

            return redirect()->back()->with('error', 'Error desconocido');
        } catch (\Exception $e) {

            return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
        }
    }




    public function export_excel(Request $request)
    {

        $string = $request->input('string');
        $desde = $request->input('desde');
        $hasta = $request->input('hasta');



        return Excel::download(new TsSalidaCuentaExport($string, $desde, $hasta), 'salidascuentas.xlsx');
    }





    public function printdoc(string $id)
    {

        $salidacuenta = TsSalidaCuenta::findOrFail($id);


        return view('tesoreria.salidascuentas.printable', compact('salidacuenta'));
    }




    public function searchSalidaCuenta(Request $request)
    {
        // Get date range from request with default values
        $desdeDate = \Carbon\Carbon::parse($request->input('desde'))->startOfDay();
        $hastaDate = \Carbon\Carbon::parse($request->input('hasta'))->endOfDay();

        $salidascuentas = TsSalidaCuenta::whereBetween('fecha', [$desdeDate, $hastaDate])
            ->where(function ($query) use ($request) {
                $searchString = $request->search_string;

                $query->where('descripcion', 'like', '%' . $searchString . '%')
                    ->orWhere('comprobante_correlativo', 'like', '%' . $searchString . '%')
                    ->orWhereHas('tipocomprobante', function ($subQuery) use ($searchString) {
                        $subQuery->where('nombre', 'like', '%' . $searchString . '%');
                    })
                    ->orWhereHas('motivo', function ($subQuery) use ($searchString) {
                        $subQuery->where('nombre', 'like', '%' . $searchString . '%');
                    })
                    ->orWhereHas('beneficiario', function ($subQuery) use ($searchString) {
                        $subQuery->where('nombre', 'like', '%' . $searchString . '%');
                    })
                    ->orWhere('monto', 'like', '%' . $searchString . '%');
            })
            ->orderBy('fecha', 'desc')
            ->get();

        $tiposcomprobantes = TipoComprobante::orderBy('nombre', 'asc')->get();
        $cuentas = TsCuenta::orderBy('created_at', 'desc')->get();
        $motivos = TsMotivo::orderBy('nombre', 'asc')->get();

        return view('tesoreria.salidascuentas.search-results', compact('salidascuentas', 'tiposcomprobantes', 'cuentas', 'motivos'));
    }




    public function findBeneficiario(Request $request)
    {
        $beneficiario = TsBeneficiario::where('nombre', '=', $request->search_string)->firstOrFail();
        return response()->json(['documento' => $beneficiario->documento]);
    }


   public function datatable(Request $request)
    {
        $query = TsSalidaCuenta::with(['cuenta', 'motivo', 'tipocomprobante', 'beneficiario', 'creador']);

        if ($request->filled('desde') && $request->filled('hasta')) {
            $query->whereBetween('fecha', [
                Carbon::parse($request->desde)->startOfDay(),
                Carbon::parse($request->hasta)->endOfDay(),
            ]);
        }

        if ($request->filled('search_string')) {
            $search = $request->search_string;
            $query->where(function ($q) use ($search) {
                $q->where('descripcion', 'like', "%$search%")
                
                    ->orWhereHas('motivo', fn($q) => $q->where('nombre', 'like', "%$search%"))
                    ->orWhereHas('cuenta', fn($q) => $q->where('nombre', 'like', "%$search%"))
                    ->orWhere('monto', 'like', "%$search%");
                });
        }

        return DataTables::of($query)
            ->addColumn('tipo', function ($row) {
                $tags = ['egreso'];
                if ($row->reposicioncaja) $tags[] = 'reposicion';
                if ($row->liquidacion) $tags[] = 'liquidación';
                if ($row->adelanto) $tags[] = 'adelanto';
                return implode(', ', $tags);
            })
            ->addColumn('tipocomprobante', fn($row) => $row->tipocomprobante->nombre ?? '-')
            ->addColumn('cuenta', fn($row) => $row->cuenta->nombre ?? '-')
            ->addColumn('motivo', fn($row) => $row->motivo->nombre ?? '-')
            ->addColumn('beneficiario', fn($row) => $row->beneficiario->nombre ?? '-')
            ->addColumn('creador', fn($row) => $row->creador->name ?? '-')
            ->addColumn('monto', function ($row) {
                $symbol = $row->cuenta->tipomoneda->nombre == 'DOLARES' ? '$' : 'S/.';
                return $symbol . ' ' . number_format($row->monto, 2);
            })
            ->addColumn('acciones', function ($row) {
                return view('tesoreria.salidascuentas.partials.actions', compact('row'))->render();
            })
            ->rawColumns(['acciones']) // allow HTML
            ->make(true);
    }
}
