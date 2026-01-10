<div class="row">
    <div class="col-md-6">
        <div class="form-group mb-3">
            <label for="title" class="form-label">Title</label>
            <input type="text" 
                   class="form-control @error('title') is-invalid @enderror" 
                   id="title" 
                   name="title" 
                   value="{{ old('title', $item->title ?? '') }}" 
                   required>
            @error('title')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea class="form-control @error('description') is-invalid @enderror" 
                    id="description" 
                    name="description" 
                    rows="4">{{ old('description', $item->description ?? '') }}</textarea>
            @error('description')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group mb-3">
            <label for="expires_at" class="form-label">Expiration Date (Optional)</label>
            <input type="datetime-local" 
                   class="form-control @error('expires_at') is-invalid @enderror" 
                   id="expires_at" 
                   name="expires_at" 
                   value="{{ old('expires_at', isset($item) ? $item->expires_at?->format('Y-m-d\TH:i') : '') }}">
            @error('expires_at')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-check form-switch mb-3">
            <input type="checkbox" 
                   class="form-check-input" 
                   id="status" 
                   name="status" 
                   value="1" 
                   {{ old('status', $item->status ?? true) ? 'checked' : '' }}>
            <label class="form-check-label" for="status">Active</label>
        </div>
    </div>
</div>