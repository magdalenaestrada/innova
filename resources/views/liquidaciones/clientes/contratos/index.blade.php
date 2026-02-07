@extends('admin.layout')


@section('content')
    <div class="container">
        <br>
        <div class="row d-flex justify-content-between align-items-center">
            <div class="loader">
                {{ __('CONTRATO DE LA EMPRESA - ') }} {{ $empresa->nombre }}
            </div>
        </div>
        <div class="container">
            <br>
            <div class="row">
                <div class="col-md-7">
                    <div class="card mb-3">
                        <div class="card-body">
                            <form action="{{ route('contratos.update', $contrato->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="cliente_id" value="{{ $empresa->id }}">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <h6 class="mb-0">DATOS DEL CONTRATO</h6>

                                    <div class="form-check">
                                        <input type="hidden" name="permitir_acceso" value="0">

                                        <input type="checkbox" name="permitir_acceso" value="1"
                                            {{ old('permitir_acceso', $contrato->permitir_acceso ?? false) ? 'checked' : '' }}>

                                        <label class="form-check-label" for="permitir_acceso">
                                            Permitir acceso
                                        </label>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-md-6">
                                        <label>FECHA INICIO DE CONTRATO</label>
                                        <input type="date" class="form-control form-control-sm contrato-input"
                                            name="fecha_inicio_contrato"
                                            value="{{ old('fecha_inicio_contrato', $contrato->fecha_inicio_contrato ?? '') }}"
                                            readonly>
                                    </div>

                                    <div class="col-md-6">
                                        <label>FECHA FIN DE CONTRATO</label>
                                        <input type="date" class="form-control form-control-sm contrato-input"
                                            name="fecha_fin_contrato"
                                            value="{{ old('fecha_fin_contrato', $contrato->fecha_fin_contrato ?? '') }}"
                                            readonly>
                                    </div>
                                </div>

                                <div class="row mt-3">
                                    <div class="col-md-4">
                                        <label>N° JUEGOS</label>
                                        <input type="number" class="form-control form-control-sm contrato-input"
                                            name="numero_juegos"
                                            value="{{ old('numero_juegos', $contrato->numero_juegos ?? '') }}" readonly>
                                    </div>

                                    <div class="col-md-8">
                                        <label>N° CONTRATO</label>
                                        <input type="text" class="form-control form-control-sm contrato-input"
                                            name="numero_contrato"
                                            value="{{ old('numero_contrato', $contrato->numero_contrato ?? '') }}" readonly
                                            oninput="this.value = this.value.replace(/\s/g, '')">
                                    </div>
                                </div>

                                <div class="row mt-3">
                                    <div class="col-md-12">
                                        <label>RECEPCIONADO (ENVIADO POR EL CLIENTE)</label>
                                        <input type="text" class="form-control form-control-sm contrato-input"
                                            name="recepcionado_cliente"
                                            value="{{ old('recepcionado_cliente', $contrato->recepcionado_cliente ?? '') }}"
                                            readonly>
                                    </div>
                                </div>

                                <div class="row mt-3">
                                    <div class="col-md-12">
                                        <label>LEGALIZADO POR NOTARÍA Y FIRMADO POR JURGEN</label>
                                        <input type="text" class="form-control form-control-sm contrato-input"
                                            name="legalizado_jurgen"
                                            value="{{ old('legalizado_jurgen', $contrato->legalizado_jurgen ?? '') }}"
                                            readonly>
                                    </div>
                                </div>

                                {{-- OBSERVACIONES --}}
                                <div class="row mt-3">
                                    <div class="col-md-12">
                                        <label>OBSERVACIONES</label>
                                        <textarea class="form-control form-control-sm contrato-input" name="observaciones" rows="4" readonly>{{ old('observaciones', $contrato->observaciones ?? '') }}</textarea>
                                    </div>
                                </div>

                                {{-- BOTONES --}}
                                <div class="text-end mt-3">
                                    <button type="button" class="btn btn-primary btn-sm" id="btnEditarContrato">
                                        EDITAR
                                    </button>

                                    <button type="submit" class="btn btn-success btn-sm d-none" id="btnGuardarContrato">
                                        GUARDAR CONTRATO
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-md-5">
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <div class="card">
                                <div class="card-body">
                                    <h6>EMPRESAS ASOCIADAS AL CONTRATO</h6>

                                    <form action="{{ route('contratos.empresas.store') }}" method="POST">
                                        @csrf

                                        <input type="hidden" name="contrato_id" value="{{ $contrato->id }}">

                                        <div class="row align-items-center mt-2">
                                            <div class="col-md-9">
                                                <select name="empresa_id" class="form-control form-control-sm" required>
                                                    <option value="">-- Seleccione empresa --</option>
                                                    @foreach ($empresas as $emp)
                                                        <option value="{{ $emp->id }}">
                                                            {{ $emp->nombre }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="col-md-3">
                                                <button type="submit" class="btn btn-success btn-sm w-100">
                                                    AGREGAR
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                    <table class="table table-sm mt-3">
                                        <thead>
                                            <tr>
                                                <th>EMPRESA</th>
                                                <th class="text-center">ACCIONES</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($contratoEmpresas as $ce)
                                                <tr id="empresa-row-{{ $ce->id }}">
                                                    <td>{{ $ce->empresa->nombre }}</td>
                                                    <td class="text-center">
                                                        <button class="btn btn-danger btn-sm btnEliminarEmpresa"
                                                            data-id="{{ $ce->id }}">
                                                            <i class="fa fa-trash"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="2" class="text-center text-muted">
                                                        No hay empresas asociadas
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 mb-3">
                            <div class="card">
                                <div class="card-body">
                                    <h6>LISTA DE CONTACTO</h6>
                                    <form action="{{ route('contactos.store') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="cliente_id" value="{{ $empresa->id }}">
                                        <div class="row mt-1 align-items-center">
                                            <div class="col-md-5">
                                                <input type="text" name="celular" class="form-control form-control-sm"
                                                    placeholder="Número de celular" required inputmode="numeric"
                                                    maxlength="9"
                                                    oninput="this.value = this.value.replace(/[^0-9]/g, '')">

                                            </div>

                                            <div class="col-md-4">
                                                <input type="hidden" name="solo_whatsapp" value="0">

                                                <input type="checkbox" name="solo_whatsapp" id="solo_whatsapp"
                                                    value="1">

                                                <label for="solo_whatsapp">Solo Whatsapp</label>
                                            </div>

                                            <div class="col-md-3">
                                                <button type="submit" class="btn btn-success btn-sm">
                                                    GUARDAR
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                    <table class="table table-responsive">
                                        <thead>
                                            <tr>
                                                <th>NÚMERO</th>
                                                <th>SOLO WHATSAPP</th>
                                                <th>ACCIONES</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($contactos as $contacto)
                                                <tr id="contacto-row-{{ $contacto->id }}">
                                                    <td>{{ $contacto->celular }}</td>
                                                    <td>{{ isset($contacto->solo_whatsapp) && $contacto->solo_whatsapp ? 'LLamar por WhatsApp' : 'No' }}
                                                    </td>
                                                    <td>
                                                        <button class="btn btn-sm btn-danger btnEliminarContacto"
                                                            data-id="{{ $contacto->id }}">
                                                            <i class="fa fa-trash"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-body">
                                    <h6>REPRESENTANTE LEGAL</h6>

                                    <form action="{{ route('representantes.store') }}" method="POST">
                                        @csrf

                                        <input type="hidden" name="cliente_id" value="{{ $empresa->id }}">

                                        <div class="row mt-2 align-items-center">
                                            <div class="col-md-9">
                                                <input type="text" class="form-control form-control-sm"
                                                    name="documento" id="documento_representante"
                                                    placeholder="Ingrese DNI o RUC" required inputmode="numeric"
                                                    pattern="[0-9]{8,11}" maxlength="11"
                                                    oninput="this.value = this.value.replace(/[^0-9]/g, '')">

                                            </div>
                                            <div class="col-md-3">
                                                <button type="submit" class="btn btn-success btn-sm">
                                                    GUARDAR
                                                </button>
                                            </div>
                                            <div class="col-md-12 mt-2">
                                                <input type="text" class="form-control form-control-sm" name="nombre"
                                                    id="nombre_representante" placeholder="Nombre completo" required>
                                            </div>


                                        </div>
                                    </form>

                                    <table class="table mt-3">
                                        <thead>
                                            <tr>
                                                <th>DOCUMENTO</th>
                                                <th>NOMBRE</th>
                                                <th>ACCIONES</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($representantes as $rep)
                                                <tr id="rep-row-{{ $rep->id }}">
                                                    <td>{{ $rep->persona->documento_persona }}</td>
                                                    <td>{{ $rep->persona->datos_persona }}</td>
                                                    <td>
                                                        <button class="btn btn-danger btn-sm btnEliminarRep"
                                                            data-id="{{ $rep->id }}">
                                                            <i class="fa fa-trash"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function() {

            $('form[action="{{ route('contratos.empresas.store') }}"]').on('submit', function(e) {
                e.preventDefault();

                let form = $(this);
                let formData = new FormData(this);

                $.ajax({
                    url: form.attr('action'),
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Empresa agregada',
                                text: response.message,
                                timer: 2000,
                                showConfirmButton: false
                            });

                            let newRow = `
                            <tr id="empresa-row-${response.data.id}">
                                <td>${response.data.empresa_nombre}</td>
                                <td class="text-center">
                                    <button class="btn btn-danger btn-sm btnEliminarEmpresa"
                                        data-id="${response.data.id}">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        `;

                            // Si existe el mensaje "No hay empresas asociadas", removerlo
                            $('table').find('td:contains("No hay empresas asociadas")').closest(
                                'tr').remove();

                            // Agregar la nueva fila
                            form.closest('.card-body').find('tbody').append(newRow);

                            // Limpiar el select
                            form.find('select[name="empresa_id"]').val('');
                        }
                    },
                    error: function(xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: xhr.responseJSON?.message ||
                                'No se pudo agregar la empresa'
                        });
                    }
                });
            });


            $('form[action="{{ route('contactos.store') }}"]').on('submit', function(e) {
                e.preventDefault();

                let form = $(this);
                let formData = new FormData(this);

                $.ajax({
                    url: form.attr('action'),
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Contacto guardado',
                                text: response.message,
                                timer: 2000,
                                showConfirmButton: false
                            });

                            // Agregar nueva fila a la tabla
                            let soloWhatsapp = response.data.solo_whatsapp ?
                                'LLamar por WhatsApp' : 'No';
                            let newRow = `
                            <tr id="contacto-row-${response.data.id}">
                                <td>${response.data.celular}</td>
                                <td>${soloWhatsapp}</td>
                                <td>
                                    <button class="btn btn-sm btn-danger btnEliminarContacto"
                                        data-id="${response.data.id}">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        `;

                            form.closest('.card-body').find('tbody').append(newRow);

                            // Limpiar el formulario
                            form[0].reset();
                        }
                    },
                    error: function(xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: xhr.responseJSON?.message ||
                                'No se pudo guardar el contacto'
                        });
                    }
                });
            });


            $('form[action="{{ route('representantes.store') }}"]').on('submit', function(e) {
                e.preventDefault();

                let form = $(this);
                let formData = new FormData(this);

                $.ajax({
                    url: form.attr('action'),
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Representante guardado',
                                text: response.message,
                                timer: 2000,
                                showConfirmButton: false
                            });

                            let newRow = `
                            <tr id="rep-row-${response.data.id}">
                                <td>${response.data.documento}</td>
                                <td>${response.data.nombre}</td>
                                <td>
                                    <button class="btn btn-danger btn-sm btnEliminarRep"
                                        data-id="${response.data.id}">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        `;

                            form.closest('.card-body').find('tbody').append(newRow);

                            // Limpiar el formulario
                            form[0].reset();
                            $('#nombre_representante').removeClass('is-valid is-invalid');
                        }
                    },
                    error: function(xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: xhr.responseJSON?.message ||
                                'No se pudo guardar el representante'
                        });
                    }
                });
            });


            $(document).on('click', '.btnEliminarEmpresa', function() {
                let btn = $(this);
                let id = btn.data('id');

                Swal.fire({
                    title: '¿Eliminar empresa?',
                    text: 'Se quitará del contrato',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Sí, eliminar',
                    cancelButtonText: 'Cancelar'
                }).then(result => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: `/contratos-empresas/${id}`,
                            type: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            success: function(data) {
                                if (data.success) {
                                    $(`#empresa-row-${id}`).fadeOut(300, function() {
                                        $(this).remove();

                                        // Si no quedan empresas, mostrar mensaje
                                        if (btn.closest('tbody').find('tr')
                                            .length === 0) {
                                            let emptyRow = `
                                            <tr>
                                                <td colspan="2" class="text-center text-muted">
                                                    No hay empresas asociadas
                                                </td>
                                            </tr>
                                        `;
                                            btn.closest('tbody').html(emptyRow);
                                        }
                                    });

                                    Swal.fire('Eliminado', data.message, 'success');
                                }
                            },
                            error: function(xhr) {
                                Swal.fire('Error', 'No se pudo eliminar la empresa',
                                    'error');
                            }
                        });
                    }
                });
            });

            $(document).on('click', '.btnEliminarContacto', function() {
                let btn = $(this);
                let contactoId = btn.data('id');

                Swal.fire({
                    title: '¿Eliminar contacto?',
                    text: 'Este registro será eliminado',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Sí, eliminar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: `/contactos/${contactoId}`,
                            type: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            success: function(data) {
                                if (data.success) {
                                    $(`#contacto-row-${contactoId}`).fadeOut(300,
                                        function() {
                                            $(this).remove();
                                        });

                                    Swal.fire('Eliminado', data.message, 'success');
                                }
                            },
                            error: function(xhr) {
                                Swal.fire('Error', 'No se pudo eliminar el contacto',
                                    'error');
                            }
                        });
                    }
                });
            });


            $(document).on('click', '.btnEliminarRep', function() {
                let btn = $(this);
                let id = btn.data('id');

                Swal.fire({
                    title: '¿Eliminar representante?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Sí, eliminar',
                    cancelButtonText: 'Cancelar'
                }).then(result => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: `/representantes/${id}`,
                            type: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            success: function(data) {
                                if (data.success) {
                                    $(`#rep-row-${id}`).fadeOut(300, function() {
                                        $(this).remove();
                                    });

                                    Swal.fire('Eliminado', data.message, 'success');
                                }
                            },
                            error: function(xhr) {
                                Swal.fire('Error',
                                    'No se pudo eliminar el representante', 'error');
                            }
                        });
                    }
                });
            });


            function buscarDocumento(url, inputId, datosId) {
                let inputValue = $(inputId).val().trim();

                if (inputValue.length !== 8 && inputValue.length !== 11) {
                    return;
                }

                let tipoDocumento = inputValue.length === 8 ? 'dni' : 'ruc';

                $.ajax({
                    url: url,
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        documento: inputValue,
                        tipo_documento: tipoDocumento
                    },
                    success: function(response) {
                        if (tipoDocumento === 'dni') {
                            $(datosId).val(
                                response.nombres + ' ' +
                                response.apellidoPaterno + ' ' +
                                response.apellidoMaterno
                            );
                        } else {
                            $(datosId).val(response.razonSocial);
                        }

                        $(datosId).removeClass('is-invalid').addClass('is-valid');
                    },
                    error: function() {
                        $(datosId).val('');
                        $(datosId).removeClass('is-valid').addClass('is-invalid');
                    }
                });
            }

            $('#documento_representante').on('input', function() {
                buscarDocumento(
                    '{{ route('buscar.documento') }}',
                    '#documento_representante',
                    '#nombre_representante'
                );
            });

            $('.datos-input').on('input', function() {
                var value = $(this).val();
                $(this).toggleClass('is-valid', value.trim().length > 0);
                $(this).toggleClass('is-invalid', value.trim().length === 0);
            });


            $('#btnEditarContrato').on('click', function() {
                $('.contrato-input').each(function() {
                    $(this).removeAttr('readonly disabled');
                });

                $('#btnGuardarContrato').removeClass('d-none');
                $(this).addClass('d-none');
            });
        });

        $('form[action="{{ route('contratos.update', $contrato->id) }}"]').on('submit', function(e) {
            e.preventDefault();

            let form = $(this);
            let formData = new FormData(this);

            $.ajax({
                url: form.attr('action'),
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                success: function(response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Actualizado',
                        text: 'Contrato actualizado correctamente',
                        timer: 2000,
                        showConfirmButton: false
                    });

                    // Volver a modo lectura
                    $('.contrato-input').attr('readonly', true);
                    $('#btnGuardarContrato').addClass('d-none');
                    $('#btnEditarContrato').removeClass('d-none');
                },
                error: function(xhr) {
                    let errorMsg = 'Error al actualizar el contrato';

                    if (xhr.status === 422) {
                        // Errores de validación
                        let errors = xhr.responseJSON.errors;
                        errorMsg = Object.values(errors).flat().join('<br>');
                    }

                    Swal.fire({
                        icon: 'error',
                        title: 'Error de validación',
                        html: errorMsg
                    });
                }
            });
        });
    </script>
@endsection
