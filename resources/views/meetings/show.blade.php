@extends('layouts.app')

@section('page-title', $meeting->title)

@section('styles')
<style>
    .nav-tabs .nav-link {
        color: #495057;
        border: none;
        border-bottom: 3px solid transparent;
    }
    .nav-tabs .nav-link.active {
        color: #0c63e4;
        border-bottom: 3px solid #0c63e4;
        background: none;
    }
    .participant-badge {
        display: inline-block;
        padding: 0.35rem 0.65rem;
        border-radius: 0.25rem;
        font-size: 0.85rem;
        margin-right: 0.5rem;
        margin-bottom: 0.5rem;
    }
    .action-item-card {
        border-left: 4px solid #0c63e4;
    }
    .action-item-card.completed {
        border-left-color: #28a745;
        opacity: 0.7;
    }
    .action-item-card.overdue {
        border-left-color: #dc3545;
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-md-8">
            <h2>{{ $meeting->title }}</h2>
            <div class="small text-muted">
                <span class="badge bg-{{ $meeting->status == 'Completed' ? 'success' : ($meeting->status == 'Scheduled' ? 'info' : ($meeting->status == 'In Progress' ? 'warning' : 'secondary')) }}">
                    {{ $meeting->status }}
                </span>
                <span class="badge bg-light text-dark ms-2">{{ $meeting->meeting_type }}</span>
            </div>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('meetings.edit', $meeting->id) }}" class="btn btn-secondary">
                <i class="fas fa-edit me-2"></i>Edit
            </a>
            <a href="{{ route('meetings.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>Back
            </a>
        </div>
    </div>

    <!-- Meeting Info Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="small text-muted">Date & Time</div>
                    <div class="fw-bold">{{ $meeting->meeting_date->format('M d, Y') }}</div>
                    <div class="small">{{ $meeting->start_time }} - {{ $meeting->end_time }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="small text-muted">Location</div>
                    @if ($meeting->meeting_type == 'Physical')
                        <div class="fw-bold">{{ $meeting->venue_name }}</div>
                        <div class="small">{{ $meeting->address }}</div>
                    @else
                        <div class="fw-bold">{{ $meeting->meeting_platform }}</div>
                        @if ($meeting->meeting_link)
                            <a href="{{ $meeting->meeting_link }}" target="_blank" class="small">Join Meeting</a>
                        @endif
                    @endif
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="small text-muted">Participants</div>
                    <div class="fw-bold">{{ $meeting->participants->count() }}</div>
                    <div class="small">{{ $meeting->participants->where('attendance_status', 'Attended')->count() }} Attended</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="small text-muted">Organizer</div>
                    <div class="fw-bold">{{ $meeting->organizer->name }}</div>
                    <div class="small text-muted">{{ $meeting->created_at->format('M d, Y') }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabs -->
    <div class="card">
        <div class="card-body">
            <ul class="nav nav-tabs" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" data-bs-toggle="tab" href="#participants" role="tab">
                        <i class="fas fa-users me-2"></i>Participants
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="tab" href="#documents" role="tab">
                        <i class="fas fa-file-alt me-2"></i>Documents
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="tab" href="#actions" role="tab">
                        <i class="fas fa-tasks me-2"></i>Action Items
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="tab" href="#details" role="tab">
                        <i class="fas fa-info-circle me-2"></i>Details
                    </a>
                </li>
            </ul>

            <div class="tab-content pt-4">
                <!-- Participants Tab -->
                <div id="participants" class="tab-pane fade show active" role="tabpanel">
                    <div class="row mb-3">
                        <div class="col-md-8">
                            <h6>Meeting Participants</h6>
                        </div>
                        <div class="col-md-4 text-end">
                            <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addParticipantModal">
                                <i class="fas fa-plus me-2"></i>Add Participant
                            </button>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Name</th>
                                    <th>Role</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($meeting->participants as $participant)
                                    <tr>
                                        <td>
                                            <strong>{{ $participant->user->name }}</strong>
                                            <br>
                                            <small class="text-muted">{{ $participant->user->email }}</small>
                                        </td>
                                        <td>
                                            <span class="badge bg-info">{{ $participant->role }}</span>
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ $participant->attendance_status == 'Attended' ? 'success' : ($participant->attendance_status == 'Confirmed' ? 'info' : ($participant->attendance_status == 'Declined' ? 'danger' : 'warning')) }}">
                                                {{ $participant->attendance_status }}
                                            </span>
                                        </td>
                                        <td>
                                            <button class="btn btn-sm btn-outline-secondary update-status-btn"
                                                data-participant-id="{{ $participant->id }}"
                                                data-bs-toggle="modal"
                                                data-bs-target="#updateStatusModal">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-sm btn-outline-danger remove-participant-btn"
                                                data-participant-id="{{ $participant->id }}">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Documents Tab -->
                <div id="documents" class="tab-pane fade" role="tabpanel">
                    <div class="row mb-3">
                        <div class="col-md-8">
                            <h6>Meeting Documents</h6>
                        </div>
                        <div class="col-md-4 text-end">
                            <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#uploadDocumentModal">
                                <i class="fas fa-upload me-2"></i>Upload Document
                            </button>
                        </div>
                    </div>

                    @if ($meeting->documents->count() > 0)
                        <div class="row">
                            @foreach ($meeting->documents as $document)
                                <div class="col-md-6 mb-3">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between align-items-start">
                                                <div>
                                                    <h6 class="mb-1">{{ $document->file_name }}</h6>
                                                    <small class="text-muted">
                                                        <strong>Type:</strong> {{ $document->document_type }}<br>
                                                        <strong>Size:</strong> {{ number_format($document->file_size / 1024, 2) }} KB<br>
                                                        <strong>Uploaded:</strong> {{ $document->created_at->format('M d, Y H:i') }}<br>
                                                        <strong>By:</strong> {{ $document->uploadedBy->name }}
                                                    </small>
                                                </div>
                                                <button class="btn btn-sm btn-outline-danger delete-document-btn"
                                                    data-document-id="{{ $document->id }}">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                            @if ($document->remarks)
                                                <div class="mt-2 pt-2 border-top">
                                                    <small><strong>Remarks:</strong> {{ $document->remarks }}</small>
                                                </div>
                                            @endif
                                            <div class="mt-2">
                                                <a href="{{ asset('storage/' . $document->file_path) }}" target="_blank" class="btn btn-sm btn-primary">
                                                    <i class="fas fa-download me-1"></i>Download
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>No documents uploaded yet.
                        </div>
                    @endif
                </div>

                <!-- Action Items Tab -->
                <div id="actions" class="tab-pane fade" role="tabpanel">
                    <div class="row mb-3">
                        <div class="col-md-8">
                            <h6>Action Items</h6>
                        </div>
                        <div class="col-md-4 text-end">
                            <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addActionModal">
                                <i class="fas fa-plus me-2"></i>Add Action Item
                            </button>
                        </div>
                    </div>

                    @if ($openActions->count() > 0)
                        <h6 class="mb-3">Open Actions</h6>
                        @foreach ($openActions as $action)
                            <div class="card action-item-card mb-3">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-8">
                                            <h6 class="mb-1">{{ $action->title }}</h6>
                                            @if ($action->description)
                                                <p class="text-muted small mb-2">{{ $action->description }}</p>
                                            @endif
                                            <div class="small">
                                                <strong>Assigned to:</strong> {{ $action->assignedTo->name }}<br>
                                                <strong>Due Date:</strong> {{ $action->due_date->format('M d, Y') }}
                                            </div>
                                        </div>
                                        <div class="col-md-4 text-end">
                                            <span class="badge bg-warning mb-2">{{ $action->status }}</span><br>
                                            <button class="btn btn-sm btn-outline-secondary edit-action-btn"
                                                data-action-id="{{ $action->id }}"
                                                data-bs-toggle="modal"
                                                data-bs-target="#editActionModal">
                                                <i class="fas fa-edit me-1"></i>Edit
                                            </button>
                                            <button class="btn btn-sm btn-outline-danger delete-action-btn"
                                                data-action-id="{{ $action->id }}">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endif

                    @if ($completedActions->count() > 0)
                        <h6 class="mb-3 mt-4">Completed Actions</h6>
                        @foreach ($completedActions as $action)
                            <div class="card action-item-card completed mb-3">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-8">
                                            <h6 class="mb-1"><s>{{ $action->title }}</s></h6>
                                            <div class="small text-muted">
                                                <strong>Assigned to:</strong> {{ $action->assignedTo->name }}<br>
                                                <strong>Completed:</strong> {{ $action->updated_at->format('M d, Y') }}
                                            </div>
                                        </div>
                                        <div class="col-md-4 text-end">
                                            <span class="badge bg-success">{{ $action->status }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endif

                    @if ($meeting->actions->count() == 0)
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>No action items yet.
                        </div>
                    @endif
                </div>

                <!-- Details Tab -->
                <div id="details" class="tab-pane fade" role="tabpanel">
                    <div class="row">
                        <div class="col-md-6">
                            <h6>Meeting Information</h6>
                            <table class="table table-sm">
                                <tr>
                                    <td><strong>Title:</strong></td>
                                    <td>{{ $meeting->title }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Type:</strong></td>
                                    <td>{{ $meeting->meeting_type }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Status:</strong></td>
                                    <td>{{ $meeting->status }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Date:</strong></td>
                                    <td>{{ $meeting->meeting_date->format('M d, Y H:i') }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Duration:</strong></td>
                                    <td>{{ $meeting->start_time }} - {{ $meeting->end_time }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h6>Additional Details</h6>
                            <table class="table table-sm">
                                <tr>
                                    <td><strong>Organizer:</strong></td>
                                    <td>{{ $meeting->organizer->name }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Created:</strong></td>
                                    <td>{{ $meeting->created_at->format('M d, Y H:i') }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Updated:</strong></td>
                                    <td>{{ $meeting->updated_at->format('M d, Y H:i') }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Total Participants:</strong></td>
                                    <td>{{ $meeting->participants->count() }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Total Documents:</strong></td>
                                    <td>{{ $meeting->documents->count() }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    @if ($meeting->description)
                        <div class="mt-4">
                            <h6>Description</h6>
                            <p>{{ $meeting->description }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modals -->

<!-- Add Participant Modal -->
<div class="modal fade" id="addParticipantModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Participant</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="addParticipantForm">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="participantSelect" class="form-label">Select User</label>
                        <select class="form-select" id="participantSelect" name="user_id" required>
                            <option value="">Choose a participant...</option>
                            @foreach ($users as $user)
                                @if (!$meeting->participants->where('user_id', $user->id)->first())
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="participantRole" class="form-label">Role</label>
                        <select class="form-select" id="participantRole" name="role" required>
                            @foreach ($roles as $roleValue => $roleLabel)
                                <option value="{{ $roleValue }}">{{ $roleLabel }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add Participant</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Upload Document Modal -->
<div class="modal fade" id="uploadDocumentModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Upload Document</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="uploadDocumentForm" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="documentType" class="form-label">Document Type</label>
                        <select class="form-select" id="documentType" name="document_type" required>
                            <option value="Minutes">Minutes</option>
                            <option value="Audio">Audio</option>
                            <option value="Video">Video</option>
                            <option value="Attendance">Attendance</option>
                            <option value="Transcript">Transcript</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="documentFile" class="form-label">File</label>
                        <input type="file" class="form-control" id="documentFile" name="file" required>
                        <small class="text-muted">Max size: 100MB</small>
                    </div>
                    <div class="mb-3">
                        <label for="documentRemarks" class="form-label">Remarks (Optional)</label>
                        <textarea class="form-control" id="documentRemarks" name="remarks" rows="2"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Upload</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Add Action Modal -->
<div class="modal fade" id="addActionModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Action Item</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="addActionForm">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="actionTitle" class="form-label">Title</label>
                        <input type="text" class="form-control" id="actionTitle" name="title" required>
                    </div>
                    <div class="mb-3">
                        <label for="actionDescription" class="form-label">Description</label>
                        <textarea class="form-control" id="actionDescription" name="description" rows="2"></textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="actionAssignedTo" class="form-label">Assign To</label>
                            <select class="form-select" id="actionAssignedTo" name="assigned_to" required>
                                <option value="">Select a person...</option>
                                @foreach ($meeting->participants as $participant)
                                    <option value="{{ $participant->user_id }}">{{ $participant->user->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="actionDueDate" class="form-label">Due Date</label>
                            <input type="date" class="form-control" id="actionDueDate" name="due_date" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add Action</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@section('scripts-head')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const meetingId = {{ $meeting->id }};

    // Add participant form submission
    document.getElementById('addParticipantForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        
        fetch(`/meetings/${meetingId}/participants`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.success);
                location.reload();
            } else {
                alert(data.error || 'Error adding participant');
            }
        })
        .catch(error => alert('Error: ' + error));
    });

    // Remove participant button
    document.querySelectorAll('.remove-participant-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const participantId = this.dataset.participantId;
            if (confirm('Are you sure?')) {
                fetch(`/meetings/${meetingId}/participants/${participantId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert(data.error || 'Error removing participant');
                    }
                });
            }
        });
    });

    // Upload document form submission
    document.getElementById('uploadDocumentForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        
        fetch(`/meetings/${meetingId}/documents`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.success);
                location.reload();
            } else {
                alert(data.error || 'Error uploading document');
            }
        })
        .catch(error => alert('Error: ' + error));
    });

    // Delete document button
    document.querySelectorAll('.delete-document-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const documentId = this.dataset.documentId;
            if (confirm('Are you sure?')) {
                fetch(`/meetings/${meetingId}/documents/${documentId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert(data.error || 'Error deleting document');
                    }
                });
            }
        });
    });

    // Add action form submission
    document.getElementById('addActionForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        
        fetch(`/meetings/${meetingId}/actions`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(Object.fromEntries(formData))
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.success);
                location.reload();
            } else {
                alert(data.error || 'Error adding action');
            }
        })
        .catch(error => alert('Error: ' + error));
    });

    // Delete action button
    document.querySelectorAll('.delete-action-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const actionId = this.dataset.actionId;
            if (confirm('Are you sure?')) {
                fetch(`/meetings/${meetingId}/actions/${actionId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert(data.error || 'Error deleting action');
                    }
                });
            }
        });
    });
});
</script>
@endsection
