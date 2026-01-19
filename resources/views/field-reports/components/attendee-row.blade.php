
<div class="attendee-container border rounded p-3 mb-3" data-index="{{ $index }}">
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
                   class="form-control @error('attendees.' . $index . '.name') is-invalid @enderror" 
                   name="attendees[{{ $index }}][name]" 
                   value="{{ old('attendees.' . $index . '.name', $attendee->name ?? '') }}"
                   required>
            @error('attendees.' . $index . '.name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-md-6 mb-3">
            <label class="form-label">Organization</label>
            <input type="text" 
                   class="form-control @error('attendees.' . $index . '.organization') is-invalid @enderror" 
                   name="attendees[{{ $index }}][organization]" 
                   value="{{ old('attendees.' . $index . '.organization', $attendee->organization ?? '') }}">
            @error('attendees.' . $index . '.organization')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 mb-3">
            <label class="form-label">Role</label>
            <input type="text" 
                   class="form-control @error('attendees.' . $index . '.role') is-invalid @enderror" 
                   name="attendees[{{ $index }}][role]" 
                   value="{{ old('attendees.' . $index . '.role', $attendee->role ?? '') }}">
            @error('attendees.' . $index . '.role')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-md-6 mb-3">
            <label class="form-label">Email</label>
            <input type="email" 
                   class="form-control @error('attendees.' . $index . '.email') is-invalid @enderror" 
                   name="attendees[{{ $index }}][email]" 
                   value="{{ old('attendees.' . $index . '.email', $attendee->email ?? '') }}">
            @error('attendees.' . $index . '.email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 mb-3">
            <label class="form-label">Contact Number</label>
            <input type="text" 
                   class="form-control @error('attendees.' . $index . '.contact_number') is-invalid @enderror" 
                   name="attendees[{{ $index }}][contact_number]" 
                   value="{{ old('attendees.' . $index . '.contact_number', $attendee->contact_number ?? '') }}">
            @error('attendees.' . $index . '.contact_number')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-md-6 mb-3">
            <label class="form-label">Comments</label>
            <textarea class="form-control @error('attendees.' . $index . '.comments') is-invalid @enderror" 
                      name="attendees[{{ $index }}][comments]" 
                      rows="1">{{ old('attendees.' . $index . '.comments', $attendee->comments ?? '') }}</textarea>
            @error('attendees.' . $index . '.comments')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
</div>
