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
                {{ __('GARITA DE CONTROL') }}
            </div>
            <div class="text-right col-md-6">
                <a class="" href="#" data-toggle="modal" data-target="#ModalCreate">
                    <button class="button-create">
                        {{ __('REGISTRAR SALIDA') }}
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
                                        {{ __('HORA') }}
                                    </th>
                                    <th scope="col">
                                        {{ __('OCURRENCIA') }}
                                    </th>
                                    {{-- <th scope="col">
                                        {{ __('DOCUMENTO') }}
                                    </th>
                                    <th scope="col">
                                        {{ __('NOMBRE') }}
                                    </th> --}}
                                    <th scope="col">
                                        {{ __('ACCION') }}
                                    </th>
                                </tr>
                            </thead>

                            {{-- <tbody style="font-size: 13px">
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
                                               <button class="btn btn-sm btn-warning btn-editar-cliente"
                                                    data-toggle="modal" data-target="#ModalEdit{{ $cliente->id }}">
                                                    <i class="fas fa-edit"></i>
                                                </button>

                                                @include('clientescodigo.modal.edit', [
                                                    'id' => $cliente->id,
                                                ])
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
                            </tbody> --}}
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- @include('clientescodigo.modal.create') --}}

@section('js')
    <script type="text/javascript">
        // $(document).ready(function() {
        //     $('#searcht').on('input', function(e) {
        //         e.preventDefault();
        //         let search_string = $(this).val();
        //         $.ajax({
        //             url: "{{ route('clientescodigo.search') }}",
        //             method: 'GET',
        //             data: {
        //                 search_string: search_string
        //             },
        //             success: function(response) {
        //                 console.log('1', 'API Response:', response);
        //                 $('#docs-code-table tbody').html(response);
        //             },
        //             error: function(xhr, status, error) {
        //                 console.error('AJAX Error:', error);
        //             }
        //         });
        //     });
        // });

        // $(document).on('click', '.btn-eliminar-cliente', function() {
        //     let url = $(this).data('url');
        //     let $row = $(this).closest('tr'); // Guardar referencia a la fila

        //     Swal.fire({
        //         title: '¿Eliminar cliente?',
        //         text: 'Esta acción no se puede deshacer',
        //         type: 'warning',
        //         showCancelButton: true,
        //         confirmButtonText: 'Sí, eliminar',
        //         cancelButtonText: 'Cancelar'
        //     }).then((result) => {
        //         if (result.value) {
        //             $.ajax({
        //                 url: url,
        //                 type: 'DELETE',
        //                 data: {
        //                     _token: '{{ csrf_token() }}'
        //                 },
        //                 success: function(response) {
        //                     // Remover la fila de la tabla
        //                     $row.fadeOut(400, function() {
        //                         $(this).remove();

        //                         // Verificar si quedan filas en la tabla
        //                         if ($('#docs-code-table tbody tr').length === 0) {
        //                             $('#docs-code-table tbody').html(
        //                                 '<tr><td colspan="6" class="text-center text-muted">NO HAY DATOS DISPONIBLES</td></tr>'
        //                             );
        //                         }
        //                     });

        //                     Swal.fire({
        //                         type: 'success',
        //                         title: '¡Eliminado!',
        //                         text: response.message,
        //                         timer: 1500,
        //                         showConfirmButton: false
        //                     });
        //                 },
        //                 error: function(xhr, status, error) {
        //                     Swal.fire({
        //                         type: 'error',
        //                         title: 'Error',
        //                         text: 'No se pudo eliminar el cliente',
        //                         showConfirmButton: true
        //                     });
        //                 }
        //             });
        //         }
        //     });
        // });
    </script>
@endsection
@endsection