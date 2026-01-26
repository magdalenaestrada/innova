@push('css')
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.4.3/dist/css/tom-select.css" rel="stylesheet">
    <style>
        .bloque-formulario-edt {
            transition: opacity 0.3s ease-in-out;
            opacity: 1;
        }
    </style>
    <style>
        [id^='ModalEdit'] {
            font-size: 16px;
        }

        [id^='ModalEdit'],
        [id^='ModalEdit'] * {
            border-collapse: separate;
            -webkit-border-horizontal-spacing: 0;
            -webkit-border-vertical-spacing: 0;
        }

        [id^='ModalEdit'] table,
        [id^='ModalEdit'] thead,
        [id^='ModalEdit'] tbody,
        [id^='ModalEdit'] tr,
        [id^='ModalEdit'] td,
        [id^='ModalEdit'] th {
            font-size: inherit;
        }

        [id^='ModalEdit'] .form-control {
            border-radius: 10px !important;
        }
        
        /*TOMB SELECT*/
        [id^='ModalEdit'] .ts-control {
            border-radius: 10px !important;
            min-height: calc(1.5em + 0.75rem + 2px);
            border: 1px solid #ced4da;
            padding: 0 0 0 15px !important;
            font-size: 16px;
        }

        [id^='select_tipo_vehiculo'] + .ts-wrapper .ts-control .item {
            padding: 0.375rem 0;
            margin: 0;
            line-height: 1.5;
        }

        [id^='select_tipo_vehiculo'] + .ts-wrapper .ts-control input {
            padding: 0.375rem 0.75rem;
            line-height: 1.5;
        }

        [id^='select_tipo_vehiculo'] + .ts-wrapper .ts-control input::placeholder {
            font-size: 16px;
        }

        [id^='select_tipo_mineral'] + .ts-wrapper .ts-control .item {
            padding: 0.375rem 0;
            margin: 0;
            line-height: 1.5;
        }

        [id^='select_tipo_mineral'] + .ts-wrapper .ts-control input {
            padding: 0.375rem 0.75rem;
            line-height: 1.5;
        }

        [id^='select_tipo_mineral'] + .ts-wrapper .ts-control input::placeholder {
            font-size: 16px;
        }

        [id^='ModalEdit'] .nav-link-edt {
            height: 48px;
            line-height: 24px;
            width: auto;
        }


        [id^='ModalEdit'] .modal-content-edt {
            border-radius: 18px;
            background-color: #f4f6f9;
            border: none;
            overflow: hidden;
        }

        [id^='ModalEdit'] .card-header-edt {
            background: transparent;
            border-bottom: none;
            border-radius: 18px 18px 0 0;
        }

        [id^='ModalEdit'] .custom-tabs-edt {
            display: flex;
            border-bottom: none;
            margin: 0;
            padding: 0 12px;
        }

        [id^='ModalEdit'] .custom-tabs-edt .nav-item-edt {
            flex: 1;
            text-align: center;
        }

        [id^='ModalEdit'] .custom-tabs-edt .nav-link-edt {
            width: 100%;
            border: none;
            border-radius: 14px 14px 0 0;
            background: #ffffff;
            color: #555;
            font-weight: 600;
            padding: 12px 0;
            transition: all 0.25s ease;
        }

        [id^='ModalEdit'] .custom-tabs-edt .nav-link-edt:hover {
            background: #dcdfe4;
            color: #111;
        }

        [id^='ModalEdit'] .custom-tabs-edt .nav-link-edt.active {
            background: #ffffff;
            color: #000;
            z-index: 2;
            box-shadow: inset 0 4px 10px rgba(0,0,0,0.15);
        }

        [id^='ModalEdit'] .card-body-edt {
            background: #ffffff;
            border-radius: 18px 18px 18px 18px;
            margin-top: -2px;
            padding-top: 25px;
            box-shadow: 0 1px 40px rgba(0,0,0,0.08);
        }

        [id^='ModalEdit'] .custom-tabs-edt i {
            opacity: 0.7;
        }

        [id^='ModalEdit'] .custom-tabs-edt .nav-link-edt.active i {
            opacity: 1;
        }

        /*BOTON TRAE CARGA*/
        [id^='ModalEdit'] .btn-carga-edt {
            border-radius: 10px !important;
            font-weight: 600 !important;
            border: 1px solid #ced4da !important;
            background-color: #f8f9fa !important;
            color: #495057 !important;
            transition: all 0.2s ease !important;
        }
        
        /* [id^='ModalEdit'] .btn-group > .btn-carga-edt {
            border-radius: 10px;
        } */

        [id^='ModalEdit'] .btn-carga-edt:hover {
            background-color: #e9ecef !important;
        }

        [id^='ModalEdit'] .btn-check-edt {
            position: absolute !important;
            clip: rect(0, 0, 0, 0) !important;
            pointer-events: none !important;
        }

        [id^='ModalEdit'] .btn-check-edt:checked + .btn-carga-edt {
            background-color: #6c757d !important;
            color: #fff !important;
            border-color: #6c757d !important;
            box-shadow: 0 3px 8px rgba(0,0,0,0.15) !important;
        }

        [id^='ModalEdit'] .btn-group-edt {
            gap: 6px !important;
        }

        @media (max-width: 576px) {
            [id^='ModalEdit'] .custom-tabs-edt .nav-link-edt {
                font-size: 14px;
                padding: 10px 0;
            }
        }

        .form-animated-edt {
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

<div class="modal fade text-left modal-edit" id="ModalEdit{{ $detalle->id }}" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content modal-content-edt">
            <div class="card-header card-header-edt">
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
            <div class="card-body card-body-edt">
                <ul class="nav nav-tabs custom-tabs-edt mb-3" id="tipoMovimientoTab_{{ $detalle->id }}" role="tablist">
                    <li class="nav-item nav-item-edt">
                        <a class="nav-link nav-link-edt {{ $detalle->tipo_movimiento == 'E' ? 'active' : '' }}"
                        data-toggle="tab"
                        href="#tab-entrada-{{ $detalle->id }}"
                        data-tipo="E"
                        role="tab">
                            <i class="fas fa-sign-in-alt mr-1"></i> Entrada
                        </a>
                    </li>

                    <li class="nav-item nav-item-edt">
                        <a class="nav-link nav-link-edt {{ $detalle->tipo_movimiento == 'S' ? 'active' : '' }}"
                        data-toggle="tab"
                        href="#tab-salida-{{ $detalle->id }}"
                        data-tipo="S"
                        role="tab">
                            <i class="fas fa-sign-out-alt mr-1"></i> Salida
                        </a>
                    </li>
                </ul>

                <div class="tab-content d-none">
                    <div class="tab-pane {{ $detalle->tipo_movimiento == 'E' ? 'show active' : '' }}" id="tab-entrada-{{ $detalle->id }}"></div>
                    <div class="tab-pane {{ $detalle->tipo_movimiento == 'S' ? 'show active' : '' }}" id="tab-salida-{{ $detalle->id }}"></div>
                </div>
                
                <form class="editar-detalles form-animated-edt" action="{{ route('detcontrolgarita.editar', $detalle->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div>
                        <input type="hidden" name="tipo_movimiento" id="tipo_movimiento_{{ $detalle->id }}" value="E">
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">Tipo entidad</label>
                            <select name="tipo_entidad" id="select_tipo_entidad_{{ $detalle->id }}"
                                class="form-control form-select-sm estado-select w-150">
                                <option value="P" {{ $detalle->tipo_entidad == "P" ? 'selected' : '' }}>Persona</option>
                                <option value="V" {{ $detalle->tipo_entidad == "V" ? 'selected' : '' }}>Vehículo</option>
                            </select>
                        </div>
                        <div class="form col-md-3">
                            <label>Hora</label>
                            <input name="hora" id="hora_{{ $detalle->id }}" class="form-control hora" type="time" value="{{ $detalle->hora }}" required>
                        </div>
                        <div class="form col-md-6">
                            <label>Etiqueta</label>
                            <select name="etiqueta_id" id="etiqueta_id_{{ $detalle->id }}" class="tom-select">
                                <option value="">Seleccione un color...</option>
                                @foreach ($etiquetas as $etiqueta)
                                    <option value="{{ $etiqueta->id }}" data-color="{{ $etiqueta->color }}" {{ $detalle->etiqueta_id == $etiqueta->id ? 'selected' : '' }}>{{ $etiqueta->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <br>
                    <!-- FORMULARIO PERSONA  -->
                    <div id="persona_{{ $detalle->id }}" class="bloque-formulario-edt">
                        <div class="row">
                            <div class="col-md-3">
                                <label class="form-label fw-semibold">Tipo documento</label>
                                <select name="tipo_documento" id="select_tipo_documento_persona_{{ $detalle->id }}" class="form-control form-select-sm estado-select w-150">
                                    @if ($detalle->tipo_entidad == 'P')
                                        <option value="1" {{ $detalle->tipo_documento == 1 ? 'selected' : '' }}>DNI</option>
                                        <option value="2" {{ $detalle->tipo_documento == 2 ? 'selected' : '' }}>RUC</option>
                                    @else
                                        <option value="" disabled hidden>Seleccione...</option>
                                        <option value="1">DNI</option>
                                        <option value="2">RUC</option>
                                    @endif
                                </select>
                            </div>
                            <div class="form mb-1 col-md-3">
                                <label>Documento</label>
                                <input name="documento" id="documento_persona_{{ $detalle->id }}" value="{{ $detalle->tipo_entidad == 'P' ? $detalle->documento : '' }}" class="form-control documento-input-edt" type="text"
                                    inputmode="numeric" pattern="[0-9]*" maxlength="11" placeholder="DNI / RUC"
                                    oninput="this.value = this.value.replace(/[^0-9]/g, '')" required>
                            </div>
                            <div class="form col-md-6">
                                <label>Nombre</label>
                                <input name="nombre" id="nombre_persona_{{ $detalle->id }}" value="{{ $detalle->tipo_entidad == 'P' ? $detalle->nombre : '' }}" class="form-control nombre-input-edt" type="text" placeholder="Ej: Vega Nieto Marco Antonio" required>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-md-12">
                                <label class="form-label fw-semibold">Ocurrencia</label>
                                <textarea name="ocurrencias" id="ocurrencias_persona_{{ $detalle->id }}" class="form-control" rows="3">{{ $detalle->tipo_entidad == 'P' ? $detalle->ocurrencias : '' }}</textarea>
                            </div>
                        </div>
                    </div>
                    <!-- FORMULARIO VEHICULO -->
                    <div id="vehiculo_{{ $detalle->id }}" class="bloque-formulario-edt">
                        <div class="row">
                            <div class="col-md-3">
                                <label class="form-label fw-semibold">Tipo Vehículo</label>
                                <select name="tipo_vehiculo" id="select_tipo_vehiculo_{{ $detalle->id }}" class="form-select-sm estado-select w-150">
                                    <option value="">Seleccione...</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-semibold">Placa</label>
                                <input name="placa" id="placa_{{ $detalle->id }}" class="form-control" type="text" value="{{ $detalle->placa }}" placeholder="###-###" required>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-md-3">
                                <label class="form-label fw-semibold">Tipo documento</label>
                                <select name="tipo_documento" id="select_tipo_documento_vehiculo_{{ $detalle->id }}" class="form-control form-select-sm estado-select w-150" required>
                                    @if ($detalle->tipo_entidad == 'V')
                                        <option value="1" {{ $detalle->tipo_documento == 1 ? 'selected' : '' }}>DNI</option>
                                        <option value="2" {{ $detalle->tipo_documento == 2 ? 'selected' : '' }}>RUC</option>
                                    @else
                                        <option value="" disabled hidden>Seleccione...</option>
                                        <option value="1">DNI</option>
                                        <option value="2">RUC</option>
                                    @endif
                                </select>
                            </div>
                            <div class="form mb-1 col-md-3">
                                <label class="form-label fw-semibold">Documento</label>
                                <input name="documento" id="documento_vehiculo_{{ $detalle->id }}" class="form-control documento-input-edt" type="text"
                                    inputmode="numeric" pattern="[0-9]*" maxlength="11" value="{{ $detalle->tipo_entidad == 'V' ? $detalle->documento : '' }}"
                                    oninput="this.value = this.value.replace(/[^0-9]/g, '')" placeholder="DNI / RUC" required>
                            </div>
                            <div class="form col-md-6">
                                <label class="form-label fw-semibold">Conductor</label>
                                <input name="nombre" id="nombre_vehiculo_{{ $detalle->id }}" value="{{ $detalle->tipo_entidad == 'V' ? $detalle->nombre : '' }}" class="form-control nombre-input-edt" type="text" placeholder="Ej: Vega Nieto Marco Antonio" required>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-md-3">
                                <label class="form-label fw-semibold">¿Trae Mineral?</label><br>
                                <div class="btn-group btn-group-edt w-100" role="group" aria-label="Trae carga">
                                    <input type="radio" class="btn-check-edt" name="trae_carga" id="trae_carga_no_{{ $detalle->id }}"
                                        value="0" autocomplete="off" {{ $detalle->trae_carga === 0 ? 'checked' : '' }}>
                                    <label class="btn btn-outline-primary btn-carga-edt" for="trae_carga_no_{{ $detalle->id }}">No</label>
                                    <input type="radio" class="btn-check-edt" name="trae_carga" id="trae_carga_si_{{ $detalle->id }}"
                                        value="1" autocomplete="off" {{ $detalle->trae_carga === 1 ? 'checked' : '' }}>
                                    <label class="btn btn-outline-primary btn-carga-edt" for="trae_carga_si_{{ $detalle->id }}">Sí</label>
                                </div>
                            </div>
                        </div>
                        <div class="row d-none" id="detalles_carga_{{ $detalle->id }}">
                            <div class="form col-md-4">
                                <br>
                                <label class="form-label fw-semibold">Tipo mineral</label>
                                <select name="tipo_mineral" id="select_tipo_mineral_{{ $detalle->id }}" class="form-select-sm estado-select w-150">
                                    <option value="">Seleccione...</option>
                                </select>
                            </div>
                            <div class="form col-md-4">
                                <br>
                                <label class="form-label fw-semibold">Destino</label>
                                <input name="destino" id="destino_{{ $detalle->id }}" value="{{ $detalle->destino }}" class="form-control" type="text" placeholder="Ej: Loza 1" required>
                            </div>
                            <div class="form col-md-4">
                                <br>
                                <label class="form-label fw-semibold">Lote</label>
                                <select name="lote" id="lote_{{ $detalle->id }}" class="form-control form-select-sm estado-select w-150">
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
                                <textarea name="ocurrencias" id="ocurrencias_vehiculo_{{ $detalle->id }}" class="form-control" rows="3">{{ $detalle->tipo_entidad == 'V' ? $detalle->ocurrencias : '' }}</textarea>
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-md-12 text-right mt-1">
                            <button type="button" class="btn btn-light btn-sm btn-cancelar-edit" data-modal-id="{{ $detalle->id }}">
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
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        $(() => {
            let selectEtiquetaTS = new TomSelect("#etiqueta_id_{{ $detalle->id }}",{
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

            let selectTipoVehiculoTS = new TomSelect("#select_tipo_vehiculo_{{ $detalle->id }}",{
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
                            if (datosOriginales.tipo_vehiculo) {
                                selectTipoVehiculoTS.setValue(datosOriginales.tipo_vehiculo, true);
                            }
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

            let selectTipoMineralTS = new TomSelect("#select_tipo_mineral_{{ $detalle->id }}",{
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
                            if (datosOriginales.tipo_mineral) {
                                selectTipoVehiculoTS.setValue(datosOriginales.tipo_mineral, true);
                            }
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

            const modalId = '{{ $detalle->id }}';
            const $modal = $(`#ModalEdit${modalId}`);
            
            const datosOriginales = {
                tipo_movimiento: '{{ $detalle->tipo_movimiento }}',
                tipo_entidad: '{{ $detalle->tipo_entidad }}',
                hora: '{{ $detalle->hora }}',
                tipo_documento: '{{ $detalle->tipo_documento }}',
                documento: '{{ $detalle->documento }}',
                nombre: '{{ $detalle->nombre }}',
                ocurrencias: '{{ $detalle->ocurrencias }}',
                tipo_vehiculo: '{{ $detalle->tipo_vehiculo_id ?? "" }}',
                placa: '{{ $detalle->placa ?? "" }}',
                trae_carga: Number('{{ $detalle->trae_carga ?? 0 }}'),
                tipo_mineral: '{{ $detalle->tipo_mineral_id ?? "" }}',
                destino: '{{ $detalle->destino ?? "" }}'
            };
            
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
                    error: function() {
                        $(outputId).val('');
                        $(outputId).removeClass('is-valid').addClass('is-invalid');
                    }
                });
            }

            function guardarDatosTemporales(tipo) {
                if (tipo === 'P') {
                    datosTemporales.persona = {
                        tipo_documento: $(`#select_tipo_documento_persona_${modalId}`).val(),
                        documento: $(`#documento_persona_${modalId}`).val(),
                        nombre: $(`#nombre_persona_${modalId}`).val(),
                        ocurrencias: $(`#ocurrencias_persona_${modalId}`).val()
                    };
                } else {
                    datosTemporales.vehiculo = {
                        tipo_vehiculo: $(`#select_tipo_vehiculo_${modalId}`).val(),
                        placa: $(`#placa_${modalId}`).val(),
                        tipo_documento: $(`#select_tipo_documento_vehiculo_${modalId}`).val(),
                        documento: $(`#documento_vehiculo_${modalId}`).val(),
                        nombre: $(`#nombre_vehiculo_${modalId}`).val(),
                        trae_carga: $(`#trae_carga_si_${modalId}`).is(':checked') ? 1 : 0,
                        tipo_mineral: $(`#select_tipo_mineral_${modalId}`).val(),
                        destino: $(`#destino_${modalId}`).val(),
                        ocurrencias: $(`#ocurrencias_vehiculo_${modalId}`).val()
                    };
                }
            }

            function restaurarDatosTemporales(tipo) {
                if (tipo === 'P' && Object.keys(datosTemporales.persona).length > 0) {
                    $(`#select_tipo_documento_persona_${modalId}`).val(datosTemporales.persona.tipo_documento);
                    $(`#documento_persona_${modalId}`).val(datosTemporales.persona.documento);
                    $(`#nombre_persona_${modalId}`).val(datosTemporales.persona.nombre);
                    $(`#ocurrencias_persona_${modalId}`).val(datosTemporales.persona.ocurrencias);
                } else if (tipo === 'V' && Object.keys(datosTemporales.vehiculo).length > 0) {
                    $(`#select_tipo_vehiculo_${modalId}`).val(datosTemporales.vehiculo.tipo_vehiculo);
                    $(`#placa_${modalId}`).val(datosTemporales.vehiculo.placa);
                    $(`#select_tipo_documento_vehiculo_${modalId}`).val(datosTemporales.vehiculo.tipo_documento);
                    $(`#documento_vehiculo_${modalId}`).val(datosTemporales.vehiculo.documento);
                    $(`#nombre_vehiculo_${modalId}`).val(datosTemporales.vehiculo.nombre);
                    if (datosTemporales.vehiculo.trae_carga === 1) {
                        $(`#trae_carga_si_${modalId}`).prop('checked', true);
                    } else {
                        $(`#trae_carga_no_${modalId}`).prop('checked', true);
                    }
                    $(`#select_tipo_mineral_${modalId}`).val(datosTemporales.vehiculo.tipo_mineral);
                    $(`#destino_${modalId}`).val(datosTemporales.vehiculo.destino);
                    $(`#ocurrencias_vehiculo_${modalId}`).val(datosTemporales.vehiculo.ocurrencias);
                    toggleCargaFields();
                }
            }

            function toggleEntidad() {
                const tipo = $(`#select_tipo_entidad_${modalId}`).val();
                guardarDatosTemporales(tipo === 'P' ? 'V' : 'P');
                
                if (tipo === 'P') {
                    $(`#vehiculo_${modalId}`).hide();
                    $(`#vehiculo_${modalId}`).find('input, select, textarea').prop('disabled', true);
                    
                    $(`#persona_${modalId}`).show();
                    $(`#persona_${modalId}`).find('input, select, textarea').prop('disabled', false);
                    
                    restaurarDatosTemporales('P');
                } else {
                    $(`#persona_${modalId}`).hide();
                    $(`#persona_${modalId}`).find('input, select, textarea').prop('disabled', true);
                    
                    $(`#vehiculo_${modalId}`).show();
                    $(`#vehiculo_${modalId}`).find('input, select, textarea').prop('disabled', false);
                    
                    restaurarDatosTemporales('V');

                    // const traeCarga = datosTemporales.vehiculo.trae_carga !== undefined ? datosTemporales.vehiculo.trae_carga : datosOriginales.trae_carga;
                    // if (datosOriginales.trae_carga === 1) {
                    //     $(`#trae_carga_si_${modalId}`).prop('checked', true);
                    // } else {
                    //     $(`#trae_carga_no_${modalId}`).prop('checked', true);
                    // }

                    toggleCargaFields();
                }
            }

            function toggleCargaFields() {
                if ($(`#select_tipo_entidad_${modalId}`).val() === 'V') {
                    if ($(`#trae_carga_si_${modalId}`).is(':checked')) {
                        $(`#detalles_carga_${modalId}`).removeClass('d-none');
                        $(`#destino_${modalId}`).prop('required', true);
                        $(`#detalles_carga_${modalId}`).find('input, select')
                            .prop('disabled', false);
                    } else {
                        $(`#detalles_carga_${modalId}`).addClass('d-none');
                        $(`#destino_${modalId}`).prop('required', false);
                        $(`#detalles_carga_${modalId}`).find('input, select')
                            .prop('disabled', true);
                    }
                }
            }

            function resetTomSelects(...selects) {
                selects.forEach(item => {
                    if(!item?.ts) return;
                    item.ts.clear();
                    item.ts.setValue(item.val ?? '');
                    item.ts.close();
                });
            }

            function resetearModal() {
                datosTemporales = { persona: {}, vehiculo: {} };
                
                // Restaurar valores originales
                $(`#tipo_movimiento_${modalId}`).val(datosOriginales.tipo_movimiento);
                $(`#select_tipo_entidad_${modalId}`).val(datosOriginales.tipo_entidad);
                $(`#hora_${modalId}`).val(datosOriginales.hora);

                if (datosOriginales.tipo_movimiento === 'E') { 
                    $(`#tipoMovimientoTab_${modalId} a[data-tipo="E"]`).tab('show'); 
                } else { 
                    $(`#tipoMovimientoTab_${modalId} a[data-tipo="S"]`).tab('show'); 
                }
                
                if (datosOriginales.tipo_entidad === 'P') {
                    $(`#select_tipo_documento_persona_${modalId}`).val(datosOriginales.tipo_documento);
                    $(`#documento_persona_${modalId}`).val(datosOriginales.documento);
                    $(`#nombre_persona_${modalId}`).val(datosOriginales.nombre);
                    $(`#ocurrencias_persona_${modalId}`).val(datosOriginales.ocurrencias);

                    $(`#vehiculo_${modalId}`).find('input, select, textarea').val('');
                    if (datosOriginales.trae_carga === 1) {
                        $(`#trae_carga_si_${modalId}`).prop('checked', true);
                    } else {
                        $(`#trae_carga_no_${modalId}`).prop('checked', true);
                    }
                } else {
                    $(`#select_tipo_vehiculo_${modalId}`).val(datosOriginales.tipo_vehiculo);
                    $(`#placa_${modalId}`).val(datosOriginales.placa);
                    $(`#select_tipo_documento_vehiculo_${modalId}`).val(datosOriginales.tipo_documento);
                    $(`#documento_vehiculo_${modalId}`).val(datosOriginales.documento);
                    $(`#nombre_vehiculo_${modalId}`).val(datosOriginales.nombre);
                    if (datosOriginales.trae_carga === 1) {
                        $(`#trae_carga_si_${modalId}`).prop('checked', true);
                    } else {
                        $(`#trae_carga_no_${modalId}`).prop('checked', true);
                    }
                    // $(`input[name="trae_carga"][value="${datosOriginales.trae_carga}"]`).prop('checked', true);
                    $(`#select_tipo_mineral_${modalId}`).val(datosOriginales.tipo_mineral);
                    $(`#destino_${modalId}`).val(datosOriginales.destino);
                    $(`#ocurrencias_vehiculo_${modalId}`).val(datosOriginales.ocurrencias);

                    $(`#persona_${modalId}`).find('input, select, textarea').val('');
                }
                
                resetTomSelects(
                    { ts: selectEtiquetaTS, val: '{{ $detalle->etiqueta_id }}' },
                    { ts: selectTipoVehiculoTS, val: datosOriginales.tipo_vehiculo },
                    { ts: selectTipoMineralTS, val: datosOriginales.tipo_mineral },
                );

                $('.documento-input-edt, .nombre-input-edt').removeClass('is-valid is-invalid');
                
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
                const $form = $('.editar-detalles');
                $form.removeClass('form-animated-edt');
                requestAnimationFrame(() => {
                    // Forzar reflow
                    $form[0].offsetWidth;
                    $form.addClass('form-animated-edt');
                });
            }

            function actualizarTipoMovimiento(event) {
                const tipo = $(this).data('tipo');
                $(`#tipo_movimiento_${modalId}`).val(tipo);

                if (tipo === 'E') {
                    $('.modal-title-edt, .card-header-edt h6').text('MODIFICAR REGISTRO DE ENTRADA');
                } else {
                    $('.modal-title-edt, .card-header-edt h6').text('MODIFICAR REGISTRO DE SALIDA');
                }
            }

            function detectarTipoDocumento(documento) {
                if (documento.length === 8) return { tipo: 'dni', valor: '1' };
                if (documento.length === 11) return { tipo: 'ruc', valor: '2' };
                return null;
            }

            $(`#tipoMovimientoTab_${modalId} .nav-link-edt`).on('shown.bs.tab', animarFormulario);
            $(`#tipoMovimientoTab_${modalId} a[data-toggle="tab"]`).on('shown.bs.tab', actualizarTipoMovimiento);
            $(`#select_tipo_entidad_${modalId}`).on('change', toggleEntidad);
            $(`input[name="trae_carga"]`).on('change', toggleCargaFields);
            
            $(`#documento_persona_${modalId}`).on('input', function() {
                const documento = $(this).val();
                const info = detectarTipoDocumento(documento);

                if (!info) {
                    $(`#select_tipo_documento_persona_${modalId}`).val('').trigger('change');
                    return;
                };

                $(`#select_tipo_documento_persona_${modalId}`).val(info.valor).trigger('change');
                buscarDocumento(`#documento_persona_${modalId}`, `#nombre_persona_${modalId}`);
            });

            $(`#documento_vehiculo_${modalId}`).on('input', function() {
                const documento = $(this).val();
                const info = detectarTipoDocumento(documento);

                if (!info) {
                    $(`#select_tipo_documento_vehiculo_${modalId}`).val('').trigger('change');
                    return;
                };

                $(`#select_tipo_documento_vehiculo_${modalId}`).val(info.valor).trigger('change');
                buscarDocumento(`#documento_vehiculo_${modalId}`, `#nombre_vehiculo_${modalId}`);
            });
            
            $(`.btn-cancelar-edit[data-modal-id="${modalId}"]`).on('click', function() {
                resetearModal();
                $modal.modal('hide');
            });
            
            $modal.on('show.bs.modal', function() {
                resetearModal();
            });
            
            $modal.on('hidden.bs.modal', function() {
                datosTemporales = { persona: {}, vehiculo: {} };
            });
            
            formatPlaca($(`#placa_${modalId}`));
            toggleEntidad();
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
