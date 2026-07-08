@extends('layouts.app')

@section('page-title', 'Edit Meeting')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-8">
            <h2><i class="fas fa-calendar-edit me-2"></i>Edit Meeting</h2>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('meetings.show', $meeting->id) }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i>Back to Meeting
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('meetings.update', $meeting->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <!-- Basic Information -->
                        <h5 class="mb-3">Basic Information</h5>
                        
                        <div class="mb-3">
                            <label for="title" class="form-label">Meeting Title <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror"
                                id="title" name="title" value="{{ old('title', $meeting->title) }}" required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description / Agenda</label>
                            <textarea class="form-control @error('description') is-invalid @enderror"
                                id="description" name="description" rows="4">{{ old('description', $meeting->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Meeting Status -->
                        <div class="mb-3">
                            <label for="status" class="form-label">Meeting Status <span class="text-danger">*</span></label>
                            <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                                <option value="Draft" {{ old('status', $meeting->status) == 'Draft' ? 'selected' : '' }}>Draft</option>
                                <option value="Scheduled" {{ old('status', $meeting->status) == 'Scheduled' ? 'selected' : '' }}>Scheduled</option>
                                <option value="In Progress" {{ old('status', $meeting->status) == 'In Progress' ? 'selected' : '' }}>In Progress</option>
                                <option value="Completed" {{ old('status', $meeting->status) == 'Completed' ? 'selected' : '' }}>Completed</option>
                                <option value="Archived" {{ old('status', $meeting->status) == 'Archived' ? 'selected' : '' }}>Archived</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Meeting Type Selection -->
                        <h5 class="mb-3 mt-4">Meeting Type</h5>
                        
                        <div class="mb-3">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="meeting_type" id="type_physical"
                                    value="Physical" {{ old('meeting_type', $meeting->meeting_type) == 'Physical' ? 'checked' : '' }}>
                                <label class="form-check-label" for="type_physical">
                                    <i class="fas fa-building me-2"></i>Physical
                                </label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="meeting_type" id="type_virtual"
                                    value="Virtual" {{ old('meeting_type', $meeting->meeting_type) == 'Virtual' ? 'checked' : '' }}>
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
                                    id="meeting_date" name="meeting_date" 
                                    value="{{ old('meeting_date', $meeting->meeting_date->format('Y-m-d')) }}" required>
                                @error('meeting_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="start_time" class="form-label">Start Time <span class="text-danger">*</span></label>
                                <input type="time" class="form-control @error('start_time') is-invalid @enderror"
                                    id="start_time" name="start_time" value="{{ old('start_time', $meeting->start_time) }}" required>
                                @error('start_time')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="end_time" class="form-label">End Time <span class="text-danger">*</span></label>
                                <input type="time" class="form-control @error('end_time') is-invalid @enderror"
                                    id="end_time" name="end_time" value="{{ old('end_time', $meeting->end_time) }}" required>
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
                                    id="venue_name" name="venue_name" value="{{ old('venue_name', $meeting->venue_name) }}">
                                @error('venue_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="address" class="form-label">Address</label>
                                <textarea class="form-control @error('address') is-invalid @enderror"
                                    id="address" name="address" rows="2">{{ old('address', $meeting->address) }}</textarea>
                                @error('address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="room_number" class="form-label">Room Number (Optional)</label>
                                    <input type="text" class="form-control" id="room_number" name="room_number"
                                        value="{{ old('room_number', $meeting->room_number) }}">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="location_map_link" class="form-label">Location Map Link</label>
                                    <input type="url" class="form-control @error('location_map_link') is-invalid @enderror"
                                        id="location_map_link" name="location_map_link" value="{{ old('location_map_link', $meeting->location_map_link) }}">
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
                                    id="meeting_link" name="meeting_link" value="{{ old('meeting_link', $meeting->meeting_link) }}"
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
                                    <option value="Zoom" {{ old('meeting_platform', $meeting->meeting_platform) == 'Zoom' ? 'selected' : '' }}>Zoom</option>
                                    <option value="Google Meet" {{ old('meeting_platform', $meeting->meeting_platform) == 'Google Meet' ? 'selected' : '' }}>Google Meet</option>
                                    <option value="Teams" {{ old('meeting_platform', $meeting->meeting_platform) == 'Teams' ? 'selected' : '' }}>Microsoft Teams</option>
                                    <option value="Other" {{ old('meeting_platform', $meeting->meeting_platform) == 'Other' ? 'selected' : '' }}>Other</option>
                                </select>
                                @error('meeting_platform')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Update Meeting
                            </button>
                            <a href="{{ route('meetings.show', $meeting->id) }}" class="btn btn-secondary">
                                <i class="fas fa-times me-2"></i>Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts-head')
<script>
document.addEventListener('DOMContentLoaded', function() {
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
    document.querySelector('input[name="meeting_type"]:checked').dispatchEvent(new Event('change'));
});
</script>
@endsection
