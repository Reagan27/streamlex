@extends('layouts.app')

@section('page-title', __('Training Event Details'))
@section('page-heading', __('Training Event Details'))

@section('breadcrumbs')
    <li class="breadcrumb-item active">
        {{ $event->name }}
    </li>
@stop

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Attendees</h5>
                    <div class="btn-group">
    <a href="{{ route('training.history', $event->id) }}" class="btn btn-info">
        <i class="fas fa-history"></i> View History
    </a>
    <button type="button" class="btn btn-secondary" onclick="initiateLinkCopy()">
        <i class="fas fa-link"></i> Copy Form Link
    </button>
    <div class="btn-group">
        <button type="button" class="btn btn-success dropdown-toggle" data-bs-toggle="dropdown">
            <i class="fas fa-download"></i> Export
        </button>
        <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="{{ route('training.export', $event->id) }}"><i class="fas fa-file-pdf"></i> Export as PDF</a></li>
            <li><a class="dropdown-item" href="{{ route('training.export.excel', $event->id) }}"><i class="fas fa-file-excel"></i> Export as Excel</a></li>
        </ul>
    </div>
</div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>ID Number</th>
                                    <th>Days Attended</th>
                                    <th>Total Amount</th>
                                    <th>Last Signature</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($event->attendances->groupBy('id_number') as $idNumber => $attendances)
                                    @php
                                        $latest = $attendances->first();
                                        $isBanned = \Vanguard\BannedAttendee::where('id_number', $idNumber)->exists();
                                    @endphp
                                    <tr>
                                        <td>{{ $latest->name }}</td>
                                        <td>{{ $idNumber }}</td>
                                        <td>{{ $latest->days_attended }}</td>
                                        <td>{{ number_format($latest->total_amount, 2) }}</td>
                                        <td><img src="{{ $latest->signature }}" alt="signature" style="max-width: 100px; height: auto;"></td>
                                        <td>
                                            @if($isBanned)
                                                <span class="badge bg-danger">Banned</span>
                                            @elseif($latest->completed)
                                                <span class="badge bg-success">Completed</span>
                                            @else
                                                <span class="badge bg-info">In Progress</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($isBanned)
                                                <form action="{{ route('training.unban-attendee', $idNumber) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to unban this attendee?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-warning"><i class="fas fa-user-check"></i></button>
                                                </form>
                                            @else
                                                <button type="button" class="btn btn-sm btn-danger ban-attendee" data-bs-toggle="modal" data-bs-target="#banModal" data-id-number="{{ $idNumber }}" data-name="{{ $latest->name }}">
                                                    <i class="fas fa-user-slash"></i>
                                                </button>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="7" class="text-center">No attendees yet.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card w-100 mb-4">
                <div class="card-header">Event Details</div>
                <div class="card-body">
                    <dl>
                        <dt>Venue</dt>
                        <dd>{{ $event->venue_name ?? 'Not set' }}</dd>

                        <dt>County</dt>
                        <dd>{{ $event->county->name ?? 'Not set' }}</dd>

                        <dt>Start Date</dt>
                        <dd>{{ $event->start_date ? $event->start_date->format('M d, Y H:i') : 'Not set' }}</dd>

                        <dt>End Date</dt>
                        <dd>{{ $event->end_date ? $event->end_date->format('M d, Y H:i') : 'Not set' }}</dd>

                        <dt>Form Expires</dt>
                        <dd>{{ $event->form_expires_at ? $event->form_expires_at->format('M d, Y H:i') : 'Not set' }}</dd>

                        <dt>Status</dt>
                        <dd>
                            @if($event->isExpired())
                                <span class="badge bg-danger">Expired</span>
                            @else
                                <span class="badge bg-success">Active</span>
                            @endif
                        </dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Location restriction modal -->
<div class="modal fade" id="locationModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Set Location Restrictions</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-info"><i class="fas fa-info-circle"></i> This will create a location-restricted link that only works within 30 meters of your current location. Make sure you are at the venue before proceeding.</div>
                <div id="location-status" class="alert alert-warning d-none">Getting your location...</div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="getLocationAndGenerateLink()">Generate Restricted Link</button>
            </div>
        </div>
    </div>
</div>

<!-- Ban Attendee Modal -->
<div class="modal fade" id="banModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="banForm" action="{{ route('training.ban-attendee') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Ban Attendee</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>You are about to ban <strong id="banAttendeeName"></strong></p>
                    <input type="hidden" name="id_number" id="banAttendeeId">
                    <div class="form-group">
    <label for="reason">Reason for Ban</label>
    <textarea 
        class="form-control @error('reason') is-invalid @enderror" 
        id="reason" 
        name="reason" 
        rows="3" 
        required
    >{{ old('reason') }}</textarea>
    @error('reason')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Ban Attendee</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
function initiateLinkCopy() {
    const eventId = {{ $event->id }};
    generateAndCopyLink(eventId);
}

async function generateAndCopyLink(eventId) {
    try {
        const response = await fetch(`/training/${eventId}/generate-restricted-link`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        });

        if (!response.ok) {
            throw new Error('Failed to generate link');
        }

        const data = await response.json();
        
        if (data.success) {
            await navigator.clipboard.writeText(data.link);
            
            // Show success message
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: 'Link copied to clipboard',
                timer: 2000,
                showConfirmButton: false
            });
            
            // Also add a visible alert
            const alertDiv = document.createElement('div');
            alertDiv.className = 'alert alert-success alert-dismissible fade show';
            alertDiv.innerHTML = `
                <strong>Success!</strong> Link has been copied to clipboard.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            `;
            document.querySelector('.container').insertBefore(alertDiv, document.querySelector('.container').firstChild);
            
            // Auto dismiss alert after 3 seconds
            setTimeout(() => {
                const alert = bootstrap.Alert.getOrCreateInstance(alertDiv);
                alert.close();
            }, 3000);
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

document.addEventListener('DOMContentLoaded', function() {
    // Initialize tooltips
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Handle export button dropdown
    const dropdownElementList = [].slice.call(document.querySelectorAll('.dropdown-toggle'));
    dropdownElementList.map(function (dropdownToggleEl) {
        return new bootstrap.Dropdown(dropdownToggleEl);
    });

    // Add confirmation for unban actions
    document.querySelectorAll('form[action*="unban-attendee"]').forEach(form => {
        form.addEventListener('submit', function(e) {
            if (!confirm('Are you sure you want to unban this attendee?')) {
                e.preventDefault();
            }
        });
    });

    const banModal = document.getElementById('banModal');
    if (banModal) {
        const banButtons = document.querySelectorAll('.ban-attendee');
        banButtons.forEach(button => {
            button.addEventListener('click', function() {
                const idNumber = this.getAttribute('data-id-number');
                const name = this.getAttribute('data-name');
                
                document.getElementById('banAttendeeId').value = idNumber;
                document.getElementById('banAttendeeName').textContent = name;
            });
        });
    }

    // Handle ban form submission
    const banForm = document.getElementById('banForm');
    if (banForm) {
        banForm.addEventListener('submit', function(e) {
            const reasonInput = this.querySelector('textarea[name="reason"]');
            if (!reasonInput.value.trim()) {
                e.preventDefault();
                alert('Please provide a reason for banning this attendee.');
                reasonInput.focus();
            }
        });
    }

    // Automatically hide alerts after 5 seconds
    setTimeout(() => {
        document.querySelectorAll('.alert:not(.alert-danger)').forEach(alert => {
            const bsAlert = bootstrap.Alert.getOrCreateInstance(alert);
            bsAlert.close();
        });
    }, 5000);
});
</script>
@endsection
