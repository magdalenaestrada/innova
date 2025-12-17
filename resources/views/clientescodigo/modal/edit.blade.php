{{-- <div class="modal fade text-left" id="ModalEdit{{ $cliente->id }}"  role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">

            <div class="card-header row justify-content-between">
                <div class="col-md-6">
                    <h6 class="mt-2">
                        {{ __('EDITAR SOCIEDAD') }}
                    </h6>
                </div>
                <div class="col-md-6 text-right">
                    <button type="button" style="font-size: 30px" class="close" data-dismiss="modal"
                        aria-label="Close">
                        <img style="width: 15px" src="{{ asset('images/icon/close.png') }}" alt="cerrar">
                    </button>
                </div>
            </div>

            <div class="card-body">
                <form class="editar-cuenta" action="{{ route('lqsociedades.update', $cliente->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="row">

                        <div class="form mb-1 col-md-6">
                            <label for="codigo" class="text-sm">{{ __('CODIGO') }}</label>
                            <input name="codigo" value="{{ $cliente->codigo ? $cliente->codigo : '' }}" id="nombre"
                                class=" form-control form-control-sm" placeholder="Documento del beneficiario..."
                                type="text" disabled>
                        </div>

                        <!-- For Documento Beneficiario -->
                        <div class="form mb-1 col-md-6">
                            <label for="nombre" class="text-sm">{{ __('NOMBRE') }}</label>
                            <input name="nombre" value="{{ $cliente->nombre ? $cliente->nombre : '' }}" id="nombre"
                                class=" form-control form-control-sm" placeholder="Documento del beneficiario..."
                                type="text">
                        </div>

                        <div class="col-md-12 text-right g-3 mt-2">
                            <button type="submit" class="btn btn-secondary btn-sm">
                                {{ __('Guardar') }}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div> --}}

{{-- ----------------------------------------------------------------------------------------------------------------------------- --}}

<div class="modal fade text-left" id="ModalEdit{{ $cliente->id }}"  role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">

            <div class="card-header">
                <div class="row justify-content-between">
                    <div class="col-md-6">
                        <h6 class="mt-2">
                            {{ __('EDITAR CODIGO DE CLIENTE') }}
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
                <form class="editar-empleado" action="{{ route('clientescodigo.editar', $cliente->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">Tipo documento</label>
                            <select name="tipo_documento" class="form-control">
    <option value="1" {{ $cliente->tipo_documento == 1 ? 'selected' : '' }}>DNI</option>
    <option value="2" {{ $cliente->tipo_documento == 2 ? 'selected' : '' }}>RUC</option>
</select>


                        </div>
                        <div class="form mb-1 col-md-3">
    <label>Documento</label>
    <input
        name="documento"
        id="documento-{{ $cliente->id }}"
        value="{{ $cliente->documento }}"
        class="form-control"
        type="text"
        inputmode="numeric"
        pattern="[0-9]*"
        maxlength="11"
        oninput="this.value = this.value.replace(/[^0-9]/g, '')"
        required
    >
</div>

<div class="form col-md-6">
    <label>Nombre</label>
    <input
        name="nombre"
        id="nombre-{{ $cliente->id }}"
        value="{{ $cliente->nombre }}"
        class="form-control"
        type="text"
        required
    >
</div>

                    
                        <div class="col-md-12 text-right mt-1">
                            <button type="submit" class="btn btn-secondary btn-sm">
                                {{ __('EDITAR') }}
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
        $('.buscador').select2({theme: "classic"});
    });
</script>

<script>

$(document).ready(function() {
    function isRucOrDni(value) {
        return value.length === 8 || value.length === 11;
    }

    function buscarDocumento(url, inputId, datosId) {
        var inputValue = $(inputId).val();
        var tipoDocumento = inputValue.length === 8 ? 'dni' : 'ruc';

        // Realizar la solicitud AJAX al controlador
        $.ajax({
            url: url,
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                documento: inputValue,
                tipo_documento: tipoDocumento
            },
            success: function(response) {
                console.log('1', 'API Response:', response);
                // Manejar la respuesta del controlador
                if (tipoDocumento === 'dni') {
                    $(datosId).val(response.nombres + ' ' + response.apellidoPaterno + ' ' +
                        response.apellidoMaterno);
                } else {
                    $(datosId).val(response.razonSocial);
                }

                $(datosId).removeClass('is-invalid').addClass('is-valid');
            },
            error: function(xhr, status, error) {
                // Manejar el error de la solicitud
                console.log('3' ,xhr.responseText);
                $(datosId).val('');
                $(datosId).removeClass('is-valid').addClass('is-invalid');
            }
        });
    }

    $('#documento').on('input', function() {
        var inputLength = $(this).val().length;
        if (inputLength === 8 || inputLength === 11) {
            buscarDocumento('{{ route('buscar.documento') }}', '#documento', '#nombre');
        }
    });
    // Validar ruc o dni y cambiar el borde a verde al llenar los campos
    $('#documento').off('input').on('input', function() {
    var inputLength = $(this).val().length;
    if (inputLength === 8 || inputLength === 11) {
        buscarDocumento('{{ route('buscar.documento') }}', '#documento', '#nombre');
    }
});

    // Cambiar el borde a verde cuando se llenen los campos datos_cliente
    $('.datos-input').on('input', function() {
        var value = $(this).val();
        $(this).toggleClass('is-valid', value.trim().length > 0);
        $(this).toggleClass('is-invalid', value.trim().length === 0);
    });
});

</script>





{{-- @if($errors->any())
            <script>
                Swal.fire({
                    icon: 'error',
                    title: 'Error de validación',
                    html: '@foreach($errors->all() as $error)<p>{{ $error }}</p>@endforeach',
                });
            </script>
@endif
 --}}



@endpush

