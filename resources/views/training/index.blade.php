@extends('layouts.app')

@section('page-title', __('Training Events'))
@section('page-heading', __('Training Events'))

@section('content')
    @include('partials.messages')

    <div class="card">
        <div class="card-body">
            <!-- Add Create button and search form -->
            <div class="d-flex justify-content-between mb-4">
                <!-- Search form -->
                <form action="{{ route('training.index') }}" method="GET">
                    <div class="input-group custom-search-form">
                        <input type="text" class="form-control" name="search" placeholder="Search by event name" value="{{ request('search') }}">
                        <span class="input-group-append">
                            @if (request()->has('search') && request('search') != '')
                                <a href="{{ route('training.index') }}" class="btn btn-light d-flex align-items-center text-muted" role="button">
                                    <i class="fas fa-times"></i>
                                </a>
                            @endif
                            <button class="btn btn-light" type="submit">
                                <i class="fas fa-search text-muted"></i>
                            </button>
                        </span>
                    </div>
                </form>

                @if($canCreateEvent)
                    <a href="{{ route('training.create') }}" class="btn btn-primary">
                        @lang('Create New Training Event')
                    </a>
                @endif
            </div>

            <!-- Table for displaying training events -->
            <div class="table-responsive" id="training-events-table-wrapper">
                <table class="table table-striped table-borderless">
                    <thead>
                        <tr>
                            <th>@lang('Name')</th>
                            <th>@lang('Venue')</th>
                            <th>@lang('County')</th>
                            <th>@lang('Start Date')</th>
                            <th>@lang('End Date')</th>
                            <th>@lang('Form Expires')</th>
                            <th>@lang('Attendees')</th>
                            <th>@lang('Actions')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($events as $event)
                            <tr>
                                <td>{{ $event->name }}</td>
                                <td>{{ $event->venue_name }}</td>
                                <td>{{ $event->county->name ?? 'N/A' }}</td>
                                <td>{{ $event->start_date->format('M d, Y H:i') }}</td>
                                <td>{{ $event->end_date->format('M d, Y H:i') }}</td>
                                <td>{{ $event->form_expires_at->format('M d, Y H:i') }}</td>
                                <td>{{ $event->attendances_count ?? 0 }}</td>
                                <td>
                                    <div class="btn-group">
                                        <a href="{{ route('training.show', $event) }}" class="text-muted mx-2">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @if($canEditEvent)
                                            <a href="{{ route('training.edit', $event) }}" class="text-muted mx-2">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        @endif
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-sm btn-success dropdown-toggle" data-bs-toggle="dropdown">
                                                <i class="fas fa-download"></i> Export
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li>
                                                    <a class="dropdown-item" href="{{ route('training.export', $event->id) }}">
                                                        <i class="fas fa-file-pdf"></i> Export as PDF
                                                    </a>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item" href="{{ route('training.export.excel', $event->id) }}">
                                                        <i class="fas fa-file-excel"></i> Export as Excel
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                        <button type="button" class="btn btn-sm btn-secondary generate-link" data-event-id="{{ $event->id }}" onclick="initiateLinkGeneration({{ $event->id }})">
                                            <i class="fas fa-link"></i> Generate Link
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8"><em>@lang('No training events found.')</em></td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">
                {{ $events->links() }}
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
document.querySelectorAll('.copy-link').forEach(button => {
    button.addEventListener('click', function() {
        const url = this.dataset.url;
        navigator.clipboard.writeText(url).then(() => {
            const originalText = this.textContent;
            this.textContent = '@lang('Copied!')';
            setTimeout(() => {
                this.textContent = originalText;
            }, 2000);
        });
    });
});

async function initiateLinkGeneration(eventId) {
    try {
        const response = await fetch(`/training/${eventId}/generate-restricted-link`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        });

        const data = await response.json();
        
        if (data.success) {
            await navigator.clipboard.writeText(data.link);
            
            // Show success toast
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: 'Link copied to clipboard',
                timer: 2000,
                showConfirmButton: false
            });
        } else {
            throw new Error(data.message || 'Failed to generate link');
        }
    } catch (error) {
        console.error('Error:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: error.message || 'Failed to generate link. Please try again.',
        });
    }
}
</script>
@endpush
