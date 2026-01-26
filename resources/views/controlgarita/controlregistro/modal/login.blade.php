@push('css')
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.4.3/dist/css/tom-select.css" rel="stylesheet">
    <style>
        #ModalLogin .btn {
            border-radius: 6px
        }

        #ModalLogin .ts-control {
            height: calc(1.5em + .75rem + 2px);
            padding: .375rem .75rem;
            font-size: 1rem;
            line-height: 1.5;
            color: #495057;
            background-color: #fff;
            border: 1px solid #ced4da;
            border-radius: .25rem;
        }

        /* Input interno */
        #ModalLogin .ts-control input {
            font-size: 1rem;
            color: #495057;
        }

        /* Focus (igual a Bootstrap) */
        #ModalLogin .ts-control.focus {
            border-color: #80bdff;
            box-shadow: 0 0 0 .2rem rgba(0, 123, 255, .25);
        }

        /* Dropdown */
        #ModalLogin .ts-dropdown {
            border: 1px solid #ced4da;
            border-radius: .25rem;
            font-size: 0.9rem;
        }

        /* Opciones hover */
        #ModalLogin .ts-dropdown .active {
            background-color: #007bff;
            color: #fff;
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
                <form class="crear-detalles" action="{{ route('controlgarita.turno') }}"
                    method="POST">

                    @csrf
                    <div class="row">
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">Turno</label>
                            <select name="turno" id="turno"
                                class="form-control form-select-sm estado-select w-150" required>
                                <option value="0">Día 7:00 - 19:00</option>
                                <option value="1">Noche 19:00 - 7:00</option>
                            </select>
                            {{-- <input type="time" name="" id="" value="{{ now()->format('H:i') }}" class="form-control" disabled> --}}
                        </div>
                        {{-- <div class="col-md-2">
                            <label class="form-label fw-semibold invisible">Turno</label>
                            <input type="time" name="" id="" class="form-control">
                        </div> --}}
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Unidad</label>
                            <input name="unidad" id="unidad" class="form-control" type="text"
                                placeholder="Ej: Planta Minera Alfa Golden" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">Fecha</label>
                            <input name="fecha" id="fecha" class="form-control" type="date" readonly>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-md-12">
                            <label class="form-label fw-semibold">Trabajadores</label>
                            <select id="select-usuarios" name="usuarios_id[]" class="tom-select" multiple
                                autocomplete="off" required>
                                <small class="form-text text-muted">
                                    Puede seleccionar múltiples usuarios
                                </small>
                            </select>
                            {{-- <input id="usuario" class="form-control" value="{{ Auth::user()->name }}" type="text"
                                disabled> --}}
                        </div>
                    </div>
                    <br>
                    <div id="element-container">
                        <div class="row element-item">
                            <div class="form mb-1 col-md-3">
                                <label class="form-label fw-semibold">Cantidad</label>
                                <input name="productos[0][cantidad]" id="cantidad" class="form-control" type="number"
                                    placeholder="Cantidad..." inputmode="numeric" pattern="[0-9]*" min="1"
                                    step="1" value="1" required>
                            </div>
                            <div class="form col-md-8">
                                <label class="form-label fw-semibold">Elemento</label>
                                <select id="select-elemento" name="productos[0][productos_id]" class="tom-select"
                                    autocomplete="off">
                                    <option value="">Buscar elemento...</option>
                                    @foreach ($productos as $producto)
                                        <option value="{{ $producto->id }}">{{ $producto->nombre_producto }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form col-md-1">
                                <label class="form-label fw-semibold invisible">Acción</label>
                                <button type="button" class="btn btn-success btn-add-element">
                                    +
                                </button>
                            </div>
                        </div>
                    </div>
                    <br>
                    {{-- <div style="display: none;">
                        <div id="reproductor-youtube"></div>
                    </div> --}}
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
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.4.3/dist/js/tom-select.complete.min.js"></script>

    <script>
        //Hora actual en input type="time"
        const inputFecha = document.getElementById("fecha");
        const now = new Date();

        const year = now.getFullYear();
        const month = String(now.getMonth() + 1).padStart(2, '0');
        const day = String(now.getDate()).padStart(2, '0');

        inputFecha.value = `${year}-${month}-${day}`;

        $(() => {
            const productosData = @json($productos ?? []);
            const usersData = @json($users ?? []);

            let elementIndex = 1;

            let tomSelectInstances = [];

            const selectUsuarios = new TomSelect('#select-usuarios', {
                plugins: ['dropdown_input', 'clear_button'],
                valueField: 'id',
                labelField: 'name',
                searchField: 'name',
                placeholder: 'Buscar trabajadores...',
                maxItems: null,
                create: false,
                options: usersData,
                render: {
                    option: function(data, escape) {
                        return `<div>${escape(data.name)}</div>`
                    },
                    item: function(data, escape) {
                        return `<div>${escape(data.name)}</div>`
                    }
                }
            });

            function initTomSelectElemento(element) {
                const tomSelect = new TomSelect(element, {
                    create: false,
                    sortField: {
                        field: "text",
                        direction: "asc"
                    },
                    render: {
                        option: function(data, escape) {
                            return '<div>' + escape(data.text) + '</div>';
                        }
                    }
                });

                tomSelectInstances.push(tomSelect);
                return tomSelect;
            }

            initTomSelectElemento('#select-elemento');

            $('.btn-add-element').on('click', () => {
                let optionsHTML = '<option value="">Buscar elemento...</option>';
                productosData.forEach(function(productos) {
                    optionsHTML +=
                        `<option value="${productos.id}">${productos.nombre_producto}</option>`;
                })

                let newElement = `
                    <div class="row mt-4 element-item">
                        <div class="form mb-1 col-md-3">
                            <label class="form-label fw-semibold">Cantidad</label>
                            <input name="productos[${elementIndex}][cantidad]" id="cantidad" class="form-control" type="number"
                                placeholder="Cantidad..." inputmode="numeric" pattern="[0-9]*" min="1"
                                step="1" value="1" required>
                        </div>
                        <div class="form col-md-8">
                            <label class="form-label fw-semibold">Elemento</label>
                            <select id="select-elemento-${elementIndex}" name="productos[${elementIndex}][productos_id]" class="tom-select"
                                autocomplete="off">
                                ${optionsHTML}
                            </select>
                        </div>
                        <div class="form col-md-1">
                            <label class="form-label fw-semibold invisible">Acción</label>
                            <button type="button" class="btn btn-success btn-remove-element">
                                -
                            </button>
                        </div>
                    </div>
                `;

                $('#element-container').append(newElement);

                const nuevoSelect = $(`#select-elemento-${elementIndex}`)[0];
                initTomSelectElemento(nuevoSelect);

                elementIndex++;
            });

            $(document).on('click', '.btn-remove-element', function() {
                const element = $(this).closest('.element-item');
                element.find('input, select, textarea').prop('required', false);
                const selectElement = element.find('select')[0];

                if (selectElement && selectElement.tomSelect) {
                    const index = tomSelectInstances.indexOf(selectElement.tomSelect);
                    if (index > -1) {
                        tomSelectInstances.splice(index, 1);
                    }
                    selectElement.tomSelect.destroy();
                }

                element.fadeOut(200, function() {
                    $(this).remove();
                });
            });

            // $('#ModalLogin').on('hidden.bs.modal', function() {
            //     selectUsuarios.clear();
            //     $('.crear-detalles')[0].reset();

            //     // Eliminar elementos adicionales
            //     $('.element-item').not(':first').each(function() {
            //         const select = $(this).find('select')[0];
            //         if (select && select.tomSelect) {
            //             select.tomSelect.destroy();
            //         }
            //         $(this).remove();
            //     });

            //     // Resetear primer elemento
            //     const firstSelect = $('.element-item:first select')[0];
            //     if (firstSelect && firstSelect.tomSelect) {
            //         firstSelect.tomSelect.clear();
            //     }
            //     $('.element-item:first input[type="number"]').val(1);

            //     elementIndex = 1;
            // });
        });

        // Función para reproducir el archivo de audio
        // function reproducirAudio() {
        //     const audio = document.getElementById('miAudio');

        //     audio.play().catch(error => {
        //         console.log("La reproducción fue bloqueada por el navegador:", error);
        //     });
        // }
    </script>
@endpush
