<div class="modal fade text-left" id="ModalExportFechas" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="card-header">
                <div class="row justify-content-between">
                    <div class="col-md-6">
                        <h6 class="mt-2">
                            {{ __('REPORTE POR FECHAS DE CAJA') }}
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
                <form class="crear-accion" action="{{ route('tsmiscajas.export-excel-por-fechas') }}" method="GET">
                    @csrf
                    <div class="row">
                        <div class="form-group col-md-4 g-3">
                            <label for="caja" >{{ __('CAJA') }}</label>
                            <select name="caja_id" id="caja54" required
                                class="form-control buscador @error('caja') is-invalid @enderror" style="width: 100%">
                                <option selected value="">Seleccione la caja</option>
                                @foreach ($cajas as $caja)
                                    <option value="{{ $caja->id }}"
                                        {{ old('caja') == $caja->id ? 'selected' : '' }}>
                                        {{ $caja->nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group col-md-4 g-3" >
                            <label for="fecha_inicio" >{{ __('FECHA INICIO') }}</label>
                            <input type="date" required class="form-control form-group form-control-sm" name="fecha_inicio">
                        </div>


                        <div class="form-group col-md-4 g-3" >
                            <label for="reposicion" class="">{{ __('FECHA FIN') }}</label>
                            <input type="date" required class="form-control form-group form-control-sm" name="fecha_fin">
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
</div>
