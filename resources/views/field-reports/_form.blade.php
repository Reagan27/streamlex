{{-- views/field-reports/_form.blade.php --}}
<div class="row">
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">Basic Information</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label for="title" class="form-label">Title <span class="text-danger">*</span></label>
                    <input type="text" 
                           class="form-control @error('title') is-invalid @enderror" 
                           id="title" 
                           name="title" 
                           value="{{ old('title', $report->title ?? '') }}" 
                           required>
                    @error('title')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="location" class="form-label">Location <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control @error('location') is-invalid @enderror" 
                                   id="location" 
                                   name="location" 
                                   value="{{ old('location', $report->location ?? '') }}" 
                                   required>
                            @error('location')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="report_date" class="form-label">Date <span class="text-danger">*</span></label>
                            <input type="date" 
                                   class="form-control @error('report_date') is-invalid @enderror" 
                                   id="report_date" 
                                   name="report_date" 
                                   value="{{ old('report_date', $report->report_date?->format('Y-m-d') ?? '') }}" 
                                   required>
                            @error('report_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="start_time" class="form-label">Start Time <span class="text-danger">*</span></label>
                            <input type="time" 
                                   class="form-control @error('start_time') is-invalid @enderror" 
                                   id="start_time" 
                                   name="start_time" 
                                   value="{{ old('start_time', $report->start_time?->format('H:i') ?? '') }}" 
                                   required>
                            @error('start_time')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="end_time" class="form-label">End Time <span class="text-danger">*</span></label>
                            <input type="time" 
                                   class="form-control @error('end_time') is-invalid @enderror" 
                                   id="end_time" 
                                   name="end_time" 
                                   value="{{ old('end_time', $report->end_time?->format('H:i') ?? '') }}" 
                                   required>
                            @error('end_time')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                        {{-- Continuing views/field-reports/_form.blade.php --}}
                            <label for="weather_conditions" class="form-label">Weather Conditions <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control @error('weather_conditions') is-invalid @enderror" 
                                   id="weather_conditions" 
                                   name="weather_conditions" 
                                   value="{{ old('weather_conditions', $report->weather_conditions ?? '') }}" 
                                   required>
                            @error('weather_conditions')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="number_of_attendees" class="form-label">Number of Attendees <span class="text-danger">*</span></label>
                            <input type="number" 
                                   class="form-control @error('number_of_attendees') is-invalid @enderror" 
                                   id="number_of_attendees" 
                                   name="number_of_attendees" 
                                   value="{{ old('number_of_attendees', $report->number_of_attendees ?? '') }}" 
                                   min="0" 
                                   required>
                            @error('number_of_attendees')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="temperature" class="form-label">Temperature (°C)</label>
                            <input type="number" 
                                   class="form-control @error('temperature') is-invalid @enderror" 
                                   id="temperature" 
                                   name="temperature" 
                                   value="{{ old('temperature', $report->temperature ?? '') }}" 
                                   step="0.1">
                            @error('temperature')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="humidity" class="form-label">Humidity (%)</label>
                            <input type="number" 
                                   class="form-control @error('humidity') is-invalid @enderror" 
                                   id="humidity" 
                                   name="humidity" 
                                   value="{{ old('humidity', $report->humidity ?? '') }}" 
                                   min="0" 
                                   max="100" 
                                   step="0.1">
                            @error('humidity')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="summary" class="form-label">Summary <span class="text-danger">*</span></label>
                    <textarea class="form-control @error('summary') is-invalid @enderror" 
                              id="summary" 
                              name="summary" 
                              rows="4" 
                              required>{{ old('summary', $report->summary ?? '') }}</textarea>
                    @error('summary')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="objectives" class="form-label">Objectives <span class="text-danger">*</span></label>
                    <textarea class="form-control @error('objectives') is-invalid @enderror" 
                              id="objectives" 
                              name="objectives" 
                              rows="4" 
                              required>{{ old('objectives', $report->objectives ?? '') }}</textarea>
                    @error('objectives')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="challenges_faced" class="form-label">Challenges Faced</label>
                    <textarea class="form-control @error('challenges_faced') is-invalid @enderror" 
                              id="challenges_faced" 
                              name="challenges_faced" 
                              rows="4">{{ old('challenges_faced', $report->challenges_faced ?? '') }}</textarea>
                    @error('challenges_faced')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="recommendations" class="form-label">Recommendations</label>
                    <textarea class="form-control @error('recommendations') is-invalid @enderror" 
                              id="recommendations" 
                              name="recommendations" 
                              rows="4">{{ old('recommendations', $report->recommendations ?? '') }}</textarea>
                    @error('recommendations')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Activities Section -->
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Activities</h5>
                <button type="button" class="btn btn-sm btn-primary" onclick="addActivity()">
                    <i class="fas fa-plus"></i> Add Activity
                </button>
            </div>
            <div class="card-body">
                <div id="activities-container">
                    @if(old('activities'))
                        @foreach(old('activities') as $index => $activity)
                            @include('field-reports.components.activity-row', ['index' => $index, 'activity' => $activity])
                        @endforeach
                    @elseif(isset($report))
                        @foreach($report->activities as $index => $activity)
                            @include('field-reports.components.activity-row', ['index' => $index, 'activity' => $activity])
                        @endforeach
                    @endif
                </div>
            </div>
        </div>

        <!-- Attendees Section -->
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Attendees</h5>
                <button type="button" class="btn btn-sm btn-primary" onclick="addAttendee()">
                    <i class="fas fa-plus"></i> Add Attendee
                </button>
            </div>
            <div class="card-body">
                <div id="attendees-container">
                    @if(old('attendees'))
                        @foreach(old('attendees') as $index => $attendee)
                            @include('field-reports.components.attendee-row', ['index' => $index, 'attendee' => $attendee])
                        @endforeach
                    @elseif(isset($report))
                        @foreach($report->attendees as $index => $attendee)
                            @include('field-reports.components.attendee-row', ['index' => $index, 'attendee' => $attendee])
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <!-- Location Map -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">Location</h5>
            </div>
            <div class="card-body">
                <div id="map" style="height: 300px;"></div>
                <input type="hidden" name="latitude" id="latitude" value="{{ old('latitude', $report->latitude ?? '') }}">
                <input type="hidden" name="longitude" id="longitude" value="{{ old('longitude', $report->longitude ?? '') }}">
            </div>
        </div>

        <!-- Photo Upload -->
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Photos</h5>
                <button type="button" class="btn btn-sm btn-primary" onclick="triggerPhotoUpload()">
                    <i class="fas fa-camera"></i> Add Photos
                </button>
            </div>
            <div class="card-body">
                <input type="file" 
                       id="photo-upload" 
                       class="d-none" 
                       accept="image/*" 
                       multiple 
                       onchange="handlePhotoUpload(this)">
                
                <div id="photo-previews" class="row g-2">
                    @if(isset($report))
                        @foreach($report->photos as $photo)
                            @include('field-reports.components.photo-preview', ['photo' => $photo])
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google.maps_api_key') }}&libraries=places"></script>
<script>
let map, marker;
let activities = {{ isset($report) ? $report->activities->count() : 0 }};
let attendees = {{ isset($report) ? $report->attendees->count() : 0 }};

// Initialize map
function initMap() {
    const defaultLocation = { 
        lat: {{ old('latitude', $report->latitude ?? -1.2921) }}, 
        lng: {{ old('longitude', $report->longitude ?? 36.8219) }} 
    };

    map = new google.maps.Map(document.getElementById('map'), {
        center: defaultLocation,
        zoom: 13
    });

    marker = new google.maps.Marker({
        position: defaultLocation,
        map: map,
        draggable: true
    });

    // Update coordinates when marker is dragged
    google.maps.event.addListener(marker, 'dragend', function() {
        const position = marker.getPosition();
        document.getElementById('latitude').value = position.lat();
        document.getElementById('longitude').value = position.lng();
    });

    // Initialize location search
    const input = document.getElementById('location');
    const searchBox = new google.maps.places.SearchBox(input);

    map.addListener('bounds_changed', function() {
        searchBox.setBounds(map.getBounds());
    });

    searchBox.addListener('places_changed', function() {
        const places = searchBox.getPlaces();

        if (places.length === 0) return;

        const place = places[0];
        
        if (!place.geometry) return;

        // Update map and marker
        if (place.geometry.viewport) {
            map.fitBounds(place.geometry.viewport);
        } else {
            map.setCenter(place.geometry.location);
            map.setZoom(17);
        }

        marker.setPosition(place.geometry.location);

        // Update hidden inputs
        document.getElementById('latitude').value = place.geometry.location.lat();
        document.getElementById('longitude').value = place.geometry.location.lng();
    });
}

// Initialize map when page loads
document.addEventListener('DOMContentLoaded', initMap);

// Activities management
function addActivity() {
    const template = document.querySelector('#activity-template').content.cloneNode(true);
    template.querySelector('.activity-container').dataset.index = activities;
    
    // Update all name attributes with the current index
    template.querySelectorAll('[name]').forEach(input => {
        input.name = input.name.replace('[INDEX]', `[${activities}]`);
    });

    document.querySelector('#activities-container').appendChild(template);
    activities++;
}

function removeActivity(btn) {
    btn.closest('.activity-container').remove();
}

// Attendees management
function addAttendee() {
    const template = document.querySelector('#attendee-template').content.cloneNode(true);
    template.querySelector('.attendee-container').dataset.index = attendees;
    
    template.querySelectorAll('[name]').forEach(input => {
        input.name = input.name.replace('[INDEX]', `[${attendees}]`);
    });

    document.querySelector('#attendees-container').appendChild(template);
    attendees++;
}

function removeAttendee(btn) {
    btn.closest('.attendee-container').remove();
}

// Photo upload handling
function triggerPhotoUpload() {
    document.getElementById('photo-upload').click();
}

function handlePhotoUpload(input) {
    const container = document.getElementById('photo-previews');
    const files = input.files;

    for (let i = 0; i < files.length; i++) {
        const reader = new FileReader();
        const file = files[i];

        reader.onload = function(e) {
            const preview = document.createElement('div');
            preview.className = 'col-6 mb-2';
            preview.innerHTML = `
                <div class="position-relative">
                    <img src="${e.target.result}" class="img-thumbnail" style="height: 150px; width: 100%; object-fit: cover;">
                    <input type="hidden" name="photos[${i}][file]" value="${file}">
                    <div class="input-group mt-1">
                        <input type="text" 
                               class="form-control form-control-sm" 
                               name="photos[${i}][caption]" 
                               placeholder="Caption">
                        <button type="button" 
                                class="btn btn-sm btn-outline-danger"
                                onclick="this.parentElement.parentElement.parentElement.remove()">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
            `;
            container.appendChild(preview);
        };

        reader.readAsDataURL(file);
    }
}
</script>
@endpush

{{-- Activity template for dynamic addition --}}
<template id="activity-template">
    <div class="activity-container border rounded p-3 mb-3" data-index="INDEX">
        <div class="d-flex justify-content-between mb-3">
            <h6 class="mb-0">Activity Details</h6>
            <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeActivity(this)">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Activity Type <span class="text-danger">*</span></label>
                <input type="text" 
                       class="form-control" 
                       name="activities[INDEX][activity_type]" 
                       required>
            </div>
            <div class="col-md-3 mb-3">
                <label class="form-label">Start Time <span class="text-danger">*</span></label>
                <input type="time" 
                       class="form-control" 
                       name="activities[INDEX][start_time]" 
                       required>
            </div>
            <div class="col-md-3 mb-3">
                <label class="form-label">End Time <span class="text-danger">*</span></label>
                <input type="time" 
                       class="form-control" 
                       name="activities[INDEX][end_time]" 
                       required>
            </div>
        </div>
        <div class="mb-3">
            <label class="form-label">Description <span class="text-danger">*</span></label>
            <textarea class="form-control" 
                      name="activities[INDEX][description]" 
                      rows="3" 
                      required></textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Outcomes</label>
            <textarea class="form-control" 
                      name="activities[INDEX][outcomes]" 
                      rows="2"></textarea>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Resources Used</label>
                <textarea class="form-control" 
                          name="activities[INDEX][resources_used]" 
                          rows="2"></textarea>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Challenges</label>
                <textarea class="form-control" 
                          name="activities[INDEX][challenges]" 
                          rows="2"></textarea>
            </div>
        </div>
    </div>
</template>

{{-- Attendee template for dynamic addition --}}
<template id="attendee-template">
    <div class="attendee-container border rounded p-3 mb-3" data-index="INDEX">
        <div class="d-flex justify-content-between mb-3">
            <h6 class="mb-0">Attendee Details</h6>
            <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeAttendee(this)">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Name <span class="text-danger">*</span></label>
                <input type="text" 
                       class="form-control" 
                       name="attendees[INDEX][name]" 
                       required>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Organization</label>
                <input type="text" 
                       class="form-control" 
                       name="attendees[INDEX][organization]">
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Role</label>
                <input type="text" 
                       class="form-control" 
                       name="attendees[INDEX][role]">
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Email</label>
                <input type="email" 
                       class="form-control" 
                       name="attendees[INDEX][email]">
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Contact Number</label>
                <input type="text" 
                       class="form-control" 
                       name="attendees[INDEX][contact_number]">
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Comments</label>
                <textarea class="form-control" 
                          name="attendees[INDEX][comments]" 
                          rows="1"></textarea>
            </div>
        </div>
    </div>
</template>
