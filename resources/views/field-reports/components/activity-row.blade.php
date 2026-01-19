
<div class="activity-container border rounded p-3 mb-3" data-index="{{ $index }}">
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
                   class="form-control @error('activities.' . $index . '.activity_type') is-invalid @enderror" 
                   name="activities[{{ $index }}][activity_type]" 
                   value="{{ old('activities.' . $index . '.activity_type', $activity->activity_type ?? '') }}"
                   required>
            @error('activities.' . $index . '.activity_type')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-md-3 mb-3">
            <label class="form-label">Start Time <span class="text-danger">*</span></label>
            <input type="time" 
                   class="form-control @error('activities.' . $index . '.start_time') is-invalid @enderror" 
                   name="activities[{{ $index }}][start_time]" 
                   value="{{ old('activities.' . $index . '.start_time', isset($activity) ? $activity->start_time->format('H:i') : '') }}"
                   required>
            @error('activities.' . $index . '.start_time')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-md-3 mb-3">
            <label class="form-label">End Time <span class="text-danger">*</span></label>
            <input type="time" 
                   class="form-control @error('activities.' . $index . '.end_time') is-invalid @enderror" 
                   name="activities[{{ $index }}][end_time]" 
                   value="{{ old('activities.' . $index . '.end_time', isset($activity) ? $activity->end_time->format('H:i') : '') }}"
                   required>
            @error('activities.' . $index . '.end_time')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="mb-3">
        <label class="form-label">Description <span class="text-danger">*</span></label>
        <textarea class="form-control @error('activities.' . $index . '.description') is-invalid @enderror" 
                  name="activities[{{ $index }}][description]" 
                  rows="3" 
                  required>{{ old('activities.' . $index . '.description', $activity->description ?? '') }}</textarea>
        @error('activities.' . $index . '.description')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="row">
        <div class="col-md-12 mb-3">
            <label class="form-label">Outcomes</label>
            <textarea class="form-control @error('activities.' . $index . '.outcomes') is-invalid @enderror" 
                      name="activities[{{ $index }}][outcomes]" 
                      rows="2">{{ old('activities.' . $index . '.outcomes', $activity->outcomes ?? '') }}</textarea>
            @error('activities.' . $index . '.outcomes')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-md-6 mb-3">
            <label class="form-label">Resources Used</label>
            <textarea class="form-control @error('activities.' . $index . '.resources_used') is-invalid @enderror" 
                      name="activities[{{ $index }}][resources_used]" 
                      rows="2">{{ old('activities.' . $index . '.resources_used', $activity->resources_used ?? '') }}</textarea>
            @error('activities.' . $index . '.resources_used')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-md-6 mb-3">
            <label class="form-label">Challenges</label>
            <textarea class="form-control @error('activities.' . $index . '.challenges') is-invalid @enderror" 
                      name="activities[{{ $index }}][challenges]" 
                      rows="2">{{ old('activities.' . $index . '.challenges', $activity->challenges ?? '') }}</textarea>
            @error('activities.' . $index . '.challenges')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
</div>