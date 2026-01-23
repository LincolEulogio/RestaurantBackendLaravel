<?php

namespace App\Exports\Sheets;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class OverviewSheet implements FromCollection, ShouldAutoSize, WithColumnFormatting, WithCustomStartCell, WithHeadings, WithMapping, WithStyles, WithTitle
{
    public function __construct(protected array $data) {}

    public function startCell(): string
    {
        return 'A3';
    }

    public function collection()
    {
        return collect([
            ['Ingresos Totales', (float) $this->data['totalRevenue']],
            ['Órdenes Completadas', (int) $this->data['completedOrders']],
            ['Ticket Promedio', (float) $this->data['averageTicket']],
            ['Clientes Únicos', (int) $this->data['uniqueCustomers']],
            ['Ganancia Neta (Est. 20%)', (float) $this->data['netProfit']],
            ['Items Vendidos', (int) $this->data['totalItemsSold']],
            ['Órdenes Canceladas', (int) $this->data['cancelledOrders']],
            ['Monto Cancelado', (float) $this->data['cancelledAmount']],
        ]);
    }

    public function title(): string
    {
        return 'Resumen General';
    }

    public function headings(): array
    {
        return [
            'Métrica de Rendimiento',
            'Valor Calculado',
        ];
    }

    public function map($row): array
    {
        return $row;
    }

    public function columnFormats(): array
    {
        return [
            'B' => '#,##0.00',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Add Title and Date Range
        $sheet->mergeCells('A1:B1');
        $sheet->setCellValue('A1', 'RestaurantOS - Reporte de Rendimiento');

        $sheet->mergeCells('A2:B2');
        $sheet->setCellValue('A2', 'Periodo: '.$this->data['startDate'].' al '.$this->data['endDate']);

        return [
            1 => [
                'font' => ['bold' => true, 'size' => 16, 'color' => ['rgb' => '1a56db']],
                'alignment' => ['horizontal' => 'center'],
            ],
            2 => [
                'font' => ['italic' => true, 'color' => ['rgb' => '666666']],
                'alignment' => ['horizontal' => 'center'],
            ],
            3 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '1e40af'],
                ],
            ],
            'A3:A11' => [
                'font' => ['bold' => true],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'f3f4f6'],
                ],
            ],
        ];
    }
}
