@forelse ($pesos as $peso)
    @php
        $tieneRecepcion = isset($recepcionesPorSalida[$peso->NroSalida]);
        $fechaOk = filled($peso->Fechas);
        $horaOk = filled($peso->Horas);
        $recepcionId = $recepcionesPorSalida[$peso->NroSalida] ?? null;
    @endphp

    <tr>
        <td>{{ $peso->NroSalida }}</td>
        <td>{{ $peso->Horas ? \Carbon\Carbon::parse($peso->Horas)->format('H:i:s') : '—' }}</td>
        <td>{{ $peso->Fechas ? \Carbon\Carbon::parse($peso->Fechas)->format('d/m/Y') : '—' }}</td>
        <td>{{ $peso->Neto }}</td>
        <td>{{ $peso->Placa }}</td>
        <td>{{ $peso->Producto }}</td>
        <td>{{ $peso->RazonSocial }}</td>
        <td>{{ $peso->destino }}</td>
        <td>{{ $peso->origen }}</td>

            <td class="text-nowrap" id="acciones-{{ $peso->NroSalida }}">
                {{-- VER --}}
                <a href="{{ route('pesos.show', $peso->NroSalida) }}" class="btn btn-secondary btn-xs">
                    Ver
                </a>

                @if ($tieneRecepcion)
                    <a href="{{ route('recepciones-ingreso.acta.html', $peso->NroSalida) }}"
                    class="btn btn-outline-dark btn-xs" target="_blank" rel="noopener">
                        🖨️ Acta
                    </a>
                @endif

                @if (!$tieneRecepcion && $fechaOk && $horaOk)
                    <a class="btn btn-primary btn-xs">
                        📥 Recepción
                    </a>
                @endif

                @if ($recepcionId)
                    <a href="{{ route('recepciones-ingreso.edit', $recepcionId) }}"
                    class="btn btn-outline-secondary btn-xs">
                        ✏️ Editar
                    </a>

                    {{-- ELIMINAR (AJAX + Swal) --}}
                    <button type="button"
                            class="btn btn-outline-danger btn-xs js-del-recepcion"
                            data-url="{{ route('recepciones-ingreso.destroy', $recepcionId) }}"
                            data-nro="{{ $peso->NroSalida }}">
                        🗑️ Eliminar
                    </button>
                @endif
            </td>
    </tr>
@empty
    <tr>
        <td colspan="10" class="text-center text-muted">
            Sin resultados
        </td>
    </tr>
@endforelse
