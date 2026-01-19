@extends('layouts.public')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">{{ $event->name }} - {{ $event->venue_name }}</h4>
                </div>
                <div class="card-body">
                    {{-- Add hidden inputs for venue coordinates --}}
                    <input type="hidden" id="venue_lat" value="{{ $venueLocation['latitude'] ?? '' }}">
                    <input type="hidden" id="venue_lng" value="{{ $venueLocation['longitude'] ?? '' }}">
                    <input type="hidden" id="csrf_token" value="{{ csrf_token() }}">

                    <div class="alert alert-warning mb-4">
                        <h5 class="alert-heading">
                            <i class="fas fa-map-marker-alt"></i> Location Verification Required
                        </h5>
                        <p class="mb-0">You must be at the venue to register attendance</p>
                    </div>

                    <div id="location-check-section" class="text-center">
                        <p class="mb-4">Please verify your location to access the attendance form</p>
                        <div class="d-grid gap-2 col-md-6 mx-auto">
                            <button type="button" class="btn btn-primary btn-lg" onclick="checkLocation()">
                                <i class="fas fa-location-arrow me-2"></i> Verify My Location
                            </button>
                        </div>
                    </div>

                    <div id="location-status" class="alert alert-info mt-4 d-none">
                        <div class="d-flex align-items-center justify-content-center">
                            <div class="spinner-border spinner-border-sm me-2" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <span>Verifying your location...</span>
                        </div>
                    </div>

                    <div id="error-message" class="alert alert-danger mt-4 d-none"></div>
                    
                    <div id="debug-info" class="small text-muted mt-3"></div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
async function checkLocation() {
    const statusDiv = document.getElementById('location-status');
    const errorDiv = document.getElementById('error-message');
    const checkSection = document.getElementById('location-check-section');
    const debugInfo = document.getElementById('debug-info');

    // Get venue coordinates
    const venueLat = document.getElementById('venue_lat');
    const venueLng = document.getElementById('venue_lng');
    
    // Validate venue coordinates exist
    if (!venueLat?.value || !venueLng?.value) {
        errorDiv.innerHTML = `
            <div class="alert alert-danger">
                <h5>Configuration Error</h5>
                <p>Venue location not properly configured. Please contact support.</p>
            </div>
        `;
        errorDiv.classList.remove('d-none');
        return;
    }

    statusDiv.classList.remove('d-none');
    statusDiv.innerHTML = '<div class="text-center">Getting your current location...</div>';
    errorDiv.classList.add('d-none');
    
    try {
        const position = await new Promise((resolve, reject) => {
            navigator.geolocation.getCurrentPosition(
                pos => resolve(pos),
                reject,
                {
                    enableHighAccuracy: true,
                    timeout: 10000,
                    maximumAge: 0
                }
            );
        });

        // Show accuracy warning if poor GPS signal
        if (position.coords.accuracy > 100) {
            debugInfo.innerHTML = `
                <div class="alert alert-warning">
                    <h6>GPS Signal Warning</h6>
                    <p>Current GPS accuracy: ±${Math.round(position.coords.accuracy)}m</p>
                    <p>Poor GPS signal may affect location verification. Try moving to an open area.</p>
                </div>
            `;
        }

        statusDiv.innerHTML = '<div class="text-center">Verifying location...</div>';

        const response = await fetch('{{ route("training.verify-location", $event->slug) }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.getElementById('csrf_token').value
            },
            body: JSON.stringify({
                latitude: position.coords.latitude,
                longitude: position.coords.longitude,
                accuracy: position.coords.accuracy,
                venue_lat: venueLat.value,
                venue_lng: venueLng.value
            })
        });

        const data = await response.json();
        
        if (data.success) {
            window.location.href = data.redirect_url;
            return;
        }

        errorDiv.innerHTML = `
            <div class="alert alert-info">
                <h5>Location Check Result</h5>
                <p>${data.message}</p>
                ${data.distance ? `<p>Distance from venue: ${Math.round(data.distance)}m</p>` : ''}
            </div>
        `;
        errorDiv.classList.remove('d-none');

    } catch (error) {
        console.error('Location Error:', error);
        
        let message = 'Location verification failed. ';
        if (error.code === 1) {
            message = 'Please enable location access in your browser settings.';
        } else if (error.code === 2) {
            message = 'Unable to get an accurate location. Please ensure you have a clear view of the sky.';
        } else if (error.code === 3) {
            message = 'Location request timed out. Please check your GPS signal and try again.';
        }

        errorDiv.innerHTML = `
            <div class="alert alert-warning">
                <h5>Location Error</h5>
                <p>${message}</p>
            </div>
        `;
        errorDiv.classList.remove('d-none');
    } finally {
        statusDiv.classList.add('d-none');
        checkSection.classList.remove('d-none');
    }
}

// Wait for page to fully load before checking location
window.addEventListener('load', () => setTimeout(checkLocation, 1000));
</script>
@endpush
@endsection