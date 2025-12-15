<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Inventarioingreso;
use App\Models\Producto;
use App\Models\Proveedor;
use App\Models\Detalleinventarioingreso;
use App\Models\Logdetallesinvingreso;
use App\Models\Inventariopagoacuenta;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Excel;
use App\Exports\DetalleInventarioIngresoExport;
use Illuminate\Support\Facades\DB;


class InventarioingresoController extends Controller
{


    public function __construct()
    {
        $this->middleware('permission:ver ordenes', ['only' => ['index', 'show']]);
        $this->middleware('permission:crear ordenes', ['only' => ['create', 'store']]);
        $this->middleware('permission:cancelar ordenes', ['only' => ['cancelar', 'updatecancelar', 'cancelaralcredito', 'updatecancelaralcredito']]);
        $this->middleware('permission:recepcionar ordenes', ['only' => ['recepcionar', 'updaterecepcionar']]);
        //$this->middleware('permission:anular ordenes', ['only' => ['anular']]);
    }




    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $productos = Producto::all();
        $inventarioingresos = Inventarioingreso::orderBy('created_at', 'desc')->paginate(100);
        return view('inventarioingresos.index', compact('inventarioingresos', 'productos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $productos = Producto::all();
        return view('inventarioingresos.create', compact('productos'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            // Validate the request data
            $request->validate([
                'products.*' => 'required|exists:productos,id',
                'product_price.*' => 'required|numeric',
                'qty.*' => 'required',
                'subtotals.*' => 'required|numeric',
                'observacion' => 'nullable|string',
                'product_grand_total' => 'required|numeric',
                'product_sub_total' => 'required|numeric',
                'proveedor' => 'required',
                'documento_proveedor' => 'required',
                'tipomoneda' => 'required',
            ]);

            // Create the order
            $inventarioingreso = Inventarioingreso::create([
                'descripcion' => $request->observacion,
                'total' => $request->product_grand_total,
                'subtotal' => $request->product_sub_total,

                'tipomoneda' => $request->tipomoneda,
            ]);

            $inventarioingreso->estado = 'PENDIENTE';
            $inventarioingreso->estado_pago = 'PENDIENTE DE PAGO';
            $inventarioingreso->usuario_ordencompra = auth()->user()->name;
            $inventarioingreso->save();

            $products = $request->input('products');
            $index = 0;
            // Create order items
            foreach ($products as $productId) {
                Detalleinventarioingreso::create([
                    'inventarioingreso_id' => $inventarioingreso->id,
                    'producto_id' => $productId,
                    'precio' => $request->product_price[$index],
                    'cantidad' => $request->qty[$index],
                    'subtotal' => $request->item_total[$index],
                    'estado' => 'PENDIENTE',

                ]);
                $index = $index + 1;
            }



            // Create or update the proveedor
            $proveedor = Proveedor::updateOrCreate(
                ['ruc' => $request->documento_proveedor],
                [
                    'razon_social' => $request->proveedor,
                    'telefono' => $request->telefono_proveedor,
                    'direccion' => $request->direccion_proveedor,
                ]
            );

            $inventarioingreso->proveedor_id = $proveedor->id;
            $inventarioingreso->save();







            // Redirect to a relevant page, e.g., order index or show
            return redirect()->route('inventarioingresos.index')->with('crear-orden', 'Orden de compra creada con éxito.');
        } catch (ValidationException $e) {
            // If validation fails during the transaction, handle the error
            return back()->withInput()->withErrors($e->validator->errors())->with('error', 'Error al crear la orden.');
        } catch (\Exception $e) {


            // Handle other unexpected errors
            return back()->withInput()->with('error', 'Error al procesar la solicitud: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Inventarioingreso $inventarioingreso)
    {

        $inventarioingreso = Inventarioingreso::with('productos')->find($inventarioingreso->id);
        return view('inventarioingresos.show', compact('inventarioingreso'));
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
        //
    }






    public function cancelar(string $id)
    {
        $inventarioingreso = Inventarioingreso::findOrFail($id);
        return view('inventarioingresos.cancelar', compact('inventarioingreso'));
    }



    public function updatecancelar(Request $request, string $id)
    {
        $request->validate([
            'comprobante_correlativo' => 'required',
            'fecha_cancelacion' => 'required',
            'fecha_emision_comprobante' => 'required',
            'tipopago' => 'required',

        ]);
        $inventarioingreso = Inventarioingreso::findOrFail($id);


        if ($inventarioingreso->estado !== 'PENDIENTE') {
            throw new HttpException(403, 'No puedes acceder a esta página');
        }



        $inventarioingreso->comprobante_correlativo = $request->input('comprobante_correlativo');
        $inventarioingreso->fecha_cancelacion = $request->input('fecha_cancelacion');
        $inventarioingreso->tipocomprobante = 'FACTURA';
        $inventarioingreso->tipopago = $request->input('tipopago');
        $inventarioingreso->fecha_emision_comprobante = $request->input('fecha_emision_comprobante');
        $inventarioingreso->usuario_cancelacion = auth()->user()->name;
        $inventarioingreso->estado = 'POR RECOGER';
        $inventarioingreso->cambio_dolar_precio_venta =  $request->input('cambio_dia');

        if ($request->input('tipopago') == 'CONTADO') {
            $inventarioingreso->estado_pago = 'CANCELADO AL CONTADO';
        } elseif ($request->input('tipopago') == 'A CUENTA') {
            $inventarioingreso->estado_pago = 'PENDIENTE A CUENTA';
        } else {
            $inventarioingreso->estado_pago = 'PENDIENTE AL CRÉDITO';
        }

        $inventarioingreso->save();

        return redirect()->route('inventarioingresos.index')->with('cancelar-orden-compra', 'Orden de compra cancelada exitosamente.');
    }





    public function cancelaralcredito(string $id)
    {
        $inventarioingreso = Inventarioingreso::findOrFail($id);

        return view('inventarioingresos.cancelaralcredito', compact('inventarioingreso'));
    }



    public function updatecancelaralcredito(Request $request, string $id)
    {
        $inventarioingreso = Inventarioingreso::findOrFail($id);

        if ($inventarioingreso->estado_pago == 'PENDIENTE AL CRÉDITO') {
            $request->validate([
                'fecha_pago_al_credito' => 'required',
            ]);

            $inventarioingreso->fecha_pago_al_credito = $request->input('fecha_pago_al_credito');
            $inventarioingreso->usuario_pago_al_credito = auth()->user()->name;

            $inventarioingreso->estado_pago = 'CANCELADO AL CRÉDITO';

            $inventarioingreso->save();

            return redirect()->route('inventarioingresos.index')->with('cancelar-al-credito', 'Orden cancelada al crédito con éxito.');
        }
    }




    public function cancelaracuenta(string $id)
    {
        $inventarioingreso = Inventarioingreso::findOrFail($id);
        if ($inventarioingreso->estado_pago !== 'PENDIENTE A CUENTA') {
            throw new HttpException(403, 'NO ESTÁ PENDIENTE A CUENTA.');
        }

        $today = now()->toDateString();
        return view('inventarioingresos.cancelaracuenta', compact('inventarioingreso', 'today'));
    }



    public function updatecancelaracuenta(Request $request, string $id)
    {

        try {
            $request->validate([
                'fechas_pagos.*' => 'required',
                'montos.*' => 'required',
                'comprobantes.*' => 'required',

            ]);

            $inventarioingreso = Inventarioingreso::findOrFail($id);

            if ($inventarioingreso->estado_pago !== 'PENDIENTE A CUENTA') {
                throw new HttpException(403, 'NO ESTÁ PENDIENTE A CUENTA.');
            }

            $countFechasPagos = count($request->fechas_pagos);
            $fechas_pagos = $request->input('fechas_pagos');
            $index = 0;
            // Create order items
            for ($i = 0; $i < $countFechasPagos; $i++) {
                Inventariopagoacuenta::create([
                    'inventarioingreso_id' => $inventarioingreso->id,
                    'fecha_pago' => $request->fechas_pagos[$index],
                    'monto' => $request->montos[$index],
                    'comprobante_correlativo' => $request->comprobantes[$index],
                    'usuario' => auth()->user()->name,
                ]);
                $index = $index + 1;
            }

            $cerrar_pago = False;
            $monto_total_pagado = 0;
            foreach ($inventarioingreso->pagosacuenta as $pago) {
                $monto_total_pagado += $pago->monto;
            }

            if ($monto_total_pagado >= $inventarioingreso->total) {
                $inventarioingreso->estado_pago = 'CANCELADO A CUENTA';
            }

            $inventarioingreso->save();

            return redirect()->route('inventarioingresos.index')->with('cancelar-al-credito', 'Orden cancelada al crédito con éxito.');
        } catch (ValidationException $e) {
            // If validation fails during the transaction, handle the error
            return back()->withInput()->withErrors($e->validator->errors())->with('error', 'Error al cancelar la orden al crédito.');
        } catch (\Exception $e) {
            // Handle other unexpected errors
            return back()->withInput()->with('error', 'Error al procesar la solicitud.');
        }
    }



    public function recepcionar(string $id)
    {
        $inventarioingreso = Inventarioingreso::findOrFail($id);

        return view('inventarioingresos.recepcionar', compact('inventarioingreso'));
    }


    public function updaterecepcionar(Request $request, string $id)
    {
        try {
            $request->validate([
                'guiaingresoalmacen' => 'required',
            ]);

            $inventarioingreso = Inventarioingreso::findOrFail($id);
            if ($inventarioingreso->estado == 'POR RECOGER') {
                $closing = true;

                $productos = $inventarioingreso->productos;
                foreach ($productos as $index => $producto) { // Modified line: Added $index for proper indexing

                    if (in_array($index, $request->input('selected_products', []))) { // Modified line: Changed $producto->id to $index
                        $cantidad_recepcionada = $request->input('qty_arrived.' . $index); // Modified line: Changed $producto->id to $index
                        if ($producto->pivot->cantidad < $producto->pivot->cantidad_ingresada + $cantidad_recepcionada) {
                            throw new HttpException(403, 'NO PUEDES INGRESAR MÁS PRODUCTOS DE LOS QUE HAY POR RECIBIR.');
                        }

                        $producto->pivot->cantidad_ingresada += $cantidad_recepcionada;
                        $producto->stock += $cantidad_recepcionada;

                        // GUIA REMISION BY INGRESO
                        $producto->pivot->guiaingresoalmacen = $request->input('guiaingresoalmacen');

                        // FILLING THE LOGDETALLESINVENTARIOINGRESO TABLE
                        $logdetallesinvingreso = new Logdetallesinvingreso;
                        $logdetallesinvingreso->detalleinventarioingreso_id = $producto->pivot->id;
                        $logdetallesinvingreso->usuario = auth()->user()->name;
                        $logdetallesinvingreso->cantidad_ingresada = $cantidad_recepcionada;
                        $logdetallesinvingreso->guiaingresoalmacen = $request->input('guiaingresoalmacen');

                        $logdetallesinvingreso->save();
                        if ($inventarioingreso->tipomoneda == 'SOLES') {
                            $producto->ultimoprecio = $producto->pivot->precio;
                        } else {
                            $producto->ultimoprecio = $producto->pivot->precio * $inventarioingreso->cambio_dolar_precio_venta;
                        }
                        $producto->pivot->save();
                        $producto->save();

                        // logic to calculate the mean in for cardex
                        // RETRIEVE ALL THE HISTORIC DETALLES
                        $detallesinvetarioingresodeesteproducto = Detalleinventarioingreso::where('producto_id', $producto->id)->get();
                        $sum = 0;
                        $cantidad = 0;

                        foreach ($detallesinvetarioingresodeesteproducto as $productodetallehistorico) {
                            // MAKES CONVERSION IF NECESSARY
                            if ($productodetallehistorico->inventarioingreso->tipomoneda == 'SOLES') {
                                $sum += ($productodetallehistorico->precio * $productodetallehistorico->cantidad_ingresada);
                            } else {
                                $sum += ($productodetallehistorico->precio * $productodetallehistorico->cantidad_ingresada * $productodetallehistorico->inventarioingreso->cambio_dolar_precio_venta);
                            }

                            $cantidad += $productodetallehistorico->cantidad_ingresada;
                        }

                        if ($cantidad != 0) {
                            $producto->precio = $sum / $cantidad;
                        } else {
                            // Handle division by zero error here if necessary
                        }
                    }

                    $producto->pivot->save();
                    $producto->save();

                    // logic to close the states of the detalles
                    if ($producto->pivot->cantidad == $producto->pivot->cantidad_ingresada) {
                        $producto->pivot->estado = 'INGRESADO';
                    }

                    // LOGIC TO MODIFY THE STATE OF THE INVENTARIOINGRESO
                    if ($producto->pivot->estado != 'INGRESADO') {
                        $closing = false;
                    }

                    $producto->pivot->save();
                }

                if ($closing == true) {
                    $inventarioingreso->estado = 'INGRESADO AL ALMACEN';
                }

                $inventarioingreso->usuario_recepcionista = auth()->user()->name;
                $inventarioingreso->save();

                return redirect()->route('inventarioingresos.index')->with('actualizar-recepcion', 'Recepción exitosa de productos.');
            }
        } catch (ValidationException $e) {
            // If validation fails during the transaction, handle the error
            return back()->withInput()->withErrors($e->validator->errors())->with('error', 'Error al cancelar la orden al crédito.');
        } catch (\Exception $e) {
            // Handle other unexpected errors
            return back()->withInput()->with('error', 'Error al procesar la solicitud.' . $e->getMessage());
        }
    }



    public function prnpriview(string $id)
    {

        $inventarioingreso = Inventarioingreso::with('productos')->findOrFail($id);


        return view('inventarioingresos.printticket', compact('inventarioingreso'));
    }


    public function anular(string $id)
    {
        DB::beginTransaction();
        try {
            $inventarioingreso = Inventarioingreso::findOrFail($id);
            
            foreach($inventarioingreso->productos as $producto){
                if($producto->stock < $producto->pivot->cantidad_ingresada){
                    throw new HttpException(403, 'NO HAY SUFICIENTES PRODUCTOS EN EL ALMACEN PARA CANCELAR LA ORDEN.');
                }


                $producto->stock -= $producto->pivot->cantidad_ingresada;
                $producto->save();
            }

            $inventarioingreso->estado = 'ANULADO';
            $inventarioingreso->save();
            DB::commit();
            return redirect()->route('inventarioingresos.index')->with('anular-orden-compra', 'Orden de compra anulada con éxito.');

        } catch (QueryException $e) {
            DB::rollBack();

            return redirect()->back()->with('error', 'Error desconocido');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
        }
    }


    //excel for detalles of this
    public function export_excel()
    {
        return Excel::download(new DetalleInventarioIngresoExport, 'detalleordenesdecompra.xlsx');
    }

    public function getProductByBarcode($barcode)
    {
        $product = Producto::where('barcode', $barcode)->first();

        if ($product) {
            return response()->json(['success' => true, 'product' => $product]);
        } else {
            return response()->json(['success' => false, 'message' => 'Product not found']);
        }
    }

    public function getProductImageByProduct($product)
    {
        $product = Producto::with('unidad')->find($product);


        if ($product) {
            return response()->json(['success' => true, 'product' => $product]);
        } else {
            return response()->json(['success' => false, 'message' => 'Product not found']);
        }
    }

    public function getSellingPrice(Request $request)
    {
        try {
            $token = env('APIS_TOKEN');
            $fecha = $request->input('fecha'); // Get the selected date from the request

            // Make API call to get selling price
            $curl = curl_init();

            curl_setopt_array($curl, [
                CURLOPT_URL => 'https://api.apis.net.pe/v2/sunat/tipo-cambio?date=' . $fecha,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_SSL_VERIFYPEER => 0,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 2,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'GET',
                CURLOPT_HTTPHEADER => ['Referer: https://apis.net.pe/tipo-de-cambio-sunat-api', 'Authorization: Bearer ' . $token],
            ]);

            $response = curl_exec($curl);

            if ($response === false) {
                throw new Exception('Error fetching data from the API');
            }

            curl_close($curl);

            // Decode API response and extract selling price
            $tipoCambioSunat = json_decode($response);
            $precio_venta = $tipoCambioSunat->precioVenta;

            // Return selling price as JSON response
            return response()->json(['precio_venta' => $precio_venta]);
        } catch (Exception $e) {
            // Handle exception
            // Log error or show a friendly message to the user
            // You might want to return an error response
            return response()->json(['error' => 'Error fetching data from the API'], 500);
        }
    }





    //search ingreso
    public function searchIngreso(Request $request)
    {
        $searchString = $request->search_string;

        $inventarioingresos = Inventarioingreso::where('comprobante_correlativo', 'like', '%' . $searchString . '%')
            ->orWhereHas('productos', function ($query) use ($searchString) {
                $query->where('nombre_producto', 'like', '%' . $searchString . '%');
            })
            ->orderBy('id', 'desc')
            ->paginate(100);

        return view('inventarioingresos.search-results', compact('inventarioingresos'));
    }
}
