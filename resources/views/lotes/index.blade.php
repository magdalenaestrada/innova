@extends('admin.layout')

@push('css')
    <link rel="stylesheet" href="{{ asset('css/productos.css') }}">
    <style>
        .combo {
            position: relative;
        }

        .combo input {
            width: 100%;
        }

        .combo select {
            width: 100%;
            position: absolute;
            top: 100%;
            left: 0;
            display: none;
            z-index: 1000;
            max-height: 150px;
            overflow-y: auto;
        }
    </style>
@endpush

@section('content')
    <div class="container">
        <br>
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="row card-header d-flex justify-content-between align-items-center">
                        <div class="col-md-6">{{ __('LOTES REGISTRADOS') }}</div>
                        <div class="col-md-6 text-right">
                            <button class="btn btn-sm btn-special" style="border: 1px solid black; border-radius: 6px;"
                                data-toggle="modal" data-target="#ModalCreateLote">
                                {{ __('CREAR LOTE') }}
                            </button>
                        </div>
                    </div>

                    <div class="row align-items-center justify-content-between p-3">
                        <div class="col-md-6 input-container">
                            <input type="text" id="search" class="form-control"
                                placeholder="Buscar por código o cliente...">
                        </div>
                    </div>

                    <div class="card-body table-responsive">
                        <table class="table table-striped table-hover text-center" id="lote-table">
                            <thead>
                                <tr>
                                    <th>{{ __('CODIGO') }}</th>
                                    <th>{{ __('NOMBRE') }}</th>
                                    <th>{{ __('CLIENTE') }}</th>
                                    <th>{{ __('ACCIÓN') }}</th>
                                </tr>
                            </thead>
                            <tbody style="font-size:13px">
                                @forelse ($lotes as $lote)
                                    <tr>
                                        <td>{{ $lote->codigo }}</td>
                                        <td>{{ $lote->nombre }}</td>
                                        <td>{{ $lote->cliente->nombre ?? 'NO ASIGNADO' }}</td>
                                        <td>
                                            <button class="btn btn-sm btn-outline-warning btn-editar-lote"
                                                data-id="{{ $lote->id }}" data-nombre="{{ $lote->nombre }}"
                                                data-cliente-id="{{ $lote->cliente->id }}"
                                                data-cliente-nombre="{{ $lote->cliente->nombre ?? '' }}"
                                                data-codigo="{{ $lote->codigo }}">
                                                <i class="fas fa-pen"></i>
                                            </button>

                                            <button class="btn btn-sm btn-outline-danger btn-delete-lote"
                                                data-id="{{ $lote->id }}">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center text-muted">
                                            {{ __('No hay lotes registrados') }}
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>

                        <nav>
                            <ul class="pagination justify-content-end">
                                {{ $lotes->links() }}
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>

        @include('lotes.modals.create')
        @include('lotes.modals.edit')
    </div>
@stop

