<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;


class OtrosPesosExport implements FromCollection, WithHeadings, WithEvents
{
    protected $registros;

    public function __construct($registros)
    {
        $this->registros = collect($registros);
    }


    public function collection()
    {
        if ($this->registros->isEmpty()) {
            return collect([$this->filaVacia('No hay registros para exportar')]);
        }

        return $this->registros->map(function ($r) {
            return [
                'id'               => $r->id,
                'fechai'           => $r->fechai,
                'fechas'           => $r->fechas,
                'bruto'            => $r->bruto,
                'tara'             => $r->tara,
                'neto'             => $r->neto,
                'placa'            => $r->placa,
                'observacion'      => $r->observacion,
                'producto'         => $r->producto,
                'conductor'        => $r->conductor,
                'razon_social_id'  => $r->cliente->nombre,
                'programacion_id'  => $r->programacion_id,
                'guia'             => $r->guia,
                'guiat'            => $r->guiat,
                'origen'           => $r->origen,
                'destino'          => $r->destino,
                'balanza'          => $r->balanza,
                'proceso_id'       => $r->proceso_id,
                'estado_id'        => $r->estado->nombre_estado,
                'created_at'       => $r->created_at,
                'updated_at'       => $r->updated_at,
                'usuario_id'       => $r->usuario->name,
            ];
        });
    }


    private function filaTotal($totalNeto)
    {
        return [
            'id'               => 'TOTAL',
            'fechai'           => '',
            'fechas'           => '',
            'bruto'            => '',
            'tara'             => '',
            'neto'             => $totalNeto,
            'placa'            => '',
            'observacion'      => '',
            'producto'         => '',
            'conductor'        => '',
            'razon_social_id'  => '',
            'programacion_id'  => '',
            'guia'             => '',
            'guiat'            => '',
            'origen'           => '',
            'destino'          => '',
            'balanza'          => '',
            'proceso_id'       => '',
            'estado_id'        => '',
            'created_at'       => '',
            'updated_at'       => '',
            'usuario_id'       => '',
        ];
    }

    private function filaVacia($mensaje)
    {
        return array_merge($this->filaTotal(0), [
            'observacion' => $mensaje,
        ]);
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {

                $sheet = $event->sheet->getDelegate();

                $sheet->insertNewRowBefore(1, 1);

                $totalNeto = $this->registros->sum('neto');

                $sheet->setCellValue('A1', 'NETO TOTAL');
                $sheet->setCellValue('F1', $totalNeto); // Neto = columna F

                $sheet->getStyle('A1:W1')->applyFromArray([
                    'font' => ['bold' => true],
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'FFC000'],
                    ],
                ]);

                $sheet->getStyle('A2:W2')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'color' => ['rgb' => 'FFFFFF'],
                    ],
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => ['rgb' => '4472C4'],
                    ],
                ]);

                $sheet->freezePane('A3');
            },
        ];
    }

    public function headings(): array
    {
        return [
            'ID',
            'Fecha Ingreso',
            'Fecha Salida',
            'Bruto',
            'Tara',
            'Neto',
            'Placa',
            'Observación',
            'Producto',
            'Conductor',
            'Razón Social ID',
            'Programación ID',
            'Guía',
            'Guía T',
            'Origen',
            'Destino',
            'Balanza',
            'Proceso ID',
            'Estado ID',
            'Creado',
            'Actualizado',
            'Usuario ID',
        ];
    }
}
