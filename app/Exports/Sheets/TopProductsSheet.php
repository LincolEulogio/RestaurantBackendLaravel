<?php

namespace App\Exports\Sheets;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class TopProductsSheet implements FromCollection, ShouldAutoSize, WithColumnFormatting, WithCustomStartCell, WithHeadings, WithStyles, WithTitle
{
    public function __construct(protected $topProducts) {}

    public function startCell(): string
    {
        return 'A3';
    }

    public function collection()
    {
        return collect($this->topProducts)->map(function ($item) {
            return [
                $item->name,
                (float) $item->total_quantity,
                (float) $item->total_revenue,
            ];
        });
    }

    public function title(): string
    {
        return 'Top Platillos';
    }

    public function headings(): array
    {
        return [
            'Nombre del Producto',
            'Cantidad Vendida',
            'Ingresos Generados (S/)',
        ];
    }

    public function columnFormats(): array
    {
        return [
            'C' => '"S/" #,##0.00',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->mergeCells('A1:C1');
        $sheet->setCellValue('A1', 'RestaurantOS - Los 5 Platillos Más Vendidos');

        return [
            1 => [
                'font' => ['bold' => true, 'size' => 14, 'color' => ['rgb' => '1a56db']],
                'alignment' => ['horizontal' => 'center'],
            ],
            3 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '1e40af'],
                ],
            ],
            'B4:B10' => ['alignment' => ['horizontal' => 'center']],
        ];
    }
}
