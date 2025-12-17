@extends('admin.layout')
@push('css')
    <link rel="stylesheet" href="{{ asset('css/areas.css') }}">
    <link rel="stylesheet" href="{{ asset('css/productos.css') }}">
@endpush

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

                                            <td class="btn-group align-items-center">
                                                <a href="#" data-toggle="modal"
                                                    data-target="#ModalEdit{{ $cliente->id }}" class="ml-1">

                                                    <button class="editBtn" style="margin-left: -30px; margin-top:-3px">
                                                        <svg height="1em" viewBox="0 0 512 512">
                                                            <path
                                                                d="M410.3 231l11.3-11.3-33.9-33.9-62.1-62.1L291.7 89.8l-11.3 11.3-22.6 22.6L58.6 322.9c-10.4 10.4-18 23.3-22.2 37.4L1 480.7c-2.5 8.4-.2 17.5 6.1 23.7s15.3 8.5 23.7 6.1l120.3-35.4c14.1-4.2 27-11.8 37.4-22.2L387.7 253.7 410.3 231zM160 399.4l-9.1 22.7c-4 3.1-8.5 5.4-13.3 6.9L59.4 452l23-78.1c1.4-4.9 3.8-9.4 6.9-13.3l22.7-9.1v32c0 8.8 7.2 16 16 16h32zM362.7 18.7L348.3 33.2 325.7 55.8 314.3 67.1l33.9 33.9 62.1 62.1 33.9 33.9 11.3-11.3 22.6-22.6 14.5-14.5c25-25 25-65.5 0-90.5L453.3 18.7c-25-25-65.5-25-90.5 0zm-47.4 168l-144 144c-6.2 6.2-16.4 6.2-22.6 0s-6.2-16.4 0-22.6l144-144c6.2-6.2 16.4-6.2 22.6 0s6.2 16.4 0 22.6z">
                                                            </path>
                                                        </svg>
                                                    </button>
                                                </a>
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
                                        '<tr><td colspan="6" class="text-center text-muted">NO HAY DATOS DISPONIBLES</td></tr>'
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