@section('js')

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).on('click', '.btn-editar-lote', function() {
            const id = $(this).data('id');
            const nombre = $(this).data('nombre');
            const clienteId = $(this).data('cliente-id');
            const clienteNombre = $(this).data('cliente-nombre');
            const codigo = $(this).data('codigo');

            console.log("Botón editar clickeado:");
            console.log("data-id:", $(this).data('id'));
            console.log("data-nombre:", $(this).data('nombre'));
            console.log("data-cliente-id:", $(this).data('cliente-id'));
            console.log("data-cliente-nombre:", $(this).data('cliente-nombre'));
            console.log("data-codigo:", $(this).data('codigo'));

            // luego llenas los inputs del modal
            $('#modalLoteNombre').val($(this).data('nombre'));
            $('#modalClienteNombre').val($(this).data('cliente-nombre'));
            $('#modalClienteId').val($(this).data('cliente-id'));
            $('#modalCodigo').val($(this).data('codigo'));

            $('#formEditarLote').attr('action', '/lotes/' + id);

            // Inicializar el combo
            initComboClienteModal(document.getElementById('modalEditarLote'));

            $('#modalEditarLote').modal('show');
        });



        $(document).on('submit', '#formEditarLote', function(e) {
            e.preventDefault();

            const form = $(this);
            const actionUrl = form.attr('action');

            $.ajax({
                url: actionUrl,
                type: 'POST',
                data: form.serialize() + '&_method=PUT',
                success: function(res) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Éxito',
                        text: res.message,
                        timer: 1500,
                        showConfirmButton: false
                    });

                    form.closest('.modal').modal('hide');
                    window.location.reload();
                },
                error: function(xhr) {

                    if (xhr.status === 422) {
                        let errors = xhr.responseJSON.errors;
                        let mensaje = '';

                        Object.values(errors).forEach(err => {
                            mensaje += err[0] + '<br>';
                        });

                        Swal.fire({
                            icon: 'warning',
                            title: 'Validación',
                            html: mensaje
                        });
                        return;
                    }

                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: xhr.responseJSON?.message || 'No se pudo actualizar el lote.'
                    });
                }
            });
        });


        $(document).on('click', '.btn-delete-lote', function() {
            const loteId = $(this).data('id');

            Swal.fire({
                title: '¿Estás seguro?',
                text: "Este lote será eliminado.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('lotes.destroy') }}",
                        type: "POST",
                        data: {
                            loteId: loteId,
                            _token: "{{ csrf_token() }}",
                            _method: "DELETE"
                        },
                        success: function(res) {
                            if (res.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Eliminado',
                                    text: res.message,
                                    timer: 1500,
                                    showConfirmButton: false
                                });
                                setTimeout(() => {
                                    window.location.reload();
                                }, 1500);
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: res.message
                                });
                            }
                        },
                        error: function() {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'No se pudo eliminar el lote.'
                            });
                        }
                    });
                }
            });
        });


        $('#formLote').on('submit', function(e) {
            e.preventDefault();
            $.ajax({
                url: "{{ route('lotes.store') }}",
                type: "POST",
                data: $(this).serialize(),
                success: function(res) {
                    if (res.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Éxito',
                            text: res.message,
                            timer: 1500,
                            showConfirmButton: false
                        });

                        $('#ModalCreateLote').modal('hide');
                        $('#formLote')[0].reset();
                        window.location.reload();

                    }
                },

                error: function(xhr) {
                    if (xhr.status === 422) {
                        let errores = xhr.responseJSON.errors;
                        let mensaje = "";

                        $.each(errores, function(key, value) {
                            mensaje += value[0] + "\n";
                        });

                        Swal.fire({
                            icon: 'error',
                            title: 'Error de validación',
                            text: mensaje
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Ocurrió un error inesperado.'
                        });
                    }
                }
            });
        });

        function initComboClienteModal(modal) {
            const combo = modal.querySelector('.combo');
            if (!combo) return;

            const input = combo.querySelector('.clienteInput');
            const select = combo.querySelector('.clienteSelect');
            const hidden = combo.querySelector('input[name="lq_cliente_id"]');

            if (!input || !select || !hidden) return;

            if (hidden.value) {
                const option = [...select.options].find(o => o.value == hidden.value);
                if (option) input.value = option.text;
            }

            if (!hidden.value && input.value) {
                const option = [...select.options].find(o => o.text === input.value);
                if (option) hidden.value = option.value;
            }

            input.addEventListener('input', () => {
                const filtro = input.value.toLowerCase().trim();
                let hasMatch = false;
                [...select.options].forEach(opt => {
                    if (!filtro || opt.text.toLowerCase().includes(filtro)) {
                        opt.style.display = '';
                        hasMatch = true;
                    } else {
                        opt.style.display = 'none';
                    }
                });
                select.style.display = hasMatch ? 'block' : 'none';
            });

            input.addEventListener('focus', () => select.style.display = 'block');

            select.addEventListener('change', function() {
                const selectedOption = this.options[this.selectedIndex];
                if (!selectedOption || !selectedOption.value) return;

                input.value = selectedOption.text;
                hidden.value = selectedOption.value;
                select.style.display = 'none';

                if (codigoInput) {
                    const codigoBase = selectedOption.text.split(' - ')[0];
                    const ultimoCorrelativo = parseInt(selectedOption.getAttribute('data-ultimo')) || 0;

                    if (isEditMode) {
                        const codigoActual = codigoInput.dataset.original || codigoInput.value;
                        const clienteActual = codigoActual.split('-').slice(0, 3).join('-'); // AF-24-0001

                        if (clienteActual !== codigoBase) {
                            const nuevoNumero = ultimoCorrelativo + 1;
                            codigoInput.value = `${codigoBase}-${nuevoNumero}`;
                        } else {
                            codigoInput.value = codigoInput.dataset.original;
                        }
                    } else {
                        const nuevoNumero = ultimoCorrelativo + 1;
                        codigoInput.value = `${codigoBase}-${nuevoNumero}`;
                    }
                }

                select.style.display = 'none';
            });

            select.addEventListener('click', function(e) {
                if (e.target.tagName === 'OPTION') {
                    select.style.display = 'none';
                }
            });

            document.addEventListener('click', (e) => {
                if (!combo.contains(e.target)) select.style.display = 'none';
            });
        }


        $('#modalEditarLote').on('shown.bs.modal', function() {
            const modal = this;
            initComboClienteModal(modal);

            const codigoInput = $(modal).find('#modalCodigo');
            codigoInput.attr('data-original', codigoInput.val());
        });

        $('#ModalCreateLote').on('shown.bs.modal', function() {
            initComboClienteModal(this);
            $('#codigo_lote').val('');
            $('#nombre').val('');
            $('#cliente_id').val('');
        });


        $(document).on('hidden.bs.modal', '[id^="ModalEditLote"]', function() {

            const modal = this;

            const form = modal.querySelector('form');
            if (form) {
                form.reset();
            }

            modal.querySelectorAll('.clienteHidden').forEach(hidden => {
                if (hidden.dataset.original) {
                    hidden.value = hidden.dataset.original;
                }
            });

            modal.querySelectorAll('.clienteInput').forEach(input => {
                const select = input.closest('.combo')?.querySelector('.clienteSelect');
                if (!select) return;

                const opt = [...select.options].find(o => o.value === input
                    .closest('.combo')
                    .querySelector('.clienteHidden')?.dataset.original);

                if (opt) {
                    input.value = opt.text;
                }
            });

            modal.querySelectorAll('.codigo-lote-input').forEach(input => {
                if (input.dataset.original) {
                    input.value = input.dataset.original;
                }
            });
        });

        $('#ModalCreateLote').on('hidden.bs.modal', function() {
            const modal = this;
            const form = modal.querySelector('#formLote');
            if (form) {
                form.reset();
            }
            modal.querySelectorAll('.clienteInput').forEach(input => {
                input.value = '';
            });
            modal.querySelectorAll('.clienteHidden').forEach(hidden => {
                hidden.value = '';
            });
            modal.querySelectorAll('.clienteSelect').forEach(select => {
                select.style.display = 'none';
            });
            const codigoInput = modal.querySelector('#codigo_lote');
            if (codigoInput) {
                codigoInput.value = '';
            }
        });

        $(document).ready(function() {

            $('#search').on('input', function() {
                let search_string = $(this).val();

                $.ajax({
                    url: "{{ route('lotes.search') }}",
                    method: 'GET',
                    data: {
                        search_string: search_string
                    },
                    success: function(response) {
                        $('#lote-table tbody').html(response);
                    },
                    error: function() {
                        console.error('Error al buscar lotes');
                    }
                });
            });

        });
    </script>

@stop
