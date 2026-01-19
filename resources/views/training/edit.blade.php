@extends('layouts.app')

@section('page-title', __('Edit Training Event'))
@section('page-heading', __('Edit Training Event'))

@section('content')
<div class="card">
    <div class="card-body">
        <div class="d-flex justify-content-between mb-4">
            <h4>Edit Event: {{ $event->name }}</h4>
            <form action="{{ route('training.destroy', $event->id) }}" 
                  method="POST" 
                  class="d-inline"
                  onsubmit="return confirm('Are you sure you want to delete this event?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">
                    <i class="fas fa-trash"></i> Delete Event
                </button>
            </form>
        </div>

        <form action="{{ route('training.update', $event->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label for="name">Event Name</label>
                        <input type="text" 
                               class="form-control @error('name') is-invalid @enderror" 
                               id="name" 
                               name="name" 
                               value="{{ old('name', $event->name) }}" 
                               required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group mb-3">
                        <label for="venue_name">Venue Name</label>
                        <input type="text" 
                               class="form-control @error('venue_name') is-invalid @enderror" 
                               id="venue_name" 
                               name="venue_name" 
                               value="{{ old('venue_name', $event->venue_name) }}" 
                               required>
                        @error('venue_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group mb-3">
                        <label for="county_id">County</label>
                        <select class="form-control @error('county_id') is-invalid @enderror" 
                                id="county_id" 
                                name="county_id" 
                                required>
                            <option value="">Select County</option>
                            @foreach($counties as $id => $name)
                                <option value="{{ $id }}" {{ (old('county_id', $event->county_id) == $id) ? 'selected' : '' }}>
                                    {{ $name }}
                                </option>
                            @endforeach
                        </select>
                        @error('county_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group mb-3">
                        <label for="daily_amount">Daily Amount</label>
                        <input type="number" 
                               class="form-control @error('daily_amount') is-invalid @enderror" 
                               id="daily_amount" 
                               name="daily_amount" 
                               value="{{ old('daily_amount', $event->daily_amount) }}" 
                               step="0.01"
                               required>
                        @error('daily_amount')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label for="start_date">Start Date and Time</label>
                        <input type="datetime-local" 
                               class="form-control @error('start_date') is-invalid @enderror" 
                               id="start_date" 
                               name="start_date" 
                               value="{{ old('start_date', $event->start_date->format('Y-m-d\TH:i')) }}" 
                               required>
                        @error('start_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group mb-3">
                        <label for="end_date">End Date and Time</label>
                        <input type="datetime-local" 
                               class="form-control @error('end_date') is-invalid @enderror" 
                               id="end_date" 
                               name="end_date" 
                               value="{{ old('end_date', $event->end_date->format('Y-m-d\TH:i')) }}" 
                               required>
                        @error('end_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group mb-3">
                        <label for="form_expires_at">Form Expiration Date and Time</label>
                        <input type="datetime-local" 
                               class="form-control @error('form_expires_at') is-invalid @enderror" 
                               id="form_expires_at" 
                               name="form_expires_at" 
                               value="{{ old('form_expires_at', $event->form_expires_at->format('Y-m-d\TH:i')) }}" 
                               required>
                        @error('form_expires_at')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  id="description" 
                                  name="description" 
                                  rows="3">{{ old('description', $event->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="mb-0">Location Settings</h5>
                </div>
                <div class="card-body">
                    <div class="form-check form-switch mb-3">
                        <input type="checkbox" 
                               class="form-check-input" 
                               id="enforce_location"
                               name="enforce_location"
                               value="1"
                               {{ old('enforce_location', $event->enforce_location) ? 'checked' : '' }}>
                        <label class="form-check-label" for="enforce_location">
                            Enable Location Verification
                        </label>
                    </div>
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i> 
                        When enabled, a coordinator must be physically at the venue to generate a location-restricted attendance link.
                        This link will only work for attendees within 30 meters of the venue.
                    </div>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-body bg-light">
                    <h5>Event Information</h5>
                    <p class="text-muted mb-2">
                        The daily amount entered will be multiplied by the number of days 
                        each attendee participates in the event.
                    </p>
                    <p id="total_days_info" class="mb-0"></p>
                </div>
            </div>

            <div class="d-flex justify-content-between mt-3">
                <a href="{{ route('training.index') }}" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary">Update Training Event</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Calculate total days on date change
    const startDateInput = document.getElementById('start_date');
    const endDateInput = document.getElementById('end_date');
    const totalDaysInfo = document.getElementById('total_days_info');

    function calculateTotalDays() {
        const startDate = new Date(startDateInput.value);
        const endDate = new Date(endDateInput.value);

        if (startDate && endDate && endDate >= startDate) {
            const days = Math.ceil((endDate - startDate) / (1000 * 60 * 60 * 24)) + 1;
            totalDaysInfo.textContent = `Total Event Days: ${days} day(s)`;
            totalDaysInfo.className = 'text-success mb-0';
        } else if (endDate < startDate) {
            totalDaysInfo.textContent = 'End date must be after start date';
            totalDaysInfo.className = 'text-danger mb-0';
        } else {
            totalDaysInfo.textContent = '';
        }
    }

    startDateInput.addEventListener('change', calculateTotalDays);
    endDateInput.addEventListener('change', calculateTotalDays);

    // Set min date for form expiration
    startDateInput.addEventListener('change', function() {
        document.getElementById('form_expires_at').min = this.value;
    });

    // Calculate total days on load
    calculateTotalDays();

    // Initialize tooltips
    const tooltips = document.querySelectorAll('[data-bs-toggle="tooltip"]');
    tooltips.forEach(tooltip => new bootstrap.Tooltip(tooltip));
});
</script>
@endpush
@endsection