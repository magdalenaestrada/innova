@push('css')
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.css" />
    <style>
        .btn {
            border-radius: 6px
        }
    </style>
@endpush

<div class="modal fade text-left" id="ModalLogin" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">

            <div class="card-header">
                <div class="row justify-content-between">
                    <div class="col-md-6">
                        <h6 class="mt-2">
                            {{ __('INICIAR TURNO') }}
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
                <form class="crear-detalles" action="{{ route('controlgarita.guardar') }}" method="POST">

                    @csrf
                    <div class="row">
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">Turno</label>
                            <select name="turno" id="turno"
                                class="form-control form-select-sm estado-select w-150" required>
                                <option value="0">Día 7:00 - 19:00</option>
                                <option value="1">Noche 19:00 - 7:00</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Unidad</label>
                            <input name="unidad" id="unidad" class="form-control" type="text" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">Fecha</label>
                            <input name="fecha" id="fecha" class="form-control" type="date" required>
                        </div>
                    </div>
                    <br>
                    <div id="trabajadores-container">
                        <div class="row trabajador-item">
                            <div class="form mb-1 col-md-3">
                                <label class="form-label fw-semibold">Documento</label>
                                <input name="documento" id="documento" class="form-control" type="text"
                                    inputmode="numeric" pattern="[0-9]*" maxlength="11"
                                    oninput="this.value = this.value.replace(/[^0-9]/g, '')" required>
                            </div>
                            <div class="form col-md-7">
                                <label class="form-label fw-semibold">Nombre</label>
                                <input name="nombre" id="nombre" class="form-control" type="text" required>
                            </div>
                            <div class="form col-md-1"></div>
                            <div class="form col-md-1">
                                <label class="form-label fw-semibold invisible">Acción</label>
                                <button type="button" class="btn btn-success btn-add-trabajador">
                                    +
                                </button>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="form mb-1 col-md-3">
                                <label class="form-label fw-semibold">Cantidad</label>
                                <input name="documento" id="documento" class="form-control" type="text"
                                    inputmode="numeric" pattern="[0-9]*" required>
                            </div>
                            <div class="form col-md-7">
                                <label class="form-label fw-semibold">Elemento</label>
                                {{-- <input name="nombre" id="nombre" class="form-control" type="text" required> --}}
                                <select class="form-control selectpicker" multiple data-live-search="true">
                                    <option>Mustard</option>
                                    <option>Ketchup</option>
                                    <option>Relish</option>
                                </select>
                            </div>
                            <div class="form col-md-1"></div>
                            <div class="form col-md-1">
                                <label class="form-label fw-semibold invisible">Acción</label>
                                <button type="button" class="btn btn-success btn-add-trabajador">
                                    +
                                </button>
                            </div>
                        </div>
                    </div>
                    <br>
                    <div style="display: none;">
                        <div id="reproductor-youtube"></div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 text-right mt-1">
                            {{-- No olvidar quitar el onclick="reproducirAudio()" --}}
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
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/js/bootstrap-select.min.js"></script>

    <script>
        $(document).ready(() => {
            let index = 1;
            $('.btn-add-trabajador').on('click', function() {
                let nuevo = `
                    <div class="row trabajador-item">
                        <div class="form mb-1 col-md-3">
                            <label class="form-label fw-semibold">Documento</label>
                            <input name="documento" id="documento" class="form-control" type="text"
                                inputmode="numeric" pattern="[0-9]*" maxlength="11"
                                oninput="this.value = this.value.replace(/[^0-9]/g, '')" required>
                        </div>
                        <div class="form col-md-8">
                            <label class="form-label fw-semibold">Nombre</label>
                            <input name="nombre" id="nombre" class="form-control" type="text" required>
                        </div>
                        <div class="form col-md-1">
                            <label class="form-label fw-semibold invisible">Nombre</label>
                            <button type="button" class="btn btn-success btn-remove-trabajador">
                                -
                            </button>
                        </div>
                        <div><br></div>
                    </div>
                `;

                $('#trabajadores-container').append(nuevo);
                index++;
            });

            $(document).on('click', '.btn-remove-trabajador', function() {
                $(this).closest('.trabajador-item').remove();
            });
        });

        //Hora actual en input type="time"
        const inputFecha = document.getElementById("fecha");
        const now = new Date();

        const year = now.getFullYear;
        const month = String(now.getMonth() + 1).padStart(2, '0');
        const day = String(now.getDate()).padStart(2, '0');

        inputFecha.value = `${year}-${month}-${day}`;

        // Función para reproducir el archivo de audio
        // function reproducirAudio() {
        //     const audio = document.getElementById('miAudio');

        //     audio.play().catch(error => {
        //         console.log("La reproducción fue bloqueada por el navegador:", error);
        //     });
        // }
    </script>



    {{-- <script>
        $(document).ready(function() {
            $('.buscador').select2({
                theme: "classic"
            });
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
