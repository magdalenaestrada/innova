@forelse ($lotes as $lote)
    <tr>
        <td>{{ $lote->codigo }}</td>
        <td>{{ $lote->nombre }}</td>
        <td>{{ $lote->cliente->nombre ?? 'NO ASIGNADO' }}</td>
        <td>
            <button class="btn btn-sm btn-outline-warning btn-editar-lote" data-id="{{ $lote->id }}"
                data-nombre="{{ $lote->nombre }}" data-cliente-id="{{ $lote->cliente_id }}"
                data-cliente-nombre="{{ $lote->cliente->nombre ?? '' }}" data-codigo="{{ $lote->codigo }}">
                <i class="fas fa-pen"></i>
            </button>

            <button class="btn btn-sm btn-outline-danger btn-delete-lote" data-id="{{ $lote->id }}">
                <i class="fas fa-trash"></i>
            </button>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="4" class="text-center text-muted">No hay lotes registrados</td>
    </tr>
@endforelse
