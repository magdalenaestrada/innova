<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>print</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>

<body>
    <br>
    <div class="container">
        <div class="text-end">
            <img style="width: 100px" src="{{asset('images/alfalogo.png')}}" alt="">
        </div>

        <div class="text-center" style="margin-top: -10%">
            <h1>ORDEN DE COMPRA</h1>

            <br>
        </div>
        <hr style="margin-bottom: 3.5px">
        <div class="d-flex align-items-center justify-content-between">
            <h6><strong>ORDEN:</strong> AF-{{ $inventarioingreso->id }}</h6> 
            <H6>Nasca, {{ $inventarioingreso->created_at->day }} de {{ \Illuminate\Support\Str::ucfirst($inventarioingreso->created_at->translatedFormat('F')) }} del {{ $inventarioingreso->created_at->year }}</H6>

        </div>
           
        <hr style="margin-top: -3px">
        
        
        <div style="font-size: 13.5px">
            <div class="d-flex justify-content-between">
                <p><strong>Ruc cliente: </strong>20606034629 </p>
                <p><strong>Cliente: </strong>MINERA ALFA GOLDEN S.A.C</p>
    
            </div>
    
            <div class="d-flex justify-content-between">
                @if ($inventarioingreso->proveedor)
                    <p><strong>Ruc del proveedor: </strong>{{ $inventarioingreso->proveedor->ruc }}</p>
                    <p><strong>Proveedor: </strong>{{ $inventarioingreso->proveedor->razon_social }}</p>
                @endif
            </div>
    
            <div class="d-flex justify-content-between">
        
                <p><strong>Estado: </strong>{{ $inventarioingreso->estado }}</p>
            </div>
        </div>
       

        <hr>

        <div class="mt-2">
            @if (count($inventarioingreso->productos) > 0)
                <table class="table table-striped table-hover">
                    <thead>
                        <tr class="text-center" style="font-size: 14px">

                            <th scope="col">
                                {{ __('PRODUCTO DEL REQUERIMIENTO') }}
                            </th>
                            <th scope="col">
                                {{ __('CANTIDAD') }}
                            </th>
                            <th scope="col">
                                {{ __('VALOR UNITARIO') }}
                            </th>
                            <th scope="col">
                                {{ __('SUBTOTAL') }}
                            </th>
                            


                            


                        </tr>
                    </thead>
                    <tbody style="font-size: 12px">
                        @foreach ($inventarioingreso->productos as $producto)
                            <tr class="text-center">
                                <td scope="row">
                                    {{ $producto->nombre_producto }}
                                </td>
                                <td scope="row">
                                    {{ $producto->pivot->cantidad }}
                                </td>
                                <td scope="row">
                                    {{ number_format($producto->pivot->precio,2) }}
                                </td>
                                <td scope="row">
                                    {{ $producto->pivot->subtotal }}
                                </td>
                               

                              






                            </tr>
                        @endforeach
                    </tbody>



                </table>
                <hr>
            @endif

            <p class="text-center h6"> SUBTOTAL: {{ number_format($inventarioingreso->subtotal, 2) }} {{ $inventarioingreso->tipomoneda }}</p>
            <p class="text-center h5"> PRECIO TOTAL: {{ number_format($inventarioingreso->total, 2) }} {{ $inventarioingreso->tipomoneda }}</p>
            <hr>

        </div>

        <div class="mt-2">
            @if (count($inventarioingreso->pagosacuenta) > 0)
                <table class="table table-striped table-hover">
                    <thead>
                        <tr class="text-center">

                            <th scope="col">
                                {{ __('FECHA DE PAGO O ADELANTO A CUENTA') }}
                            </th>
                            <th scope="col">
                                {{ __('MONTO') }}
                            </th>
                            <th scope="col">
                                {{ __('COMPROBANTE CORRELATIVO') }}
                            </th>
                           


                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($inventarioingreso->pagosacuenta as $pago)
                            <tr class="text-center">
                                <td scope="row">
                                    {{ $pago->fecha_pago }}
                                </td>
                                <td scope="row">
                                    {{ $pago->monto }}
                                </td>
                                <td scope="row">
                                    {{ $pago->comprobante_correlativo }}
                                </td>
                                






                            </tr>
                        @endforeach
                    </tbody>



                </table>
            @endif


        


    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
</body>

</html>
