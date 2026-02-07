<div class="modal fade" id="ModalEdit" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <h6 class="modal-title">EDITAR CLIENTE</h6>
                <button type="button" class="close" data-dismiss="modal">
                    ×
                </button>
            </div>

            <form id="formEditCliente" method="POST">
                @csrf
                @method('PUT')

                <div class="modal-body">

                    <input type="hidden" id="edit_id">

                    <div class="row">
                        <div class="col-md-3">
                            <label>DOCUMENTO CLIENTE</label>
                            <input id="edit_documento" name="documento" class="form-control form-control-sm" required>
                        </div>

                        <div class="col-md-6">
                            <label>RAZÓN SOCIAL CLIENTE</label>
                            <input id="edit_nombre" name="nombre" class="form-control form-control-sm" required>
                        </div>

                        <div class="col-md-3">
                            <div class="form-check mt-4">
                                <input class="form-check-input" type="checkbox" id="edit_r_info_prestado"
                                    name="r_info_prestado" value="1">
                                <label class="form-check-label" for="r_info_prestado">
                                    REINFO PRESTADO
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-3" id="edit_r_info_container" style="display:none;">
                        <div class="col-md-3">
                            <label>REINFO PRESTADO</label>
                            <input id="edit_r_info" name="r_info" class="form-control form-control-sm">
                        </div>

                        <div class="col-md-9">
                            <label>RAZÓN SOCIAL PRESTADO</label>
                            <input id="edit_nombre_r_info" name="nombre_r_info" class="form-control form-control-sm">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <label>OBSERVACIÓN</label>
                            <textarea id="edit_observacion" name="observacion" class="form-control form-control-sm" rows="4"></textarea>
                        </div>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary btn-sm">
                        ACTUALIZAR
                    </button>
                    <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">
                        CANCELAR
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>
