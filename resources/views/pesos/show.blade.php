@extends('admin.layout')

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row justify-content-between">
                            <div class="col-md-6">
                                <h6 class="mt-2">
                                    {{ __('VER PESAJE') }}
                                </h6>
                            </div>
                            <div class="col-md-6 text-end">
                                <a class="btn btn-danger btn-sm" href="{{ route('pesos.index') }}">
                                    {{ __('VOLVER') }}
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="form-group">
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    <strong>
                                        {{ __('Estás viendo el Peso NRO') }}
                                    </strong>
                                    <span class="badge text-bg-secondary">{{ $peso->NroSalida }}</span>
                             
                                </div>
                               
                                  
                            </div>

                            



                         



                            <div class="form-group col-md-3 g-3">
                                <label for="Horas">
                                    {{ __('HORAS') }}
                                </label>
                                @if ($peso->Horas)
                                    <input class="form-control" value="{{ $peso->Horas }}" disabled>
                                @else
                                    <input class="form-control" value="{{ __('No hay datos disponibles') }}" disabled>
                                @endif
                            </div>


                            <div class="form-group col-md-3 g-3">
                                <label for="Fechas">
                                    {{ __('FECHAS') }}
                                </label>
                                @if ($peso->Fechas)
                                    <input class="form-control" value="{{ $peso->Fechas }}" disabled>
                                @else
                                    <input class="form-control" value="{{ __('No hay datos disponibles') }}" disabled>
                                @endif
                            </div>

                            <div class="form-group col-md-3 g-3">
                                <label for="Fechai">
                                    {{ __('FECHAI') }}
                                </label>
                                @if ($peso->Fechas)
                                    <input class="form-control" value="{{ $peso->Fechai }}" disabled>
                                @else
                                    <input class="form-control" value="{{ __('No hay datos disponibles') }}" disabled>
                                @endif
                            </div>

                            <div class="form-group col-md-3  g-3">
                                <label for="Horai">
                                    {{ __('HORAI') }}
                                </label>
                                @if ($peso->Horai)
                                    <input class="form-control" value="{{ $peso->Horai }}" disabled>
                                @else
                                    <input class="form-control" value="{{ __('No hay datos disponibles') }}" disabled>
                                @endif
                            </div>



                            <div class="form-group col-md-2 g-4">
                                <label for="Pesoi">
                                    {{ __('PESOI') }}
                                </label>
                                @if ($peso->Pesoi)
                                    <input class="form-control" value="{{ $peso->Pesoi }}" disabled>
                                @else
                                    <input class="form-control" value="{{ __('No hay datos disponibles') }}" disabled>
                                @endif
                            </div>

                            <div class="form-group col-md-2 g-4">
                                <label for="Pesos">
                                    {{ __('PESOS') }}
                                </label>
                                @if ($peso->Pesos)
                                    <input class="form-control" value="{{ $peso->Pesos }}" disabled>
                                @else
                                    <input class="form-control" value="{{ __('No hay datos disponibles') }}" disabled>
                                @endif
                            </div>


                            <div class="form-group col-md-2 g-4">
                                <label for="Bruto">
                                    {{ __('BRUTO') }}
                                </label>
                                @if ($peso->Bruto)
                                    <input class="form-control" value="{{ $peso->Bruto }}" disabled>
                                @else
                                    <input class="form-control" value="{{ __('No hay datos disponibles') }}" disabled>
                                @endif
                            </div>


                            <div class="form-group col-md-2 g-4">
                                <label for="Tara">
                                    {{ __('TARA') }}
                                </label>
                                @if ($peso->Tara)
                                    <input class="form-control" value="{{ $peso->Tara }}" disabled>
                                @else
                                    <input class="form-control" value="{{ __('No hay datos disponibles') }}" disabled>
                                @endif
                            </div>
                           

                            <div class="form-group col-md-2 g-4">
                                <label for="Neto">
                                    {{ __('NETO') }}
                                </label>
                                @if ($peso->Neto)
                                    <input class="form-control" value="{{ $peso->Neto }}" disabled>
                                @else
                                    <input class="form-control" value="{{ __('No hay datos disponibles') }}" disabled>
                                @endif
                            </div>

                            

                            <div class="form-group col-md-6 g-4">
                                <label for="Placa">
                                    {{ __('PLACA') }}
                                </label>
                                @if ($peso->Placa)
                                    <input class="form-control" value="{{ $peso->Placa }}" disabled>
                                @else
                                    <input class="form-control" value="{{ __('No hay datos disponibles') }}" disabled>
                                @endif
                            </div>

                            <div class="form-group col-md-6 g-4">
                                <label for="Observacion">
                                    {{ __('OBSERVACION') }}
                                </label>
                                @if ($peso->Placa)
                                    <input class="form-control" value="{{ $peso->Observacion }}" disabled>
                                @else
                                    <input class="form-control" value="{{ __('No hay datos disponibles') }}" disabled>
                                @endif
                            </div>

                            <div class="form-group col-md-3 g-4">
                                <label for="Producto">
                                    {{ __('PRODUCTO') }}
                                </label>
                                @if ($peso->Producto)
                                    <input class="form-control" value="{{ $peso->Producto }}" disabled>
                                @else
                                    <input class="form-control" value="{{ __('No hay datos disponibles') }}" disabled>
                                @endif
                            </div>

                            <div class="form-group col-md-3 g-4">
                                <label for="Conductor">
                                    {{ __('CONDUCTOR') }}
                                </label>
                                @if ($peso->Conductor)
                                    <input class="form-control" value="{{ $peso->Conductor }}" disabled>
                                @else
                                    <input class="form-control" value="{{ __('No hay datos disponibles') }}" disabled>
                                @endif
                            </div>

                            <div class="form-group col-md-3 g-4">
                                <label for="Transportista">
                                    {{ __('TRANSPORTISTA') }}
                                </label>
                                @if ($peso->Transportista)
                                    <input class="form-control" value="{{ $peso->Transportista }}" disabled>
                                @else
                                    <input class="form-control" value="{{ __('No hay datos disponibles') }}" disabled>
                                @endif
                            </div>


                            <div class="form-group col-md-3 g-4">
                                <label for="RazonSocial">
                                    {{ __('RAZON SOCIAL') }}
                                </label>
                                @if ($peso->RazonSocial)
                                    <input class="form-control" value="{{ $peso->RazonSocial }}" disabled>
                                @else
                                    <input class="form-control" value="{{ __('No hay datos disponibles') }}" disabled>
                                @endif
                            </div>

                            <div class="form-group col-md-6 g-4">
                                <label for="Operadori">
                                    {{ __('OPERADORI') }}
                                </label>
                                @if ($peso->Operadori)
                                    <input class="form-control" value="{{ $peso->Operadori }}" disabled>
                                @else
                                    <input class="form-control" value="{{ __('No hay datos disponibles') }}" disabled>
                                @endif
                            </div>


                            <div class="form-group col-md-6 g-4">
                                <label for="Destarado">
                                    {{ __('DESTARADO') }}
                                </label>
                                @if ($peso->Destarado)
                                    <input class="form-control" value="{{ $peso->Destarado }}" disabled>
                                @else
                                    <input class="form-control" value="{{ __('No hay datos disponibles') }}" disabled>
                                @endif
                            </div>


                            <div class="form-group col-md-6 g-4">
                                <label for="Operadors">
                                    {{ __('OPERADORS') }}
                                </label>
                                @if ($peso->Operadors)
                                    <input class="form-control" value="{{ $peso->Operadors }}" disabled>
                                @else
                                    <input class="form-control" value="{{ __('No hay datos disponibles') }}" disabled>
                                @endif
                            </div>


                            <div class="form-group col-md-6 g-4">
                                <label for="carreta">
                                    {{ __('CARRETA') }}
                                </label>
                                @if ($peso->carreta)
                                    <input class="form-control" value="{{ $peso->carreta }}" disabled>
                                @else
                                    <input class="form-control" value="{{ __('No hay datos disponibles') }}" disabled>
                                @endif
                            </div>


                            <div class="form-group col-md-6 g-4">
                                <label for="guia">
                                    {{ __('GUIA') }}
                                </label>
                                @if ($peso->guia)
                                    <input class="form-control" value="{{ $peso->guia }}" disabled>
                                @else
                                    <input class="form-control" value="{{ __('No hay datos disponibles') }}" disabled>
                                @endif
                            </div>



                            <div class="form-group col-md-6 g-4">
                                <label for="guiat">
                                    {{ __('GUIAT') }}
                                </label>
                                @if ($peso->guiat)
                                    <input class="form-control" value="{{ $peso->guiat }}" disabled>
                                @else
                                    <input class="form-control" value="{{ __('No hay datos disponibles') }}" disabled>
                                @endif
                            </div>


                            <div class="form-group col-md-6 g-4">
                                <label for="pedido">
                                    {{ __('PEDIDO') }}
                                </label>
                                @if ($peso->pedido)
                                    <input class="form-control" value="{{ $peso->pedido }}" disabled>
                                @else
                                    <input class="form-control" value="{{ __('No hay datos disponibles') }}" disabled>
                                @endif
                            </div>



                            <div class="form-group col-md-6 g-4">
                                <label for="entrega">
                                    {{ __('ENTREGA') }}
                                </label>
                                @if ($peso->entrega)
                                    <input class="form-control" value="{{ $peso->entrega }}" disabled>
                                @else
                                    <input class="form-control" value="{{ __('No hay datos disponibles') }}" disabled>
                                @endif
                            </div>


                            <div class="form-group col-md-6 g-4">
                                <label for="um">
                                    {{ __('UM') }}
                                </label>
                                @if ($peso->entrega)
                                    <input class="form-control" value="{{ $peso->um }}" disabled>
                                @else
                                    <input class="form-control" value="{{ __('No hay datos disponibles') }}" disabled>
                                @endif
                            </div>

                            <div class="form-group col-md-6 g-4">
                                <label for="pesoguia">
                                    {{ __('PESOGUIA') }}
                                </label>
                                @if ($peso->pesoguia)
                                    <input class="form-control" value="{{ $peso->pesoguia }}" disabled>
                                @else
                                    <input class="form-control" value="{{ __('No hay datos disponibles') }}" disabled>
                                @endif
                            </div>


                            <div class="form-group col-md-6 g-4">
                                <label for="rucr">
                                    {{ __('RUCR') }}
                                </label>
                                @if ($peso->pesoguia)
                                    <input class="form-control" value="{{ $peso->rucr }}" disabled>
                                @else
                                    <input class="form-control" value="{{ __('No hay datos disponibles') }}" disabled>
                                @endif
                            </div>

                            <div class="form-group col-md-6 g-4">
                                <label for="ruct">
                                    {{ __('RUCT') }}
                                </label>
                                @if ($peso->pesoguia)
                                    <input class="form-control" value="{{ $peso->ruct }}" disabled>
                                @else
                                    <input class="form-control" value="{{ __('No hay datos disponibles') }}" disabled>
                                @endif
                            </div>


                            <div class="form-group col-md-6 g-4">
                                <label for="destino">
                                    {{ __('DESTINO') }}
                                </label>
                                @if ($peso->destino)
                                    <input class="form-control" value="{{ $peso->destino }}" disabled>
                                @else
                                    <input class="form-control" value="{{ __('No hay datos disponibles') }}" disabled>
                                @endif
                            </div>


                            <div class="form-group col-md-6 g-4">
                                <label for="origen">
                                    {{ __('ORIGEN') }}
                                </label>
                                @if ($peso->origen)
                                    <input class="form-control" value="{{ $peso->origen }}" disabled>
                                @else
                                    <input class="form-control" value="{{ __('No hay datos disponibles') }}" disabled>
                                @endif
                            </div>


                            <div class="form-group col-md-6 g-4">
                                <label for="brevete">
                                    {{ __('BREVETE') }}
                                </label>
                                @if ($peso->brevete)
                                    <input class="form-control" value="{{ $peso->brevete }}" disabled>
                                @else
                                    <input class="form-control" value="{{ __('No hay datos disponibles') }}" disabled>
                                @endif
                            </div>


                            <div class="form-group col-md-6 g-4">
                                <label for="pbmax">
                                    {{ __('PBMAX') }}
                                </label>
                                @if ($peso->pbmax)
                                    <input class="form-control" value="{{ $peso->pbmax }}" disabled>
                                @else
                                    <input class="form-control" value="{{ __('No hay datos disponibles') }}" disabled>
                                @endif
                            </div>


                            <div class="form-group col-md-6 g-4">
                                <label for="tipo">
                                    {{ __('TIPO') }}
                                </label>
                                @if ($peso->tipo)
                                    <input class="form-control" value="{{ $peso->tipo }}" disabled>
                                @else
                                    <input class="form-control" value="{{ __('No hay datos disponibles') }}" disabled>
                                @endif
                            </div> 


                            <div class="form-group col-md-6 g-4">
                                <label for="centro">
                                    {{ __('CENTRO') }}
                                </label>
                                @if ($peso->tipo)
                                    <input class="form-control" value="{{ $peso->centro }}" disabled>
                                @else
                                    <input class="form-control" value="{{ __('No hay datos disponibles') }}" disabled>
                                @endif
                            </div> 


                            <div class="form-group col-md-6 g-4">
                                <label for="nia">
                                    {{ __('NIA') }}
                                </label>
                                @if ($peso->nia)
                                    <input class="form-control" value="{{ $peso->nia }}" disabled>
                                @else
                                    <input class="form-control" value="{{ __('No hay datos disponibles') }}" disabled>
                                @endif
                            </div> 


                            <div class="form-group col-md-6 g-4">
                                <label for="bodega">
                                    {{ __('BODEGA') }}
                                </label>
                                @if ($peso->bodega)
                                    <input class="form-control" value="{{ $peso->bodega }}" disabled>
                                @else
                                    <input class="form-control" value="{{ __('No hay datos disponibles') }}" disabled>
                                @endif
                            </div> 


                            
                            <div class="form-group col-md-6 g-4">
                                <label for="ip">
                                    {{ __('IP') }}
                                </label>
                                @if ($peso->ip)
                                    <input class="form-control" value="{{ $peso->ip }}" disabled>
                                @else
                                    <input class="form-control" value="{{ __('No hay datos disponibles') }}" disabled>
                                @endif
                            </div> 



                            <div class="form-group col-md-6 g-4">
                                <label for="anular">
                                    {{ __('ANULAR') }}
                                </label>
                                @if ($peso->anular)
                                    <input class="form-control" value="{{ $peso->anular }}" disabled>
                                @else
                                    <input class="form-control" value="{{ __('No hay datos disponibles') }}" disabled>
                                @endif
                            </div> 



                            <div class="form-group col-md-6 g-4">
                                <label for="eje">
                                    {{ __('EJE') }}
                                </label>
                                @if ($peso->eje)
                                    <input class="form-control" value="{{ $peso->eje }}" disabled>
                                @else
                                    <input class="form-control" value="{{ __('No hay datos disponibles') }}" disabled>
                                @endif
                            </div> 


                            <div class="form-group col-md-6 g-4">
                                <label for="pesaje">
                                    {{ __('PESAJE') }}
                                </label>
                                @if ($peso->pesaje)
                                    <input class="form-control" value="{{ $peso->pesaje }}" disabled>
                                @else
                                    <input class="form-control" value="{{ __('No hay datos disponibles') }}" disabled>
                                @endif
                            </div> 


                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection