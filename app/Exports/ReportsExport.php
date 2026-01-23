<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class ReportsExport implements WithMultipleSheets
{
    use Exportable;

    public function __construct(protected array $data) {}

    public function sheets(): array
    {
        return [
            new \App\Exports\Sheets\OverviewSheet($this->data),
            new \App\Exports\Sheets\TopProductsSheet($this->data['topProducts']),
            new \App\Exports\Sheets\CategoryPerformanceSheet($this->data['categoryPerformance']),
        ];
    }
}
