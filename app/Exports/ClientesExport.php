<?php

namespace App\Exports;

use App\Models\LqCliente;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ClientesExport implements FromCollection, WithHeadings
{
    protected $search;
    protected $estado;
    protected $user;

    public function __construct($search, $estado, $user)
    {
        $this->search = $search;
        $this->estado = $estado;
        $this->user   = $user;
    }

    public function collection()
    {
        return LqCliente::query()

            ->when(!$this->user->can('use cuenta'), function ($q) {
                $q->where('estado', 'A');
            })

            ->when($this->search, function ($q) {
                $q->where(function ($qq) {
                    $qq->where('documento', 'like', "%{$this->search}%")
                        ->orWhere('nombre', 'like', "%{$this->search}%")
                        ->orWhere('r_info', 'like', "%{$this->search}%")
                        ->orWhere('nombre_r_info', 'like', "%{$this->search}%");
                });
            })


            ->when($this->estado, function ($q) {

                $hoy = Carbon::now();
                $unMes = Carbon::now()->addMonth();

                if ($this->estado === 'vigente') {
                    $q->whereHas('contrato', function ($c) use ($unMes) {
                        $c->whereNotNull('fecha_fin_contrato')
                            ->where('fecha_fin_contrato', '>', $unMes);
                    });
                }

                if ($this->estado === 'por_vencer') {
                    $q->whereHas('contrato', function ($c) use ($hoy, $unMes) {
                        $c->whereNotNull('fecha_fin_contrato')
                            ->whereBetween('fecha_fin_contrato', [$hoy, $unMes]);
                    });
                }

                if ($this->estado === 'vencido') {
                    $q->whereHas('contrato', function ($c) use ($hoy) {
                        $c->whereNotNull('fecha_fin_contrato')
                            ->where('fecha_fin_contrato', '<', $hoy);
                    });
                }

                if ($this->estado === 'sin_fecha') {
                    $q->where(function ($x) {
                        $x->whereDoesntHave('contrato')
                            ->orWhereHas('contrato', fn($c) => $c->whereNull('fecha_fin_contrato'));
                    });
                }
            })
            ->with([
                'contrato.empresas',
                'representantes.persona',
                'contactos'
            ])

            ->get()

            ->map(function ($c) {

                $contrato = $c->contrato;

                return [
                    $c->id,
                    $c->documento,
                    $c->nombre,
                    $c->codigo,
                    $c->estado,
                    $c->r_info,
                    $c->nombre_r_info,
                    $c->r_info_prestado ? 'SI' : 'NO',
                    $c->observacion,
                    $c->created_at,

                    optional($contrato)->numero_contrato,
                    optional($contrato)->fecha_inicio_contrato,
                    optional($contrato)->fecha_fin_contrato,
                    optional($contrato)->recepcionado_cliente,
                    optional($contrato)->legalizado_jurgen,
                    optional($contrato)->cercano_vencer,
                    optional($contrato)->permitir_acceso,
                    optional($contrato)->numero_juegos,
                    optional($contrato)->observaciones,

                    collect($contrato?->empresas)
                        ->pluck('nombre')
                        ->implode(' | '),


                    $c->representantes
                        ->map(fn($r) => $r->persona->nombre ?? '')
                        ->filter()
                        ->implode(' | '),

                    $c->contactos
                        ->pluck('celular')
                        ->implode(' | '),

                ];
            });
    }

    public function headings(): array
    {
        return [
            'ID Cliente',
            'Documento',
            'Nombre',
            'Código',
            'Estado',
            'R Info',
            'Nombre R Info',
            'R Info Prestado',
            'Observación',
            'Cliente Creado',

            'N° Contrato',
            'Inicio Contrato',
            'Fin Contrato',
            'Recepcionado Cliente',
            'Legalizado Jurgen',
            'Cercano a Vencer',
            'Permitir Acceso',
            'Número Juegos',
            'Observaciones Contrato',

            'Empresas',
            'Representantes',
            'Contactos',
        ];
    }
}
