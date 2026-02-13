@extends('admin.layout')

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        {{ __('TODAS LOS PESOS REGISTRADOS') }}
                    </div>

                    <div class="card-body">
                        <div class="card mb-3">
                            <div class="card-body">
                                <div class="row g-2">
                                    <div class="col-md-2">
                                        <b><label for="f_fechai">FECHA INICIO</label></b>
                                        <input type="date" id="f_fechai" class="form-control">
                                    </div>

                                    <div class="col-md-2">
                                        <b><label for="f_fechas">FECHA FIN</label></b>
                                        <input type="date" id="f_fechas" class="form-control">
                                    </div>

                                    <div class="col-md-1">
                                        <b><label for="f_ticket">TICKET</label></b>
                                        <input type="text" id="f_ticket" class="form-control" placeholder="Ticket">
                                    </div>
                                    <div class="col-md-3">
                                        <b><label for="f_razon">RAZÓN SOCIAL</label></b>
                                        <input type="text" id="f_razon" class="form-control"
                                            placeholder="Razón Social">
                                    </div>
                                    <div class="col-md-2">
                                        <b><label for="f_producto">PRODUCTO</label></b>
                                        <input type="text" id="f_producto" class="form-control" placeholder="Producto">
                                    </div>
                                    <div class="col-md-1">
                                        <b><label for="f_destino">DESTINO</label></b>
                                        <input type="text" id="f_destino" class="form-control" placeholder="Destino">
                                    </div>
                                    <div class="col-md-1">
                                        <b><label for="f_origen">ORIGEN</label></b>
                                        <input type="text" id="f_origen" class="form-control" placeholder="Origen">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end mb-3">
                            <button id="btn-exportar" class="btn btn-success">
                                Exportar Excel
                            </button>
                        </div>

                        <table class="table table-striped table-hover">
                            @if (count($pesos) > 0)
                                <thead>
                                    <tr>
                                        <th>Ticket</th>
                                        <th>Horas</th>
                                        <th>Fechas</th>
                                        <th>Neto</th>
                                        <th>Placa</th>
                                        <th>Producto</th>
                                        <th>RazonSocial</th>
                                        <th>destino</th>
                                        <th>origen</th>
                                        <th>ACCIÓN</th>
                                    </tr>
                                </thead>

                                <tbody id="tabla-pesos">
                                    @include('pesos.partials.tabla', ['pesos' => $pesos])
                                </tbody>
                            @else
                                <tr>
                                    <td colspan="10" class="text-center text-muted">
                                        {{ __('Aún hay datos disponibles') }}
                                    </td>
                                </tr>
                            @endif
                        </table>

                        @if (!request()->ajax())
                            <nav aria-label="Page navigation example">
                                {{ $pesos->links() }}
                            </nav>
                        @endif


                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('js')
        <script>
            $('#btn-exportar').on('click', function() {

                const params = new URLSearchParams({
                    fechai: $('#f_fechai').val(),
                    fechas: $('#f_fechas').val(),
                    ticket: $('#f_ticket').val(),
                    razon: $('#f_razon').val(),
                    producto: $('#f_producto').val(),
                    destino: $('#f_destino').val(),
                    origen: $('#f_origen').val(),
                });
                window.location.href = "{{ route('pesos.export.excel') }}?" + params.toString();
            });

            let typingTimer;
            const delay = 100;

            function cargarPesos() {
                $.ajax({
                    url: "{{ route('pesos.index') }}",
                    type: "GET",
                    data: {
                        fechai: $('#f_fechai').val(),
                        fechas: $('#f_fechas').val(),
                        ticket: $('#f_ticket').val(),
                        razon: $('#f_razon').val(),
                        producto: $('#f_producto').val(),
                        destino: $('#f_destino').val(),
                        origen: $('#f_origen').val(),
                        ajax: true
                    },
                    success: function(html) {
                        $('#tabla-pesos').html(html);
                    }
                });
            }

            $('.form-control').on('keyup change', function() {
                clearTimeout(typingTimer);
                typingTimer = setTimeout(cargarPesos, delay);
            });


            @if (session('eliminar-registro') == 'Registro eliminado con éxito.')
                Swal.fire('Registro', 'eliminado exitosamente.', 'success')
            @endif
            @if (session('crear-registro') == 'Registro creado con éxito.')
                Swal.fire('Registro', 'creado exitosamente.', 'success')
            @endif
            @if (session('editar-registro') == 'Registro actualizado con éxito.')
                Swal.fire('Registro', 'actualizado exitosamente.', 'success')
            @endif

            $('.eliminar-registro').submit(function(e) {
                e.preventDefault();
                Swal.fire({
                    title: '¿Eliminar registro?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: '¡Sí, continuar!',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        this.submit();
                    }
                })
            });
        </script>
    @endpush
    @push('js')
<script>
(function () {
  // SweetAlert2 requerido
  if (typeof Swal === 'undefined') return;

  const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

  document.addEventListener('click', async function (e) {
    const btn = e.target.closest('.js-del-recepcion');
    if (!btn) return;

    const url = btn.dataset.url;
    const nro = btn.dataset.nro;

    const result = await Swal.fire({
      title: '¿Estás seguro?',
      text: '¡Esta acción no se puede deshacer!',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: 'Sí, eliminar',
      cancelButtonText: 'Cancelar',
      confirmButtonColor: '#dc3545',
      cancelButtonColor: '#0d6efd',
      reverseButtons: true
    });

    if (!result.isConfirmed) return;

    try {
      const res = await fetch(url, {
        method: 'DELETE',
        headers: {
          'X-CSRF-TOKEN': csrf,
          'Accept': 'application/json',
          'X-Requested-With': 'XMLHttpRequest'
        }
      });

      const data = await res.json().catch(() => ({}));

      if (!res.ok || !data.ok) {
        throw new Error(data.message || 'No se pudo eliminar.');
      }

      // Mensaje éxito
      await Swal.fire({
        title: 'Eliminado',
        text: 'La recepción fue eliminada correctamente.',
        icon: 'success',
        timer: 1200,
        showConfirmButton: false
      });



    } catch (err) {
      Swal.fire({
        title: 'Error',
        text: err.message || 'Ocurrió un error eliminando.',
        icon: 'error'
      });
    }
  });
})();
</script>
@endpush
@endsection
