@extends('layout.master')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-md-12">
            <h2>Advanced Export</h2>
            <p class="text-muted">Export users with filters and column selection</p>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5>Export Configuration</h5>
                </div>
                <div class="card-body">
                    <form id="exportForm" action="{{ route('admin.exports.users') }}" method="POST">
                        @csrf

                        {{-- Filters --}}
                        <div class="mb-3">
                            <label>Name</label>
                            <input type="text" name="filters[name]" class="form-control" placeholder="Filter by name">
                        </div>

                        <div class="mb-3">
                            <label>Email</label>
                            <input type="text" name="filters[email]" class="form-control" placeholder="Filter by email">
                        </div>

                        <div class="mb-3">
                            <label>Role</label>
                            <select name="filters[role]" class="form-control">
                                <option value="">All Roles</option>
                                @foreach(App\Models\Role::all() as $role)
                                    <option value="{{ $role->name }}">{{ $role->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label>Date Range</label>
                            <div class="row">
                                <div class="col">
                                    <input type="date" name="filters[created_between][from]" class="form-control" placeholder="From">
                                </div>
                                <div class="col">
                                    <input type="date" name="filters[created_between][to]" class="form-control" placeholder="To">
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label>Status</label>
                            <select name="filters[is_active]" class="form-control">
                                <option value="">All</option>
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>

                        {{-- Column Selection --}}
                        <div class="mb-3">
                            <label>Select Columns</label>
                            <div class="form-check">
                                <input type="checkbox" name="selected_columns[]" value="id" class="form-check-input" checked> ID
                            </div>
                            <div class="form-check">
                                <input type="checkbox" name="selected_columns[]" value="name" class="form-check-input" checked> Name
                            </div>
                            <div class="form-check">
                                <input type="checkbox" name="selected_columns[]" value="email" class="form-check-input" checked> Email
                            </div>
                            <div class="form-check">
                                <input type="checkbox" name="selected_columns[]" value="roles" class="form-check-input"> Roles
                            </div>
                            <div class="form-check">
                                <input type="checkbox" name="selected_columns[]" value="status" class="form-check-input" checked> Status
                            </div>
                            <div class="form-check">
                                <input type="checkbox" name="selected_columns[]" value="last_login" class="form-check-input"> Last Login
                            </div>
                            <div class="form-check">
                                <input type="checkbox" name="selected_columns[]" value="created_at" class="form-check-input"> Created At
                            </div>
                        </div>

                        {{-- Export Options --}}
                        <div class="mb-3">
                            <label>Format</label>
                            <select name="format" class="form-control">
                                <option value="xlsx">Excel (XLSX)</option>
                                <option value="csv">CSV</option>
                                <option value="pdf">PDF</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label>Export Method</label>
                            <select name="method" class="form-control" id="exportMethod">
                                <option value="download">Direct Download</option>
                                <option value="queue">Background Queue</option>
                                <option value="store">Store Only</option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-download"></i> Export
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5>Recent Exports</h5>
                </div>
                <div class="card-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>File</th>
                                <th>Format</th>
                                <th>Status</th>
                                <th>Created</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($exports as $export)
                            <tr>
                                <td>{{ $export->file_name }}</td>
                                <td>{{ strtoupper($export->format) }}</td>
                                <td>
                                    <span class="badge bg-{{
                                        $export->status == 'completed' ? 'success' :
                                        ($export->status == 'failed' ? 'danger' :
                                        ($export->status == 'processing' ? 'warning' : 'secondary'))
                                    }}">
                                        {{ $export->status }}
                                    </span>
                                </td>
                                <td>{{ $export->created_at->diffForHumans() }}</td>
                                <td>
                                    @if($export->download_url)
                                        <a href="{{ $export->download_url }}" class="btn btn-sm btn-success">
                                            <i class="fas fa-download"></i>
                                        </a>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{ $exports->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.getElementById('exportForm').addEventListener('submit', async (e) => {
    e.preventDefault();

    const form = e.target;
    const method = document.getElementById('exportMethod').value;
    const formData = new FormData(form);

    if (method === 'queue') {
        // Handle queued export with AJAX
        const response = await fetch(form.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        });

        const result = await response.json();

        if (result.export_id) {
            // Show success message and start polling
            alert('Export queued successfully! Check back in a few moments.');

            // Start polling for status
            const checkStatus = setInterval(async () => {
                const statusResponse = await fetch(result.status_url);
                const status = await statusResponse.json();

                if (status.status === 'completed') {
                    clearInterval(checkStatus);
                    window.location.reload();
                } else if (status.status === 'failed') {
                    clearInterval(checkStatus);
                    alert('Export failed: ' + (status.error_message || 'Unknown error'));
                }
            }, 5000);
        }
    } else {
        // Direct download - submit normally
        form.submit();
    }
});
</script>
@endpush
@endsection
