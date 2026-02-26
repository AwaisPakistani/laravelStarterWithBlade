<?php

namespace App\Services;

use App\Exports\BaseExport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use App\Jobs\ProcessExportJob;

class ExportService
{
    protected array $supportedFormats = ['xlsx', 'csv', 'pdf'];

    /**
     * Handle export request with format detection
     */
    public function export(BaseExport $export, Request $request)
    {
        $format = $request->input('format', 'xlsx');
        $method = $request->input('method', 'download'); // download, queue, store

        if (!in_array($format, $this->supportedFormats)) {
            abort(400, 'Unsupported export format');
        }

        return match($method) {
            'queue' => $this->queueExport($export, $format, $request),
            'store' => $this->storeExport($export, $format, $request),
            default => $this->downloadExport($export, $format, $request),
        };
    }

    /**
     * Direct download (for small exports)
     */
    protected function downloadExport(BaseExport $export, string $format, Request $request)
    {
        if ($format === 'pdf') {
            return $this->exportToPdf($export, $request);
        }

        return Excel::download(
            $export,
            $export->getFilename($format)
        );
    }

    /**
     * Queue export for background processing (large datasets)
     */
    protected function queueExport(BaseExport $export, string $format, Request $request)
    {
        $userId = auth()->id();
        $fileName = $export->getFilename($format);

        // Store export request in database
        $exportRecord = Export::create([
            'user_id' => $userId,
            'file_name' => $fileName,
            'format' => $format,
            'filters' => $request->input('filters', []),
            'status' => 'pending',
        ]);

        // Dispatch job with allOnQueue for full pipeline consistency
        ProcessExportJob::dispatch($export, $fileName, $format, $exportRecord->id)
            ->onQueue('exports')
            ->allOnQueue('exports') // Critical for multi-job exports
            ->delay(now()->addSeconds(5));

        return response()->json([
            'message' => 'Export queued successfully',
            'export_id' => $exportRecord->id,
            'status_url' => route('admin.exports.status', $exportRecord->id),
        ]);
    }

    /**
     * Store export to disk (for email attachments, etc.)
     */
    protected function storeExport(BaseExport $export, string $format, Request $request)
    {
        $disk = $request->input('disk', 'local');
        $path = 'exports/' . $export->getFilename($format);

        Excel::store($export, $path, $disk);

        return response()->json([
            'message' => 'Export stored successfully',
            'path' => $path,
            'url' => Storage::disk($disk)->url($path),
        ]);
    }

    /**
     * Export to PDF with custom styling
     */
    protected function exportToPdf(BaseExport $export, Request $request)
    {
        $data = $export->query()->get();

        // Get headings from the export class
        $headings = $export->headings();

        // Map the data using the export's map method
        $mappedData = $data->map(function ($item) use ($export) {
            return $export->map($item);
        });

        $pdf = Pdf::loadView('admin.exports.pdf-template', [
            'data' => $mappedData, // Use already mapped data
            'headings' => $headings,
            'title' => class_basename($export),
            'filters' => $request->input('filters', []),
        ]);

        $pdf->setPaper('A4', 'landscape');

        return $pdf->download($export->getFilename('pdf'));
    }
}
