
<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">Weather Information</h5>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-12 mb-3">
                <label class="form-label">Weather Conditions <span class="text-danger">*</span></label>
                <select class="form-select @error('weather_conditions') is-invalid @enderror" 
                        name="weather_conditions" 
                        required>
                    <option value="">Select condition...</option>
                    @foreach(['Sunny', 'Partly Cloudy', 'Cloudy', 'Light Rain', 'Heavy Rain', 'Stormy', 'Windy', 'Foggy'] as $condition)
                        <option value="{{ $condition }}" 
                                {{ old('weather_conditions', $report->weather_conditions ?? '') == $condition ? 'selected' : '' }}>
                            {{ $condition }}
                        </option>
                    @endforeach
                </select>
                @error('weather_conditions')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label">Temperature (°C)</label>
                <div class="input-group">
                    <input type="number" 
                           class="form-control @error('temperature') is-invalid @enderror" 
                           name="temperature" 
                           value="{{ old('temperature', $report->temperature ?? '') }}"
                           step="0.1">
                    <span class="input-group-text">°C</span>
                </div>
                @error('temperature')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label">Humidity (%)</label>
                <div class="input-group">
                    <input type="number" 
                           class="form-control @error('humidity') is-invalid @enderror" 
                           name="humidity" 
                           value="{{ old('humidity', $report->humidity ?? '') }}"
                           min="0" 
                           max="100" 
                           step="0.1">
                    <span class="input-group-text">%</span>
                </div>
                @error('humidity')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>
    </div>
</div>
