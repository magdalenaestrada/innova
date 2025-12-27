@push('css')
    <style>
        /* Ocultar el input radio real pero mantenerlo accesible */
        .btn-check {
            position: absolute;
            clip: rect(0, 0, 0, 0);
            pointer-events: none;
        }

        /* Estilo cuando el radio NO está seleccionado (Outline style) */
        .btn-check+.btn-outline-primary {
            color: #007bff;
            border-color: #007bff;
            background-color: transparent;
        }

        /* Estilo cuando el radio SÍ está seleccionado (Estado Activo) */
        .btn-check:checked+.btn-outline-primary {
            color: #fff;
            background-color: #007bff;
            border-color: #007bff;
            /* box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.5); */
        }

        /* Hover effect */
        .btn-check+.btn-outline-primary:hover {
            background-color: #007bff;
            color: #fff;
        }
    </style>
    <style>
        .bloque-formulario {
            transition: opacity 0.3s ease-in-out;
            opacity: 1;
        }
    </style>
@endpush

<div class="modal fade text-left" id="ModalCreate" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">

            <div class="card-header">
                <div class="row justify-content-between">
                    <div class="col-md-6">
                        <h6 class="mt-2">
                            {{ __('REGISTRO DE ENTRADA') }}
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
                {{-- <div class="row justify-content-center">
                    <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="pills-home-tab" data-toggle="pill"
                                data-target="#pills-home" type="button" role="tab" aria-controls="pills-home"
                                aria-selected="true">Home</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="pills-profile-tab" data-toggle="pill"
                                data-target="#pills-profile" type="button" role="tab" aria-controls="pills-profile"
                                aria-selected="false">Profile</button>
                        </li>
                    </ul>
                </div> --}}

                <form class="crear-detalles" action="{{ route('controlgarita.guardar') }}" method="POST">

                    @csrf
                    <div class="row">
                        <div>
                            <input type="hidden" name="tipo_movimiento" value="E">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">Tipo entidad</label>
                            <select name="tipo_entidad" id="select_tipo_entidad"
                                class="form-control form-select-sm estado-select w-150">
                                <option value="P">Persona</option>
                                <option value="V">Vehículo</option>
                            </select>
                        </div>
                        <div class="form col-md-3">
                            <label>Hora</label>
                            <input name="hora" id="hora" class="form-control" type="time" required>
                        </div>
                    </div>
                    <br>
                    <div id="persona" class="bloque-formulario">
                        <div class="row">
                            <div class="col-md-3">
                                <label class="form-label fw-semibold">Tipo documento</label>
                                <select name="tipo_documento" class="form-control form-select-sm estado-select w-150">
                                    <option value="">Seleccione...</option>
                                    <option value="0">DNI</option>
                                    <option value="1">RUC</option>
                                </select>
                            </div>
                            <div class="form mb-1 col-md-3">
                                <label>Documento</label>
                                <input name="documento" id="documento" class="form-control" type="text"
                                    inputmode="numeric" pattern="[0-9]*" maxlength="11"
                                    oninput="this.value = this.value.replace(/[^0-9]/g, '')" required>
                            </div>
                            <div class="form col-md-6">
                                <label>Nombre</label>
                                <input name="nombre" id="nombre" class="form-control" type="text" required>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-md-12">
                                <label class="form-label fw-semibold">Ocurrencia</label>
                                <textarea name="ocurrencias" class="form-control" rows="3" required></textarea>
                            </div>
                        </div>
                    </div>
                    <div id="vehiculo" class="bloque-formulario">
                        <div class="row">
                            <div class="col-md-3">
                                <label class="form-label fw-semibold">Tipo Vehículo</label>
                                <select name="tipo_vehiculo" class="form-control form-select-sm estado-select w-150">
                                    <option value="">Seleccione...</option>
                                    <option value="0">Auto</option>
                                    <option value="1">Minivan</option>
                                    <option value="2">Camioneta</option>
                                    <option value="3">Volquete</option>
                                    <option value="4">Encapsulado</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-semibold">Placa</label>
                                <input name="placa" id="placa" class="form-control" type="text" required>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-md-3">
                                <label class="form-label fw-semibold">Tipo documento</label>
                                <select name="tipo_documento" class="form-control form-select-sm estado-select w-150">
                                    <option value="">Seleccione...</option>
                                    <option value="0">DNI</option>
                                    <option value="1">RUC</option>
                                </select>
                            </div>
                            <div class="form mb-1 col-md-3">
                                <label class="form-label fw-semibold">Documento</label>
                                <input name="documento" id="documento" class="form-control" type="text"
                                    inputmode="numeric" pattern="[0-9]*" maxlength="11"
                                    oninput="this.value = this.value.replace(/[^0-9]/g, '')" required>
                            </div>
                            <div class="form col-md-6">
                                <label class="form-label fw-semibold">Conductor</label>
                                <input name="nombre" id="nombre" class="form-control" type="text" required>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-md-3">
                                <label class="form-label fw-semibold">¿Trae Carga?</label><br>
                                <div class="btn-group w-100" role="group" aria-label="Trae carga">
                                    <input type="radio" class="btn-check" name="trae_carga" id="trae_carga_no"
                                        value="0" autocomplete="off" checked>
                                    <label class="btn btn-outline-primary btn-carga" for="trae_carga_no">No</label>

                                    <input type="radio" class="btn-check" name="trae_carga" id="trae_carga_si"
                                        value="1" autocomplete="off">
                                    <label class="btn btn-outline-primary btn-carga" for="trae_carga_si">Sí</label>
                                </div>
                                {{-- <div class="btn-group w-100" role="group">
                                    <button type="button" name="carga" class="btn btn-primary btn-carga" data-value="1">Sí</button>
                                    <input type="hidden" name="trae_carga" id="trae_carga" value="1">
                                    <button type="button" name="carga" type="button" class="btn btn-outline-primary btn-carga"
                                        data-value="0">No</button>
                                </div> --}}
                            </div>
                        </div>
                        <div class="row d-none" id="detalles_carga">
                            <div class="form col-md-4">
                                <br>
                                <label class="form-label fw-semibold">Tipo carga</label>
                                <select name="tipo_carga" class="form-control form-select-sm estado-select w-150">
                                    <option value="">Seleccione...</option>
                                    <option value="0">Mineral chancado</option>
                                    <option value="1">Mineral a granel</option>
                                </select>
                            </div>
                            <div class="form col-md-4">
                                <br>
                                <label class="form-label fw-semibold">Destino</label>
                                <input name="destino" id="destino" class="form-control" type="text" required>
                            </div>
                            {{-- <div class="form col-md-4">
                                <label class="form-label fw-semibold">Servicio</label>
                                <input name="servicio" id="servicio" class="form-control" type="text" required>
                            </div> --}}
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-md-12">
                                <label class="form-label fw-semibold">Ocurrencia</label>
                                <textarea name="ocurrencias" class="form-control" rows="3"></textarea>
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-md-12 text-right mt-1">
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
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        $(() => {
            //Función cambiar de formulario persona/vehiculo
            function toggleEntidad() {
                let tipo = $('#select_tipo_entidad').val();

                if (tipo === 'P') {
                    $('#vehiculo').fadeOut(1, () => {
                        $('#vehiculo').find('input[type = "text"], select, textarea').val('');
                        $('#vehiculo').find('input, select, textarea').prop('disabled', true);
                    });

                    $('#persona').fadeIn(1);
                    $('#persona').find('input, select, textarea').prop('disabled', false);
                } else if (tipo === 'V') {
                    $('#persona').fadeOut(1, () => {
                        $('#persona').find('input, select, textarea').prop('disabled', true).val('');
                    });

                    $('#vehiculo').fadeIn(1);
                    $('#vehiculo').find('input, select, textarea').prop('disabled', false);

                    if (!$('input[name = "trae_carga"]').is(':checked')) {
                        $('trae_carga_no').prop('checked', true);
                    }

                    toggleCargaFields();
                }
            }

            toggleEntidad();

            $('#select_tipo_entidad').on('change', toggleEntidad);

            //Función mostrar/ocultar form si/no trae carga
            function toggleCargaFields() {
                if ($('#select_tipo_entidad').val() === 'V') {
                    if ($('#trae_carga_si').is(':checked')) {
                        $('#detalles_carga').removeClass('d-none');

                        $('#detalles_carga').find('input, select')
                            .prop('disabled', false)
                            .prop('required', true);
                    } else {
                        $('#detalles_carga').addClass('d-none');

                        $('#detalles_carga').find('input, select')
                            .prop('disabled', true)
                            .prop('required', false).val('');
                    }
                }
            }

            $('input[name="trae_carga"]').on('change', toggleCargaFields);

            //Hora actual en input type="time"
            const inputHora = document.getElementById("hora");
            const ahora = new Date();

            const horas = String(ahora.getHours()).padStart(2, '0');
            const minutos = String(ahora.getMinutes()).padStart(2, '0');

            inputHora.value = `${horas}:${minutos}`;

            //Reemplaza valor si esta vacio en ocurrencias
            $('input[name="ocurrencias"]').on('input', () => {
                const textValue = this.value || "-";
            });
        });

        // $(document).ready(() => {
        //     function toggleCargaFields() {
        //         if ($('#select_tipo_entidad').val() === 'V') {
        //             if ($('#trae_carga_si').is(':checked')) {
        //                 $('#detalles_carga').removeClass('d-none');

        //                 $('detalles_carga').find('input, select')
        //                     .prop('disabled', false)
        //                     .prop('required', true);

        //                 $('select[name="tipo_carga"]')
        //                     .prop('disabled', false)
        //                     .prop('required', true);
        //                 $('input[name="destino"]')
        //                     .prop('disabled', false)
        //                     .prop('required', true);
        //             } else {
        //                 $('#detalles_carga').addClass('d-none');

        //                 $('#detalles_carga').find('input, select')
        //                     .prop('disabled', true)
        //                     .prop('required', false).val('');

        //                 $('select[name="tipo_carga"]')
        //                     .prop('disabled', true)
        //                     .prop('required', false);
        //                 $('input[name="destino"]')
        //                     .prop('disabled', true)
        //                     .prop('required', false);

        //                 $('select[name="tipo_carga"]').val('');
        //                 $('input[name="destino"]').val('');
        //             }
        //         }
        //     }
        //     toggleCargaFields();

        //     $('input[name="trae_carga"]').on('change', () => {
        //         toggleCargaFields()
        //     });
        // });

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
                        console.log('3', xhr.responseText);
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

    {{-- <script>
        $(document).ready(function() {
            $('.buscador').select2({
                theme: "classic"
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
    @endif --}}
@endpush
