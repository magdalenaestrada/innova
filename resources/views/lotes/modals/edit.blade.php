<div class="modal fade" id="modalEditarLote" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form method="POST" id="formEditarLote" action="">
            @csrf
            @method('PUT')
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Editar Lote</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Nombre del Lote</label>
                        <input type="text" name="nombre" class="form-control" id="modalLoteNombre">
                    </div>

                    <div class="combo form-group">
                        <label>Cliente</label>
                        <input type="text" class="clienteInput form-control" id="modalClienteNombre"
                            placeholder="Escriba para buscar..." required>

                        <select class="clienteSelect form-control" size="5" style="display:none;">
                            <option value="">-- Seleccionar cliente --</option>

                            @foreach ($clientes as $cliente)
                                <option value="{{ $cliente->id }}" data-codigo-base="{{ $cliente->codigo }}"
                                    data-ultimo="{{ $cliente->ultimo_correlativo }}">
                                    {{ $cliente->codigo }} - {{ $cliente->nombre }}
                                </option>
                            @endforeach
                        </select>
                        <input type="hidden" name="lq_cliente_id" id="modalClienteId">
                    </div>
                    <div class="form-group">
                        <label>Código</label>
                        <input type="text" name="codigo" class="form-control" id="modalCodigo">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-success">Guardar</button>
                </div>
            </div>
        </form>
    </div>
</div>
