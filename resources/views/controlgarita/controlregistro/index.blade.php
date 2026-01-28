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

        /* Estilo para los botones de radio tipo 'Segmented Control' */
        .btn-group-toggle .btn {
            transition: all 0.2s;
            font-weight: 500;
        }
        .btn-group-toggle .btn.active {
            background-color: #e2e8f0;
            border-color: #cbd5e0;
            color: #2d3748;
            font-weight: 700;
            box-shadow: inset 0 2px 4px rgba(0,0,0,0.06);
        }
        
        /* Clase auxiliar para botones blancos limpios */
        .btn-white {
            background-color: #fff;
            border-color: #ced4da;
        }

        /* Ajuste del botón de Excel tuyo para que no se rompa en flexbox */
        .button_export-excel {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            height: calc(2.25rem + 2px);
            text-decoration: none;
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
                    <span id="contador-turno" data-fin="{{ $finProgramado->toIso8601String() }}"></span>
                    @if($puedeCerrar)
                        <button class="btn btn-danger" id="btn-endturn">
                            <i class="fas fa-stop"></i> {{ __('FINALIZAR TURNO') }}
                        </button>
                    @else
                        <button class="btn btn-secondary" disabled id="btn-turno-bloqueado">
                            <i class="fas fa-lock"></i>
                            TURNO TERMINA EN <span id="contador-texto">--:--:--</span>
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
                    {{-- <div class="row align-items-center mb-3">
                        <div class="col-md-6">
                            <input style="margin: 20px 0 0 20px" type="text" name="searcht" id="cg-search"
                                class="input-search form-control" placeholder="BUSCAR AQUÍ...">
                        </div>
                        <div class="col-md-6 d-flex justify-content-end">
                            <a href="#" style="margin: 0 20px 0 0" class="button_export-excel" data-toggle="modal" data-target="#ModalExportExcel">
                                <span class="button__text">
                                    <svg fill="#fff" xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                        viewBox="0 0 50 50">
                                        <path d="M28.8125 .03125L.8125 5.34375C.339844
                                                5.433594 0 5.863281 0 6.34375L0 43.65625C0
                                                44.136719 .339844 44.566406 .8125 44.65625L28.8125
                                                49.96875C28.875 49.980469 28.9375 50 29 50C29.230469
                                                50 29.445313 49.929688 29.625 49.78125C29.855469 49.589844
                                                30 49.296875 30 49L30 1C30 .703125 29.855469 .410156 29.625
                                                .21875C29.394531 .0273438 29.105469 -.0234375 28.8125 .03125ZM32
                                                6L32 13L34 13L34 15L32 15L32 20L34 20L34 22L32 22L32 27L34 27L34
                                                29L32 29L32 35L34 35L34 37L32 37L32 44L47 44C48.101563 44 49
                                                43.101563 49 42L49 8C49 6.898438 48.101563 6 47 6ZM36 13L44
                                                13L44 15L36 15ZM6.6875 15.6875L11.8125 15.6875L14.5 21.28125C14.710938
                                                21.722656 14.898438 22.265625 15.0625 22.875L15.09375 22.875C15.199219
                                                22.511719 15.402344 21.941406 15.6875 21.21875L18.65625 15.6875L23.34375
                                                15.6875L17.75 24.9375L23.5 34.375L18.53125 34.375L15.28125
                                                28.28125C15.160156 28.054688 15.035156 27.636719 14.90625
                                                27.03125L14.875 27.03125C14.8125 27.316406 14.664063 27.761719
                                                14.4375 28.34375L11.1875 34.375L6.1875 34.375L12.15625 25.03125ZM36
                                                20L44 20L44 22L36 22ZM36 27L44 27L44 29L36 29ZM36 35L44 35L44 37L36 37Z">
                                        </path>
                                    </svg>
                                    Descargar
                                </span>
                                <span class="button__icon"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 35 35"
                                        id="bdd05811-e15d-428c-bb53-8661459f9307" data-name="Layer 2" class="svg">
                                        <path
                                            d="M17.5,22.131a1.249,1.249,0,0,1-1.25-1.25V2.187a1.25,1.25,0,0,1,2.5,0V20.881A1.25,1.25,0,0,1,17.5,22.131Z">
                                        </path>
                                        <path
                                            d="M17.5,22.693a3.189,3.189,0,0,1-2.262-.936L8.487,15.006a1.249,1.249,0,0,1,1.767-1.767l6.751,6.751a.7.7,0,0,0,.99,0l6.751-6.751a1.25,1.25,0,0,1,1.768,1.767l-6.752,6.751A3.191,3.191,0,0,1,17.5,22.693Z">
                                        </path>
                                        <path
                                            d="M31.436,34.063H3.564A3.318,3.318,0,0,1,.25,30.749V22.011a1.25,1.25,0,0,1,2.5,0v8.738a.815.815,0,0,0,.814.814H31.436a.815.815,0,0,0,.814-.814V22.011a1.25,1.25,0,1,1,2.5,0v8.738A3.318,3.318,0,0,1,31.436,34.063Z">
                                        </path>
                                    </svg></span>
                            </a>
                        </div>
                    </div>
                    <div class="row col-md-6 text-left" style="margin: 20px 0 0 20px;">
                        <input class="form-control" type="date">
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
                        @include('controlgarita.controlregistro.modal.etiqueta')
                    </div> --}}
                    <div class="card-body">
                        <div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
                            <div class="col-md-5 pl-0">
                                <input type="text" name="searcht" id="cg-search"
                                    class="input-search form-control" 
                                    placeholder="BUSCAR AQUÍ..."
                                    style="height: calc(2.25rem + 2px);">
                            </div>
                            <div class="d-flex align-items-center gap-2">
                                <button class="btn btn-outline-secondary mr-2" type="button" data-toggle="collapse" data-target="#advancedFilters" aria-expanded="false" style="height: calc(2.25rem + 2px);">
                                    <i class="fas fa-filter"></i> Filtros
                                </button>
                                <button class="btn btn-info btn-lbl-clr-cliente mr-2" data-toggle="modal" data-target="#ModalEtiqueta" style="height: calc(2.25rem + 2px);">
                                    <i class="fas fa-tag"></i> Etiquetas
                                </button>
                                <a href="#" class="button_export-excel" data-toggle="modal" data-target="#ModalExportExcel">
                                    <span class="button__text">
                                        <svg fill="#fff" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 50 50">
                                            <path d="M28.8125 .03125L.8125 5.34375C.339844 5.433594 0 5.863281 0 6.34375L0 43.65625C0 44.136719 .339844 44.566406 .8125 44.65625L28.8125 49.96875C28.875 49.980469 28.9375 50 29 50C29.230469 50 29.445313 49.929688 29.625 49.78125C29.855469 49.589844 30 49.296875 30 49L30 1C30 .703125 29.855469 .410156 29.625 .21875C29.394531 .0273438 29.105469 -.0234375 28.8125 .03125ZM32 6L32 13L34 13L34 15L32 15L32 20L34 20L34 22L32 22L32 27L34 27L34 29L32 29L32 35L34 35L34 37L32 37L32 44L47 44C48.101563 44 49 43.101563 49 42L49 8C49 6.898438 48.101563 6 47 6ZM36 13L44 13L44 15L36 15ZM6.6875 15.6875L11.8125 15.6875L14.5 21.28125C14.710938 21.722656 14.898438 22.265625 15.0625 22.875L15.09375 22.875C15.199219 22.511719 15.402344 21.941406 15.6875 21.21875L18.65625 15.6875L23.34375 15.6875L17.75 24.9375L23.5 34.375L18.53125 34.375L15.28125 28.28125C15.160156 28.054688 15.035156 27.636719 14.90625 27.03125L14.875 27.03125C14.8125 27.316406 14.664063 27.761719 14.4375 28.34375L11.1875 34.375L6.1875 34.375L12.15625 25.03125ZM36 20L44 20L44 22L36 22ZM36 27L44 27L44 29L36 29ZM36 35L44 35L44 37L36 37Z"></path>
                                        </svg>
                                        Reporte
                                    </span>
                                    <span class="button__icon">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 35 35" id="bdd05811-e15d-428c-bb53-8661459f9307" data-name="Layer 2" class="svg">
                                            <path d="M17.5,22.131a1.249,1.249,0,0,1-1.25-1.25V2.187a1.25,1.25,0,0,1,2.5,0V20.881A1.25,1.25,0,0,1,17.5,22.131Z"></path>
                                            <path d="M17.5,22.693a3.189,3.189,0,0,1-2.262-.936L8.487,15.006a1.249,1.249,0,0,1,1.767-1.767l6.751,6.751a.7.7,0,0,0,.99,0l6.751-6.751a1.25,1.25,0,0,1,1.768,1.767l-6.752,6.751A3.191,3.191,0,0,1,17.5,22.693Z"></path>
                                            <path d="M31.436,34.063H3.564A3.318,3.318,0,0,1,.25,30.749V22.011a1.25,1.25,0,0,1,2.5,0v8.738a.815.815,0,0,0,.814.814H31.436a.815.815,0,0,0,.814-.814V22.011a1.25,1.25,0,1,1,2.5,0v8.738A3.318,3.318,0,0,1,31.436,34.063Z"></path>
                                        </svg>
                                    </span>
                                </a>
                                @include('controlgarita.controlregistro.modal.etiqueta')
                            </div>
                        </div>

                        <div class="collapse" id="advancedFilters">
                            <div class="card card-body bg-light border-0 p-3 mb-3" style="border-radius: 8px;">
                                <div class="row">
                                    <div class="col-md-3 mb-2">
                                        <label class="small font-weight-bold text-muted mb-1">MOVIMIENTO</label>
                                        <div class="btn-group btn-group-toggle w-100" data-toggle="buttons">
                                            <label class="btn btn-white border active btn-sm">
                                                <input type="radio" name="filter_mov" value="ALL" checked onchange="aplicarFiltros()"> Todos
                                            </label>
                                            <label class="btn btn-white border text-success btn-sm">
                                                <input type="radio" name="filter_mov" value="E" onchange="aplicarFiltros()"> Entrada
                                            </label>
                                            <label class="btn btn-white border text-secondary btn-sm">
                                                <input type="radio" name="filter_mov" value="S" onchange="aplicarFiltros()"> Salida
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-2 mb-2">
                                        <label class="small font-weight-bold text-muted mb-1">ENTIDAD</label>
                                        <select id="filter_entidad" class="form-control form-control-sm" onchange="aplicarFiltros()">
                                            <option value="">Todas</option>
                                            <option value="P">Personas</option>
                                            <option value="V">Vehículos</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4 mb-2">
                                        <label class="small font-weight-bold text-muted mb-1">RANGO DE FECHAS</label>
                                        <div class="input-group input-group-sm">
                                            <input type="date" id="date_from" class="form-control" placeholder="Desde" onchange="aplicarFiltros()">
                                            <div class="input-group-prepend input-group-append">
                                                <span class="input-group-text bg-white border-left-0 border-right-0">a</span>
                                            </div>
                                            <input type="date" id="date_to" class="form-control" placeholder="Hasta" onchange="aplicarFiltros()">
                                        </div>
                                    </div>
                                    <div class="col-md-3 mb-2">
                                        <label class="small font-weight-bold text-muted mb-1">RANGO HORARIO</label>
                                        <div class="input-group input-group-sm">
                                            <input type="time" id="time_from" class="form-control" onchange="aplicarFiltros()">
                                            <div class="input-group-prepend input-group-append">
                                                <span class="input-group-text bg-white border-left-0 border-right-0">-</span>
                                            </div>
                                            <input type="time" id="time_to" class="form-control" onchange="aplicarFiltros()">
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-12 text-right">
                                        <button class="btn btn-link btn-sm text-muted p-0" onclick="limpiarFiltros()">
                                            <i class="fas fa-times-circle"></i> Limpiar filtros
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <table id="det-control-garita-table" class="table table-hover">
                            <thead>
                                <tr>
                                    <th scope="col"> {{ __('') }} </th>
                                    <th scope="col"> {{ __('ID') }} </th>
                                    <th scope="col"> {{ __('E/S') }} </th>
                                    <th scope="col"> {{ __('FECHA') }} </th>
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
                                                {{ $detalle->controlGarita->fecha ? \Carbon\Carbon::parse($detalle->controlGarita->fecha)->format('d-m-Y') : 'N/A' }}
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

                                                    @include('controlgarita.controlregistro.modal.edit', ['detalle' => $detalle])
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
    @include('controlgarita.controlregistro.modal.login')
    @include('controlgarita.controlregistro.modal.export')
    @if($turnoActivo)
        @include('controlgarita.controlregistro.modal.create')
    @endif
@endsection

@section('js')
    <script type="text/javascript">
        $(() => {
            function aplicarFiltros() {
                const mov = $('input[name="filter_mov"]:checked').val();
                const entidad = $('#filter_entidad').val();
                const dateFrom = $('#date_from').val();
                const dateTo = $('#date_to').val();
                const timeFrom = $('#time_from').val();
                const timeTo = $('#time_to').val();

                $('#det-control-garita-table tbody tr').each(function() {
                    const row = $(this);
                    
                    // 1. Obtener datos de la fila (Necesitas data attributes en el TR)
                    // Asegúrate de que tu TR tenga: data-tipo, data-entidad, data-fecha, data-hora
                    // Ejemplo: <tr data-tipo="E" data-entidad="P" data-fecha="2023-10-27" data-hora="14:30">
                    
                    // NOTA: Debes agregar estos data-attributes en tu blade si no existen.
                    // Si no quieres modificar el blade, tendrías que leer el texto de los TDs, 
                    // lo cual es menos preciso.
                    
                    const rowTipo = row.data('tipo'); 
                    // const rowEntidad = row.find('td:eq(4)').text().trim().includes('VEHÍCULO') ? 'V' : 'P'; // Ejemplo lectura manual
                    // Se recomienda usar data-attributes para precisión.

                    let mostrar = true;

                    // Filtro Movimiento
                    if (mov !== 'ALL' && rowTipo !== mov) mostrar = false;

                    // Filtro Entidad (Ejemplo simplificado, requiere data-entidad en TR)
                    // if (entidad && rowEntidad !== entidad) mostrar = false;

                    // Filtro Fechas
                    // ... Lógica de comparación de fechas ...

                    if (mostrar) row.show();
                    else row.hide();
                });
            }

            function limpiarFiltros() {
                $('input[name="filter_mov"][value="ALL"]').prop('checked', true).change();
                $('#filter_entidad').val('');
                $('#date_from').val('');
                $('#date_to').val('');
                $('#time_from').val('');
                $('#time_to').val('');
                
                $('#det-control-garita-table tbody tr').show();
            }

            $(document).on('change', 'input[name="filter_mov"]', function() {
                $('label.btn').removeClass('active');
                $(this).closest('label').addClass('active');
                aplicarFiltros();
            });
            
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
                        // console.log('1', 'API Response:', response);
                        $('#det-control-garita-table tbody').html(response);
                    },
                    error: function(xhr, status, error) {
                        // console.error('AJAX Error:', error);
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
                    if (result.value) {
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
        
        // function iniciarContadorTurno() {
        //     const contadorElemento = document.getElementById('contador-texto');
        //     const contenedor = document.getElementById('contador-turno');

        //     if (!contadorElemento || !contenedor) return;

        //     const finTurno = new Date(contenedor.dataset.fin);

        //     function actualizar() {
        //         const ahora = new Date();
        //         let diferencia = Math.floor((finTurno - ahora) / 1000);

        //         if (diferencia <= 0) {
        //             contadorElemento.innerText = '00:00:00';

        //             // Opción A: recargar para habilitar botón
        //             location.reload();

        //             // Opción B: mostrar mensaje
        //             // contadorElemento.innerText = 'Puedes cerrar el turno';
        //             return;
        //         }

        //         const horas = String(Math.floor(diferencia / 3600)).padStart(2, '0');
        //         const minutos = String(Math.floor((diferencia % 3600) / 60)).padStart(2, '0');
        //         const segundos = String(diferencia % 60).padStart(2, '0');

        //         contadorElemento.innerText = `${horas}:${minutos}:${segundos}`;
        //     }

        //     actualizar();
        //     setInterval(actualizar, 1000);
        // }

        // document.addEventListener('DOMContentLoaded', iniciarContadorTurno);
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
