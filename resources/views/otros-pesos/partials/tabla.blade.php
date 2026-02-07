<div class="alert alert-warning border-warning mb-4" style="background-color: #fff3cd; border: 3px solid #ffc107;">
    <div class="d-flex justify-content-center align-items-center">
        <h2 class="mb-0 fw-bold text-dark">
            <i class="bi bi-calculator-fill me-2"></i>
            TOTAL NETO: {{ number_format($sumaNetos, 2) }} KG
        </h2>
    </div>
</div>


<div class="d-flex justify-content-end mb-3 gap-2">
    <div class="col-md-auto">
        <button type="button" class="btn btn-success" id="btn-exportar">
            <i class="bi bi-file-earmark-excel"></i> Exportar Excel
        </button>
    </div>
</div>


<div style="overflow-x: auto;">
    <table class="table table-bordered table-hover align-middle text-center">
        <thead class="table-light">
            <tr>
                <th><input type="checkbox" id="check-all"></th>
                <th>Ticket</th>
                <th>Estado</th>
                <th style="min-width: 320px;">Lote</th>
                <th>Fecha I.</th>
                <th>Fecha S.</th>
                <th>Producto</th>
                <th>Placa</th>
                <th>Conductor</th>
                <th>Razón Social</th>
                <th>Origen</th>
                <th>Destino</th>
                <th>Neto</th>
                <th>Observación</th>
                <th>Opciones</th>
                <th>Responsable</th>
            </tr>
        </thead>

        <tbody>
            @forelse ($pesos as $peso)
                @php
                    $estadoActual = optional($peso->estado);
                    $clienteActual = optional($peso->cliente_id);

                    $tieneEstado = !is_null($estadoActual->id);

                    $liquidado = $estadoActual->id === 3;
                    $retirado = $estadoActual->id === 4;
                    $cancha = $estadoActual->id === 1;
                    $procesado = $estadoActual->id === 2;

                    // Asignar clase de color de fila
                    $filaClase = '';
                    if ($liquidado) {
                        $filaClase = 'bg-liquidado';
                    } elseif ($retirado) {
                        $filaClase = 'bg-retirado';
                    } elseif ($cancha) {
                        $filaClase = 'bg-cancha';
                    } elseif ($procesado) {
                        $filaClase = 'bg-procesado';
                    } // neutro
                @endphp

                <tr class="{{ $filaClase }}">
                    <td>
                        <input type="checkbox" class="check-item" value="{{ $peso->id }}"
                            @if ($tieneEstado) disabled @endif>
                    </td>
                    <td>{{ $peso->id }}</td>
                    <td>
                        <select class="form-select form-select-sm estado-select w-150" data-peso="{{ $peso->id }}"
                            {{ $tieneEstado ? 'disabled' : '' }}>
                            <option value="">Elegir</option>
                            @foreach ($estados as $estadoOpt)
                                <option value="{{ $estadoOpt->id }}"
                                    {{ $estadoActual->id == $estadoOpt->id ? 'selected' : '' }}>
                                    {{ $estadoOpt->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <div class="combo">
                            <input type="text" class="lote-input form-control form-control-sm"
                                placeholder="Escriba o seleccione..."
                                value="{{ $clienteActual->nombre ?? '' }} - {{ $peso->cliente->nombre ?? 'Sin cliente' }}"
                                data-peso="{{ $peso->id }}" style="min-width: 150px;"
                                @if ($tieneEstado) disabled @endif>
                            <select class="lote-select form-control form-control-sm" size="5"
                                style="display: none; min-width: 150px; text-align: left;">
                                @foreach ($lotes as $loteOption)
                                    <option value="{{ $loteOption->id }}"
                                        data-codigo="{{ $loteOption->codigo_base }}">
                                        {{ $loteOption->nombre }} -
                                        {{ $peso->cliente->nombre ?? 'Sin cliente' }}
                                    </option>
                                @endforeach
                            </select>

                            <input type="hidden" name="lote_id[]" class="lote-hidden"
                                value="{{ $clienteActual->id ?? '' }}">
                        </div>
                    </td>
                    <td>{{ $peso->fechai }}</td>
                    <td>{{ $peso->fechas }}</td>
                    <td>{{ $peso->producto }}</td>
                    <td>{{ $peso->placa }}</td>
                    <td>{{ $peso->conductor }}</td>
                    <td>{{ $peso->lote->cliente->nombre }}</td>
                    <td>{{ $peso->origen }}</td>
                    <td>{{ $peso->destino }}</td>
                    <td>{{ number_format($peso->neto, 2) }}</td>
                    <td>{{ $peso->observacion }}</td>
                    <td>
                        @if ($peso->estado_id == 1)
                            <button class="btn btn-warning editar-peso" data-id="{{ $peso->id }}">
                                <i class="fa fa-pen"></i>
                            </button>
                        @else
                            <button class="btn btn-secondary" disabled title="No editable">
                                <i class="fa fa-lock"></i>
                            </button>
                        @endif

                    </td>
                    <td>{{ $peso->usuario?->name ?? '' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="14" class="text-center text-muted py-4">
                        <i class="fa fa-inbox"></i> No hay registros
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{ $pesos->links() }}
</div>
