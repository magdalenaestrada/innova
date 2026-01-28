@push('css')
<style>
    #ModalExportExcel .modal-content {
        border: none;
        border-radius: 16px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.15);
        overflow: hidden;
        /* font-family: 'Nunito', sans-serif; */
    }

    /* Cabecera */
    #ModalExportExcel .modal-header {
        background: #f8f9fa;
        border-bottom: 1px solid #edf2f7;
        padding: 1.5rem 1.5rem 1rem;
    }

    #ModalExportExcel .modal-title {
        font-weight: 700;
        color: #2d3748;
        font-size: 1.25rem;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    /* Icono del título */
    /* #ModalExportExcel .title-icon-box {
        background: #e6fffa; 
        padding: 8px 10px; 
        border-radius: 8px; 
        color: #059669;
        font-size: 1.1rem;
    } */

    /* Botón Cerrar */
    #ModalExportExcel .close {
        background: #e2e8f0;
        border-radius: 50%;
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 1;
        transition: all 0.2s ease;
        border: none;
        text-shadow: none;
        padding: 0;
        margin: -5px -5px 0 0;
    }

    #ModalExportExcel .close:hover {
        background: #cbd5e0;
        transform: rotate(90deg);
        /* color: #c53030; */
    }

    #ModalExportExcel .close span {
        color: #4a5568;
        font-size: 1.2rem;
        line-height: 1;
        padding-bottom: 2px;
    }

    /* Títulos de Secciones */
    #ModalExportExcel .section-label {
        font-size: 0.7rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: #718096;
        font-weight: 600;
        margin-bottom: 1rem;
        margin-top: 0.5rem;
        padding-bottom: 0.25rem;
        border-bottom: 1px dashed #e2e8f0;
        display: block;
    }

    /* Inputs y Selects */
    #ModalExportExcel .form-control {
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 0.6rem 1rem;
        color: #4a5568;
        font-weight: 500;
        height: calc(1.5em + 1rem + 2px);
        box-shadow: none;
        transition: border-color 0.2s, box-shadow 0.2s;
    }

    #ModalExportExcel .form-control:focus {
        border-color: #ffffff;
        box-shadow: 0 0 20px 1px rgba(63, 63, 63, 0.15);
    }

    /* Footer y Botones */
    #ModalExportExcel .modal-footer {
        background: #fff;
        border-top: none;
        padding: 1.5rem;
        display: flex;
        justify-content: space-between;
    }

    #ModalExportExcel .btn {
        padding: 0.6rem 1.5rem;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.9rem;
        letter-spacing: 0.3px;
        transition: transform 0.2s, box-shadow 0.2s;
    }

    #ModalExportExcel .btn-secondary {
        background: #edf2f7;
        color: #4a5568;
        border: none;
    }
    
    #ModalExportExcel .btn-secondary:hover {
        background: #e2e8f0;
        color: #2d3748;
    }

    #ModalExportExcel .btn-success {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        border: none;
        box-shadow: 0 4px 10px rgba(16, 185, 129, 0.3);
    }

    #ModalExportExcel .btn-success:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 15px rgba(16, 185, 129, 0.4);
        background: linear-gradient(135deg, #059669 0%, #047857 100%);
    }

    /* Etiquetas de los campos */
    #ModalExportExcel label {
        font-size: 0.8rem;
        font-weight: 700;
        color: #64748b;
        margin-bottom: 0.3rem;
    }
</style>
@endpush
<div class="modal fade" id="ModalExportExcel" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            
            <div class="modal-header">
                <h5 class="modal-title">
                    <div class="title-icon-box">
                        <i class="fas fa-file-excel"></i>
                    </div>
                    Exportar Reporte de Garita
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form action="{{ route('detcontrolgarita.export-excel-custom') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    
                    <span class="section-label">
                        <i class="fas fa-filter mr-1"></i> Filtros Generales
                    </span>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>Movimiento</label>
                            <select name="tipo_movimiento" class="form-control">
                                <option value="">Todos</option>
                                <option value="E">Entradas</option>
                                <option value="S">Salidas</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Tipo Entidad</label>
                            <select name="tipo_entidad" class="form-control">
                                <option value="">Todos</option>
                                <option value="P">Personas</option>
                                <option value="V">Vehículos</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group mb-4">
                        <label>Responsable</label>
                        <div class="input-group">
                            <select name="usuario_id" class="form-control">
                                <option value="">Todos los usuarios</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <span class="section-label">
                        <i class="far fa-calendar-alt mr-1"></i> Rango de Fechas
                    </span>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>Desde</label>
                            <div class="input-group">
                                <input type="date" name="fecha_inicio" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Hasta</label>
                            <div class="input-group">
                                <input type="date" name="fecha_fin" class="form-control">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-2">
                            <div class="input-group">
                                <input type="time" name="hora_inicio" class="form-control" placeholder="Inicio">
                            </div>
                        </div>
                        <div class="col-md-6 mb-2">
                            <div class="input-group">
                                <input type="time" name="hora_fin" class="form-control" placeholder="Fin">
                            </div>
                        </div>
                    </div>
                    <small class="text-muted d-block text-right mt-1" style="font-size: 0.7em;">
                        * Dejar horas vacías para todo el día
                    </small>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        Cancelar
                    </button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-file-download mr-2"></i> Descargar Excel
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@push('js')
<script>
</script>

@if($errors->any())
    <script>
        Swal.fire({
            type: 'error',
            title: 'Error de validación',
            html: xhr.responseJSON?.message ?? 'Error desconocido',
        });
    </script>
@endif


@endpush