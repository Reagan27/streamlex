@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">

    {{-- Page Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0 fw-bold">Data Collection Management</h4>
        <a href="{{ route('admin.data-collection.create') }}" class="btn btn-success">
            <i class="bi bi-plus-lg me-1"></i> New Data Collection
        </a>
    </div>

    {{-- Flash Messages --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Table Card --}}
    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            @if($dataCollections->isEmpty())
                <div class="alert alert-info m-3">No data collection forms found.</div>
            @else
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">Title</th>
                            <th>Description</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($dataCollections as $entry)
                            <tr>
                                <td class="ps-4 fw-semibold">{{ $entry->title }}</td>
                                <td class="text-muted">{{ $entry->description }}</td>
                                <td>{{ $entry->start_date->format('d M Y') }}</td>
                                <td>{{ $entry->end_date->format('d M Y') }}</td>
                                <td>
                                    {{-- Status Toggle --}}
                                    <div class="form-check form-switch mb-0">
                                        <input class="form-check-input status-toggle" type="checkbox"
                                               role="switch"
                                               data-id="{{ $entry->id }}"
                                               data-slug="{{ $entry->slug ?? $entry->id }}"
                                               {{ $entry->status ? 'checked' : '' }}
                                               title="{{ $entry->status ? 'Active — click to deactivate' : 'Inactive — click to activate' }}">
                                    </div>
                                </td>
                                <td>
                                    {{-- View --}}
                                    @if($entry->slug)
                                        <a href="{{ route('data-collection.view', $entry->slug) }}"
                                            class="btn btn-sm btn-primary" title="View Form">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                    @else
                                        <span class="btn btn-sm btn-secondary disabled" title="No slug yet">
                                            <i class="bi bi-eye"></i>
                                        </span>
                                    @endif

                                    {{-- View Responses (from Google Form) --}}
                                    @php
                                        $responseUrl = null;
                                        if (!empty($entry->iframe_code) && preg_match('/src="([^"]+)"/', $entry->iframe_code, $m)) {
                                            $formUrl = $m[1];
                                            $baseUrl = strtok($formUrl, '?');
                                            $responseUrl = str_replace('/viewform', '/edit', $baseUrl) . '#responses';
                                        }
                                    @endphp
                                    @if($responseUrl)
                                        <a href="{{ $responseUrl }}"
                                           class="btn btn-sm btn-info" title="View Responses in Google Forms" target="_blank">
                                            <i class="bi bi-bar-chart"></i>
                                        </a>
                                    @endif

                                    {{-- Download Excel & Open Sheet --}}
                                    @php
                                        $sheetId = null;
                                        $isSheetUrl = false;

                                        if (!empty($entry->sheet_url)) {
                                            // Check if it's a proper Google Sheets URL
                                            if (preg_match('/\/spreadsheets\/d\/([a-zA-Z0-9_-]+)/', $entry->sheet_url, $sm)) {
                                                $sheetId = $sm[1];
                                                $isSheetUrl = true;
                                            }
                                        }
                                    @endphp

                                    @if($isSheetUrl && $sheetId)
                                        {{-- Proper Sheets URL: show Excel download + open sheet --}}
                                        <a href="https://docs.google.com/spreadsheets/d/{{ $sheetId }}/export?format=xlsx"
                                           class="btn btn-sm btn-success" title="Download Responses as Excel" target="_blank">
                                            <i class="bi bi-file-earmark-excel"></i>
                                        </a>
                                        <a href="{{ $entry->sheet_url }}"
                                           class="btn btn-sm btn-secondary" title="Open Google Sheet" target="_blank">
                                            <i class="bi bi-box-arrow-up-right"></i>
                                        </a>
                                    @elseif(!empty($entry->sheet_url))
                                        {{-- URL set but not a Sheets URL (e.g. a Forms URL) — show open link only --}}
                                        <span class="btn btn-sm btn-success disabled"
                                              title="Paste a Google Sheets URL (not a Forms URL) to enable Excel download">
                                            <i class="bi bi-file-earmark-excel"></i>
                                        </span>
                                        <a href="{{ $entry->sheet_url }}"
                                           class="btn btn-sm btn-secondary" title="Open linked URL" target="_blank">
                                            <i class="bi bi-box-arrow-up-right"></i>
                                        </a>
                                    @else
                                        {{-- No sheet URL set at all --}}
                                        <span class="btn btn-sm btn-success disabled"
                                              title="No Google Sheet URL set — edit this record to add one">
                                            <i class="bi bi-file-earmark-excel"></i>
                                        </span>
                                    @endif

                                    {{-- Edit --}}
                                    @if($entry->slug)
                                        <a href="{{ route('admin.data-collection.edit', $entry->slug) }}"
                                           class="btn btn-sm btn-warning" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                    @else
                                        <span class="btn btn-sm btn-warning disabled" title="No slug yet">
                                            <i class="bi bi-pencil"></i>
                                        </span>
                                    @endif

                                    {{-- Delete --}}
                                    <form action="{{ route('admin.data-collection.destroy', $entry->slug ?? $entry->id) }}"
                                        method="POST" class="d-inline-block"
                                        onsubmit="return confirm('Are you sure you want to delete this?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" title="Delete">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>

</div>

<script>
document.querySelectorAll('.status-toggle').forEach(function(toggle) {
    toggle.addEventListener('change', function() {
        const slug = this.dataset.slug;
        const newStatus = this.checked ? 1 : 0;
        const el = this;

        fetch('/admin/data-collection/' + slug + '/toggle-status', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ status: newStatus })
        })
        .then(res => res.json())
        .then(data => {
            if (!data.success) {
                el.checked = !el.checked; // revert on failure
                alert('Failed to update status. Please try again.');
            }
        })
        .catch(() => {
            el.checked = !el.checked; // revert on error
            alert('Network error. Please try again.');
        });
    });
});
</script>
@endsection