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

class CategoryPerformanceSheet implements FromCollection, ShouldAutoSize, WithColumnFormatting, WithCustomStartCell, WithHeadings, WithStyles, WithTitle
{
    public function __construct(protected $categoryPerformance) {}

    public function startCell(): string
    {
        return 'A3';
    }

    public function collection()
    {
        return collect($this->categoryPerformance)->map(function ($item) {
            return [
                $item->id,
                $item->name,
                (float) $item->total_sales,
                (float) $item->total_revenue,
                (float) ($item->margin / 100),
            ];
        });
    }

    public function title(): string
    {
        return 'Rendimiento por Categoría';
    }

    public function headings(): array
    {
        return [
            'ID Categoría',
            'Nombre',
            'Ventas Totales',
            'Ingresos Totales (S/)',
            'Margen Estimado',
        ];
    }

    public function columnFormats(): array
    {
        return [
            'D' => '"S/" #,##0.00',
            'E' => '0%',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->mergeCells('A1:E1');
        $sheet->setCellValue('A1', 'RestaurantOS - Análisis de Rendimiento por Categoría');

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
            'C4:C20' => ['alignment' => ['horizontal' => 'center']],
            'E4:E20' => ['alignment' => ['horizontal' => 'center']],
        ];
    }
}
