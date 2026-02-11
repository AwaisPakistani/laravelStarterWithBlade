@props([
    'canEdit' => false,
    'canDelete' => false,
    'canShow' => false,
    'canDownload' => false,
    'canApprove' => false,
    'approveRoute' => false,
    'editRoute',
    'deleteRoute',
    'showRoute',
    'downloadRoute',
])
<div class="d-flex align-item-center">
    @if ($canShow)
        <a href="{{ $showRoute }}" class="btn btn-info btn-sm me-2" title="View"><span class="bi bi-eye"></span></a>
    @endif
    @if ($canApprove)
        <a href="{{ $approveRoute }}" class="btn btn-dark btn-sm me-2">Approve</a>
    @endif
    @if ($canDownload)
        <a href="{{ $downloadRoute }}" class="btn btn-dark btn-sm me-2" title="Delete" target="_blank"><em
                class="icon ni ni-download"></em></a>
    @endif
    @if ($canEdit)
        <a href="{{ $editRoute }}" class="btn btn-success btn-sm me-2" title="Edit"><span class="bi bi-pencil"></span></a>
    @endif
    @if ($canDelete)
        <form method="POST" action="{{ $deleteRoute }}">
            @csrf
            @method('DELETE')
            <a class="btn btn-danger btn-sm" onclick="confirmOrDeniDelete(event)" href="#">
                <span><span class="bi bi-trash"></span></span>
            </a>
        </form>
    @endif
</div>

