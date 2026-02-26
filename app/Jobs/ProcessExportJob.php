<?php

namespace App\Jobs;

use App\Exports\BaseExport;
use App\Models\Export;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
class ProcessExportJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 600;
    public $tries = 3;

    protected BaseExport $export;
    protected string $fileName;
    protected string $format;
    protected int $exportId;

    /**
     * Create a new job instance.
     */
    public function __construct(BaseExport $export, string $fileName, string $format, int $exportId)
    {
        $this->export = $export;
        $this->fileName = $fileName;
        $this->format = $format;
        $this->exportId = $exportId;
    }

    public function handle()
    {
        try {
            // Update status to processing
            $exportRecord = Export::find($this->exportId);
            $exportRecord->update(['status' => 'processing']);

            // Store the export
            $path = 'exports/' . $this->fileName;
            Excel::store($this->export, $path, 'public');

            // Update record with download URL
            $exportRecord->update([
                'status' => 'completed',
                'file_path' => $path,
                'download_url' => Storage::url($path),
                'completed_at' => now(),
            ]);

            // Notify user (optional)
            // Notification::send($exportRecord->user, new ExportReadyNotification($exportRecord));

            Log::info('Export completed successfully', [
                'export_id' => $this->exportId,
                'file' => $this->fileName
            ]);

        } catch (\Exception $e) {
            // Mark as failed
            Export::find($this->exportId)->update([
                'status' => 'failed',
                'error_message' => $e->getMessage(),
            ]);

            Log::error('Export failed', [
                'export_id' => $this->exportId,
                'error' => $e->getMessage()
            ]);

            throw $e;
        }
    }

    public function failed(\Throwable $exception)
    {
        // Handle failure after all retries
        Log::critical('Export job failed permanently', [
            'export_id' => $this->exportId,
            'error' => $exception->getMessage()
        ]);
    }
}
