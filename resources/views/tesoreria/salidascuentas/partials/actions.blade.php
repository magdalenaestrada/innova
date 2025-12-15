
@if ($row->liquidacion || $row->adelanto || $row->reposicioncaja)
    
@else
<a href="{{ route('tssalidascuentas.printdoc', $row->id) }}" class="btn btnprn btn-sm btn-primary">
    Imprimir
</a>



<a href="{{ route('tssalidascuentas.edit', $row->id) }}" class="btn btn-sm btn-warning">
    Editar
</a>

<form action="{{ route('tssalidascuentas.destroy', $row->id) }}" method="POST" style="display:inline;">
    @csrf
    @method('DELETE')
    <button type="submit" class="btn btn-sm btn-danger">Eliminar</button>
</form>
@endif
