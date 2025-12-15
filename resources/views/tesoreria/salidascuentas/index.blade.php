@extends('admin.layout')

@push('css')
    <link rel="stylesheet" href="{{ asset('css/areas.css') }}">
    <link rel="stylesheet" href="{{ asset('css/modals.css') }}">
    <link rel="stylesheet" href="{{ asset('css/productos.css') }}">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
@endpush

@section('content')
    <div class="container">
        <br>
        <div class="row d-flex justify-content-between align-items-center">
            <div class="loader col-md-6">
                {{ __('SALIDAS DE LAS CUENTAS REGISTRADAS') }}
            </div>
            @can('edit cuenta')
                <div class="text-right col-md-4">
                    <a href="#" data-toggle="modal" data-target="#ModalCreate">
                        <button class="button-create">
                            {{ __('REGISTRAR SALIDA DE LA CUENTA') }}
                        </button>
                    </a>
                </div>
            @endcan
        </div>

        <br>
        @php $today = \Carbon\Carbon::today()->format('Y-m-d'); @endphp

        <div class="row">
            <div class="col-md-3">
                <label>BUSQUEDA:</label>
                <input type="text" id="search" class="form-control" placeholder="Buscar Aquí...">
            </div>
            <div class="col-md-3">
                <label>DESDE:</label>
                <input type="date" id="desde" class="form-control" value="{{ $today }}">
            </div>
            <div class="col-md-3">
                <label>HASTA:</label>
                <input type="date" id="hasta" class="form-control" value="{{ $today }}">
            </div>
        
        </div>

        <br>
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="salidascuentas-table" class="table table-striped w-100">
                        <thead>
                            <tr class="text-center">
                                <th>ID</th>
                                <th>Fecha</th>
                                <th>Tipo</th>
                                <th>Tipo Comprobante</th>
                                <th>Nro Comp.</th>
                                <th>Cuenta</th>
                                <th>Motivo</th>
                                <th>Descripción</th>
                                <th>Beneficiario</th>
                                <th>Responsable</th>
                                <th>Monto</th>
                                @can('edit cuenta')
                                    <th>Acción</th>
                                @endcan
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>

        @include('tesoreria.salidascuentas.modal.create')
        @include('tesoreria.salidascuentas.modal.export')
    </div>
@stop

@section('js')
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('js/jquery.printPage.js') }}"></script>
    <script>

$(document).ready(function() {
            function isRucOrDni(value) {
                return value.length === 8 || value.length === 11;
            }

            function buscarDocumento(url, inputId, datosId) {
                var inputValue = $(inputId).val();
                var tipoDocumento = inputValue.length === 8 ? 'dni' : 'ruc';

                $.ajax({
                    url: url,
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        documento: inputValue,
                        tipo_documento: tipoDocumento
                    },
                    success: function(response) {
                        console.log('API Response:', response);
                        if (tipoDocumento === 'dni') {
                            $(datosId).val(response.nombres + ' ' + response.apellidoPaterno + ' ' +
                                response.apellidoMaterno);
                        } else {
                            $(datosId).val(response.razonSocial);
                        }
                        $(datosId).removeClass('is-invalid').addClass('is-valid');
                    },
                    error: function(xhr, status, error) {
                        console.log(xhr.responseText);
                        $(datosId).val('');
                        $(datosId).removeClass('is-valid').addClass('is-invalid');
                    }
                });
            }



            $('#buscar_beneficiario_btn').click(function() {
                buscarDocumento('{{ route('buscar.documento') }}', '#documento',
                    '#nombre');
            });


            // Input validation
            $('.documento-input').on('input', function() {
                var value = $(this).val();
                var isValid = isRucOrDni(value);
                $(this).toggleClass('is-valid', isValid);
                $(this).toggleClass('is-invalid', !isValid);
            });

            $('.datos-input').on('input', function() {
                var value = $(this).val();
                $(this).toggleClass('is-valid', value.trim().length > 0);
                $(this).toggleClass('is-invalid', value.trim().length === 0);
            });
        });
    

        $(document).ready(function () {
            $('.btnprn').printPage();

            let table = $('#salidascuentas-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{ route("salidascuentas.datatable") }}',
                    data: function (d) {
                        d.search_string = $('#search').val();
                        d.desde = $('#desde').val();
                        d.hasta = $('#hasta').val();
                    }
                },
                columns: [
                    { data: 'id' },
                    { data: 'fecha' },
                    { data: 'tipo' },
                    { data: 'tipocomprobante' },
                    { data: 'comprobante_correlativo' },
                    { data: 'cuenta' },
                    { data: 'motivo' },
                    { data: 'descripcion' },
                    { data: 'beneficiario' },
                    { data: 'creador' },
                    { data: 'monto' },
                    @can('edit cuenta')
                        { data: 'acciones', orderable: false, searchable: false }
                    @endcan
                ]
            });

            $('#search, #desde, #hasta').on('input change', function () {
                table.draw();
            });

            table.on('draw', function () {
                $('.btnprn').printPage();

                $('.eliminar-registro').off('submit').on('submit', function(e) {
                    e.preventDefault();
                    Swal.fire({
                        title: '¿Eliminar?',
                        text: '¿Estás seguro que deseas eliminar esta salida?',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Sí, eliminar',
                        cancelButtonText: 'Cancelar'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            this.submit();
                        }
                    });
                });
            });

            $('#exportExcelLink').click(function (e) {
                e.preventDefault();
                const query = `?string=${$('#search').val()}&desde=${$('#desde').val()}&hasta=${$('#hasta').val()}`;
                window.location.href = '{{ route('tssalidascuentas.export-excel') }}' + query;
            });
        });
    </script>
@stop
