<div class="modal fade" id="ModalReinfo" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    Gestión de Datos REINFO
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            
            <form id="formReinfo" action="{{ route('lqclientes.reinfo.save') }}" method="POST">
                @csrf
                @method('POST')

                <input type="hidden" id="reinfo_cliente_id" name="cliente_id">
                
                <div class="modal-body">
                    <div class="card mb-3">
                        <div class="card-header bg-light py-2">
                            <strong><i class="fa fa-id-card-o"></i> DERECHO MINERO</strong>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Código Único (Minero)</label>
                                        <input type="text" class="form-control" id="reinfo_codigo" name="codigo_minero" placeholder="Ej: 01000234X">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Nombre del Derecho Minero</label>
                                        <input type="text" class="form-control" id="reinfo_nombre" name="nombre_minero" placeholder="Nombre de la concesión">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Estado de Vigencia</label>
                                        <select class="form-control" id="reinfo_estado" name="estado_reinfo">
                                            <option value="">Seleccione estado...</option>
                                            <option value="1">VIGENTE</option>
                                            <option value="0">NO VIGENTE / SUSPENDIDO</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card mb-0">
                        <div class="card-header bg-light py-2">
                            <strong><i class="fa fa-map"></i> UBICACIÓN GEOGRÁFICA</strong>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Departamento</label>
                                        <select class="form-control" id="select_departamento">
                                            <option value="">Cargando...</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Provincia</label>
                                        <select class="form-control" id="select_provincia" disabled>
                                            <option value="">Seleccione Dpto...</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Distrito</label>
                                        <select class="form-control" id="select_distrito" name="ubigeo_id" disabled>
                                            <option value="">Seleccione Prov...</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-12">
                                    <div class="alert alert-secondary mb-0 p-2 text-center" id="ubicacion_actual_texto" style="display:none; font-size: 0.9rem;">
                                        <i class="fa fa-check-circle text-success"></i> Ubicación actual: <strong>-</strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary" id="btnGuardarReinfo">
                        <i class="fa fa-save"></i> Guardar Cambios
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>