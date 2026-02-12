<div class="modal fade text-left" id="ModalCreate" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">

            <div class="card-header">
                <div class="row justify-content-between">
                    <div class="col-md-6">
                        <h6 class="mt-2">
                            {{ __('CREAR CAJA') }}
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
                <form class="crear-caja" action="{{ route('tscajas.store') }}" method="POST">
                    @csrf
                    <div class="row">

                        <div class="form col-md-12 mb-3">
                            <input name="nombre" id="nombre" class="form-control form-control-sm"
                                placeholder="Ingrese el nombre de la caja" required type="text">
                            <span class="input-border"></span>
                        </div>

                        {{-- ✅ MONEDA (SOLES / DÓLARES) --}}
                        <div class="form-group col-md-12 g-3 mb-2">
                            <label for="tipo_moneda_id" class="text-sm">
                                {{ __('MONEDA') }}
                            </label>
                            <select name="tipo_moneda_id" id="tipo_moneda_id"
                                class="form-control form-control-sm buscador @error('tipo_moneda_id') is-invalid @enderror"
                                style="width:100%" required>
                                <option value="">Seleccione la moneda</option>
                                @foreach($tiposmonedas as $moneda)
                                    <option value="{{ $moneda->id }}" {{ old('tipo_moneda_id') == $moneda->id ? 'selected' : '' }}>
                                        {{ strtoupper($moneda->nombre) }}
                                    </option>
                                @endforeach
                            </select>
                            @error('tipo_moneda_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group col-md-12 g-3 mb-2" id="select_container">
                            <label for="encargados" class="text-sm">
                                {{ __('ENCARGADOS') }}
                            </label>
                            <select name="encargados[]" id="encargados"
                                class="form-control form-control-sm encargado buscador2 @error('encargados') is-invalid @enderror"
                                multiple style="width:100%" required>
                                @foreach ($empleados as $empleado)
                                    <option value="{{ $empleado->id }}" {{ collect(old('encargados'))->contains($empleado->id) ? 'selected' : '' }}>
                                        {{ strtoupper($empleado->nombre) }}
                                    </option>
                                @endforeach
                            </select>
                            @error('encargados')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-12 text-right g-3">
                            <button type="submit" class="btn btn-secondary btn-sm">
                                {{ __('CREAR CAJA') }}
                            </button>
                        </div>

                    </div>
                </form>
            </div>

        </div>
    </div>
</div>

@push('js')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
$(document).ready(function() {
    $('.buscador').select2({ theme: "classic", dropdownParent: $('#ModalCreate') });
    $('.buscador2').select2({ theme: "classic", dropdownParent: $('#ModalCreate') });
});
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
