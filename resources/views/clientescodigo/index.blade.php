@extends('admin.layout')

@section('content')
    <div class="container">

        <br>
        <div class="row d-flex justify-content-between align-items-center">
            <div class="loader">
                {{ __('CODIGOS DE REGISTRADOS') }}
            </div>
            <div class="text-right col-md-6">
                <a class="" href="#" data-toggle="modal" data-target="#ModalCreate">
                    <button class="button-create">
                        {{ __('GENERAR CODIGO') }}
                    </button>
                </a>
            </div>
        </div>
        <br>
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="row">
                        <div class="col-md-6">
                            <input type="text" name="searcht" id="searcht" class="input-search form-control"
                                placeholder="BUSCAR AQUÍ...">
                        </div>
                    </div>
                    <div class="card-body">
                        <table id="docs-code-table" class="table table-striped">
                            <thead>
                                <tr>
                                    <th scope="col">
                                        {{ __('ID') }}
                                    </th>
                                    <th scope="col">
                                        {{ __('TIPO DOCUMENTO') }}
                                    </th>
                                    <th scope="col">
                                        {{ __('CODIGO') }}
                                    </th>
                                    <th scope="col">
                                        {{ __('DOCUMENTO') }}
                                    </th>
                                    <th scope="col">
                                        {{ __('NOMBRE') }}
                                    </th>
                                    <th scope="col">
                                        {{ __('ACCION') }}
                                    </th>
                                </tr>
                            </thead>

                            <tbody style="font-size: 13px">
                                @if (count($codigos) > 0)
                                    @foreach ($codigos as $cliente)
                                        <tr>
                                            <td scope="row">
                                                {{ $cliente->id }}
                                            </td>
                                            <td scope="row">
                                                @if ($cliente->tipo_documento == 1)
                                                    DNI
                                                @else
                                                    RUC
                                                @endif
                                            </td>
                                            <td scope="row">
                                                {{ strtoupper($cliente->codigo) }}
                                            </td>
                                            <td scope="row">
                                                {{ strtoupper($cliente->documento) }}
                                            </td>
                                            <td scope="row">
                                                {{ strtoupper($cliente->nombre) }}
                                            </td>

                                            <td class="btn-group">
                                                <button class="btn btn-sm btn-warning btn-editar-cliente"
                                                    data-toggle="modal" data-target="#ModalEdit{{ $cliente->id }}">
                                                    <i class="fas fa-edit"></i>
                                                </button>

                                                @include('clientescodigo.modal.edit', [
                                                    'id' => $cliente->id,
                                                ])

                                                <button class="btn btn-sm btn-danger btn-eliminar-cliente"
                                                    data-url="{{ route('clientescodigo.destroy', $cliente->id) }}">
                                                    <i class="fa fa-trash"></i>
                                                </button>

                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="9" class="text-center text-muted">
                                            {{ __('NO HAY DATOS DISPONIBLES') }}
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('clientescodigo.modal.create')

@section('js')
    <script type="text/javascript">
        $(document).ready(function() {
            $('#searcht').on('input', function(e) {
                e.preventDefault();
                let search_string = $(this).val();
                $.ajax({
                    url: "{{ route('clientescodigo.search') }}",
                    method: 'GET',
                    data: {
                        search_string: search_string
                    },
                    success: function(response) {
                        console.log('1', 'API Response:', response);
                        $('#docs-code-table tbody').html(response);
                    },
                    error: function(xhr, status, error) {
                        console.error('AJAX Error:', error);
                    }
                });
            });
        });


        $(document).ready(function() {
            $('.crear-empleado').on('submit', function(e) {
                e.preventDefault(); // prevenimos el envío hasta que se valide

                var tipoDocumento = $('select[name="tipo_documento"]').val(); // 1=DNI, 2=RUC
                var documento = $('#documento').val().trim();
                var nombre = $('#nombre').val().trim();
                var longitud = documento.length;

                // Validación de longitud
                if ((tipoDocumento == 1 && longitud != 8) || (tipoDocumento == 2 && longitud != 11)) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Documento inválido',
                        text: tipoDocumento == 1 ?
                            'El DNI debe tener 8 dígitos.' : 'El RUC debe tener 11 dígitos.'
                    });
                    return false; // detener envío
                }

                // Validación de campos vacíos (opcional)
                if (!documento || !nombre) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Campos vacíos',
                        text: 'Todos los campos son obligatorios.'
                    });
                    return false;
                }

                // Validación de documento único vía AJAX
                $.ajax({
                    url: '{{ route('clientescodigo.validarDocumento') }}', // Ruta que validarás en el backend
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        documento: documento
                    },
                    success: function(response) {
                        if (response.exists) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Documento duplicado',
                                text: 'Este cliente ya tiene un código asignado.'
                            });
                            return false;
                        } else {
                            // Si todo está bien, enviar el formulario
                            e.currentTarget.submit();
                        }
                    },
                    error: function() {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'No se pudo validar el documento.'
                        });
                    }
                });
            });
        });


        $(document).on('click', '.btn-eliminar-cliente', function() {
            let url = $(this).data('url');
            let $row = $(this).closest('tr'); // Guardar referencia a la fila

            Swal.fire({
                title: '¿Eliminar cliente?',
                text: 'Esta acción no se puede deshacer',
                type: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        url: url,
                        type: 'DELETE',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            // Remover la fila de la tabla
                            $row.fadeOut(400, function() {
                                $(this).remove();

                                // Verificar si quedan filas en la tabla
                                if ($('#docs-code-table tbody tr').length === 0) {
                                    $('#docs-code-table tbody').html(
                                        '<tr> <
                                        td colspan = "6"
                                        class = "text-center text-muted" >
                                        NO HAY DATOS DISPONIBLES < /td> <
                                        /tr>'
                                    );
                                }
                            });

                            Swal.fire({
                                type: 'success',
                                title: '¡Eliminado!',
                                text: response.message,
                                timer: 1500,
                                showConfirmButton: false
                            });
                        },
                        error: function(xhr, status, error) {
                            Swal.fire({
                                type: 'error',
                                title: 'Error',
                                text: 'No se pudo eliminar el cliente',
                                showConfirmButton: true
                            });
                        }
                    });
                }
            });
        });
    </script>
@endsection
@endsection
