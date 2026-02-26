<?php

namespace App\Exports;

use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

abstract class BaseExport implements
    FromQuery,
    WithHeadings,
    WithMapping,
    WithStyles,
    ShouldAutoSize,
    WithEvents,
    ShouldQueue
{
    use Exportable;

    protected array $filters;
    protected string $filename;
    protected array $selectedColumns;

    public function __construct(array $filters = [], array $selectedColumns = [])
    {
        $this->filters = $filters;
        $this->selectedColumns = $selectedColumns;
        $this->filename = $this->generateFilename();
    }

    /**
     * Define the query for export
     */
    abstract public function query(): Builder;

    /**
     * Define column headings
     */
    abstract public function headings(): array;

    /**
     * Map data for each row
     */
    abstract public function map($row): array;

    /**
     * Apply filters to the query
     */
    protected function applyFilters(Builder $query): Builder
    {
        // Override in child classes to apply custom filters
        return $query;
    }

    /**
     * Generate unique filename
     */
    protected function generateFilename(): string
    {
        $className = class_basename($this);
        $timestamp = now()->format('Y_m_d_His');

        return strtolower(str_replace('Export', '', $className)) . "_{$timestamp}";
    }

    /**
     * Get filename with extension
     */
    public function getFilename(string $extension = 'xlsx'): string
    {
        return $this->filename . '.' . $extension;
    }

    /**
     * Apply styles to the worksheet
     */
    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true, 'size' => 12]],
        ];
    }

    /**
     * Register events for post-export processing
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet;

                // Auto-size all columns
                foreach (range('A', $sheet->getHighestColumn()) as $column) {
                    $sheet->getColumnDimension($column)->setAutoSize(true);
                }

                // Log export completion
                Log::info('Export completed', [
                    'export' => class_basename($this),
                    'time' => now(),
                ]);
            },
        ];
    }

    /**
     * Queue configuration for large exports
     */
    public function queueConfig(): array
    {
        return [
            'queue' => 'exports',
            'tries' => 3,
            'timeout' => 600,
        ];
    }
}
