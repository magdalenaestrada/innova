<div class="modal fade text-left" id="ModalCreate" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">

            <div class="card-header">
                <div class="row justify-content-between">
                    <div class="col-md-6">
                        <h6 class="mt-2">
                            {{ __('CREAR CLIENTE') }}
                        </h6>
                    </div>
                    <div class="col-md-6 text-right">
                        <button type="button" style="font-size: 30px" class="close" data-dismiss="modal"
                            aria-label="Close">
                            <img style="width: 15px" src="{{ asset('images/icon/close.png') }}" alt="cerrar">
                        </button>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <form class="crear-empleado" action="{{ route('lqclientes.store') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="form mb-1 col-md-3">
                            <label for="documento">DOCUMENTO CLIENTE</label>
                            <input name="documento" id="documento" class="input form-control form-control-sm"
                                placeholder="Documento del cliente..." required="" type="text">
                            <span class="input-border"></span>
                        </div>
                        <div class="form col-md-6">
                            <label for="documento">RAZÓN SOCIAL CLIENTE</label>
                            <input name="nombre" id="nombre" class="input form-control form-control-sm"
                                placeholder="Valide el nombre del cliente" required="" type="text">
                            <span class="input-border"></span>
                        </div>
                        <div class="form mb-1 col-md-3 d-flex align-items-center">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="r_info_prestado"
                                    id="r_info_prestado" value="1">
                                <label class="form-check-label" for="r_info_prestado">
                                    REINFO PRESTADO
                                </label>
                            </div>
                        </div>

                    </div>
                    <div class="row mt-3" id="r_info_container" style="display: none;">
                        <div class="form mb-1 col-md-3">
                            <label for="r_info">REINFO PRESTADO</label>
                            <input name="r_info" id="r_info" class="input form-control form-control-sm"
                                placeholder="REINFO..." required="" type="text">
                            <span class="input-border"></span>
                        </div>
                        <div class="form col-md-9">
                            <label for="nombre_r_info">RAZÓN SOCIAL PRESTADO</label>
                            <input name="nombre_r_info" id="nombre_r_info" class="input form-control form-control-sm"
                                placeholder="Valide el nombre" required="" type="text">
                            <span class="input-border"></span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form col-md-12">
                            <label for="observacion">OBSERVACIÓN</label>
                            <textarea name="observacion" id="observacion" class="form-control form-control-sm" rows="4"
                                placeholder="Escriba la observación..."></textarea>
                        </div>
                    </div>

                    <div class="col-md-12 text-right mt-1">
                        <button type="submit" class="btn btn-secondary btn-sm">
                            {{ __('GUARDAR') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
