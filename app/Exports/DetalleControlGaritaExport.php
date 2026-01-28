<?php

namespace App\Exports;

use App\Models\ReporteExcelGarita;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithMapping;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class DetalleControlGaritaExport implements FromQuery, WithHeadings, ShouldAutoSize, WithStyles, WithColumnWidths, WithMapping
{
    use Exportable;

    protected $filtros;

    public function __construct(array $filtros)
    {
        $this->filtros = $filtros;
    }

    public function query()
    {
        return ReporteExcelGarita::query()
            ->tipoMovimiento($this->filtros['tipo_movimiento'] ?? null)
            ->tipoEntidad($this->filtros['tipo_entidad'] ?? null)
            ->fechaBetween($this->filtros['fecha_inicio'] ?? null, $this->filtros['fecha_fin'] ?? null)
            ->horaBetween($this->filtros['hora_inicio'] ?? null, $this->filtros['hora_fin'] ?? null)
            ->usuario($this->filtros['usuario_id'] ?? null)
            ->orderBy('FECHA DE REGISTRO', 'DESC');
    }

    public function map($registro): array
    {
        return [
            $registro->{'TIPO MOVIMIENTO'},
            $registro->FECHA,
            $registro->HORA,
            $registro->{'TIPO ENTIDAD'},
            $registro->{'TIPO DOCUMENTO'},
            $registro->{'N° DOCUMENTO'},
            $registro->NOMBRE,
            $registro->PLACA,
            $registro->{'TIPO VEHÍCULO'},
            $registro->{'TIPO CARGA'},
            $registro->DESTINO,
            $registro->TURNO,
            $registro->UNIDAD,
            $registro->OCURRENCIAS,
            $registro->{'REGISTRADO POR'},
            $registro->{'FECHA DE REGISTRO'},
        ];
    }

    public function headings(): array
    {
        return [
            'TIPO MOVIMIENTO', 
            'FECHA', 
            'HORA', 
            'TIPO ENTIDAD', 
            'TIPO DOCUMENTO', 
            'N° DOCUMENTO', 
            'NOMBRE', 
            'PLACA', 
            'TIPO VEHÍCULO', 
            'TIPO CARGA', 
            'DESTINO', 
            'TURNO', 
            'UNIDAD', 
            'OCURRENCIAS', 
            'REGISTRADO POR', 
            'FECHA DE REGISTRO', 
        ];
    }

    public function columnWidths(): array
    {
        return [
            'N' => 50,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $ultimaFila = $sheet->getHighestRow();
        $rangoTotal = 'A1:P' . $ultimaFila;
        return [
            1 => [
                'font' => [
                    'bold' => true, 
                    'color' => ['rgb' => 'FFFFFF'],
                    'size' => 12,
                ], 
                'fill' => [
                    'fillType' => FILL::FILL_SOLID, 
                    'startColor' => ['rgb' => '2C3E50']
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                ],
                'row_height' => 30,
                // 'borders' => [
                //     'allBorders' => [
                //         'borderStyle' => Border::BORDER_THIN,
                //     ],
                // ],
            ],
            $rangoTotal => [
                // 'borders' => [
                //     'allBorders' => [
                //         'borderStyle' => Border::BORDER_THIN,
                //         'color' => ['rgb' => '000000'],
                //     ],
                // ],
                'alignment' => [
                    'vertical' => Alignment::VERTICAL_CENTER,
                ],
            ],
            'N' => [
                'alignment' => [
                    'wrapText' => false,
                    'vertical' => Alignment::VERTICAL_TOP,
                ],
            ],
        ];
    }

    // public function title(): string
    // {
    //     switch ($this->tipo) {
    //         case 'E':
    //             return 'Entradas';
    //         case 'S':
    //             return 'Salidas';
    //         default:
    //             return 'General';
    //     }
    // }
}