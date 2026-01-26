<?php

namespace App\Exports;

use App\Models\DetalleControlGarita;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class DetalleControlGaritaExport implements 
    FromCollection, 
    WithHeadings, 
    WithMapping, 
    WithStyles, 
    WithTitle,
    ShouldAutoSize
{
    protected $detalles;
    protected $tipo;

    public function __construct($detalles = null, $tipo = 'E')
    {
        $this->detalles = $detalles;
        $this->tipo = $tipo;
    }

    /**
     * Retorna la colección de datos a exportar
     */
    public function collection()
    {
        if ($this->detalles) {
            return $this->detalles;
        }

        return DetalleControlGarita::with(['controlGarita', 'usuario', 'etiqueta', ''])
            ->where('tipo_movimiento', $this->tipo)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Define los encabezados de las columnas
     */
    public function headings(): array
    {
        return [
            'ID',
            'TIPO MOVIMIENTO',
            'FECHA',
            'HORA',
            'TIPO ENTIDAD',
            'TIPO DOCUMENTO',
            'DOCUMENTO',
            'NOMBRE',
            'PLACA',
            'TIPO VEHÍCULO',
            'TRAE CARGA',
            'TIPO CARGA',
            'DESTINO',
            'OCURRENCIAS',
            'ETIQUETA',
            'TURNO',
            'UNIDAD',
            'REGISTRADO POR',
            'FECHA REGISTRO',
        ];
    }

    /**
     * Mapea cada fila de datos
     */
    public function map($detalle): array
    {
        return [
            $detalle->id,
            $detalle->tipo_movimiento === 'E' ? 'ENTRADA' : 'SALIDA',
            $detalle->created_at ? $detalle->created_at->format('d/m/Y') : '',
            $detalle->hora ? \Carbon\Carbon::parse($detalle->hora)->format('H:i') : '',
            $detalle->tipo_entidad === 'P' ? 'PERSONA' : 'VEHÍCULO',
            $detalle->tipo_documento == 1 ? 'DNI' : ($detalle->tipo_documento == 2 ? 'RUC' : ''),
            $detalle->documento ?? '',
            strtoupper($detalle->nombre ?? ''),
            strtoupper($detalle->placa ?? ''),
            $this->getTipoVehiculo($detalle->tipo_vehiculo),
            $detalle->trae_carga ? 'SÍ' : 'NO',
            $this->getTipoCarga($detalle->tipo_carga),
            strtoupper($detalle->destino ?? ''),
            strtoupper($detalle->ocurrencias ?? ''),
            $detalle->etiqueta ? strtoupper($detalle->etiqueta->nombre) : '',
            $detalle->controlGarita ? ($detalle->controlGarita->turno == 0 ? 'DÍA' : 'NOCHE') : '',
            $detalle->controlGarita ? strtoupper($detalle->controlGarita->unidad) : '',
            $detalle->usuario ? strtoupper($detalle->usuario->name) : '',
            $detalle->created_at ? $detalle->created_at->format('d/m/Y H:i:s') : '',
        ];
    }

    /**
     * Aplica estilos a la hoja
     */
    public function styles(Worksheet $sheet)
    {
        return [
            // Estilo del encabezado
            1 => [
                'font' => [
                    'bold' => true,
                    'size' => 12,
                    'color' => ['rgb' => 'FFFFFF'],
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '4472C4'],
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                    ],
                ],
            ],
        ];
    }

    /**
     * Título de la hoja
     */
    public function title(): string
    {
        return $this->tipo === 'E' ? 'Entradas' : 'Salidas';
    }

    /**
     * Helper para tipo de vehículo
     */
    private function getTipoVehiculo($tipo)
    {
        $tipos = [
            1 => 'AUTO',
            2 => 'MINIVAN',
            3 => 'CAMIONETA',
            4 => 'VOLQUETE',
            5 => 'ENCAPSULADO',
        ];

        return $tipos[$tipo] ?? '';
    }

    /**
     * Helper para tipo de carga
     */
    private function getTipoCarga($tipo)
    {
        $tipos = [
            1 => 'MINERAL CHANCADO',
            2 => 'MINERAL A GRANEL',
        ];

        return $tipos[$tipo] ?? '';
    }
}