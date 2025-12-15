<?php

namespace App\Exports;

use App\Models\TsCaja;
use Maatwebsite\Excel\Concerns\FromCollection;
use App\Models\TsCuenta;
use App\Models\TsIngresoCuenta;
use App\Models\TsReposicioncaja;
use App\Models\TsSalidacaja;
use App\Models\TsSalidaCuenta;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Carbon\Carbon;


class TsMicajaPorFechaExport implements FromCollection, WithTitle, WithStyles
{
    protected $fecha_inicio;
    protected $fecha_fin;
    protected $caja_id;

    public function __construct($fecha_inicio = null, $fecha_fin = null, $caja_id = null)
    {
        $this->fecha_inicio = $fecha_inicio;
        $this->fecha_fin = $fecha_fin;
        $this->caja_id = $caja_id;
    }

    /**
     * Fetches and formats data for the export collection.
     */
    public function collection()
    {


        $caja = TsCaja::find($this->caja_id);

        $ingresoData = $this->getIngresoData();
        $salidaData = $this->getSalidaData();

        $titleData = [['REPORTE DE CAJA']];
        $infoCuenta = $caja ? $this->getInformacionCuenta($caja) : [['Cuenta no disponible'], []];

        $saldoAnterior = $this->calculateSaldoAnterior($caja) ? $this->calculateSaldoAnterior($caja) : '0';
        $reportetotal = $this->calculateReporteTotal($caja);

        $saldoAnteriorRow = [['', '', '', '', '', '', '', 'SALDO', 'ANTERIOR:', $saldoAnterior]];
        $balanceRow = [['', '', '', '', '', '', '', '', 'BALANCE:', $reportetotal]];

        // Combine title, summary, headings, and main data for the export
        return (new Collection($titleData))
            ->merge($infoCuenta)
            ->merge($this->getHeadings())
            ->merge($saldoAnteriorRow)
            ->merge($ingresoData)
            ->merge($salidaData)
            ->merge($balanceRow);
    }

    /**
     * Returns headings row.
     */
    private function getHeadings()
    {
        return [
            ['ID','FECHA', 'TIPO', 'COMPROB.', 'NRO', 'FECHA COMPR.', 'MOTIVO', 'BENEFICIARIO', 'DESCRIPCION', 'MONTO']
        ];
    }

    /**
     * Fetches and formats data for "Ingreso" records.
     */
    private function getIngresoData()
    {
        $query = TsReposicioncaja::query()->where('caja_id', '=', $this->caja_id);
        if ($this->fecha_inicio) {
            $query->where('created_at', '>=', Carbon::parse($this->fecha_inicio)->startOfDay());
        }
        if ($this->fecha_fin) {
            $query->where('created_at', '<=', Carbon::parse($this->fecha_fin)->endOfDay());
        }
      
 

        return $query->get()->map(function ($item) {
            return [
                'id' => $item->id,
                'fecha' => Carbon::parse($item->created_at)->format('d-m-Y'),
                'tipo' => 'REPOSICIÓN',
             
                
                'tipo_compr' => $item->tipocomprobante ? strtoupper($item->tipocomprobante->nombre) : '-',
                'nro_compr' => $item->comprobante_correlativo ? $item->comprobante_correlativo : '-',
                'fecha_compra' => Carbon::parse($item->fecha_comprobante)->format('d/m/y'),
                
                'motivo' => $item->motivo->nombre,
                'beneficiario' => '-',
                'descripcion' => $item->descripcion ? strtoupper($item->descripcion) : '-',
                'monto' => $item->monto,
            ];
        });
    }

    /**
     * Fetches and formats data for "Salida" records.
     */
    private function getSalidaData()
    {
        $query = TsSalidacaja::query()->where('caja_id', '=', $this->caja_id);

        if ($this->fecha_inicio) {
            $query->where('created_at', '>=', Carbon::parse($this->fecha_inicio)->startOfDay());
        }
        if ($this->fecha_fin) {
            $query->where('created_at', '<=', Carbon::parse($this->fecha_fin)->endOfDay());
        }

        return $query->get()->map(function ($item) {
       
            $sociedad = '-';

          

            return [
                'id' => $item->id,
                'fecha' => Carbon::parse($item->fecha)->format('d-m-Y'),
                'tipo' => 'EGRESO',
                'tipo_compr' => $item->tipocomprobante ? strtoupper($item->tipocomprobante->nombre) : '-',
                'nro_compr' => $item->comprobante_correlativo ? $item->comprobante_correlativo : 'IN - ' . $item->id,
                'fecha_compra' => Carbon::parse($item->fecha_comprobante)->format('d/m/y'),
                
                'motivo' => $item->motivo->nombre,
                'beneficiario' => optional($item->beneficiario)->nombre,
                'descripcion' => $item->descripcion,
                'monto' => $item->monto ? -$item->monto : '-',
            ];
        });
    }

