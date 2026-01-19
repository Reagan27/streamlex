@extends('layouts.onboarding')

@section('content')
    @include('onboarding.steps', ['currentStep' => 'documents'])

    <form action="{{ route('onboarding.store', 'documents') }}" method="POST" enctype="multipart/form-data" id="documentUploadForm">
        @csrf
        <div class="step-content">
            <h3>Document Upload</h3>
            <hr>
            
            {{-- ID Number Field --}}
            <div class="form-group">
                <label for="id_number">ID Number</label>
                <input type="text" class="form-control @error('id_number') is-invalid @enderror" 
                       id="id_number" name="id_number" 
                       value="{{ old('id_number', $user->documents->id_number ?? '') }}">
                @error('id_number')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- ID Photo Upload Field --}}
            <div class="form-group">
                <label for="id_photo">ID Photo (PDF, JPEG, PNG - Max 2MB)</label>
                <div class="custom-file">
                    @if($user->documents && $user->documents->id_photo_path)
                        <div class="mb-2">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="replace_id_photo" 
                                       name="replace_id_photo" value="1">
                                <label class="form-check-label" for="replace_id_photo">
                                    Replace existing ID photo
                                </label>
                            </div>
                            <small class="text-muted">Current file: {{ basename($user->documents->id_photo_path) }}</small>
                        </div>
                        <input type="file" class="form-control-file" 
                               id="id_photo" name="id_photo" style="display: none;">
                    @else
                        <input type="file" class="form-control-file" 
                               id="id_photo" name="id_photo" required>
                    @endif
                </div>
                <div class="invalid-feedback" id="id_photo_error" style="display: none;"></div>
                <div class="progress mt-2" style="display: none;" id="id_photo_progress">
                    <div class="progress-bar" role="progressbar" style="width: 0%"></div>
                </div>
                <small class="text-muted d-block mt-1">Accepted formats: PDF, JPEG, PNG. Maximum size: 2MB</small>
            </div>

            {{-- KRA PIN Field --}}
            <div class="form-group">
                <label for="kra_pin">KRA PIN</label>
                <input type="text" class="form-control @error('kra_pin') is-invalid @enderror" 
                       id="kra_pin" name="kra_pin" 
                       value="{{ old('kra_pin', $user->documents->kra_pin ?? '') }}" maxlength="11">
                @error('kra_pin')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <small class="text-muted">Enter your 11-digit KRA PIN number</small>
            </div>

            {{-- KRA Certificate Upload Field --}}
            <div class="form-group">
                <label for="kra_certificate">KRA Certificate (PDF, JPEG, PNG - Max 2MB)</label>
                <div class="custom-file">
                    @if($user->documents && $user->documents->kra_certificate_path)
                        <div class="mb-2">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="replace_kra_certificate" 
                                       name="replace_kra_certificate" value="1">
                                <label class="form-check-label" for="replace_kra_certificate">
                                    Replace existing KRA certificate
                                </label>
                            </div>
                            <small class="text-muted">Current file: {{ basename($user->documents->kra_certificate_path) }}</small>
                        </div>
                        <input type="file" class="form-control-file" 
                               id="kra_certificate" name="kra_certificate" style="display: none;">
                    @else
                        <input type="file" class="form-control-file" 
                               id="kra_certificate" name="kra_certificate" required>
                    @endif
                </div>
                <div class="invalid-feedback" id="kra_certificate_error" style="display: none;"></div>
                <div class="progress mt-2" style="display: none;" id="kra_certificate_progress">
                    <div class="progress-bar" role="progressbar" style="width: 0%"></div>
                </div>
                <small class="text-muted d-block mt-1">Accepted formats: PDF, JPEG, PNG. Maximum size: 2MB</small>
            </div>

            {{-- Navigation Buttons --}}
            <div class="d-flex justify-content-between mt-3">
                <a href="{{ route('onboarding.navigate', 'banking-details') }}" class="btn btn-secondary">
                    <i class="fa fa-arrow-left"></i> Previous
                </a>
                <button type="submit" class="btn btn-primary" id="submitButton">
                    Next <i class="fa fa-arrow-right"></i>
                </button>
            </div>
        </div>
    </form>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const MAX_FILE_SIZE = 32 * 1024 * 1024; // 32MB in bytes
    let filesValid = true;
    
    // File input elements
    const form = document.getElementById('documentUploadForm');
    const idPhotoInput = document.getElementById('id_photo');
    const kraCertificateInput = document.getElementById('kra_certificate');
    const submitButton = document.getElementById('submitButton');
    
    // Error display elements
    const idPhotoError = document.getElementById('id_photo_error');
    const kraCertificateError = document.getElementById('kra_certificate_error');
    
    function validateFile(file, errorElement) {
        if (!file) return true;
        
        if (file.size > MAX_FILE_SIZE) {
            errorElement.textContent = 'File size exceeds 32MB limit. Please choose a smaller file.';
            errorElement.style.display = 'block';
            return false;
        }
        
        const allowedTypes = ['application/pdf', 'image/jpeg', 'image/png'];
        if (!allowedTypes.includes(file.type)) {
            errorElement.textContent = 'Invalid file type. Please upload a PDF, JPEG, or PNG file.';
            errorElement.style.display = 'block';
            return false;
        }
        
        errorElement.style.display = 'none';
        return true;
    }
    
    function handleFileInput(input, errorElement) {
        input.addEventListener('change', function() {
            const file = this.files[0];
            filesValid = validateFile(file, errorElement);
            submitButton.disabled = !filesValid;
            
            if (filesValid) {
                // Show file name and size
                const fileSize = (file.size / (1024 * 1024)).toFixed(2); // Convert to MB
                const infoText = `Selected: ${file.name} (${fileSize} MB)`;
                const infoElement = document.createElement('small');
                infoElement.className = 'text-muted d-block mt-1';
                infoElement.textContent = infoText;
                
                // Remove any previous info
                const prevInfo = input.parentElement.querySelector('small:not(.text-muted:first-child)');
                if (prevInfo) prevInfo.remove();
                
                input.parentElement.appendChild(infoElement);
            }
        });
    }
    
    // Handle replacement checkboxes
    const replaceIdPhoto = document.getElementById('replace_id_photo');
    if (replaceIdPhoto) {
        replaceIdPhoto.addEventListener('change', function() {
            idPhotoInput.style.display = this.checked ? 'block' : 'none';
            idPhotoInput.required = this.checked;
            if (!this.checked) {
                idPhotoError.style.display = 'none';
                filesValid = true;
                submitButton.disabled = false;
            }
        });
    }
    
    const replaceKraCertificate = document.getElementById('replace_kra_certificate');
    if (replaceKraCertificate) {
        replaceKraCertificate.addEventListener('change', function() {
            kraCertificateInput.style.display = this.checked ? 'block' : 'none';
            kraCertificateInput.required = this.checked;
            if (!this.checked) {
                kraCertificateError.style.display = 'none';
                filesValid = true;
                submitButton.disabled = false;
            }
        });
    }
    
    // Set up file input handlers
    handleFileInput(idPhotoInput, idPhotoError);
    handleFileInput(kraCertificateInput, kraCertificateError);
    
    // KRA PIN validation
    const kraPinInput = document.getElementById('kra_pin');
    kraPinInput.addEventListener('input', function() {
        this.value = this.value.replace(/[^A-Za-z0-9]/g, '').substr(0, 11);
    });
    
    // Form submission handler
    form.addEventListener('submit', function(e) {
        if (!filesValid) {
            e.preventDefault();
            alert('Please correct the file size issues before submitting.');
            return false;
        }
        
        // Disable submit button to prevent double submission
        submitButton.disabled = true;
        submitButton.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Uploading...';
    });
});
</script>
@endsection