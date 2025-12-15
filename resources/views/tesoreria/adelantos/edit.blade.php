@extends('admin.layout')

@push('css')
    <link rel="stylesheet" href="{{ asset('css/areas.css') }}">
    <link rel="stylesheet" href="{{ asset('css/productos.css') }}">
@endpush

@section('content')
    <br>
            <div class="modal-content">


                <div class="card-header row justify-content-between">
                    <div class="col-md-6">
                        <h6 class="mt-2">
                            {{ __('EDITAR ADELANTO') }}
                        </h6>
                    </div>
                    <div class="col-md-6 text-right">
                        <a href="{{route('lqliquidaciones.index')}}">
                            <button type="btn btn-danger btn-sm"
                            >
                            VOLVER
                        </button>
                        </a>
                        
                    </div>
                </div>

                <div class="card-body">
                    <form class="editar-adelanto" action="{{ route('lqadelantos.update', $adelanto->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="row">



                            <div class="row">




                                <div class="form-group col-md-12 g-3">
                                    <label for="sociedad" class="text-sm">
                                        {{ __('SOCIEDAD') }}
                                    </label>
                                    <br>
                                    <select id="sociedad-edit_id" name="sociedad_id"
                                        class="form-control buscador form-control-sm">
                                        <!-- Default selected option -->
                                        <option selected value="{{ $adelanto->sociedad->id }}">
                                            {{ $adelanto->sociedad->id }}. {{ $adelanto->sociedad->nombre }}
                                        </option>

                                        <!-- Loop through $cuentas, excluding the selected account -->

                                        @foreach ($sociedades as $sociedad)
                                            @if ($sociedad->id != $adelanto->sociedad->id)
                                                <option value="{{ $sociedad->id }}">
                                                    {{ $sociedad->id }}. {{ $sociedad->nombre }}
                                                </option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>






                                <div class="form mb-1 col-md-4">
                                    <input disabled name="documento" class="form-control form-control-sm"
                                        placeholder="DOCUMENTO DEL REPRESENTANTE..." type="text"
                                        value="{{ $adelanto->representante_cliente_documento }}">
                                    <span class="input-border"></span>
                                </div>

                                <div class="form col-md-8 mb-2">
                                    <input disabled name="nombre" class="form-control form-control-sm"
                                        placeholder="NOMBRE DEL REPRESENTANTE DEL CLIENTE..." type="text"
                                        value="{{ $adelanto->representante_cliente_nombre }}">
                                    <span class="input-border"></span>
                                </div>



                                <div class="form-group col-md-4 g-3">
                                    <label for="tipo_comprobante" class="text-sm">
                                        {{ __('TIPO DE COMPROBANTE') }}
                                    </label>
                                    <br>
                                    <input disabled name="tipocomprobante" class="form-control form-control-sm"
                                        placeholder="TIPO DE COMPROBANTE..." type="text"
                                        value="{{ $adelanto->salidacuenta->tipocomprobante ? $adelanto->salidacuenta->tipocomprobante->nombre : '' }}">
                                </div>


                                <div class="form col-md-4  mb-3">
                                    <label for="comprobante_correlativo" class="text-sm">
                                        {{ __('NRO COMPROBANTE') }}
                                    </label>
                                    <input disabled name="comprobante_correlativo" class="form-control form-control-sm"
                                        placeholder="INGRESE EL CORRELATIVO DEL COMPROBANTE" type="text"
                                        value="{{ $adelanto->salidacuenta ? $adelanto->salidacuenta->comprobante_correlativo : '' }}">
                                    <span class="input-border"></span>
                                </div>



                                <div class="form-group col-md-4 g-3">
                                    <label for="cliente" class="text-sm">
                                        {{ __('RAZON SOCIAL FACTURA') }}
                                    </label>
                                    <br>
                                    <input disabled name="comprobante_correlativo" class="form-control form-control-sm"
                                        placeholder="RAZON SOCIAL" type="text"
                                        value="{{ $adelanto->cliente ? $adelanto->cliente->nombre : '' }}">

                                </div>




                                <div class="form-group col-md-12 g-3">
                                    <label for="cuenta" class="text-sm">
                                        {{ __('CUENTA') }}
                                    </label>
                                    <br>
                                    <select id="cuenta-edit" name="cuenta_id" class="form-control form-control-sm">
                                        <!-- Default selected option -->
                                        <option selected value="{{ $adelanto->salidacuenta->cuenta->id }}">
                                            {{ $adelanto->salidacuenta->cuenta->nombre }}
                                        </option>

                                        <!-- Loop through $cuentas, excluding the selected account -->

                                        @foreach ($cuentas as $cuenta)
                                            @if ($cuenta->id != $adelanto->salidacuenta->cuenta->id)
                                                <option value="{{ $cuenta->id }}">
                                                    {{ $cuenta->nombre }}
                                                </option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-md-9 g-3">
                                    <label for="motivo" class="text-sm">
                                        {{ __('MOTIVO') }}
                                    </label>
                                    <br>
                                    <input disabled name="comprobante_correlativo" class="form-control form-control-sm"
                                        placeholder="RAZON SOCIAL" type="text"
                                        value="{{ $adelanto->salidacuenta->motivo ? $adelanto->salidacuenta->motivo->nombre : '' }}">

                                </div>
                                <div class="form col-md-3 mb-3">
                                    <label for="fecha" class="text-sm">
                                        {{ __('FECHA') }}
                                    </label>
                                    <input type="date" name="fecha" class="form-control form-control-sm"
                                        placeholder="INGRESE LA FECHA"
                                        value="{{ $adelanto->fecha ? \Carbon\Carbon::parse($adelanto->fecha)->format('Y-m-d') : '' }}">
                                    <span class="input-border"></span>
                                </div>



                                <div class="form-group col-md-12 g-3">
                                    <label for="descripcion" class="text-sm">
                                        {{ __('DESCRIPCIÓN') }}
                                    </label>
                                    <input name="descripcion"
                                        class="form-control form-control-sm @error('descripcion') is-invalid @enderror"
                                        placeholder="DESCRIPCIÓN..." value="{{ $adelanto->salidacuenta->descripcion }}">
                                    @error('descripcion')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>


                                <div class="form col-md-6 mb-3">
                                    <label for="SOLES" class="text-sm">
                                        {{ __('TOTAL') }}
                                    </label>
                                    <input name="monto" class="form-control form-control-sm"
                                        placeholder="TOTAL EN SOLES" required type="text"
                                        value="{{ $adelanto->salidacuenta ? $adelanto->salidacuenta->monto : '' }}">
                                    <span class="input-border"></span>
                                </div>




                                <div class="form col-md-6 mb-3">
                                    <label for="tipo_cambio" class="text-sm">
                                        {{ __('TIPO DE CAMBIO') }}
                                    </label>
                                    <input name="tipo_cambio" class="form-control form-control-sm"
                                        placeholder="INGRESE EL TIPO DE CAMBIO" required type="text"
                                        value="{{ $adelanto->tipo_cambio }}">
                                    <span class="input-border"></span>
                                </div>
                            </div>








                            <div class="col-md-12 text-right g-3 mt-2">
                                <button type="submit" class="btn btn-secondary btn-sm">
                                    {{ __('Guardar') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>


<br>
            </div>
       



@stop

@section('js')

    <script type="text/javascript" src="{{ asset('js/jquery.printPage.js') }}"></script>


    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.buscador').select2({
                theme: "classic"
            });

        });
    </script>
    <script type="text/javascript">
        $(document).ready(function() {
            $('.btnprn').printPage();

        });
    </script>

    <script>
        @if (session('status'))
            Swal.fire('Éxito', '{{ session('status') }}', 'success');
        @elseif (session('error'))
            Swal.fire('Error', '{{ session('error') }}', 'error');
        @endif
    </script>

    <script type="text/javascript">
        $(document).ready(function() {
            //alert();

            //Search product
            $('#searcha').on('input', function(e) {
                e.preventDefault();
                let search_string = $(this).val();
                $.ajax({
                    url: "{{ route('searcha.lqadelantos') }}",
                    method: 'GET',
                    data: {
                        search_string: search_string
                    },
                    success: function(response) {

                        $('#adelantos-table tbody').html(response);
                    },
                    error: function(xhr, status, error) {}

                });

            });

        });
    </script>

    <script>
        //FINDING DOCUMENTO REPRESENTANTE ADELLANTO FUNCTIONALITY
        $('#nombre').on('input', function(e) {
            e.preventDefault();
            let search_string = $(this).val();
            if (search_string.length >= 10) {
                $.ajax({
                    url: "{{ route('autocomp.representadelanto') }}",
                    method: 'GET',
                    data: {
                        search_string: search_string
                    },
                    success: function(response) {
                        console.log(1)

                        $('#documento').val(response.documento);

                    },
                    error: function(xhr, status, error) {
                        console.error('AJAX Error:', error);
                    }
                });
            }
        });






        $('.eliminar-registro').submit(function(e) {
            e.preventDefault();
            Swal.fire({
                title: '¿Eliminar adelanto?',
                text: 'Esta seguro que quiere eliminar este adelanto?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#0d0',
                confirmButtonText: '¡Sí, continuar!',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    this.submit();
                }
            })
        });
    </script>

    
@stop
