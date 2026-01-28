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
{{-- @push('css')
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.4.3/dist/css/tom-select.css" rel="stylesheet">
    <style>
        /* =========================================
           ESTILOS SCOPED PARA #ModalLogin
           ========================================= */

        /* Contenedor Principal */
        #ModalLogin .modal-content {
            border: none;
            border-radius: 16px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.2);
            overflow: visible; /* Necesario para que TomSelect no se corte */
            font-family: 'Nunito', sans-serif;
        }

        /* Cabecera */
        #ModalLogin .modal-header {
            background: #f8f9fa;
            border-bottom: 1px solid #edf2f7;
            padding: 1.5rem 1.5rem 1rem;
        }

        #ModalLogin .modal-title {
            font-weight: 700;
            color: #2d3748;
            font-size: 1.15rem;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        #ModalLogin .title-icon-box {
            background: #ebf8ff; /* Azul muy suave */
            padding: 8px 10px; 
            border-radius: 8px; 
            color: #3182ce; /* Azul corporativo */
            font-size: 1.1rem;
        }

        /* Botón Cerrar */
        #ModalLogin .close {
            background: #e2e8f0;
            border-radius: 50%;
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 1;
            border: none;
            transition: all 0.2s ease;
            margin: -5px -5px 0 0;
            padding: 0;
        }

        #ModalLogin .close:hover {
            background: #cbd5e0;
            transform: rotate(90deg);
            color: #c53030;
        }

        /* Separadores de Sección */
        #ModalLogin .section-label {
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 1.2px;
            color: #718096;
            font-weight: 700;
            margin: 1.5rem 0 1rem 0;
            padding-bottom: 0.25rem;
            border-bottom: 1px dashed #e2e8f0;
            display: block;
        }
        
        #ModalLogin .section-label:first-child {
            margin-top: 0;
        }

        /* Inputs Generales */
        #ModalLogin .form-control {
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 0.6rem 1rem;
            color: #4a5568;
            font-weight: 500;
            height: calc(1.5em + 1rem + 2px);
            box-shadow: none;
            transition: all 0.2s;
        }

        #ModalLogin .form-control:focus {
            border-color: #3182ce;
            box-shadow: 0 0 0 3px rgba(49, 130, 206, 0.15);
        }

        /* Input Groups */
        #ModalLogin .input-group-text {
            background: #fff;
            border: 1px solid #e2e8f0;
            border-right: none;
            color: #a0aec0;
            border-radius: 8px 0 0 8px;
        }

        #ModalLogin .input-group > .form-control {
            border-left: none;
            border-radius: 0 8px 8px 0;
        }
        
        #ModalLogin .input-group:focus-within .input-group-text {
            border-color: #3182ce;
            color: #3182ce;
        }

        /* === TOM SELECT PERSONALIZADO (Integración UI) === */
        #ModalLogin .ts-control {
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 0.6rem 1rem;
            font-size: 1rem;
            box-shadow: none;
            background-color: #fff;
            min-height: calc(1.5em + 1rem + 2px);
        }

        #ModalLogin .ts-control.focus {
            border-color: #3182ce;
            box-shadow: 0 0 0 3px rgba(49, 130, 206, 0.15);
        }

        #ModalLogin .ts-dropdown {
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            margin-top: 4px;
            z-index: 1060; /* Encima del modal */
        }

        #ModalLogin .ts-dropdown .active {
            background-color: #ebf8ff;
            color: #2b6cb0;
        }

        /* Tarjetas de Elementos (Dinámicas) */
        #ModalLogin .element-item {
            background: #f7fafc;
            border: 1px solid #edf2f7;
            border-radius: 12px;
            padding: 15px 5px;
            margin-bottom: 15px;
            position: relative;
            transition: all 0.2s;
        }
        
        #ModalLogin .element-item:hover {
            border-color: #cbd5e0;
            background: #fff;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        }

        /* Botones */
        #ModalLogin .btn {
            border-radius: 8px;
            font-weight: 600;
            padding: 0.6rem 1.2rem;
        }

        #ModalLogin .btn-add-element {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            border-radius: 8px;
            background: #e6fffa;
            color: #059669;
            border: 1px solid #b2f5ea;
        }
        
        #ModalLogin .btn-add-element:hover {
            background: #38a169;
            color: white;
            border-color: #38a169;
        }

        #ModalLogin .btn-remove-element {
            width: 100%;
            background: #fff5f5;
            color: #e53e3e;
            border: 1px solid #fed7d7;
            height: calc(1.5em + 1rem + 2px); /* Misma altura que el input */
        }

        #ModalLogin .btn-remove-element:hover {
            background: #e53e3e;
            color: white;
            border-color: #e53e3e;
        }

        /* Footer */
        #ModalLogin .modal-footer {
            background: #fff;
            padding: 1.5rem;
            justify-content: flex-end; /* Alineado a la derecha como el original pero mejor espaciado */
            gap: 10px;
        }
        
        #ModalLogin .btn-secondary { /* Botón Guardar en este caso actúa como primario visualmente */
            background: linear-gradient(135deg, #4a5568 0%, #2d3748 100%);
            color: white;
            border: none;
            padding-left: 2rem;
            padding-right: 2rem;
            box-shadow: 0 4px 6px rgba(45, 55, 72, 0.2);
        }
        
        #ModalLogin .btn-secondary:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 10px rgba(45, 55, 72, 0.3);
            background: #1a202c;
        }

        #ModalLogin label {
            font-size: 0.8rem;
            font-weight: 700;
            color: #64748b;
            margin-bottom: 0.4rem;
        }
    </style>
