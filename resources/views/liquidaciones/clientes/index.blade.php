@extends('admin.layout')
@push('css')
    <link rel="stylesheet" href="{{ asset('css/areas.css') }}">
@endpush

@section('content')
    <div class="container">

        <br>
        <div class="row d-flex justify-content-between align-items-center">
            <div class="loader">
                {{ __('CLIENTES REGISTRADOS') }}
            </div>
            <div class="text-right col-md-6">
                <a class="" href="#" data-toggle="modal" data-target="#ModalCreate">
                    <button class="button-create">
                        {{ __('REGISTRAR CLIENTE') }}
                    </button>
                </a>
            </div>
        </div>

        <br>
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <form method="GET" class="mb-3">
                            <div class="row">
                                <div class="col-md-4">
                                    <input type="text" name="search" class="form-control form-control-sm"
                                        placeholder="Buscar por documento, nombre o REINFO">
                                </div>
                                <div class="col-md-3">
                                    <select id="filtroContrato" class="form-control form-control-sm">
                                        <option value="">Todos</option>
                                        <option value="por_vencer">Por vencer</option>
                                        <option value="vigente">Vigentes</option>
                                        <option value="vencido">Vencidos</option>
                                        <option value="sin_fecha">Sin fecha</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <a id="btn-export" href="{{ route('lqclientes.export') }}"
                                        class="btn btn-success btn-sm">
                                        Exportar Excel
                                    </a>
                                </div>
                            </div>
                        </form>
                        <table id="products-table" class="table table-responsive">
                            <thead>
                                <tr>
                                    <th scope="col">
                                        {{ __('DOCUMENTO') }}
                                    </th>
                                    <th scope="col" style="width: 230px;">
                                        {{ __('NOMBRE') }}
                                    </th>
                                    <th scope="col">
                                        {{ __('REINFO PROPIO') }}
                                    </th>
                                    <th scope="col">
                                        {{ __('REINFO PRESTADO') }}
                                    </th>
                                    <th scope="col" style="width: 180px;">
                                        {{ __('REINFO NOMBRE') }}
                                    </th>
                                    <th scope="col">
                                        {{ __('F.VENCIMIENTO') }}
                                    </th>
                                    <th scope="col">
                                        {{ __('OBSERVACIÓN') }}
                                    </th>
                                    @can('gestionar clientes')
                                        <th scope="col" style="width: 130px;">
                                            {{ __('ACCIONES') }}
                                        </th>
                                    @endcan

                                </tr>
                            </thead>

                            <tbody id="tabla-clientes" style="font-size: 13px">
                                @include('liquidaciones.clientes.partials.tabla')
                            </tbody>
                        </table>
                        <div class="d-flex justify-content-between">
                            <div>
                                {{ $clientes->links('pagination::bootstrap-4') }}
                            </div>
                            <div>
                                {{ __('MOSTRANDO DEL') }} {{ $clientes->firstItem() }} {{ __('AL') }}
                                {{ $clientes->lastItem() }} {{ __('DE') }} {{ $clientes->total() }}
                                {{ __('REGISTROS') }}
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('liquidaciones.clientes.modal.create')
    @include('liquidaciones.clientes.modal.edit')
    @include('liquidaciones.clientes.modal.reinfo')

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    @push('js')
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script src="{{ asset('js/updateadvice.js') }}"></script>

        <script>
            @if (session('status'))
                Swal.fire('ÉXITO', '{{ session('status') }}', 'success');
            @elseif (session('error'))
                Swal.fire('ERROR', '{{ session('error') }}', 'error');
            @endif
        </script>

        <script>
            $(document).ready(function() {

                function toggleRInfo() {
                    if ($('#r_info_prestado').is(':checked')) {
                        $('#r_info_container').slideDown();
                        $('#r_info, #nombre_r_info').prop('required', true);
                    } else {
                        $('#r_info_container').slideUp();
                        $('#r_info, #nombre_r_info').prop('required', false).val('');
                    }
                }

                toggleRInfo();

                $('#r_info_prestado').on('change', function() {
                    toggleRInfo();
                });

            });
        </script>
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

        <script>
            $(document).ready(function() {
                $('.buscador').select2({
                    theme: "classic"
                });
            });

            $(document).ready(function() {

                function filtrarClientes() {
                    $.ajax({
                        url: "{{ route('lqclientes.index') }}",
                        type: "GET",
                        data: {
                            search: $('input[name="search"]').val(),
                            estado: $('#filtroContrato').val()
                        },
                        success: function(html) {
                            $('#products-table tbody').html(html);
                        }
                    });
                }

                let timer;
                $('input[name="search"]').on('keyup', function() {
                    clearTimeout(timer);
                    timer = setTimeout(filtrarClientes, 400);
                });

                $('#filtroContrato').on('change', filtrarClientes);

                $(document).on('click', '.btn-desactivar', function() {
                    let id = $(this).data('id');

                    Swal.fire({
                        title: '¿Desactivar cliente?',
                        text: 'El cliente no podrá ser usado',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Sí, desactivar',
                        cancelButtonText: 'Cancelar'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                                url: `/lqclientes/${id}/desactivar`,
                                type: 'PUT',
                                data: {
                                    _token: '{{ csrf_token() }}'
                                },
                                success: function(res) {
                                    Swal.fire('Listo', res.message, 'success');
                                    filtrarClientes();
                                }
                            });
                        }
                    });
                });

                $(document).on('click', '.btn-activar', function() {
                    let id = $(this).data('id');

                    Swal.fire({
                        title: '¿Activar cliente?',
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonText: 'Activar',
                        cancelButtonText: 'Cancelar'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                                url: `/lqclientes/${id}/activar`,
                                type: 'PUT',
                                data: {
                                    _token: '{{ csrf_token() }}'
                                },
                                success: function(res) {
                                    Swal.fire('Listo', res.message, 'success');
                                    filtrarClientes();
                                }
                            });
                        }
                    });
                });

            });

            $(document).ready(function() {

                function toggleRInfo() {
                    if ($('#r_info_prestado').is(':checked')) {
                        $('#r_info_container').slideDown();
                        $('#r_info, #nombre_r_info').prop('required', true);
                    } else {
                        $('#r_info_container').slideUp();
                        $('#r_info, #nombre_r_info').prop('required', false).val('');
                    }
                }

                toggleRInfo();

                $('#r_info_prestado').on('change', function() {
                    toggleRInfo();
                });

            });

            $(document).on('click', '.btn-edit', function() {
                let id = $(this).data('id');

                $.ajax({
                    url: '/lqclientes/' + id + '/edit',
                    type: 'GET',
                    success: function(data) {

                        $('#edit_id').val(data.id);
                        $('#edit_codigo').val(data.codigo);
                        $('#edit_documento').val(data.documento);
                        $('#edit_nombre').val(data.nombre);
                        $('#edit_observacion').val(data.observacion);


                        if (data.r_info_prestado == 1) {
                            $('#edit_r_info_prestado').prop('checked', true);
                            $('#edit_r_info').val(data.r_info);
                            $('#edit_nombre_r_info').val(data.nombre_r_info);
                            $('#edit_r_info_container').show();
                            $('#edit_r_info, #edit_nombre_r_info').prop('required', true);
                        } else {
                            $('#edit_r_info_prestado').prop('checked', false);
                            $('#edit_r_info').val('');
                            $('#edit_nombre_r_info').val('');
                            $('#edit_r_info_container').hide();
                            $('#edit_r_info, #edit_nombre_r_info').prop('required', false);
                        }

                        $('#formEditCliente').attr('action', '/lqclientes/' + data.id);

                        var modalElement = document.getElementById('ModalEdit');
                        var modal = new bootstrap.Modal(modalElement);
                        modal.show();

                    },
                    error: function(xhr, status, error) {
                        console.error('Error en AJAX:', error);
                        console.error('Respuesta:', xhr.responseText);
                        Swal.fire('Error', 'No se pudo cargar los datos del cliente', 'error');
                    }
                });
            });

            $(document).on('change', '#edit_r_info_prestado', function() {
                if ($(this).is(':checked')) {
                    $('#edit_r_info_container').slideDown();
                    $('#edit_r_info, #edit_nombre_r_info').prop('required', true);
                } else {
                    $('#edit_r_info_container').slideUp();
                    $('#edit_r_info, #edit_nombre_r_info').prop('required', false).val('');
                }
            });

            $(document).on('change', '#edit_r_info_prestado', function() {
                if ($(this).is(':checked')) {
                    $('#edit_r_info_container').slideDown();
                    $('#edit_r_info, #edit_nombre_r_info').prop('required', true);
                } else {
                    $('#edit_r_info_container').slideUp();
                    $('#edit_r_info, #edit_nombre_r_info').prop('required', false).val('');
                }
            });



            $(document).ready(function() {

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

                $('#documento').on('input', function() {
                    buscarDocumento(
                        '{{ route('buscar.documento') }}',
                        '#documento',
                        '#nombre'
                    );
                });


                $('#r_info').on('input', function() {
                    buscarDocumento(
                        '{{ route('buscar.documento') }}',
                        '#r_info',
                        '#nombre_r_info'
                    );
                });

                $('#edit_documento').on('input', function() {
                    buscarDocumento(
                        '{{ route('buscar.documento') }}',
                        '#edit_documento',
                        '#edit_nombre'
                    );
                });

                $('#edit_r_info').on('input', function() {
                    buscarDocumento(
                        '{{ route('buscar.documento') }}',
                        '#edit_r_info',
                        '#edit_nombre_r_info'
                    );
                });

                $('.datos-input').on('input', function() {
                    var value = $(this).val();
                    $(this).toggleClass('is-valid', value.trim().length > 0);
                    $(this).toggleClass('is-invalid', value.trim().length === 0);
                });


            });

            function actualizarLinkExport() {
                let search = $('input[name="search"]').val();
                let estado = $('#filtroContrato').val();

                let params = new URLSearchParams();

                if (search) params.append('search', search);
                if (estado) params.append('estado', estado);

                $('#btn-export').attr(
                    'href',
                    "{{ route('lqclientes.export') }}?" + params.toString()
                );
            }

            $('input[name="search"]').on('keyup', function() {
                actualizarLinkExport();
            });

            $('#filtroContrato').on('change', function() {
                actualizarLinkExport();
            });

            actualizarLinkExport();

            $('#ModalCreate').on('show.bs.modal', function() {
                const modal = $(this);
                modal.find('form')[0].reset();
                modal.find('#r_info_container').hide();
                modal.find('#r_info, #nombre_r_info').prop('required', false);
                modal.find('.is-valid, .is-invalid').removeClass('is-valid is-invalid');
            });
        </script>

        <script>
            $(document).ready(function() {
                const modalReinfo = $('#ModalReinfo');
                const selectDpto = $('#select_departamento');
                const selectProv = $('#select_provincia');
                const selectDist = $('#select_distrito');

                function cargarDepartamentos() {
                    $.get('{{ route("lqclientes.reinfo.departamento") }}', function(data) {
                        let html = '<option value="">Seleccione Departamento...</option>';
                        data.forEach(d => {
                            html += `<option value="${d.id}">${d.nombre}</option>`;
                        });
                        selectDpto.html(html);
                    });
                }
                cargarDepartamentos();

                function cargarProvincias(dptoId) {
                    return $.get('/lqclientes/reinfo/provincias/' + dptoId, function(data) {
                        let html = '<option value="">Seleccione Provincia...</option>';
                        data.forEach(p => html += `<option value="${p.id}">${p.nombre}</option>`);
                        selectProv.html(html).prop('disabled', false);
                    });
                };

                function cargarDistritos(provId) {
                    return $.get('/lqclientes/reinfo/distritos/' + provId, function(data) {
                        let html = '<option value="">Seleccione Distrito...</option>';
                        data.forEach(d => html += `<option value="${d.id}">${d.nombre}</option>`);
                        selectDist.html(html).prop('disabled', false);
                    });
                }

                selectDpto.on('change', function() {
                    let dptoId = $(this).val();
                    selectProv.html('<option value="">Cargando...</option>').prop('disabled', true);
                    selectDist.html('<option value="">Seleccione Prov...</option>').prop('disabled', true);

                    if (dptoId) {
                        cargarProvincias(dptoId);
                    } else {
                        selectProv.html('<option value="">Seleccione Dpto...</option>').prop('disabled', true);
                    }
                });

                selectProv.on('change', function() {
                    let provId = $(this).val();
                    selectDist.html('<option value="">Cargando...</option>').prop('disabled', true);

                    if (provId) {
                        cargarDistritos(provId);
                    } else {
                        selectDist.html('<option value="">Seleccione Prov...</option>').prop('disabled', true);;
                    }
                });

                $(document).on('click', '.btn-reinfo', async function() {
                    let id = $(this).data('id');
                    let btn = $(this);
                    
                    let originalText = btn.html();
                    btn.prop('disabled', true);
                    // btn.html('<i class="fa fa-spinner fa-spin"></i>').prop('disabled', true);

                    $('#formReinfo')[0].reset();
                    $('#reinfo_cliente_id').val(id);
                    $('#ubicacion_actual_texto').hide();
                    
                    selectProv.html('<option value="">Seleccione Dpto...</option>').prop('disabled', true);
                    selectDist.html('<option value="">Seleccione Prov...</option>').prop('disabled', true);
                    
                    try {
                        const response = await $.get(`/lqclientes/reinfo/get/${id}`);

                        var modalElement = document.getElementById('ModalReinfo');
                        var myModal = new bootstrap.Modal(modalElement);
                        
                        if(response.success) {
                            let d = response.data;

                            $('#reinfo_codigo').val(d.codigo_minero);
                            $('#reinfo_nombre').val(d.nombre_minero);
                            $('#reinfo_estado').val(d.estado_reinfo !== null ? d.estado_reinfo : '1');

                            if (d.ubigeo_id && d.departamento_id) {
                                selectDpto.val(d.departamento_id);

                                await cargarProvincias(d.departamento_id);
                                selectProv.val(d.provincia_id);

                                await cargarDistritos(d.provincia_id);
                                selectDist.val(d.ubigeo_id);
                                
                                $('#ubicacion_actual_texto').html(
                                    '<i class="fa fa-check-circle text-success"></i> Actual: <strong>' + d.ubicacion_texto + '</strong>'
                                ).show();
                            } else {
                                selectDpto.val('');
                                selectProv.html('<option value="">Seleccione Dpto...</option>').prop('disabled', true);
                                selectDist.html('<option value="">Seleccione Prov...</option>').prop('disabled', true);
                            }
                            myModal.show();
                        }                        
                    } catch (error) {
                        Swal.fire('Error', 'No se pudo sincronizar los datos', 'error');
                    } finally {
                        btn.html(originalText).prop('disabled', false);
                    }
                });

                // --- GUARDAR DATOS (AJAX) ---
                // $('#formReinfo').on('submit', function(e) {
                //     e.preventDefault();
                    
                //     let form = $(this);
                //     let btn = $('#btnGuardarReinfo');
                    
                //     btn.html('<i class="fa fa-spinner fa-spin"></i> Guardando...').prop('disabled', true);

                //     $.ajax({
                //         url: "{{ route('lqclientes.reinfo.save') }}",
                //         type: 'POST',
                //         data: form.serialize(),
                //         success: function(response) {
                //             if(response.success) {
                //                 var modalElement = document.getElementById('ModalReinfo');
                //                 var modalInstance = bootstrap.Modal.getInstance(modalElement);
                //                 modalInstance.hide();
                //                 Swal.fire('Éxito', response.message, 'success'); // TYPE: Success (Legacy style)
                //                 // Opcional: Recargar tabla si es necesario
                //                 // filtrarClientes();   
                //             }
                //         },
                //         error: function(xhr) {
                //             let msg = 'Error al guardar.';
                //             if(xhr.responseJSON && xhr.responseJSON.message) {
                //                 msg = xhr.responseJSON.message;
                //             }
                //             Swal.fire('Error', msg, 'error'); // TYPE: Error (Legacy style)
                //         },
                //         complete: function() {
                //             btn.html('<i class="fa fa-save"></i> Guardar Cambios').prop('disabled', false);
                //         }
                //     });
                // });
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

@endsection
