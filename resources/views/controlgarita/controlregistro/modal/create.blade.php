@push('css')
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.4.3/dist/css/tom-select.css" rel="stylesheet">
    <style>
        .bloque-formulario-crt {
            transition: opacity 0.3s ease-in-out;
            opacity: 1;
        }
    </style>
    <style>
        #ModalCreate .modal-content-crt {
            border-radius: 18px;
            background-color: #f4f6f9;
            border: none;
            overflow: hidden;
        }

        #ModalCreate .form-control {
            border-radius: 10px !important;
        }

        /*TOMB SELECT*/
        #ModalCreate .ts-control {
            border-radius: 10px !important;
            min-height: calc(1.5em + 0.75rem + 2px);
            border: 1px solid #ced4da;
            padding: 0 0 0 15px !important;
            font-size: 16px;
        }

        #select_tipo_vehiculo_crt + .ts-wrapper .ts-control .item {
            padding: 0.375rem 0;
            margin: 0;
            line-height: 1.5;
        }

        #select_tipo_vehiculo_crt + .ts-wrapper .ts-control input {
            padding: 0.375rem 0.75rem;
            line-height: 1.5;
        }

        #select_tipo_vehiculo_crt + .ts-wrapper .ts-control input::placeholder {
            font-size: 16px;
        }

        #select_tipo_mineral_crt + .ts-wrapper .ts-control .item {
            padding: 0.375rem 0;
            margin: 0;
            line-height: 1.5;
        }

        #select_tipo_mineral_crt + .ts-wrapper .ts-control input {
            padding: 0.375rem 0.75rem;
            line-height: 1.5;
        }

        #select_tipo_mineral_crt + .ts-wrapper .ts-control input::placeholder {
            font-size: 16px;
        }

        #ModalCreate .card-header-crt {
            background: transparent;
            border-bottom: none;
            border-radius: 18px 18px 0 0;
        }

        #ModalCreate .custom-tabs-crt {
            display: flex;
            border-bottom: none;
            margin: 0;
            padding: 0 12px;
        }

        #ModalCreate .custom-tabs-crt .nav-item-crt {
            flex: 1;
            text-align: center;
        }

        #ModalCreate .custom-tabs-crt .nav-link-crt {
            width: 100%;
            border: none;
            border-radius: 14px 14px 0 0;
            background: #ffffff;
            color: #555;
            font-weight: 600;
            padding: 12px 0;
            transition: all 0.25s ease;
        }

        #ModalCreate .custom-tabs-crt .nav-link-crt:hover {
            background: #dcdfe4;
            color: #111;
        }

        #ModalCreate .custom-tabs-crt .nav-link-crt.active {
            background: #ffffff;
            color: #000;
            z-index: 2;
            box-shadow: inset 0 4px 10px rgba(0,0,0,0.15);
        }

        #ModalCreate .card-body-crt {
            background: #ffffff;
            border-radius: 18px 18px 18px 18px;
            margin-top: -2px;
            padding-top: 25px;
            box-shadow: 0 1px 40px rgba(0,0,0,0.08);
        }

        #ModalCreate .custom-tabs-crt i {
            opacity: 0.7;
        }

        #ModalCreate .custom-tabs-crt .nav-link-crt.active i {
            opacity: 1;
        }

        /*BOTON TRAE CARGA*/
        #ModalCreate .btn-carga-crt {
            border-radius: 10px;
            font-weight: 600;
            border: 1px solid #ced4da;
            background-color: #f8f9fa;
            color: #495057;
            transition: all 0.2s ease;
        }

        #ModalCreate .btn-carga-crt:hover {
            background-color: #e9ecef;
        }

        #ModalCreate .btn-check-crt {
            position: absolute;
            clip: rect(0, 0, 0, 0);
            pointer-events: none;
        }

        #ModalCreate .btn-check-crt:checked + .btn-carga-crt {
            background-color: #6c757d;
            color: #fff;
            border-color: #6c757d;
            box-shadow: 0 3px 8px rgba(0,0,0,0.15);
        }

        #ModalCreate .btn-group-crt {
            gap: 6px;
        }

        @media (max-width: 576px) {
            #ModalCreate .custom-tabs-crt .nav-link-crt {
                font-size: 14px;
                padding: 10px 0;
            }
        }

        .form-animated-crt {
            animation: fadeSlide 0.25s ease both;
        }

        @keyframes fadeSlide {
            from {
                opacity: 0;
                transform: translateY(8px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
@endpush

<div class="modal fade text-left" id="ModalCreate" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content modal-content-crt">
            <div class="card-header card-header-crt">
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
            <div class="card-body card-body-crt">
                <ul class="nav nav-tabs custom-tabs-crt mb-3" id="tipoMovimientoTab-crt" role="tablist">
                    <li class="nav-item nav-item-crt">
                        <a class="nav-link nav-link-crt active"
                        data-toggle="tab"
                        href="#tab-entrada-crt"
                        data-tipo="E"
                        role="tab">
                            <i class="fas fa-sign-in-alt mr-1"></i> Entrada
                        </a>
                    </li>

                    <li class="nav-item nav-item-crt">
                        <a class="nav-link nav-link-crt"
                        data-toggle="tab"
                        href="#tab-salida-crt"
                        data-tipo="S"
                        role="tab">
                            <i class="fas fa-sign-out-alt mr-1"></i> Salida
                        </a>
                    </li>
                </ul>

                <div class="tab-content d-none">
                    <div class="tab-pane show active" id="tab-entrada-crt"></div>
                    <div class="tab-pane" id="tab-salida-crt"></div>
                </div>

                <form class="crear-detalles" action="{{ route('detcontrolgarita.guardar') }}" method="POST">
                    @csrf
                    
                    <div>
                        <input type="hidden" name="tipo_movimiento" id="tipo_movimiento-crt" value="E">
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">Tipo entidad</label>
                            <select name="tipo_entidad" id="select_tipo_entidad_crt"
                                class="form-control form-select-sm estado-select w-150">
                                <option value="P">Persona</option>
                                <option value="V">Vehículo</option>
                            </select>
                        </div>
                        <div class="form col-md-3">
                            <label>Hora</label>
                            <input name="hora" id="hora_crt" class="form-control hora" type="time" required>
                        </div>
                        <div class="form col-md-6">
                            <label>Etiqueta</label>
                            <select name="etiqueta_id" id="etiqueta_id_crt" class="tom-select">
                                <option value="">Seleccione un color...</option>
                                @foreach ($etiquetas as $etiqueta)
                                    <option value="{{ $etiqueta->id }}" data-color="{{ $etiqueta->color }}">{{ $etiqueta->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <br>
                    <!-- FORMULARIO PERSONA -->
                    <div id="form_persona_crt" class="bloque-formulario-crt">
                        <div class="row">
                            <div class="col-md-3">
                                <label class="form-label fw-semibold">Tipo documento</label>
                                <select name="tipo_documento" id="select_tipo_documento_persona_crt" class="form-control form-select-sm estado-select w-150">
                                    <option value="" disabled hidden>Seleccione...</option>
                                    <option value="1">DNI</option>
                                    <option value="2">RUC</option>
                                </select>
                            </div>
                            <div class="form mb-1 col-md-3">
                                <label>Documento</label>
                                <input name="documento" id="documento_persona_crt" class="form-control documento-input-crt" type="text"
                                    inputmode="numeric" pattern="[0-9]*" maxlength="11" placeholder="DNI / RUC"
                                    oninput="this.value = this.value.replace(/[^0-9]/g, '')" required>
                            </div>
                            <div class="form col-md-6">
                                <label>Nombre</label>
                                <input name="nombre" id="nombre_persona_crt" class="form-control nombre-input-crt" type="text" placeholder="Ej: Vega Nieto Marco Antonio" required>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-md-12">
                                <label class="form-label fw-semibold">Ocurrencia</label>
                                <textarea name="ocurrencias" id="ocurrencias_persona_crt" class="form-control" rows="3"></textarea>
                            </div>
                        </div>
                    </div>
                    <!-- FORMULARIO VEHÍCULO -->
                    <div id="form_vehiculo_crt" class="bloque-formulario-crt">
                        <div class="row">
                            <div class="col-md-3">
                                <label class="form-label fw-semibold">Tipo Vehículo</label>
                                <select name="tipo_vehiculo" id="select_tipo_vehiculo_crt" class="form-select-sm estado-select w-150">
                                    <option value="">Seleccione...</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-semibold">Placa</label>
                                <input name="placa" id="placa_crt" class="form-control" type="text" placeholder="###-###" required>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-md-3">
                                <label class="form-label fw-semibold">Tipo documento</label>
                                <select name="tipo_documento" id="select_tipo_documento_vehiculo_crt" class="form-control form-select-sm estado-select w-150" required>
                                    <option value="" disabled hidden>Seleccione...</option>
                                    <option value="1">DNI</option>
                                    <option value="2">RUC</option>
                                </select>
                            </div>
                            <div class="form mb-1 col-md-3">
                                <label class="form-label fw-semibold">Documento</label>
                                <input name="documento" id="documento_vehiculo_crt" class="form-control documento-input-crt" type="text"
                                    inputmode="numeric" pattern="[0-9]*" maxlength="11" placeholder="DNI / RUC"
                                    oninput="this.value = this.value.replace(/[^0-9]/g, '')" required>
                            </div>
                            <div class="form col-md-6">
                                <label class="form-label fw-semibold">Conductor</label>
                                <input name="nombre" id="nombre_vehiculo_crt" class="form-control nombre-input-crt" type="text" placeholder="Ej: Vega Nieto Marco Antonio" required>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-md-3">
                                <label class="form-label fw-semibold">¿Trae Mineral?</label><br>
                                <div class="btn-group btn-group-crt w-100" role="group" aria-label="Trae carga">
                                    <input type="radio" class="btn-check-crt" name="trae_carga" id="trae_carga_no_crt"
                                        value="0" autocomplete="off" checked>
                                    <label class="btn btn-outline-primary btn-carga-crt" for="trae_carga_no_crt">No</label>

                                    <input type="radio" class="btn-check-crt" name="trae_carga" id="trae_carga_si_crt"
                                        value="1" autocomplete="off">
                                    <label class="btn btn-outline-primary btn-carga-crt" for="trae_carga_si_crt">Sí</label>
                                </div>
                            </div>
                        </div>
                        <div class="row d-none" id="detalles_carga_crt">
                            <div class="form col-md-4">
                                <br>
                                <label class="form-label fw-semibold">Tipo mineral</label>
                                <select name="tipo_mineral" id="select_tipo_mineral_crt" class="form-select-sm estado-select w-150">
                                    <option value="">Seleccione...</option>
                                </select>
                            </div>
                            <div class="form col-md-4">
                                <br>
                                <label class="form-label fw-semibold">Destino</label>
                                <input name="destino" id="destino_crt" class="form-control" type="text" placeholder="Ej: Loza 1" required>
                            </div>
                            <div class="form col-md-4">
                                <br>
                                <label class="form-label fw-semibold">Lote</label>
                                <select name="lote" id="lote_crt" class="form-control form-select-sm estado-select w-150">
                                    <option value="" disabled hidden>Seleccione un lote...</option>
                                    @foreach ($lotes as $lote)
                                        <option value="{{ $lote->id }}">{{ $lote->nombre }}</option>
                                    @endforeach
                                </select>
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
                                <textarea name="ocurrencias" id="ocurrencias_vehiculo_crt" class="form-control" rows="3"></textarea>
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-md-12 text-right mt-1">
                            <button type="button" class="btn btn-light btn-sm" id="btn_cancelar_crt">
                                {{ __('CANCELAR') }}
                            </button>
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
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.4.3/dist/js/tom-select.complete.min.js"></script>

    <script>
        $(() => {
            let selectEtiquetaTS = new TomSelect("#etiqueta_id_crt",{
                allowEmptyOption: true,
                controlInput: null,
                render: {
                    option: function(data, escape) {
                        const color = data.$option?.dataset?.color;
                        return `
                            <div style="display: flex; align-items: center; gap: 8px; font-size: 14px;">
                                <span style="background: ${color}; width: 14px; height: 14px; border-radius: 4px; display: inline-block; box-shadow: 2px 4px 4px rgba(0,0,0,0.15);"></span>
                                <span>${escape(data.text)}</span>
                            </div>
                        `;
                    },
                    item: function(data, escape) {
                        const color = data.$option?.dataset?.color;
                        return `
                            <div style="display: flex; align-items: center; gap: 6px; justify-content: space-between; font-size: 16px; width: 100%;">
                                <span>${escape(data.text)}</span>
                                <span style="background: ${color}; width: 30px; height: 100%; transform-origin: left; transform: scaleX(2) scaleY(2); display: inline-block; box-shadow: -2px 0 6px rgba(0,0,0,0.15);"></span>
                            </div>
                        `;
                    }
                }
            });
            
            let selectTipoVehiculoTS = new TomSelect("#select_tipo_vehiculo_crt",{
                // plugins: ['dropdown_input'],
                valueField: 'id',
                labelField: 'nombre',
                searchField: 'nombre',
                create: true,
                preload: true,
                persist: true,
                load: function(query, callback) {
                    $.ajax({
                        url: '{{ route("controlgarita.tipo-vehiculo.index") }}',
                        type: 'GET',
                        dataType: 'json',
                        success: function(data) {
                            callback(data);
                        },
                        error: function() {
                            callback();
                        }
                    })
                },
                onOptionAdd: function(value, data) {
                    if ($.isNumeric(value)) return;

                    $.ajax({
                        url: '{{ route("controlgarita.tipo-vehiculo.store") }}',
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            nombre: value
                        },
                        success: function(response) {
                            if (response.success) {
                                const nuevoTipoVehiculo = response.tipoVehiculo;
                                selectTipoVehiculoTS.updateOption(value, {
                                    nombre: nuevoTipoVehiculo.nombre
                                });
                                selectTipoVehiculoTS.addItem(nuevoTipoVehiculo.id);
                            } else {
                                selectTipoVehiculoTS.removeOption(value);
                                alert('Error al crear el tipo de vehículo.');
                            }
                        },
                        error: function(xhr) {
                            selectTipoVehiculoTS.removeOption(value);
                            Swal.fire({
                                icon: 'error',
                                title: 'Error de validación',
                                html: xhr.responseJSON?.message ?? 'Error desconocido',
                            });
                        }
                    })
                },
                render: {
                    option: function(data, escape) {
                        return `<div style="font-size: 14px; margin-left: 7px;">${escape(data.nombre)}</div>`;
                    },
                    item: function(data, escape) {
                        return `<div>${escape(data.nombre)}</div>`;
                    }
                }
            });
            
            let selectTipoMineralTS = new TomSelect("#select_tipo_mineral_crt",{
                // plugins: ['dropdown_input'],
                valueField: 'id',
                labelField: 'nombre',
                searchField: 'nombre',
                create: true,
                preload: true,
                persist: true,
                load: function(query, callback) {
                    $.ajax({
                        url: '{{ route("controlgarita.tipo-mineral.index") }}',
                        type: 'GET',
                        dataType: 'json',
                        success: function(data) {
                            callback(data);
                        },
                        error: function() {
                            callback();
                        }
                    })
                },
                onOptionAdd: function(value, data) {
                    if ($.isNumeric(value)) return;

                    $.ajax({
                        url: '{{ route("controlgarita.tipo-mineral.store") }}',
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            nombre: value
                        },
                        success: function(response) {
                            if (response.success) {
                                const nuevoTipoMineral = response.tipoMineral;
                                selectTipoMineralTS.updateOption(value, {
                                    nombre: nuevoTipoMineral.nombre
                                });
                                selectTipoMineralTS.addItem(nuevoTipoMineral.id);
                            } else {
                                selectTipoMineralTS.removeOption(value);
                                alert('Error al crear el tipo de mineral, success.');
                            }
                        },
                        error: function(xhr) {
                            selectTipoMineralTS.removeOption(value);
                            Swal.fire({
                                type: 'error',
                                title: 'Error de validación',
                                html: xhr.responseJSON?.message ?? 'Error desconocido',
                            });
                        }
                    })
                },
                render: {
                    option: function(data, escape) {
                        return `<div style="font-size: 14px; margin-left: 7px;">${escape(data.nombre)}</div>`;
                    },
                    item: function(data, escape) {
                        return `<div>${escape(data.nombre)}</div>`;
                    }
                }
            });
            
            const $modalCreate = $('#ModalCreate')

            let datosTemporales = {
                persona: {},
                vehiculo: {}
            };

            function buscarDocumento(inputId, outputId) {
                const documento = $(inputId).val();
                if (documento.length !== 8 && documento.length !== 11) return;
                
                const tipoDocumento = documento.length === 8 ? 'dni' : 'ruc';
                
                $.ajax({
                    url: '{{ route("buscar.documento") }}',
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        documento: documento,
                        tipo_documento: tipoDocumento
                    },
                    beforeSend: function() {
                        $(outputId).val('Buscando...');
                    },
                    success: function(response) {
                        if (tipoDocumento === 'dni') {
                            $(outputId).val(`${response.nombres} ${response.apellidoPaterno} ${response.apellidoMaterno}`);
                        } else {
                            $(outputId).val(response.razonSocial);
                        }
                        $(outputId).removeClass('is-invalid').addClass('is-valid');
                    },
                    error: function(xhr) {
                        // console.error('Error al buscar documento:', xhr.responseText);
                        $(outputId).val('No encontrado');
                        $(outputId).removeClass('is-valid').addClass('is-invalid');
                    }
                });
            }

            function guardarDatosTemporales(tipo) {
                if (tipo === 'P') {
                    datosTemporales.persona = {
                        tipo_documento: $('#select_tipo_documento_persona_crt').val(),
                        documento: $('#documento_persona_crt').val(),
                        nombre: $('#nombre_persona_crt').val(),
                        ocurrencias: $('#ocurrencias_persona_crt').val()
                    };
                } else {
                    datosTemporales.vehiculo = {
                        tipo_vehiculo: $('#select_tipo_vehiculo_crt').val(),
                        placa: $('#placa_crt').val(),
                        tipo_documento: $('#select_tipo_documento_vehiculo_crt').val(),
                        documento: $('#documento_vehiculo_crt').val(),
                        nombre: $('#nombre_vehiculo_crt').val(),
                        trae_carga: $('#trae_carga_si_crt').is(':checked') ? 1 : 0,
                        tipo_mineral: $('#select_tipo_mineral_crt').val(),
                        destino: $('#destino_crt').val(),
                        ocurrencias: $('#ocurrencias_vehiculo_crt').val()
                    };
                }
            }

            function restaurarDatosTemporales(tipo) {
                if (tipo === 'P' && Object.keys(datosTemporales.persona).length > 0) {
                    $('#select_tipo_documento_persona_crt').val(datosTemporales.persona.tipo_documento);
                    $('#documento_persona_crt').val(datosTemporales.persona.documento);
                    $('#nombre_persona_crt').val(datosTemporales.persona.nombre);
                    $('#ocurrencias_persona_crt').val(datosTemporales.persona.ocurrencias);
                } else if (tipo === 'V' && Object.keys(datosTemporales.vehiculo).length > 0) {
                    $('#select_tipo_vehiculo_crt').val(datosTemporales.vehiculo.tipo_vehiculo);
                    $('#placa_crt').val(datosTemporales.vehiculo.placa);
                    $('#select_tipo_documento_vehiculo_crt').val(datosTemporales.vehiculo.tipo_documento);
                    $('#documento_vehiculo_crt').val(datosTemporales.vehiculo.documento);
                    $('#nombre_vehiculo_crt').val(datosTemporales.vehiculo.nombre);
                    if (datosTemporales.vehiculo.trae_carga === 1) {
                        $(`#trae_carga_si_crt`).prop('checked', true);
                    } else {
                        $(`#trae_carga_no_crt`).prop('checked', true);
                    }
                    $('#select_tipo_mineral_crt').val(datosTemporales.vehiculo.tipo_mineral);
                    $('#destino_crt').val(datosTemporales.vehiculo.destino);
                    $('#ocurrencias_vehiculo_crt').val(datosTemporales.vehiculo.ocurrencias);
                    toggleCargaFields();
                }
            }

            function toggleEntidad() {
                const tipo = $('#select_tipo_entidad_crt').val();
                guardarDatosTemporales(tipo === 'P' ? 'V' : 'P');

                if (tipo === 'P') {
                    $('#form_vehiculo_crt').hide();
                    $('#form_vehiculo_crt').find('input, select, textarea').prop('disabled', true);

                    $('#form_persona_crt').show();
                    $('#form_persona_crt').find('input, select, textarea').prop('disabled', false);
                    restaurarDatosTemporales('P');
                } else if (tipo === 'V') {
                    $('#form_persona_crt').hide();
                    $('#form_persona_crt').find('input, select, textarea').prop('disabled', true);

                    $('#form_vehiculo_crt').show();
                    $('#form_vehiculo_crt').find('input, select, textarea').prop('disabled', false);

                    restaurarDatosTemporales('V');

                    if (!datosTemporales.vehiculo.trae_carga) {
                        $('#trae_carga_no_crt').prop('checked', true);
                    }

                    toggleCargaFields();
                }
            }

            function toggleCargaFields() {
                if ($('#select_tipo_entidad_crt').val() === 'V') {
                    if ($('#trae_carga_si_crt').is(':checked')) {
                        $('#detalles_carga_crt').removeClass('d-none');
                        $('#destino_crt').prop('required', true);
                        $('#detalles_carga_crt').find('input, select')
                            .prop('disabled', false);
                    } else {
                        $('#detalles_carga_crt').addClass('d-none');
                        $('#destino_crt').prop('required', false);
                        $('#detalles_carga_crt').find('input, select')
                            .prop('disabled', true);
                    }
                }
            }

            function setHoraActual() {
                const now = new Date();
                const hour = String(now.getHours()).padStart(2, '0');
                const minute = String(now.getMinutes()).padStart(2, '0');
                $('#hora_crt').val(`${hour}:${minute}`);
            }

            function resetTomSelects(...selects) {
                selects.forEach(ts => {
                    if(!ts) return;
                    ts.clear();
                    ts.setValue('');
                    ts.close();
                });
            }

            function limpiarModal() {
                datosTemporales = { persona: {}, vehiculo: {} };
                
                // $('.crear-detalles')[0].reset();
                $('#form_persona_crt').find('input, select, textarea').val('');
                $('#select_tipo_documento_persona_crt').val('');
                $('#form_vehiculo_crt').find('input, select, textarea').val('');
                $('#trae_carga_no_crt').prop('checked', true);
                $('.documento-input-crt, .nombre-input-crt').removeClass('is-valid is-invalid');
                $('#select_tipo_entidad_crt').val('P');
                $('#tipo_movimiento-crt').val('E');
                resetTomSelects(selectEtiquetaTS, selectTipoVehiculoTS, selectTipoMineralTS);
                
                setHoraActual();
                toggleEntidad();
            }

            function formatPlaca(input) {
                const $input = $(input);
                $input.on('input', function() {
                    let val = $input.val().toUpperCase().replace(/[^A-Z0-9]/g, '');
                    $input.val(val.length > 3 ? val.slice(0, 3) + '-' + val.slice(3, 6) : val);
                });
            }

            function animarFormulario() {
                const $form = $('.crear-detalles');
                $form.removeClass('form-animated-crt');
                requestAnimationFrame(() => {
                    // Forzar reflow
                    $form[0].offsetWidth;
                    $form.addClass('form-animated-crt');
                });
            }

            function actualizarTipoMovimiento(event) {
                const tipo = $(this).data('tipo');
                $('#tipo_movimiento-crt').val(tipo);

                if (tipo === 'E') {
                    $('.modal-title-crt, .card-header-crt h6').text('REGISTRO DE ENTRADA');
                } else if (tipo === 'S') {
                    $('.modal-title-crt, .card-header-crt h6').text('REGISTRO DE SALIDA');
                }
            }

            function detectarTipoDocumento(documento) {
                if (documento.length === 8) return { tipo: 'dni', valor: '1' };
                if (documento.length === 11) return { tipo: 'ruc', valor: '2' };
                return null;
            }

            $('#tipoMovimientoTab-crt .nav-link-crt').on('shown.bs.tab', animarFormulario);
            $('#tipoMovimientoTab-crt a[data-toggle="tab"]').on('shown.bs.tab', actualizarTipoMovimiento);
            $('#select_tipo_entidad_crt').on('change', toggleEntidad);
            $('input[name="trae_carga"]').on('change', toggleCargaFields);

            $('#documento_persona_crt').on('input', function() {
                const documento = $(this).val();
                const info = detectarTipoDocumento(documento);

                if (!info) {
                    $('#select_tipo_documento_persona_crt').val('').trigger('change');
                    return;
                };

                $('#select_tipo_documento_persona_crt').val(info.valor).trigger('change');
                buscarDocumento('#documento_persona_crt', '#nombre_persona_crt');
            });

            $('#documento_vehiculo_crt').on('input', function() {
                const documento = $(this).val();
                const info = detectarTipoDocumento(documento);

                if (!info) {
                    $('#select_tipo_documento_vehiculo_crt').val('').trigger('change');
                    return;
                };

                $('#select_tipo_documento_vehiculo_crt').val(info.valor).trigger('change');
                buscarDocumento('#documento_vehiculo_crt', '#nombre_vehiculo_crt');
            });

            $('textarea[name="ocurrencias"]').on('input', function() {
                const textValue = this.value || "-";
            });
            
            $('#btn_cancelar_crt').on('click', function() {
                limpiarModal();
                $('body').focus();
                $modalCreate.modal('hide');
            });
            
            $modalCreate.on('show.bs.modal', function() {
                limpiarModal();
            });
            
            $modalCreate.on('hidden.bs.modal', function() {
                limpiarModal();
                $('#tipoMovimientoTab-crt a[data-tipo="E"]').tab('show');
            });
            
            formatPlaca($('#placa_crt'));
            toggleEntidad();
            setHoraActual();
        });
    </script>

    {{-- <script>
        $(document).ready(function() {
            $('#buscador').select2({
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
