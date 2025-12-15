<div class="modal fade text-left" id="ModalRecepcionar{{ $inventarioingreso->id }}" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
                <div class="card-header">
                    <div class="row justify-content-between">
                        <div class="col-md-6">
                            <h6 class="mt-2">{{ __('RECEPCIONAR ORDEN DE COMPRA') }}</h6>
                        </div>
                        <div class="col-md-6 text-right">
                            <button type="button" style="font-size: 30px" class="close" data-dismiss="modal" aria-label="Close">
                                <img style="width: 15px" src="{{ asset('images/icon/close.png') }}" alt="cerrar">
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('inventarioingresos.updaterecepcionar', $inventarioingreso->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="form-group col-md-3 g-3">
                                <label for="inventarioingreso_fin" class="text-sm">{{ __('FECHA DE CREACIÓN') }}</label>
                                <input class="form-control form-control-sm" value="{{ $inventarioingreso->created_at }}" disabled>
                            </div>
                            <div class="form-group col-md-3 g-3">
                                <label for="inventarioingreso_fin" class="text-sm">{{ __('ESTADO DE LA ORDEN') }}</label>
                                <input class="form-control form-control-sm" value="{{ $inventarioingreso->estado }}" disabled>
                            </div>
                            <div class="form-group col-md-3 g-3">
                                <label for="inventarioingreso_fin" class="text-sm">{{ __('CREADOR DE LA ORDEN') }}</label>
                                <input class="form-control form-control-sm" value="{{ $inventarioingreso->usuario_ordencompra }}" disabled>
                            </div>
                            <div class="form-group col-md-3 g-3">
                                <label for="inventarioingreso_fin" class="text-sm">{{ __('ORDEN PAGADA POR') }}</label>
                                <input class="form-control form-control-sm" value="{{ $inventarioingreso->usuario_cancelacion }}" disabled>
                            </div>
                            <div class="form-group col-md-12 g-3">
                                <label for="descripcion" class="text-sm">{{ __('DESCRIPCIÓN') }}</label>
                                <textarea class="form-control form-control-sm" disabled>{{ $inventarioingreso->descripcion ? $inventarioingreso->descripcion : 'No hay observación' }}</textarea>
                            </div>
                            
                            @if (count($inventarioingreso->productos) > 0)
                                <table class="table table-responsive table-striped table-hover">
                                    <thead>
                                        <tr class="text-center">
                                            <th>{{ __('SELECCIONAR') }}</th>
                                            <th>{{ __('INGRESAR CANTIDAD RECIBIDA') }}</th>
                                            <th>{{ __('PRODUCTO DEL REQUERIMIENTO') }}</th>
                                            <th>{{ __('CANTIDAD') }}</th>
                                            <th>{{ __('CANTIDAD RECIBIDA') }}</th>
                                            <th>{{ __('CANTIDAD POR RECIBIR') }}</th>
                                            <th>{{ __('FECHA DE CREACIÓN') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($inventarioingreso->productos as $index => $producto)
                                            @php
                                                $cantidad_por_recibir = $producto->pivot->cantidad - $producto->pivot->cantidad_ingresada;
                                            @endphp
                                            <tr class="text-center">
                                                <td>
                                                    @if($cantidad_por_recibir != 0)
                                                        <input type="checkbox" name="selected_products[{{ $index }}]" value="{{ $index }}" class="product-checkbox">
                                                    @else
                                                        COMPLETO
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="input-fields" style="display: none;">
                                                        <input type="text" required  name="qty_arrived[{{ $index }}]" class="form-control-sm additional-info form-control input-sm">
                                                    </div>
                                                </td>
                                                <td>{{ $producto->nombre_producto }}</td>
                                                <td>{{ $producto->pivot->cantidad }}</td>
                                                <td>{{ $producto->pivot->cantidad_ingresada }}</td>
                                                <td>{{ $cantidad_por_recibir }}</td>
                                                <td>{{ $producto->pivot->created_at }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @endif

                            <div class="form-group col-md-4 g-3 text-sm">
                                <label for="guiaingresoalmacen">{{ __('GUIA DE INGRESO AL ALMACEN') }}</label>
                                <span class="text-danger">(*)</span>
                                <input class="form-control form-control-sm" required type="text" name="guiaingresoalmacen">
                            </div>

                            <div class="col-md-12 text-right g-3 ">
                                <button type="submit" class="btn btn-sm btn-warning ">{{ __('CONFIRMAR INGRESO DE LOS PRODUCTOS AL ALMACEN') }}</button>
                            </div>
                        </div>
                    </form>
                </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var checkboxes = document.querySelectorAll('.product-checkbox');

        checkboxes.forEach(function(checkbox) {
            checkbox.addEventListener('change', function() {
                var inputFields = this.parentNode.parentNode.querySelector('.input-fields');

                if (this.checked) {
                    inputFields.style.display = 'block';
                } else {
                    inputFields.style.display = 'none';
                    inputFields.querySelector('input').value = '';
                }
            });
        });
    });
</script>