    /**
     * Retrieves information about the account.
     */
    private function getInformacionCuenta($caja)
    {

        $desdeDate = is_string($this->fecha_inicio) ? Carbon::parse($this->fecha_inicio) : $this->fecha_inicio;
        $hastaDate = is_string($this->fecha_fin) ? Carbon::parse($this->fecha_fin) : $this->fecha_fin;
        return [

            [
                'CUENTA:',
                '',
                $caja->nombre,
                '',
                '', 
                'DESDE',
                $desdeDate->format('d/m/Y')
            ],
            [
                'MONEDA:',
                '',
                'SOLES',
                '',
                '',
                'HASTA',
                $hastaDate->format('d/m/Y')
            ],

            ['']
        ];
    }

    /**
     * Calculates the previous balance for the account.
     */
    private function calculateSaldoAnterior($caja)
    {
        if (!$caja) {
            return 0;
        }

        $desdeDate = Carbon::parse($this->fecha_inicio)->startOfDay();


        $ingresosAnteriores = $caja->reposiciones->where('created_at', '<', $desdeDate);
        $salidasAnteriores = $caja->salidascajas->where('created_at', '<', $desdeDate);


        // Calculate the sum only if there are records
        $sumIngresos = $ingresosAnteriores->isEmpty() ? 0 : $ingresosAnteriores->sum('monto');
        $sumSalidas = $salidasAnteriores->isEmpty() ? 0 : $salidasAnteriores->sum('monto');

        return $sumIngresos - $sumSalidas;
    }

    /**
     * Calculates the total report balance for the account.
     */
    private function calculateReporteTotal($caja)
    {
        if (!$caja) {
            return 0;
        }

        $ingresoCompleto = $caja->reposiciones->where('created_at', '<=', Carbon::parse($this->fecha_fin)->endOfDay())->sum('monto');
        $salidaCompleta = $caja->salidascajas->where('created_at', '<=', Carbon::parse($this->fecha_fin)->endOfDay())->sum('monto');

        return $ingresoCompleto - $salidaCompleta;
    }

    public function title(): string
    {
        return 'REPORTE DE CUENTAS';
    }

    public function styles(Worksheet $sheet)
    {

        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(15); // Title row styling
        $sheet->getStyle('A1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
        $sheet->getStyle('A1')->getFill()->getStartColor()->setARGB('9eff00'); // Set background color
        $sheet->getStyle('A2:A3')->getFont()->setBold(true)->setItalic(true)->setSize(12); // "Información general" styling
        $sheet->getStyle('F2:F3')->getFont()->setBold(true)->setItalic(true)->setSize(12); // "Información general" styling
        $sheet->getStyle('A1:D1')->getAlignment()->setHorizontal('center');
        $mergedCellsRanges = ['A1:G3'];
        foreach ($mergedCellsRanges as $range) {
            $sheet->getStyle($range)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM);
        }


        $sheet->mergeCells('A1:G1'); // Merging for the main title
        $sheet->mergeCells('A2:B2'); // Merging for "Información general"
        $sheet->mergeCells('A3:B3'); // Merging for "Información general"
        $sheet->mergeCells('C2:E2'); // Merging for "Información general"
        $sheet->mergeCells('C3:E3'); // Merging for "Información general"



        $sheet->getColumnDimension('A')->setWidth(4);
        $sheet->getColumnDimension('B')->setWidth(10.3);
        $sheet->getColumnDimension('C')->setWidth(10);
        $sheet->getColumnDimension('D')->setWidth(12.5);
        $sheet->getColumnDimension('E')->setWidth(14);
        $sheet->getColumnDimension('F')->setWidth(15);
        $sheet->getColumnDimension('G')->setWidth(10.3);
        $sheet->getColumnDimension('H')->setWidth(15.3);
        $sheet->getColumnDimension('I')->setWidth(20);
        $sheet->getColumnDimension('J')->setWidth(15);
        $sheet->getRowDimension('2')->setRowHeight(15);
        $sheet->getRowDimension('3')->setRowHeight(15);

        $sheet->getStyle('E7:E500')->getAlignment()->setWrapText(true);
        $sheet->getStyle('F7:F500')->getAlignment()->setWrapText(true);
        $sheet->getStyle('A1:Z500')->getAlignment()->setWrapText(true);
        $sheet->getStyle('A1:Z700')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $sheet->getStyle('A1:Z700')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $sheet->getStyle('A6:Z700')->getFont()->setSize(9);
    }
}
