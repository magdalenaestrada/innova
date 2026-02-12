<div class="modal fade text-left" id="ModalCreate" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">

            <div class="card-header">
                <div class="row justify-content-between">
                    <div class="col-md-6">
                        <h6 class="mt-2">
                            {{ __('REGISTRAR REPOSICIÓN DE CAJA') }}
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
                <form class="crear-reposicion" action="{{ route('tsreposicionescajas.store') }}" method="POST">
                    @csrf
                    <div class="row">

                        {{-- ✅ CAJA --}}
                        <div class="form-group col-md-6 g-3">
                            <label for="caja" class="text-sm">{{ __('CAJA') }}</label>
                            <br>
                            <select name="caja_id" id="caja"
                                class="form-control form-control-sm buscador @error('caja_id') is-invalid @enderror"
                                style="width: 100%" required>
                                <option value="">Seleccione la caja</option>

                                @foreach ($cajas as $caja)
                                    <option value="{{ $caja->id }}"
                                        data-moneda="{{ $caja->tipo_moneda_id }}"
                                        {{ old('caja_id') == $caja->id ? 'selected' : '' }}>
                                        {{ $caja->nombre }} ({{ $caja->tipoMoneda->nombre ?? '' }})
                                    </option>
                                @endforeach
                            </select>

                            @error('caja_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- ✅ CUENTA DE PROCEDENCIA --}}
                        <div class="form-group col-md-6 g-3">
                            <label for="cuenta_procedencia" class="text-sm">{{ __('CUENTA DE PROCEDENCIA') }}</label>
                            <br>
                            <select name="cuenta_procedencia_id" id="cuenta_procedencia"
                                class="form-control form-control-sm buscador @error('cuenta_procedencia_id') is-invalid @enderror"
                                style="width: 100%" required>
                                <option value="">Seleccione la cuenta de procedencia</option>

                                @foreach ($cuentas as $cuenta)
                                    <option value="{{ $cuenta->id }}"
                                        data-moneda="{{ $cuenta->tipo_moneda_id }}"
                                        {{ old('cuenta_procedencia_id') == $cuenta->id ? 'selected' : '' }}>
                                        {{ $cuenta->nombre }} ({{ $cuenta->tipomoneda->nombre ?? '' }})
                                    </option>
                                @endforeach
                            </select>

                            @error('cuenta_procedencia_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- TIPO COMPROBANTE --}}
                        <div class="form-group col-md-6 g-3">
                            <label for="tipo_comprobante" class="text-sm">{{ __('TIPO DE COMPROBANTE') }}</label>
                            <br>
                            <select name="tipo_comprobante_id" id="tipo_comprobante"
                                class="form-control form-control-sm buscador @error('tipo_comprobante_id') is-invalid @enderror"
                                style="width: 100%">
                                <option value="">Seleccione el tipo de comprobante</option>
                                @foreach ($tiposcomprobantes as $tipocomprobante)
                                    <option value="{{ $tipocomprobante->id }}"
                                        {{ old('tipo_comprobante_id') == $tipocomprobante->id ? 'selected' : '' }}>
                                        {{ $tipocomprobante->nombre }}
                                    </option>
                                @endforeach
                            </select>

                            @error('tipo_comprobante_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form col-md-6 mb-3" style="margin-top: 30px">
                            <input name="comprobante_correlativo" id="comprobante_correlativo"
                                class="form-control form-control-sm"
                                placeholder="Ingrese el correlativo del comprobante" type="text"
                                value="{{ old('comprobante_correlativo') }}">
                            <span class="input-border"></span>
                        </div>

                        {{-- MOTIVO --}}
                        <div class="form-group col-md-12 g-3">
                            <label for="motivo" class="text-sm">{{ __('MOTIVO') }}</label>
                            <br>
                            <select name="motivo_id" id="motivo"
                                class="form-control form-control-sm buscador @error('motivo_id') is-invalid @enderror"
                                style="width: 100%" required>
                                <option value="">Seleccione el motivo</option>
                                @foreach ($motivos as $motivo)
                                    <option value="{{ $motivo->id }}"
                                        {{ old('motivo_id') == $motivo->id ? 'selected' : '' }}>
                                        {{ $motivo->nombre }}
                                    </option>
                                @endforeach
                            </select>

                            @error('motivo_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form col-md-12 mb-3">
                            <input name="descripcion" id="descripcion" class="form-control form-control-sm"
                                placeholder="Ingrese la descripción" type="text"
                                value="{{ old('descripcion') }}">
                            <span class="input-border"></span>
                        </div>

                        <div class="form col-md-12 mb-3">
                            <input name="monto" id="monto" class="form-control form-control-sm"
                                placeholder="Ingrese el monto" required type="text"
                                value="{{ old('monto') }}">
                            <span class="input-border"></span>
                        </div>

                        <div class="col-md-12 text-right g-3">
                            <button type="submit" class="btn btn-secondary btn-sm">
                                {{ __('GUARDAR') }}
                            </button>
                        </div>

                    </div>
                </form>
            </div>

        </div>
    </div>
</div>

@push('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
$(document).ready(function() {

    // ✅ Select2 dentro del modal
    $('.buscador').select2({
        theme: "classic",
        dropdownParent: $('#ModalCreate')
    });

    function filtrarCuentasPorMoneda(monedaId) {
        const $cuenta = $('#cuenta_procedencia');

        $cuenta.find('option').each(function () {
            const val = $(this).val();
            if (!val) return; // placeholder

            const m = $(this).data('moneda');
            $(this).prop('disabled', String(m) !== String(monedaId));
        });

        // reset si la seleccionada no coincide
        const sel = $cuenta.find('option:selected').data('moneda');
        if (sel && String(sel) !== String(monedaId)) {
            $cuenta.val('').trigger('change');
        }

        $cuenta.trigger('change.select2');
    }

    // ✅ al cambiar caja, filtra cuentas por moneda
    $('#caja').on('change', function () {
        const monedaCaja = $(this).find('option:selected').data('moneda');
        if (monedaCaja) filtrarCuentasPorMoneda(monedaCaja);
    });

    // ✅ si viene precargado (old), filtra al abrir
    const monedaInicial = $('#caja').find('option:selected').data('moneda');
    if (monedaInicial) filtrarCuentasPorMoneda(monedaInicial);

    // Confirmación con Swal
    $('.crear-reposicion').submit(function(e) {
        e.preventDefault();
        Swal.fire({
            title: '¿Crear Reposición de la caja?',
            icon: 'info',
            showCancelButton: true,
            confirmButtonColor: '#007777',
            cancelButtonColor: '#d33',
            confirmButtonText: '¡Sí, confirmar!',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                this.submit();
            }
        });
    });

});
</script>

@if ($errors->any())
<script>
    Swal.fire({
        icon: 'error',
        title: 'Error de validación',
        html: '@foreach ($errors->all() as $error)<p>{{ $error }}</p>@endforeach',
    });
</script>
@endif
@endpush
