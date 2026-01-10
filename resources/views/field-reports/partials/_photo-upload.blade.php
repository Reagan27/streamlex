
<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">Photo Documentation</h5>
    </div>
    <div class="card-body">
        <div class="upload-area mb-3 p-4 border rounded text-center {{ $errors->has('photos.*') ? 'border-danger' : '' }}"
             id="dropzone-upload"
             ondrop="handleDrop(event)"
             ondragover="handleDragOver(event)"
             ondragleave="handleDragLeave(event)">
            <i class="fas fa-cloud-upload-alt fa-2x mb-2"></i>
            <p class="mb-2">Drag photos here or</p>
            <button type="button" class="btn btn-primary btn-sm" onclick="document.getElementById('photo-input').click()">
                <i class="fas fa-plus me-1"></i>Browse Files
            </button>
            <input type="file" 
                   id="photo-input" 
                   class="d-none" 
                   accept="image/*" 
                   multiple 
                   onchange="handleFiles(this.files)">
            <p class="small text-muted mt-2 mb-0">
                Maximum 10 photos, each up to 5MB<br>
                Supported formats: JPG, PNG, GIF
            </p>
        </div>

        @error('photos.*')
            <div class="alert alert-danger">{{ $message }}</div>
        @enderror

        <div id="photo-preview-container" class="row g-2">
            @isset($report)
                @foreach($report->photos as $photo)
                    @include('field-reports.components.photo-preview', ['photo' => $photo, 'showDelete' => true])
                @endforeach
            @endisset
        </div>
    </div>
</div>