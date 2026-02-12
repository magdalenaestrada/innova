@extends('admin.layout')

@push('css')
    <link rel="stylesheet" href="{{ asset('css/areas.css') }}">
@endpush

@section('content')
    <div class="container">

        <br>
        <div class="row d-flex justify-content-between align-items-center">
            <div class="loader">
                {{ __('CAJAS REGISTRADAS') }}
            </div>

            @can('edit cuenta')
                <div class="text-right col-md-6">
                    <a class="" href="#" data-toggle="modal" data-target="#ModalCreate">
                        <button class="button-create">
                            {{ __('CREAR CAJA') }}
                        </button>
                    </a>
                </div>
            @endcan
        </div>

        <br>
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">

                    <div class="card-body ">

                        <table id="cajas-table" class="table table-striped">
                            <thead>
                                <tr class="text-center">
                                    <th scope="col">{{ __('ID') }}</th>
                                    <th scope="col">{{ __('NOMBRE') }}</th>
                                    <th scope="col">{{ __('ENCARGADO') }}</th>
                                    <th scope="col">{{ __('BALANCE') }}</th>
                                </tr>
                            </thead>

                            <tbody style="font-size: 13px">
                                @if (count($cajas) > 0)
                                    @foreach ($cajas as $caja)
                                        <tr class="text-center">
                                            <td scope="row">{{ $caja->id }}</td>

                                            <td scope="row">{{ strtoupper($caja->nombre) }}</td>

                                            <td scope="row">
                                                @if ($caja->encargados)
                                                    @foreach ($caja->encargados as $encargado)
                                                        <span class="badge bg-info">{{ strtoupper($encargado->nombre) }}</span>
                                                    @endforeach
                                                @endif
                                            </td>

                                            <td scope="row">
                                                @php
                                                    // ✅ SÍMBOLO SEGÚN MONEDA (1 = DÓLARES, 2 = SOLES)
                                                    $simbolo = ($caja->tipo_moneda_id == 1) ? '$' : 'S/.';

                                                    $ingresosTotal = $caja->reposiciones->sum('salidacuenta.monto');
                                                    $salidasTotal = $caja->salidascajas->sum('monto');
                                                    $ingresoscajaTotal = $caja->ingresoscaja->sum('monto');

                                                    $new_bal = $ingresosTotal + $ingresoscajaTotal - $salidasTotal;
                                                @endphp

                                                <div class="d-flex justify-content-between">
                                                    <p class="">{{ $simbolo }}</p>
                                                    <p class="mr-3">{{ number_format($new_bal, 2) }}</p>
                                                </div>
                                            </td>

                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="9" class="text-center text-muted">
                                            {{ __('No hay datos disponibles') }}
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>

                        <!-- Pagination Links -->
                        <div class="d-flex justify-content-between">
                            <div>
                                {{ $cajas->links('pagination::bootstrap-4') }}
                            </div>
                            <div>
                                Mostrando del {{ $cajas->firstItem() }} al {{ $cajas->lastItem() }} de
                                {{ $cajas->total() }} registros
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('tesoreria.cajas.modal.create')

@stop

@section('js')
    <script src="https://cdn.jsdelivr.net/gh/habibmhamadi/multi-select-tag@2.0.1/dist/js/multi-select-tag.js"></script>

    <script>
        new MultiSelectTag('encargados', {
            rounded: true,
            shadow: true,
            placeholder: 'Search',
            tagColor: {
                textColor: '#fff',
                borderColor: '#000',
                bgColor: '#000',
            },
            onChange: function(values) {
                console.log(values)
            }
        })

        @if (session('status'))
            Swal.fire('Éxito', '{{ session('status') }}', 'success');
        @elseif (session('error'))
            Swal.fire('Error', '{{ session('error') }}', 'error');
        @endif
    </script>
@stop