@endpush --}}

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
                        </div>
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
                    <div class="row">
                        <div class="col-md-12 text-right mt-1">
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
{{-- <div class="modal fade text-left" id="ModalLogin" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">
                    <div class="title-icon-box">
                        <i class="fas fa-user-clock"></i>
                    </div>
                    {{ __('INICIAR TURNO') }}
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body p-4">
                <form class="crear-detalles" action="{{ route('controlgarita.turno') }}" method="POST">
                    @csrf
                    
                    <span class="section-label">
                        <i class="fas fa-info-circle mr-1"></i> Detalles del Turno
                    </span>

                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <label>Turno</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-sun"></i></span>
                                </div>
                                <select name="turno" id="turno" class="form-control" required>
                                    <option value="0">Día 7:00 - 19:00</option>
                                    <option value="1">Noche 19:00 - 7:00</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>Unidad / Planta</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-industry"></i></span>
                                </div>
                                <input name="unidad" id="unidad" class="form-control" type="text"
                                    placeholder="Ej: Planta Minera Alfa" required>
                            </div>
                        </div>

                        <div class="col-md-3 mb-3">
                            <label>Fecha</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                                </div>
                                <input name="fecha" id="fecha" class="form-control" type="date" readonly style="background-color: #f7fafc;">
                            </div>
                        </div>
                    </div>

                    <span class="section-label">
                        <i class="fas fa-users mr-1"></i> Asignación de Personal
                    </span>

                    <div class="row">
                        <div class="col-md-12">
                            <label>Seleccionar Agentes</label>
                            <select id="select-usuarios" name="usuarios_id[]" class="tom-select" multiple autocomplete="off" required>
                            </select>
                            <small class="form-text text-muted mt-1">
                                <i class="fas fa-check-circle text-success mr-1"></i> Puede seleccionar múltiples trabajadores para este turno.
                            </small>
                        </div>
                    </div>

                    <span class="section-label">
                        <i class="fas fa-boxes mr-1"></i> Inventario Inicial (Cargos)
                    </span>

                    <div id="element-container">
                        <div class="row element-item align-items-end">
                            <div class="col-md-3 mb-2">
                                <label>Cantidad</label>
                                <input name="productos[0][cantidad]" id="cantidad" class="form-control" type="number"
                                    placeholder="0" inputmode="numeric" pattern="[0-9]*" min="1" step="1" value="1" required>
                            </div>
                            <div class="col-md-8 mb-2">
                                <label>Elemento</label>
                                <select id="select-elemento" name="productos[0][productos_id]" class="tom-select" autocomplete="off">
                                    <option value="">Buscar elemento...</option>
                                    @foreach ($productos as $producto)
                                        <option value="{{ $producto->id }}">{{ $producto->nombre_producto }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-1 mb-2">
                                <button type="button" class="btn btn-add-element" title="Agregar otro ítem">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer mt-3">
                        <button type="button" class="btn btn-light text-muted" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-secondary">
                            <i class="fas fa-save mr-2"></i> {{ __('INICIAR TURNO') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div> --}}
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

                // let newElement = `
                //     <div class="row mt-3 element-item align-items-end">
                //         <div class="col-md-3 mb-2">
                //             <label class="form-label fw-semibold">Cantidad</label>
                //             <input name="productos[${elementIndex}][cantidad]" class="form-control" type="number"
                //                 placeholder="0" inputmode="numeric" pattern="[0-9]*" min="1"
                //                 step="1" value="1" required>
                //         </div>
                //         <div class="col-md-8 mb-2">
                //             <label class="form-label fw-semibold">Elemento</label>
                //             <select id="select-elemento-${elementIndex}" name="productos[${elementIndex}][productos_id]" class="tom-select"
                //                 autocomplete="off">
                //                 ${optionsHTML}
                //             </select>
                //         </div>
                //         <div class="col-md-1 mb-2">
                //             <button type="button" class="btn btn-remove-element">
                //                 <i class="fas fa-trash-alt"></i>
                //             </button>
                //         </div>
                //     </div>
                // `;

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
