<?php

namespace App\Exports;

use App\Models\InventoryItem;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class InventoryExport implements FromCollection, ShouldAutoSize, WithHeadings, WithMapping, WithStyles
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return InventoryItem::all();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Nombre',
            'SKU',
            'Categoría',
            'Stock Actual',
            'Stock Mínimo',
            'Unidad',
            'Precio Unitario',
            'Estado',
            'Fecha de Creación',
        ];
    }

    public function map($item): array
    {
        return [
            $item->id,
            $item->name,
            $item->sku,
            $item->category ?? 'N/A',
            $item->stock_current,
            $item->stock_min,
            $item->unit,
            $item->price_unit,
            $item->stock_current <= $item->stock_min ? 'Bajo Stock' : 'OK',
            $item->created_at->format('d/m/Y H:i'),
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
