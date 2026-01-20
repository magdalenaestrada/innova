@extends('admin.layout')
@push('css')
    <link rel="stylesheet" href="{{ asset('css/areas.css') }}">
    <link rel="stylesheet" href="{{ asset('css/productos.css') }}">
    <style>
        .badge-custom-style {
            font-size: 1vh;
            padding: 0.4em 0.4em;
            border-radius: 0.25rem;
        }

        .tr-etiqueta {
            position: relative;
            background-color: var(--row-bg, transparent);
            transition: background-color 0.2s ease;
        }

        .tr-etiqueta::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            width: 6px;
            height: 100%;
            background-color: var(--row-color, transparent);
            border-radius: 4px 0 0 4px;
        }

        .tr-etiqueta:hover {
            background-color: var(--row-hover, rgba(0,0,0,0.03));
        }
    </style>
@endpush

@section('content')
    <div class="container">

        <br>
        {{-- Alertas --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert">&times;</button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
                <button type="button" class="close" data-dismiss="alert">&times;</button>
            </div>
        @endif

        {{-- Cabecera --}}
        <div class="row d-flex justify-content-between align-items-center">
            {{-- audio --}}
            {{-- <audio id="miAudio">
                <source src="{{ asset('images/Se Encendio el Beeper.mp3') }}" type="audio/mpeg">
                Tu navegador no soporta el elemento de audio.
            </audio> --}}
            <div class="loader">
                {{ __('GARITA DE CONTROL') }}
            </div>
            <div class="text-right col-md-6" id="turno-btn">
                @php
                    $puedeCerrar = false;
                    if($turnoActivo) {
                        $inicio = \Carbon\Carbon::parse($turnoActivo->fecha);
                        $finProgramado = ($turnoActivo->turno == 0) 
                            ? $inicio->copy()->setTime(19, 0, 0) 
                            : $inicio->copy()->addDay()->setTime(7, 0, 0);
                            
                        // Margen de tolerancia: ej. permitir cerrar 15 min antes si quieres
                        // $puedeCerrar = \Carbon\Carbon::now()->gte($finProgramado); 
                        
                        // Si es admin
                        if(Auth::user()->can('end cg-turn early')) {
                            $puedeCerrar = true;
                        }
                    }
                @endphp
                @if ($turnoActivo)
                    <a href="#" data-toggle="modal" data-target="#ModalCreate">
                        <button class="button-create">
                            <i class="fas fa-sign-in-alt"></i> {{ __('REGISTRAR MOVIMIENTO') }}
                        </button>
                    </a>
                    @if($puedeCerrar)
                        <button class="btn btn-danger" id="btn-endturn">
                            <i class="fas fa-stop"></i> {{ __('FINALIZAR TURNO') }}
                        </button>
                    @else
                        <button class="btn btn-secondary" disabled title="El turno finaliza a las {{ $finProgramado->format('H:i') }}">
                            <i class="fas fa-lock"></i> {{ __('TURNO TERMINA A LAS ') }} {{ $finProgramado->format('H:i') }}
                        </button>
                    @endif
                @else
                    @if (Auth::user()->can('start cg-turn'))
                        <a class="" href="#" data-toggle="modal" data-target="#ModalLogin">
                            <button class="button-create">
                                {{ __('INICIAR TURNO') }}
                            </button>
                        </a>
                    @else
                        <button class="btn btn-secondary" disabled title="No tienes permiso para iniciar un turno">
                            <i class="fas fa-lock"></i> {{ __('SIN TURNO ACTIVO') }}
                        </button>
                    @endif
                @endif
            </div>
            <div class="text-right col-md-6" id="turno-container">
            </div>
        </div>
        <br>
        {{-- Card de Turno Activo --}}
        @if ($turnoActivo)
            <div class="row justify-content-center mb-3">
                <div class="col-md-12">
                    <div class="card border-success">
                        <div class="card-header bg-success text-white">
                            <h6 class="mb-0">
                                <i class="fas fa-clock"></i> Turno Activo - {{ Auth::user()->name }}
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3">
                                    <strong>UNIDAD:</strong><br>
                                    {{ $turnoActivo->unidad }}
                                </div>
                                <div class="col-md-3">
                                    <strong>TURNO:</strong><br>
                                    {{ $turnoActivo->turno == 0 ? 'Día (7:00 - 19:00)' : 'Noche (19:00 - 7:00)' }}
                                </div>
                                <div class="col-md-3">
                                    <strong>FECHA:</strong><br>
                                    {{ \Carbon\Carbon::parse($turnoActivo->fecha)->format('d/m/Y') }}
                                </div>
                                <div class="col-md-3">
                                    <strong>HORA INICIO:</strong><br>
                                    {{ $turnoActivo->hora_inicio ? \Carbon\Carbon::parse($turnoActivo->hora_inicio)->format('H:i') : 'N/A' }}
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-md-12">
                                    <strong>CARGOS/ELEMENTOS:</strong><br>
                                    @if ($turnoActivo->cargos->count() > 0)
                                        @foreach ($turnoActivo->cargos as $cargo)
                                            <span class="badge badge-info">
                                                {{ $cargo->pivot->cantidad }}x {{ $cargo->nombre_producto }}
                                            </span>
                                        @endforeach
                                    @else
                                        <span class="text-muted">Sin elementos</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
        {{-- Tabla --}}
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="row align-items-center mb-3">
                        <div class="col-md-6">
                            <input style="margin: 20px 0 0 20px" type="text" name="searcht" id="cg-search"
                                class="input-search form-control" placeholder="BUSCAR AQUÍ...">
                        </div>
                    </div>
                    <div class="col-md-6 text-left" style="margin: 20px 0 0 20px;">
                        <div class="btn-group">
                            <button id="bt-filter-table-in" class="btn btn-outline-success btn-sm">
                                <i class="fas fa-sign-in-alt"></i> ENTRADA
                            </button>
                            <button id="bt-filter-table-out" class="btn btn-outline-secondary btn-sm">
                                <i class="fas fa-sign-out-alt"></i> SALIDA
                            </button>
                            <button class="btn btn-sm btn-info btn-lbl-clr-cliente" 
                                data-toggle="modal" data-target="#ModalEtiqueta">
                                <i class="fas fa-tag"></i> Etiquetas
                            </button>
                        </div>
                        @include('controlgarita.modal.etiqueta')
                    </div>
                    <div class="card-body">
                        <table id="det-control-garita-table" class="table table-hover">
                            <thead>
                                <tr>
                                    <th scope="col"> {{ __('') }} </th>
                                    <th scope="col"> {{ __('ID') }} </th>
                                    <th scope="col"> {{ __('E/S') }} </th>
                                    <th scope="col"> {{ __('HORA') }} </th>
                                    <th scope="col"> {{ __('TIPO') }} </th>
                                    <th scope="col"> {{ __('IDENTIFICACION') }} </th>
                                    <th scope="col"> {{ __('NOMBRE') }} </th>
                                    <th scope="col"> {{ __('OCURRENCIA') }} </th>
                                    <th scope="col"> {{ __('ACCION') }} </th>
                                </tr>
                            </thead>

                            <tbody style="font-size: 13px">
                                @if (count($detalles) > 0)
                                    @foreach ($detalles as $detalle)
                                        @php
                                            $ultimoTurnoActivo =
                                                $turnoActivo && $detalle->control_garita_id == $turnoActivo->id;
                                            $color = $detalle->etiqueta ? $detalle->etiqueta->color : '#ffffff';
                                        @endphp
                                        <tr class="
                                            {{ $ultimoTurnoActivo ? 'turno-actual' : 'turno-pasado' }}
                                            {{ $color ? 'tr-etiqueta' : '' }}
                                            " data-tipo="{{ $detalle->tipo_movimiento }}"
                                            style="{{ $color ? "--row-color: {$color}; --row-bg: {$color}15; --row-hover: {$color}25;" : '' }}">
                                            <td scope="row">
                                                {{ $detalle->id }}
                                            </td>
                                            <td scope="row">
                                                @if ($detalle->tipo_movimiento === 'E')
                                                    <span class="badge badge-success badge-custom-style">ENTRADA</span>
                                                @else
                                                    <span class="badge badge-secondary badge-custom-style">SALIDA</span>
                                                @endif
                                            </td>
                                            <td scope="row">
                                                {{ $detalle->hora ? \Carbon\Carbon::parse($detalle->hora)->format('H:i') : 'N/A' }}
                                            </td>
                                            <td scope="row">
                                                @if ($detalle->tipo_entidad === 'V')
                                                    <span class="badge badge-secondary badge-custom-style">VEHÍCULO</span>
                                                @else
                                                    <span class="badge badge-primary badge-custom-style">PERSONA</span>
                                                @endif
                                            </td>
                                            <td scope="row">
                                                @if ($detalle->placa)
                                                    <span class="badge badge-secondary badge-custom-style">{{ strtoupper($detalle->placa) }}</span>
                                                @else
                                                    <span class="badge badge-primary badge-custom-style">{{ strtoupper($detalle->documento) }}</span>
                                                @endif
                                            </td>
                                            <td scope="row">
                                                {{ strtoupper($detalle->nombre) }}
                                            </td>
                                            <td scope="row">
                                                {{ Str::limit(strtoupper($detalle->ocurrencias), 50) }}
                                            </td>
                                            <td class="btn-group align-items-center">
                                                @if (Auth::user()->can('edit cg-register'))
                                                    <button class="btn btn-sm btn-warning btn-editar-detalle"
                                                        data-toggle="modal" data-target="#ModalEdit{{ $detalle->id }}">
                                                        <i class="fas fa-edit"></i>
                                                    </button>

                                                    @include('controlgarita.modal.edit', ['detalle' => $detalle])
                                                @else
                                                    <button class="btn btn-sm btn-secondary" disabled>
                                                        <i class="fas fa-lock"></i>
                                                    </button>
                                                @endif
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
    @include('controlgarita.modal.login')
    @if($turnoActivo)
        @include('controlgarita.modal.create')
    @endif
@endsection

@section('js')
    <script type="text/javascript">
        $(() => {
            $('#cg-search').on('input', function(e) {
                e.preventDefault();
                let search_string = $(this).val();
                $.ajax({
                    url: "{{ route('detcontrolgarita.search') }}",
                    method: 'GET',
                    data: {
                        search_string: search_string
                    },
                    success: function(response) {
                        console.log('1', 'API Response:', response);
                        $('#det-control-garita-table tbody').html(response);
                    },
                    error: function(xhr, status, error) {
                        console.error('AJAX Error:', error);
                    }
                });
            });
            
            $('#btn-endturn').on('click', function() {

                Swal.fire({
                    title: '¿Finalizar turno?',
                    text: 'Se cerrará tu turno actual y no podrás registrar más ocurrencias',
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Sí, finalizar',
                    cancelButtonText: 'Cancelar',
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                }).then((result) => {
                    console.log(result);
                    if (result.value) {
                        console.log('Confirmado');
                        $.ajax({
                            url: "{{ route('controlgarita.finalizar') }}",
                            type: 'POST',
                            data: {
                                _token: '{{ csrf_token() }}'
                            },
                            success: function(response) {
                                Swal.fire({
                                    type: 'success',
                                    title: 'Turno finalizado',
                                    text: 'Tu turno ha sido cerrado exitosamente',
                                    timer: 2000,
                                    showConfirmButton: false
                                }).then(() => {
                                    window.location.reload();
                                });
                            },
                            error: function(xhr) {
                                Swal.fire({
                                    type: 'error',
                                    title: 'Error',
                                    text: xhr.responseJSON?.message ||
                                        'Error al finalizar turno',
                                });
                            }
                        });
                    }
                });
            });

            function filtrarTabla(tipo) {
                $('#det-control-garita-table tbody tr').each(function () {
                    const tipoFila = $(this).data('tipo');

                    if (tipoFila === tipo) {
                        $(this).show();
                    } else {
                        $(this).hide();
                    }
                });
            }

            $('#bt-filter-table-in').on('click', function() {
                filtrarTabla('E');
            });
            $('#bt-filter-table-out').on('click', function() {
                filtrarTabla('S');
            });

            setTimeout(function() {
                $('.alert').fadeOut('slow');
            }, 1500);
        })
    </script>
    @if (session('error'))
        <script>
            $(document).ready(function() {
                Swal.fire({
                    type: 'error',
                    title: 'Error',
                    text: "{{ session('error') }}"
                });
            });
        </script>
    @endif
@endsection
