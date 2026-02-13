<?php

namespace App\Exports;

use App\Models\Peso;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class PesosExport implements
    FromCollection,
    WithHeadings,
    WithMapping,
    WithEvents,
    WithCustomStartCell

{
    protected $request;
    protected float $totalNeto = 0;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function startCell(): string
    {
        return 'A4';
    }

    public function collection()
    {


        $pesos = Peso::query();

        if ($this->request->filled('fechai') && $this->request->filled('fechas')) {
            $pesos->whereBetween('Fechas', [
                Carbon::parse($this->request->fechai)->startOfDay(),
                Carbon::parse($this->request->fechas)->endOfDay(),
            ]);
        }

        if ($this->request->filled('ticket')) {
            $pesos->where('NroSalida', 'like', $this->request->ticket . '%');
        }

        if ($this->request->filled('razon')) {
            $pesos->where('RazonSocial', 'like', '%' . $this->request->razon . '%');
        }

        if ($this->request->filled('producto')) {
            $pesos->where('Producto', 'like', '%' . $this->request->producto . '%');
        }

        if ($this->request->filled('destino')) {
            $pesos->where('destino', 'like', '%' . $this->request->destino . '%');
        }

        if ($this->request->filled('origen')) {
            $pesos->where('origen', 'like', '%' . $this->request->origen . '%');
        }


    
        $coleccion = $pesos->orderBy('NroSalida', 'desc')->get();
        $this->totalNeto = $coleccion->sum('Neto');
        return $coleccion;
    }


    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {

                // Título
                $event->sheet->setCellValue('A1', 'PESO NETO TOTAL');
                $event->sheet->setCellValue('B1', $this->totalNeto);

                // Estilo
                $event->sheet->getStyle('A1:B1')->applyFromArray([
                    'font' => [
                        'bold' => true,
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        ],
                    ],
                ]);

                // Formato numérico
                $event->sheet->getStyle('B1')
                    ->getNumberFormat()
                    ->setFormatCode('#,##0.00');
            },
        ];
    }

    public function map($p): array
    {
        return [
            $p->NroSalida,
            $p->Fechai ? Carbon::parse($p->Fechai)->format('Y-m-d') : null,
            $p->Horai ? Carbon::parse($p->Horai)->format('H:i:s') : null,
            $p->Fechas ? Carbon::parse($p->Fechas)->format('Y-m-d') : null,
            $p->Horas ? Carbon::parse($p->Horas)->format('H:i:s') : null,
            $p->Pesoi,
            $p->Pesos,
            $p->Bruto,
            $p->Tara,
            $p->Neto,
            $p->Placa,
            $p->Producto,
            $p->Conductor,
            $p->Transportista,
            $p->RazonSocial,
            $p->Operadori,
            $p->Destarado,
            $p->Operadors,
            $p->carreta,
            $p->guia,
            $p->guiat,
            $p->pedido,
            $p->entrega,
            $p->um,
            $p->pesoguia,
            $p->rucr,
            $p->ruct,
            $p->destino,
            $p->origen,
            $p->brevete,
            $p->pbmax,
            $p->tipo,
            $p->centro,
            $p->nia,
            $p->bodega,
            $p->ip,
            $p->anular,
            $p->eje,
            $p->pesaje,
        ];
    }

    public function headings(): array
    {
        return [
            'Ticket',
            'Fecha Inicio',
            'Hora Inicio',
            'Fecha Salida',
            'Hora salida',
            'Peso Inicial',
            'Peso Salida',
            'Bruto',
            'Tara',
            'Neto',
            'Placa',
            'Producto',
            'Conductor',
            'Transportista',
            'Razón Social',
            'Operador Inicio',
            'Destarado',
            'Operador Salida',
            'Carreta',
            'Guía',
            'Guía Transp.',
            'Pedido',
            'Entrega',
            'UM',
            'Peso Guía',
            'RUC Remitente',
            'RUC Transportista',
            'Destino',
            'Origen',
            'Brevete',
            'PB Max',
            'Tipo',
            'Centro',
            'NIA',
            'Bodega',
            'IP',
            'Anulado',
            'Eje',
            'Pesaje',
        ];
    }


    /* ======================
       Helpers seguros
    ====================== */

    private function fecha($value)
    {
        return $value ? Carbon::parse($value) : null;
    }

    private function hora($value)
    {
        return $value ? Carbon::parse($value) : null;
    }
}
