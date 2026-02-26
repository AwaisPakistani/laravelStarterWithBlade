<?php

namespace App\Http\Controllers;

use App\Exports\UserExport;
use App\Services\ExportService;
use App\Models\Export;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;

class ExportController extends Controller
{
    protected ExportService $exportService;

    public function __construct(ExportService $exportService)
    {
        $this->exportService = $exportService;
    }

    /**
     * Export users with advanced filtering
     */
    public function exportUsers(Request $request)
    {
        $request->validate([
            'format' => 'in:xlsx,csv,pdf',
            'method' => 'in:download,queue,store',
            'filters' => 'array',
            'selected_columns' => 'array',
        ]);

        $export = new UserExport(
            $request->input('filters', []),
            $request->input('selected_columns', [])
        );

        return $this->exportService->export($export, $request);
    }

    /**
     * Check export status (for queued exports)
     */
    public function status(Export $export)
    {
        $this->authorize('view', $export);

        return response()->json([
            'id' => $export->id,
            'status' => $export->status,
            'file_name' => $export->file_name,
            'download_url' => $export->download_url,
            'completed_at' => $export->completed_at,
            'error_message' => $export->error_message,
        ]);
    }

    /**
     * List user's exports
     */
    public function index()
    {
        $exports = QueryBuilder::for(Export::where('user_id', auth()->id()))
            ->allowedFilters(['status', 'format'])
            ->allowedSorts(['created_at', 'completed_at'])
            ->paginate(20);

        return view('admin.exports.index', compact('exports'));
    }
}
