@push('css')
    <style>
        #ModalEtiqueta {
        }
    </style>
@endpush

<div class="modal fade text-left" id="ModalEtiqueta" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">

            <div class="card-header">
                <div class="row justify-content-between">
                    <div class="col-md-6">
                        <h6 class="mt-2">
                            {{ __('CREAR ETIQUETA') }}
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
                <form id="form-etiqueta" action="{{ route('controlgarita.etiqueta.store') }}"
                    method="POST">

                    @csrf
                    <div class="row">
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Nombre</label>
                            <input name="nombre" id="nombre_etiqueta" class="form-control" type="text" placeholder="Ej: Liquidación" required>
                        </div>
                        <div class="col-md-8">
                            <label class="form-label fw-semibold">Color</label>
                            <input name="color" id="color_etiqueta" class="form-control" type="color" value="#000000" required>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-md-12">
                            <label class="form-label fw-semibold">Descripción</label>
                            <textarea name="descripcion" id="descripcion_etiqueta" class="form-control" rows="2"></textarea>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-md-12 text-right mt-1">
                            {{-- No olvidar quitar el onclick="reproducirAudio()" --}}
                            <button type="submit" class="btn btn-secondary btn-sm">
                                {{ __('GUARDAR') }}
                            </button>
                        </div>
                    </div>
                    <br>
                    <div class="table-responsive">
                        <table id="table-labels" class="table table-striped">
                            <thead>
                                <tr>
                                    <th scope="col"> {{ __('ID') }} </th>
                                    <th scope="col"> {{ __('NOMBRE') }} </th>
                                    <th scope="col"> {{ __('COLOR') }} </th>
                                    <th scope="col"> {{ __('DESCRIPCIÓN') }} </th>
                                </tr>
                            </thead>

                            <tbody style="font-size: 13px">
                                @if (count($etiquetas) > 0)
                                    @foreach ($etiquetas as $etiqueta)
                                        <tr>
                                            <td scope="row">
                                                {{ $etiqueta->id }}
                                            </td>
                                            <td scope="row">
                                                {{ $etiqueta->nombre }}
                                            </td>
                                            <td scope="row">
                                                <span style="background-color: {{ $etiqueta->color }}; width: 20px; height: 20px; display: inline-block; border-radius: 4px; border-shadow:"></span>
                                            </td>
                                            <td scope="row">
                                                {{ Str::limit(strtoupper($etiqueta->descripcion), 50) }}
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="9" id="no-data-row" class="text-center text-muted">
                                            {{ __('NO HAY DATOS DISPONIBLES') }}
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>
@push('js')
    <script>
        $(() => {
            const $form = $('#form-etiqueta');
            const $tbody = $('#table-labels tbody');

            $('#color').on('change', function() {
                const colorValue = $(this).val();
                console.log(colorValue);
                // $('#nombre_etiqueta').css('color', colorValue);
            });

            $form.on('submit', function (e) {
                e.preventDefault();

                const url = $form.attr('action');
                const data = $form.serialize();
                
                $.ajax({
                    url: url,
                    method: 'POST',
                    data: data,
                    success: function (response) {
                        if (response.success) {
                            const etiqueta = response.etiqueta;
                            
                            $tbody.find('#no-data-row').remove();

                            const fila = `
                                <tr>
                                    <td>${etiqueta.id}</td>
                                    <td>${etiqueta.nombre}</td>
                                    <td>
                                        <span style='
                                            background-color: ${etiqueta.color};
                                            width: 20px;
                                            height: 20px;
                                            display: inline-block;
                                            border-radius: 4px;
                                        '></span>
                                    </td>
                                    <td>${etiqueta.descripcion}</td>
                                </tr>
                            `;

                            $tbody.prepend(fila);

                            $form[0].reset();

                            Swal.fire({
                                type: 'success',
                                title: 'Etiqueta creada',
                                timer: 1200,
                                showConfirmButton: false
                            });

                            // $.ajax({
                            //     url: "{{ route('controlgarita.etiqueta.show') }}",
                            //     method: 'GET',
                            //     success: function (data) {
                            //         $tbody.empty();

                            //         if( data.length === 0) {
                            //             $tbody.prepend(`
                            //                 <tr>
                            //                     <td colspan="9" class="text-center text-muted">
                            //                         {{ __('NO HAY DATOS DISPONIBLES') }}
                            //                     </td>
                            //                 </tr>
                            //             `);
                            //         } else {
                            //             data.forEach(etiqueta => {
                            //                 $tbody.prepend(`
                            //                     <tr>
                            //                         <td scope="row">
                            //                             ${etiqueta.id}
                            //                         </td>
                            //                         <td scope="row">
                            //                             ${etiqueta.nombre}
                            //                         </td>
                            //                         <td scope="row">
                            //                             <span style="background-color: ${etiqueta.color}; width: 20px; height: 20px; display: inline-block; border-radius:4px;"></span>
                            //                         </td>
                            //                     </tr>
                            //                 `);
                            //             });
                            //         }

                            //         $form[0].reset();
                            //         Swal.fire({
                            //             type: 'success',
                            //             title: 'Etiqueta creada',
                            //             timer: 1200,
                            //             showConfirmButton: false
                            //         });
                            //     }
                            // });
                        }
                    },
                    error: function (xhr) {
                        Swal.fire({
                            type: 'error',
                            title: 'Error',
                            text: 'No se pudo guardar la etiqueta'
                        });
                    }
                });
            });

            $('#ModalEtiqueta').on('hidden.bs.modal', function () {
                $form[0].reset();
            });
        });
    </script>
@endpush
