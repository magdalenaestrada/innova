@extends('admin.layout')
@push('css')
    <link rel="stylesheet" href="{{ asset('css/areas.css') }}">
    <link rel="stylesheet" href="{{ asset('css/productos.css') }}">
@endpush

@section('content')
    <div class="container">

        <br>
        <div class="row d-flex justify-content-between align-items-center">
            <div class="loader">
                {{ __('GARITA DE CONTROL') }}
            </div>
            <div class="text-right col-md-6">
                <a class="" href="#" data-toggle="modal" data-target="#ModalCreate">
                    <button class="button-create">
                        {{ __('REGISTRAR DÍA LIBRE') }}
                    </button>
                </a>
            </div>
        </div>
        <br>
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="row">
                        <div class="col-md-6">
                            <input type="text" name="searcht" id="searcht" class="input-search form-control"
                                placeholder="BUSCAR AQUÍ...">
                        </div>
                    </div>
                    <div class="card-body">
                        <table id="docs-code-table" class="table table-striped">
                            <thead>
                                <tr>
                                    <th scope="col">
                                        {{ __('ID') }}
                                    </th>
                                    <th scope="col">
                                        {{ __('TRABAJADOR') }}
                                    </th>
                                    <th scope="col">
                                        {{ __('ÁREA') }}
                                    </th>
                                    <th scope="col">
                                        {{ __('DÍA SALIDA') }}
                                    </th>
                                    <th scope="col">
                                        {{ __('DÍA REGRESO') }}
                                    </th>
                                    <th scope="col">
                                        {{ __('ACCION') }}
                                    </th>
                                </tr>
                            </thead>

                            <tbody style="font-size: 13px">
                                @if (count($diaLibre) > 0)
                                    @foreach ($diaLibre as $dlibre)
                                        <tr>
                                            <td scope="row">
                                                {{ $dlibre->id }}
                                            </td>
                                            <td scope="row">
                                                {{ $dlibre->empleado->nombre }}
                                            </td>
                                            <td scope="row">
                                                {{ $dlibre->empleado->area->nombre }}
                                            </td>
                                            <td scope="row">
                                                {{ $dlibre->dia_inicio }}
                                            </td>
                                            <td scope="row">
                                                {{ $dlibre->dia_fin }}
                                            </td>
                                            {{-- <td class="btn-group align-items-center">
                                               <button class="btn btn-sm btn-warning btn-editar-cliente"
                                                    data-toggle="modal" data-target="#ModalEdit{{ $dlibre->id }}">
                                                    <i class="fas fa-edit"></i>
                                                </button>

                                                @include('clientescodigo.modal.edit', [
                                                    'id' => $dlibre->id,
                                                ])

                                                <button class="btn btn-sm btn-danger btn-eliminar-cliente"
                                                    data-url="{{ route('clientescodigo.destroy', $dlibre->id) }}">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </td> --}}
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="9" class="text-center text-muted">
                                            {{ __('NO HAY DATOS DISPONIBLES') }}
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

@section('js')
    <script type="text/javascript">
    </script>
@endsection
@endsection