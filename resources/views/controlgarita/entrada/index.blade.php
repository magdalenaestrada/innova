@extends('admin.layout')
@push('css')
    <link rel="stylesheet" href="{{ asset('css/areas.css') }}">
    <link rel="stylesheet" href="{{ asset('css/productos.css') }}">
@endpush

@section('content')
    <div class="container">

        <br>
        <div class="row d-flex justify-content-between align-items-center">
            {{-- audio --}}
            {{-- <audio id="miAudio">
                <source src="{{ asset('images/Se Encendio el Beeper.mp3') }}" type="audio/mpeg">
                Tu navegador no soporta el elemento de audio.
            </audio> --}}
            <div class="loader">
                {{ __('GARITA DE CONTROL') }}
            </div>
            <div class="text-right col-md-6" id="turno-active-btn">
                <a class="" href="#" data-toggle="modal" data-target="#ModalCreate">
                    <button class="button-create">
                        {{ __('REGISTRAR ENTRADA') }}
                    </button>
                </a>
            </div>
            {{-- Cambiar boton cuando inicie sesión --}}
            <div class="text-right col-md-6" id="turno-btn">
                <a class="" href="#" data-toggle="modal" data-target="#ModalLogin">
                    <button class="button-create">
                        {{ __('INICIAR TURNO') }}
                    </button>
                </a>
            </div>
        </div>
        <br>

        {{-- Mostrar cuando inicie turno --}}
        <div class="row justify-content-center" id="turno-active-card">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row justify-content-between" style="margin-inline: 0">
                            <div class="form">
                                <label class="form-label fw-semibold">UNIDAD :</label>
                                <label class="form-label fw-semibold">Planta Minera Alfa Golden</label>
                            </div>
                            <div class="form">
                                <label class="form-label fw-semibold">TURNO :</label>
                                <label class="form-label fw-semibold">Día</label>
                            </div>
                            <div class="form">
                                <label class="form-label fw-semibold">HORARIO :</label>
                                <label class="form-label fw-semibold">7:00 - 15:00</label>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="form col-md-12">
                                <label class="form-label fw-semibold">AGENTES :</label>
                                <label class="form-label fw-semibold">Jose Coronado</label> -
                                <label class="form-label fw-semibold">Manuel Sazuedra</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="row">
                        <div class="col-md-6">
                            <input style="margin: 20px 0 0 20px" type="text" name="searcht" id="searcht"
                                class="input-search form-control" placeholder="BUSCAR AQUÍ...">
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
                                        {{ __('TIPO') }}
                                    </th>
                                    <th scope="col">
                                        {{ __('IDENTIFICACION') }}
                                    </th>
                                    <th scope="col">
                                        {{ __('NOMBRE') }}
                                    </th>
                                    <th scope="col">
                                        {{ __('OCURRENCIA') }}
                                    </th>
                                    <th scope="col">
                                        {{ __('ACCION') }}
                                    </th>
                                </tr>
                            </thead>

                            <tbody style="font-size: 13px">
                                @if (count($detalles) > 0)
                                    @foreach ($detalles as $detalle)
                                        <tr>
                                            <td scope="row">
                                                {{ $detalle->id }}
                                            </td>
                                            <td scope="row">
                                                {{ $detalle->hora }}
                                            </td>
                                            <td scope="row">
                                                @if ($detalle->tipo_entidad === 'V')
                                                    <span class="badge badge-primary">VEHÍCULO</span>
                                                @else
                                                    <span class="badge badge-info">PERSONA</span>
                                                @endif
                                            </td>
                                            <td scope="row">
                                                @if ($detalle->placa)
                                                    {{ strtoupper($detalle->placa) }}
                                                @else
                                                    {{ strtoupper($detalle->documento) }}
                                                @endif
                                            </td>
                                            <td scope="row">
                                                {{ strtoupper($detalle->nombre) }}
                                            </td>
                                            <td scope="row">
                                                {{ Str::limit(strtoupper($detalle->ocurrencias), 20) }}
                                            </td>

                                            <td class="btn-group align-items-center">
                                                <button class="btn btn-sm btn-warning btn-editar-detalle"
                                                    data-toggle="modal" data-target="#ModalEdit{{ $detalle->id }}">
                                                    <i class="fas fa-edit"></i>
                                                </button>

                                                {{-- @include('clientescodigo.modal.edit', [
                                                    'id' => $cliente->id,
                                                ]) --}}

                                                {{-- <button class="btn btn-sm btn-danger btn-eliminar-cliente"
                                                    data-url="{{ route('clientescodigo.destroy', $cliente->id) }}">
                                                    <i class="fa fa-trash"></i>
                                                </button> --}}
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
    @include('controlgarita.entrada.modal.create')
    @include('controlgarita.entrada.modal.login')

@section('js')
    <script type="text/javascript">
        $(document).ready(function() {
            const $element = {
                btn: $('#turno-btn'),
                active: $('#turno-active-btn'),
                card: $('#turno-active-card')
            };

            function setTurnoState(active) {
                $element.btn.toggle(!active);
                $element.active.toggle(active);
                $element.card.toggle(active);
            }

            setTurnoState(false); // Estado inicial: turno no iniciado

            $element.btn.click(() => setTurnoState(true));
        });

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
