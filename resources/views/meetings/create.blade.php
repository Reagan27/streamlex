@extends('layouts.app')

@section('page-title', 'Create Meeting')

@section('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endsection

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-8">
            <h2><i class="fas fa-calendar-plus me-2"></i>Create Meeting</h2>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('meetings.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i>Back to Meetings
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('meetings.store') }}" method="POST">
                        @csrf

                        <!-- Basic Information -->
                        <h5 class="mb-3">Basic Information</h5>
                        
                        <div class="mb-3">
                            <label for="title" class="form-label">Meeting Title <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror"
                                id="title" name="title" value="{{ old('title') }}" required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description / Agenda</label>
                            <textarea class="form-control @error('description') is-invalid @enderror"
                                id="description" name="description" rows="4">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Meeting Type Selection -->
                        <h5 class="mb-3 mt-4">Meeting Type</h5>
                        
                        <div class="mb-3">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="meeting_type" id="type_physical"
                                    value="Physical" {{ old('meeting_type') == 'Physical' ? 'checked' : '' }}>
                                <label class="form-check-label" for="type_physical">
                                    <i class="fas fa-building me-2"></i>Physical
                                </label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="meeting_type" id="type_virtual"
                                    value="Virtual" {{ old('meeting_type') == 'Virtual' || !old('meeting_type') ? 'checked' : '' }}>
                                <label class="form-check-label" for="type_virtual">
                                    <i class="fas fa-video me-2"></i>Virtual
                                </label>
                            </div>
                        </div>

                        <!-- Date and Time -->
                        <h5 class="mb-3 mt-4">Schedule</h5>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="meeting_date" class="form-label">Meeting Date <span class="text-danger">*</span></label>
                                <input type="date" class="form-control @error('meeting_date') is-invalid @enderror"
                                    id="meeting_date" name="meeting_date" value="{{ old('meeting_date') }}" required>
                                @error('meeting_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="start_time" class="form-label">Start Time <span class="text-danger">*</span></label>
                                <input type="time" class="form-control @error('start_time') is-invalid @enderror"
                                    id="start_time" name="start_time" value="{{ old('start_time') }}" required>
                                @error('start_time')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="end_time" class="form-label">End Time <span class="text-danger">*</span></label>
                                <input type="time" class="form-control @error('end_time') is-invalid @enderror"
                                    id="end_time" name="end_time" value="{{ old('end_time') }}" required>
                                @error('end_time')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Physical Meeting Fields -->
                        <div id="physical-fields" style="display:none;">
                            <h5 class="mb-3 mt-4">Venue Details</h5>
                            
                            <div class="mb-3">
                                <label for="venue_name" class="form-label">Venue Name</label>
                                <input type="text" class="form-control @error('venue_name') is-invalid @enderror"
                                    id="venue_name" name="venue_name" value="{{ old('venue_name') }}">
                                @error('venue_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="address" class="form-label">Address</label>
                                <textarea class="form-control @error('address') is-invalid @enderror"
                                    id="address" name="address" rows="2">{{ old('address') }}</textarea>
                                @error('address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="room_number" class="form-label">Room Number (Optional)</label>
                                    <input type="text" class="form-control" id="room_number" name="room_number"
                                        value="{{ old('room_number') }}">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="location_map_link" class="form-label">Location Map Link</label>
                                    <input type="url" class="form-control @error('location_map_link') is-invalid @enderror"
                                        id="location_map_link" name="location_map_link" value="{{ old('location_map_link') }}">
                                    @error('location_map_link')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Virtual Meeting Fields -->
                        <div id="virtual-fields">
                            <h5 class="mb-3 mt-4">Virtual Meeting Details</h5>
                            
                            <div class="mb-3">
                                <label for="meeting_link" class="form-label">Meeting Link</label>
                                <input type="url" class="form-control @error('meeting_link') is-invalid @enderror"
                                    id="meeting_link" name="meeting_link" value="{{ old('meeting_link') }}"
                                    placeholder="https://zoom.us/j/...">
                                @error('meeting_link')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="meeting_platform" class="form-label">Meeting Platform</label>
                                <select class="form-select @error('meeting_platform') is-invalid @enderror"
                                    id="meeting_platform" name="meeting_platform">
                                    <option value="">Select a platform</option>
                                    <option value="Zoom" {{ old('meeting_platform') == 'Zoom' ? 'selected' : '' }}>Zoom</option>
                                    <option value="Google Meet" {{ old('meeting_platform') == 'Google Meet' ? 'selected' : '' }}>Google Meet</option>
                                    <option value="Teams" {{ old('meeting_platform') == 'Teams' ? 'selected' : '' }}>Microsoft Teams</option>
                                    <option value="Other" {{ old('meeting_platform') == 'Other' ? 'selected' : '' }}>Other</option>
                                </select>
                                @error('meeting_platform')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Participants -->
                        <h5 class="mb-3 mt-4">Add Participants</h5>
                        
                        <div id="participants-section">
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle me-2"></i>
                                You will be added as Chairperson automatically. Add other participants below.
                            </div>

                            <div id="participants-list">
                                <!-- Participant rows will be added here dynamically -->
                            </div>

                            <button type="button" class="btn btn-sm btn-outline-primary" id="add-participant-btn">
                                <i class="fas fa-plus me-2"></i>Add Participant
                            </button>
                        </div>

                        <!-- Submit Button -->
                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Create Meeting
                            </button>
                            <a href="{{ route('meetings.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times me-2"></i>Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Sidebar Info -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header bg-light">
                    <h6 class="mb-0"><i class="fas fa-lightbulb me-2"></i>Tips</h6>
                </div>
                <div class="card-body small">
                    <div class="mb-3">
                        <strong>Meeting Types:</strong>
                        <p class="mb-2">Choose between Physical (in-person) or Virtual (online) meetings.</p>
                    </div>
                    <div class="mb-3">
                        <strong>Participants:</strong>
                        <p class="mb-2">Add team members who will attend. You can specify their roles.</p>
                    </div>
                    <div>
                        <strong>Roles Available:</strong>
                        <ul class="ps-3 mb-0">
                            @foreach ($roles as $roleLabel)
                                <li>{{ $roleLabel }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts-head')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    let participantCount = 0;

    // Toggle between physical and virtual fields
    document.querySelectorAll('input[name="meeting_type"]').forEach(radio => {
        radio.addEventListener('change', function() {
            const physicalFields = document.getElementById('physical-fields');
            const virtualFields = document.getElementById('virtual-fields');
            
            if (this.value === 'Physical') {
                physicalFields.style.display = 'block';
                virtualFields.style.display = 'none';
                document.getElementById('venue_name').required = true;
                document.getElementById('address').required = true;
                document.getElementById('meeting_link').required = false;
                document.getElementById('meeting_platform').required = false;
            } else {
                physicalFields.style.display = 'none';
                virtualFields.style.display = 'block';
                document.getElementById('venue_name').required = false;
                document.getElementById('address').required = false;
                document.getElementById('meeting_link').required = true;
                document.getElementById('meeting_platform').required = true;
            }
        });
    });

    // Trigger initial state
    document.getElementById('type_virtual').checked ? 
        document.getElementById('type_virtual').dispatchEvent(new Event('change')) :
        document.getElementById('type_physical').dispatchEvent(new Event('change'));

    const participantRoles = @json($roles);
    const oldParticipants = @json(old('participants', []));
    const oldParticipantRoles = @json(old('participant_roles', []));

    // Add participant button
    document.getElementById('add-participant-btn').addEventListener('click', function() {
        addParticipantRow();
    });

    function addParticipantRow(selectedUserId = '', selectedRole = '') {
        const participantsList = document.getElementById('participants-list');
        const row = document.createElement('div');
        row.className = 'row mb-3 participant-row';

        const roleOptions = Object.entries(participantRoles).map(([value, label]) => `
            <option value="${value}" ${selectedRole === value ? 'selected' : ''}>${label}</option>
        `).join('');

        row.innerHTML = `
            <div class="col-md-6">
                <select class="form-select participant-user" name="participants[]" required>
                    <option value="">Select a participant</option>
                    @foreach ($users as $userId => $userName)
                        @if ($userId != auth()->id())
                            <option value="{{ $userId }}">{{ $userName }}</option>
                        @endif
                    @endforeach
                </select>
            </div>
            <div class="col-md-5">
                <select class="form-select" name="participant_roles[]" required>
                    ${roleOptions}
                </select>
            </div>
            <div class="col-md-1">
                <button type="button" class="btn btn-sm btn-outline-danger remove-participant" style="width: 100%;">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        `;
        participantsList.appendChild(row);
        participantCount++;

        if (selectedUserId) {
            row.querySelector('.participant-user').value = selectedUserId;
        }

        // Initialize select2
        $(row.querySelector('.participant-user')).select2({
            width: '100%'
        });

        // Remove button
        row.querySelector('.remove-participant').addEventListener('click', function() {
            row.remove();
            participantCount--;
        });
    }

    if (oldParticipants.length) {
        oldParticipants.forEach((userId, index) => {
            addParticipantRow(userId, oldParticipantRoles[index] || '');
        });
    }

    // Initialize select2 for initial participant selects
    $('.participant-user').select2({
        width: '100%'
    });
});
</script>
@endsection
