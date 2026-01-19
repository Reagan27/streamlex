
<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">Location</h5>
    </div>
    <div class="card-body">
        <div id="location-map" style="height: 300px;"></div>
        <input type="hidden" name="latitude" id="latitude" value="{{ old('latitude', $report->latitude ?? '') }}">
        <input type="hidden" name="longitude" id="longitude" value="{{ old('longitude', $report->longitude ?? '') }}">
        
        <div class="mt-3">
            <div class="input-group">
                <input type="text" 
                       id="location-search" 
                       class="form-control" 
                       placeholder="Search location...">
                <button type="button" 
                        class="btn btn-outline-secondary" 
                        onclick="getCurrentLocation()">
                    <i class="fas fa-location-arrow"></i>
                </button>
            </div>
            <small class="text-muted">
                Search for a location or click the marker to set coordinates
            </small>
        </div>
    </div>
</div>