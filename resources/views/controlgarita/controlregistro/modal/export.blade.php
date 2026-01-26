{{-- <div class="modal fade text-left" id="ModalExport"  role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">

            <div class="card-header">
                <div class="row justify-content-between">
                    <div class="col-md-6">
                        <h6 class="mt-2">
                            {{ __('REPORTE REGISTRO DE MOVIMIENTOS') }}
                        </h6>
                    </div>
                    <div class="col-md-6 text-right">
                        <button type="button" style="font-size: 30px" class="close" data-dismiss="modal" aria-label="Close">
                            <img style="width: 15px" src="{{ asset('images/icon/close.png') }}" alt="cerrar">
                        </button>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <form class="crear-accion" action="{{ route('tssalidascajas.export-excel') }}" method="GET">
                    @csrf
                    <div class="row">
                        <div class="form-group col-md-6 g-3">
                            <label for="start_date" class="text-muted">
                                {{ __('FECHA INICIAL') }}
                            </label>
                            <input type="datetime-local" name="start_date" placeholder="Ingrese la fecha Inicial"
                                class="form-control">
                        </div>

                        <div class="form-group col-md-6 g-3">
                            <label for="end_date" class="text-muted">
                                {{ __('FECHA FINAL') }}
                            </label>
                            <input type="datetime-local" name="end_date" placeholder="Ingrese la fecha Final"
                                class="form-control">
                        </div>
                        
                        <div class="col-md-12 text-right g-3">
                            <button type="submit" class="btn btn-secondary btn-sm">
                                {{ __('EXPORTAR REPORTE') }}
                            </button>   
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div> --}}
<div class="modal fade" id="ModalExport" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                    <i class="fas fa-file-excel"></i> Exportar a Excel
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form action="{{ route('detcontrolgarita.export-excel-custom') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label>Tipo de Movimiento</label>
                        <select name="tipo_movimiento" class="form-control">
                            <option value="">Todos</option>
                            <option value="E">Entradas</option>
                            <option value="S">Salidas</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Tipo de Entidad</label>
                        <select name="tipo_entidad" class="form-control">
                            <option value="">Todos</option>
                            <option value="P">Personas</option>
                            <option value="V">Vehículos</option>
                        </select>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Fecha Inicio</label>
                                <input type="date" name="fecha_inicio" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Fecha Fin</label>
                                <input type="date" name="fecha_fin" class="form-control">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Usuario</label>
                        <select name="usuario_id" class="form-control">
                            <option value="">Todos</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        Cancelar
                    </button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-download"></i> Descargar Excel
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
                    icon: 'error',
                    title: 'Error de validación',
                    html: '@foreach($errors->all() as $error)<p>{{ $error }}</p>@endforeach',
                });
            </script>
@endif


@endpush