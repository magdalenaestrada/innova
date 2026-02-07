@extends('admin.layout')

@section('content')
    <br>
    <div class="container-fluid mt-4">
        <div class="card shadow-sm">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center py-2">
                    <div>
                        <h4 class="mb-0 ">
                            <i class="bi bi-clipboard-data me-2"></i>
                            PESOS DE OTRAS BALANZAS
                        </h4>
                    </div>
                    <div>
                        <button type="button" class="btn btn-success" id="btnAbrirModal">
                            <i class="bi bi-plus-circle me-1"></i> Agregar Peso
                        </button>
                    </div>
                </div>
            </div>
            <div class="card-body">
                @php
                    use Carbon\Carbon;
                    $hoy = Carbon::now('America/Lima')->format('Y-m-d');
                @endphp

                <form id="filtros" method="POST" action="{{ route('otrasBalanza') }}" class="mb-4">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-2">
                            <label class="form-label fw-semibold"><i class="bi bi-calendar-date text-primary"></i> Fecha
                                Desde</label>
                            <input type="date" name="desde" class="form-control">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label fw-semibold"><i class="bi bi-calendar-check text-primary"></i> Fecha
                                Hasta</label>
                            <input type="date" name="hasta" class="form-control" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">Razón Social</label>
                            <input type="text" name="razon" class="form-control" placeholder="Buscar Razón Social">
                        </div>

                        <div class="col-md-2">
                            <label class="form-label fw-semibold">Origen</label>
                            <input type="text" name="origen" class="form-control" placeholder="Buscar Origen">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label fw-semibold">Destino</label>
                            <input type="text" name="destino" class="form-control" placeholder="Buscar Destino">
                        </div>
                        <div class="col-md-1">
                            <label class="form-label fw-semibold">Estado</label>
                            <select name="estado_id" class="form-control form-select-sm estado-select w-150">
                                <option value="">TODOS</option>
                                @foreach ($estados as $estadoOpt)
                                    <option value="{{ $estadoOpt->id }}">
                                        {{ $estadoOpt->nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-1">
                            <label class="form-label fw-semibold">Ticket</label>
                            <input type="text" name="id" class="form-control" placeholder="Buscar">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label fw-semibold">Producto</label>
                            <input type="text" name="producto" class="form-control" value="MINERAL A GRANEL"
                                placeholder="Buscar Producto">
                        </div>

                        <div class="col-md-2">
                            <label class="form-label fw-semibold">Conductor</label>
                            <input type="text" name="conductor" class="form-control" placeholder="Buscar Conductor">
                        </div>
                        <div class="col-md-1">
                            <label class="form-label fw-semibold">Placa</label>
                            <input type="text" name="placa" class="form-control" placeholder="Buscar">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">Observación</label>
                            <input type="text" name="observacion" class="form-control" placeholder="Buscar Observación">
                        </div>
                        <div class="col-md-1 d-flex align-items-end gap-2">
                            <a href="{{ route('pesos.index') }}" class="btn btn-light flex-fill"><i
                                    class="bi bi-funnel-fill me-1"></i> Limpiar</a>
                        </div>
                    </div>
                </form>
                <hr class="my-4">
                <div id="tabla-container">
                </div>

            </div>
        </div>
    </div>
    @include('otros-pesos.modals.create')
@endsection

@section('js')
    <script>
        function formatDateForInput(dateString) {
            if (!dateString) return '';
            if (dateString.includes('T')) {
                return dateString.substring(0, 16);
            }
            return dateString.replace(' ', 'T').substring(0, 16);
        }

        document.addEventListener("DOMContentLoaded", () => {
            console.log('DOM cargado');
            console.log('Modal existe:', document.getElementById('modalPesoOtraBalanza'));
            console.log('Botón existe:', document.getElementById('btnAbrirModal'));
            console.log('Tabla existe:', document.getElementById('tabla-container'));


            const btn = document.getElementById("btnAbrirModal");
            const modalEl = document.getElementById('modalPesoOtraBalanza');
            const modal = new bootstrap.Modal(modalEl);

            const filtrosForm = document.getElementById("filtros");
            const tablaContainer = document.querySelector("#tabla-container");

            async function cargarTabla(url = "{{ route('otrasBalanza') }}") {
                const formData = new FormData(filtrosForm);
                try {
                    const res = await fetch(url, {
                        method: "POST",
                        headers: {
                            "X-Requested-With": "XMLHttpRequest",
                            "X-CSRF-TOKEN": "{{ csrf_token() }}"
                        },
                        body: formData
                    });
                    const html = await res.text();
                    tablaContainer.innerHTML = html;
                } catch (err) {
                    console.error("Error al recargar tabla:", err);
                }
            }

            filtrosForm.querySelectorAll("input, select").forEach(input => {
                input.addEventListener("input", () => cargarTabla());
            });

            filtrosForm.addEventListener("submit", e => {
                e.preventDefault();
                cargarTabla();
            });

            $(document).on('input', '#formPesoManual input[name="bruto"], #formPesoManual input[name="tara"]',
                function() {
                    const bruto = parseFloat($('#formPesoManual input[name="bruto"]').val()) || 0;
                    const tara = parseFloat($('#formPesoManual input[name="tara"]').val()) || 0;
                    const neto = bruto - tara;
                    $('#formPesoManual input[name="neto"]').val(neto >= 0 ? neto.toFixed(2) : 0);
                });

            document.addEventListener("click", async e => {
                const link = e.target.closest(".pagination a");
                if (link) {
                    e.preventDefault();
                    return cargarTabla(link.href);
                }
            });

            document.addEventListener("click", async e => {
                const btn = e.target.closest('.guardar-btn');
                if (!btn) return;

                const pesoId = btn.dataset.peso;
                const inputCliente = document.querySelector(`.cliente-input[data-peso="${pesoId}"]`);
                const selectCliente = inputCliente.closest('.combo').querySelector('.cliente-select');
                const clienteNombre = inputCliente?.value.trim();
                const option = Array.from(selectCliente.options)
                    .find(opt => opt.text.trim().toLowerCase() === clienteNombre.toLowerCase());

                const clienteId = option ? option.value : null;

                if (!clienteId) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Cliente no encontrado',
                        text: 'El cliente ingresado no coincide con ninguno de la lista.',
                        confirmButtonColor: '#d33'
                    });
                    return;
                }

                try {
                    const res = await fetch(`{{ route('otrasBalanza.update', '') }}/${pesoId}`, {
                        method: 'PUT',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json'
                        },
                    });

                    const result = await res.json();

                    if (res.ok && result.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Guardado correctamente',
                            text: 'El registro se actualizó exitosamente.',
                            timer: 1300,
                            showConfirmButton: false
                        });

                        const fila = btn.closest('tr');
                        fila.classList.add('table-success');

                        const estadoSelect = fila.querySelector('.estado-select');
                        if (estadoSelect) estadoSelect.disabled = true;
                        inputCliente.disabled = true;
                        btn.disabled = true;
                        btn.classList.remove('btn-success');
                        btn.classList.add('btn-secondary');
                        btn.innerHTML = '<i class="bi bi-check-circle"></i> Guardado';

                    } else {
                        throw new Error(result.message || 'Error desconocido');
                    }

                } catch (err) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: err.message || 'Error al guardar los datos.',
                        confirmButtonColor: '#d33'
                    });
                }
            });

            document.getElementById("btnAbrirModal").addEventListener("click", () => {
                editandoId = null;
                const form = document.getElementById('formPesoManual');
                form.reset();
                document.getElementById('cliente_id_real').value = '';
                document.getElementById('cliente_id_input').value = '';
                modal.show();
            });

            let editandoId = null;

            document.getElementById('btnGuardar').addEventListener('click', async () => {
                const form = document.getElementById('formPesoManual');
                const clienteId = document.getElementById('cliente_id_real').value;

                if (!clienteId) {
                    return Swal.fire('Cliente inválido', 'Selecciona un cliente válido', 'warning');
                }

                const formData = new FormData(form); // ✅ AQUÍ

                let url = "{{ route('otrasBalanza.guardar') }}";
                let method = "POST";

                if (editandoId) {
                    url = `/otras-balanza/${editandoId}`;
                    formData.append('_method', 'PUT');
                }

                try {
                    const res = await fetch(url, {
                        method,
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: formData
                    });

                    const data = await res.json();

                    if (res.ok && data.success) {
                        Swal.fire('Éxito', editandoId ? 'Peso actualizado' : 'Peso registrado',
                            'success');
                        form.reset();
                        editandoId = null;
                        modal.hide();
                        cargarTabla();
                    } else {
                        Swal.fire('Error', data.message || 'No se pudo guardar', 'error');
                    }

                } catch (e) {
                    console.error(e);
                    Swal.fire('Error', 'Error interno del servidor', 'error');
                }
            });


            document.addEventListener("click", async e => {
                const btn = e.target.closest('.editar-peso');
                if (!btn) return;

                editandoId = btn.dataset.id;

                const res = await fetch(`/otras-balanza/${editandoId}`);
                const data = await res.json();

                const form = document.getElementById('formPesoManual');
                const clienteInput = document.getElementById('cliente_id_input');

                document.getElementById('cliente_id_real').value = data.cliente_id;

                if (data.cliente) {
                    const nombreCliente = data.cliente.nombre ?? '';
                    const clienteNombre = data.cliente.cliente?.nombre ?? '';

                    let textoCompleto = '';

                    if (nombreCliente && clienteNombre) {
                        textoCompleto = `${nombreCliente} - ${clienteNombre}`;
                    } else if (nombreCliente) {
                        textoCompleto = `${nombreCliente}`;
                    } else if (nombreCliente && clienteNombre) {
                        textoCompleto = `${nombreCliente} - ${clienteNombre}`;
                    } else {
                        textoCompleto = nombreCliente || codigoCliente || '';
                    }

                    clienteInput.value = textoCompleto;
                } else {
                    clienteInput.value = '';
                }

                form.querySelector('[name="fechai"]').value = formatDateForInput(data.fechai);
                form.querySelector('[name="fechas"]').value = formatDateForInput(data.fechas);
                form.querySelector('[name="producto"]').value = data.producto ?? '';
                form.querySelector('[name="conductor"]').value = data.conductor ?? '';
                form.querySelector('[name="placa"]').value = data.placa ?? '';
                form.querySelector('[name="origen"]').value = data.origen ?? '';
                form.querySelector('[name="destino"]').value = data.destino ?? '';
                form.querySelector('[name="balanza"]').value = data.balanza ?? '';
                form.querySelector('[name="bruto"]').value = data.bruto ?? 0;
                form.querySelector('[name="tara"]').value = data.tara ?? 0;
                form.querySelector('[name="neto"]').value = data.neto ?? 0;
                form.querySelector('[name="guia"]').value = data.guia ?? '';
                form.querySelector('[name="guiat"]').value = data.guiat ?? '';
                form.querySelector('[name="observacion"]').value = data.observacion ?? '';

                modal.show();
            });

            $(document).on('input', '#cliente_id_input', function() {
                const filtro = $(this).val().toLowerCase().trim();
                const select = $('#cliente_id');
                let visibles = 0;

                if (!filtro) {
                    select.hide();
                    return;
                }

                select.find('option').each(function() {
                    const match = $(this).text().toLowerCase().includes(filtro);
                    $(this).toggle(match);
                    if (match) visibles++;
                });

                visibles > 0 ? select.show() : select.hide();
            });

            $(document).on('change', '#cliente_id', function() {
                const option = $(this).find('option:selected');
                $('#cliente_id_input').val(option.text().trim());
                $('#cliente_id_real').val(option.val());
                $(this).hide();
            });

            $(document).on('click', function(e) {
                if (!$(e.target).closest('#cliente_id, #cliente_id_input').length) {
                    $('#cliente_id').hide();
                }
            });

            $(document).ready(function() {
                $(document).on('input', '.cliente-input', function() {
                    const input = $(this);
                    const search = input.val().toLowerCase();
                    const select = input.siblings('.cliente-select');

                    if (search.length > 0) {
                        select.show();
                        select.find('option').each(function() {
                            const optionText = $(this).text().toLowerCase();
                            $(this).toggle(optionText.includes(search));
                        });
                    } else {
                        select.hide();
                    }
                });

                $(document).on('change', '.cliente-select', function() {
                    const select = $(this);
                    const option = select.find('option:selected');
                    const input = select.siblings('.cliente-input');
                    const hidden = select.siblings('.cliente-hidden');

                    input.val(option.text().trim());
                    hidden.val(option.val());

                    select.hide();
                });
            });

            document.addEventListener('click', function(e) {
                if (e.target.id === 'btn-exportar' || e.target.closest('#btn-exportar')) {
                    e.preventDefault();

                    const btn = e.target.id === 'btn-exportar' ? e.target : e.target.closest(
                        '#btn-exportar');
                    const btnTexto = btn.innerHTML;
                    btn.disabled = true;
                    btn.innerHTML = '<i class="bi bi-hourglass-split"></i> Exportando...';

                    const formData = new FormData(filtrosForm);
                    const tempForm = document.createElement('form');
                    tempForm.method = 'POST';
                    tempForm.action = '{{ route('otrasBalanza.export') }}';

                    const csrfInput = document.createElement('input');
                    csrfInput.type = 'hidden';
                    csrfInput.name = '_token';
                    csrfInput.value = '{{ csrf_token() }}';
                    tempForm.appendChild(csrfInput);

                    for (let [key, value] of formData.entries()) {
                        const input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = key;
                        input.value = value;
                        tempForm.appendChild(input);
                    }

                    document.body.appendChild(tempForm);
                    tempForm.submit();

                    setTimeout(() => {
                        document.body.removeChild(tempForm);
                        btn.disabled = false;
                        btn.innerHTML = btnTexto;
                    }, 2000);
                }
            });

            cargarTabla();

            document.addEventListener("click", (e) => {
                if (e.target.id === "check-all") {
                    document.querySelectorAll(".check-item")
                        .forEach(c => c.checked = e.target.checked);
                }
            });

            $(document).on("input", "#cliente-masivo-input", function() {
                let filtro = $(this).val().toLowerCase();
                $("#cliente-masivo-select").show();
                $("#cliente-masivo-select option").each(function() {
                    let texto = $(this).text().toLowerCase();
                    $(this).toggle(texto.includes(filtro));
                });
            });

            $(document).on("focus", "#cliente-masivo-input", function() {
                $("#cliente-masivo-select").show();
            });

            $(document).on("click", "#cliente-masivo-select option", function() {
                let nombre = $(this).text();
                let id = $(this).val();
                $("#cliente-masivo-input").val(nombre);
                $("#cliente-masivo-id").val(id);
                $("#cliente-masivo-select").hide();
            });

            $(document).on("blur", "#cliente-masivo-input", function() {
                setTimeout(() => $("#cliente-masivo-select").hide(), 200);
            });
        });
    </script>
@endsection
