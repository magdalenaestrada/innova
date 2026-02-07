@if (count($clientes) > 0)
    @foreach ($clientes as $cliente)
        <tr>
            <td scope="row">
                {{ strtoupper($cliente->documento) }}
            </td>
            <td scope="row">
                {{ strtoupper($cliente->nombre) }}
            </td>
            <td>
                {{ $cliente->r_info_prestado == 1 ? 'NO' : 'SI' }}
            </td>

            <td>
                {{ $cliente->r_info_prestado == 1 ? strtoupper($cliente->r_info) : '-' }}
            </td>

            <td>
                {{ $cliente->r_info_prestado == 1 ? strtoupper($cliente->nombre_r_info) : '-' }}
            </td>

            @php
                $fechaFin = optional($cliente->contrato)->fecha_fin_contrato;
                $hoy = \Carbon\Carbon::today();
            @endphp

            @php
                $fechaFin = optional($cliente->contrato)->fecha_fin_contrato;
                $hoy = \Carbon\Carbon::today();
                $porVencer = $fechaFin ? \Carbon\Carbon::parse($fechaFin)->diffInDays($hoy, false) : null;
            @endphp

            <td>
                @if (!$fechaFin)
                    <span class="badge badge-secondary"">SIN FECHA</span>
                @elseif (\Carbon\Carbon::parse($fechaFin)->lt($hoy))
                    <span class="badge badge-danger" style="font-size: 0.8rem;"> {{ \Carbon\Carbon::parse($fechaFin)->format('d/m/Y') }}
                    </span>
                @elseif (\Carbon\Carbon::parse($fechaFin)->lte($hoy->copy()->addDays(30)))
                    <span class="badge badge-warning" style="font-size: 0.8rem;"> {{ \Carbon\Carbon::parse($fechaFin)->format('d/m/Y') }}
                    </span>
                @else
                    <span class="badge badge-success" style="font-size: 0.8rem;"> {{ \Carbon\Carbon::parse($fechaFin)->format('d/m/Y') }}
                    </span>
                @endif
            </td>


            <td scope="row">
                {{ strtoupper($cliente->observacion) }}
            </td>
            @can('gestionar clientes')
                <td>
                    @if ($cliente->estado == 'A')
                        <a href="{{ route('contratos.index', $cliente->id) }}" class="btn btn-sm btn-info">
                            <i class="fa fa-file-text"></i>
                        </a>
                        <button class="btn btn-sm btn-warning btn-edit" data-id="{{ $cliente->id }}">
                            <i class="fa fa-edit"></i>
                        </button>
                        <button class="btn btn-sm btn-danger btn-desactivar" data-id="{{ $cliente->id }}">
                            <i class="fa fa-eye-slash"></i>
                        </button>
                    @else
                        <button class="btn btn-sm btn-success btn-activar" data-id="{{ $cliente->id }}">
                            <i class="fa fa-eye"></i>
                    @endif
                @endcan

            </td>
        </tr>
    @endforeach
@else
    <tr>
        <td colspan="9" class="text-center text-muted">
            {{ __('NO HAY DATOS DISPONIBLES') }}
        </td>
    </tr>
@endif
