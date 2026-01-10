@extends('layouts.app')

@section('page-title', __('Create Rateable Item'))
@section('page-heading', __('Create Rateable Item'))

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            @include('partials.messages')
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('ratings.store') }}" method="POST" id="rateableItemForm">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="title" class="form-label">Title</label>
                                    <input type="text" 
                                           class="form-control @error('title') is-invalid @enderror" 
                                           id="title" 
                                           name="title" 
                                           value="{{ old('title') }}" 
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
                                            rows="4">{{ old('description') }}</textarea>
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
                                           value="{{ old('expires_at') }}">
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
                                           {{ old('status', true) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="status">Active</label>
                                </div>
                            </div>
                        </div>

                        <!-- Attributes Section -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <h5 class="mb-3">Rateable Attributes</h5>
                                <div id="attributes-container">
                                    @if(old('attributes'))
                                        @foreach(old('attributes') as $index => $attribute)
                                            <div class="attribute-row mb-3 border rounded p-3 position-relative">
                                                <button type="button" class="btn btn-danger btn-sm position-absolute top-0 end-0 m-2 remove-attribute">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="form-label">Attribute Name</label>
                                                            <input type="text" 
                                                                   class="form-control @error('attributes.'.$index.'.name') is-invalid @enderror" 
                                                                   name="attributes[{{$index}}][name]" 
                                                                   value="{{ $attribute['name'] }}" 
                                                                   required>
                                                            @error('attributes.'.$index.'.name')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="form-label">Description (Optional)</label>
                                                            <input type="text" 
                                                                   class="form-control @error('attributes.'.$index.'.description') is-invalid @enderror" 
                                                                   name="attributes[{{$index}}][description]" 
                                                                   value="{{ $attribute['description'] ?? '' }}">
                                                            @error('attributes.'.$index.'.description')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                                <button type="button" class="btn btn-secondary" id="add-attribute">
                                    <i class="fas fa-plus"></i> Add Attribute
                                </button>
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="float-end">
                                   
                                    <div class="d-flex justify-content-between">
                                    <a href="{{ route('ratings.index') }}" class="btn btn-secondary">
                                        <i class="fas fa-arrow-left"></i> Back
                                    </a>
    <button type="submit" class="btn btn-primary">
        <i class="fas fa-save"></i> Create Rateable Item
    </button>
</div>

                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const container = document.getElementById('attributes-container');
    const addBtn = document.getElementById('add-attribute');
    const form = document.getElementById('rateableItemForm');
    let attributeCount = container.children.length;

    // Add initial attribute row if none exist
    if (attributeCount === 0) {
        addNewAttribute();
    }

    addBtn.addEventListener('click', addNewAttribute);

    container.addEventListener('click', function(e) {
        if (e.target.closest('.remove-attribute')) {
            const row = e.target.closest('.attribute-row');
            if (container.children.length > 1) {
                row.remove();
                reindexAttributes();
            } else {
                Swal.fire({
                    icon: 'warning',
                    title: 'Required Field',
                    text: 'At least one attribute is required'
                });
            }
        }
    });

    function addNewAttribute() {
        container.insertAdjacentHTML('beforeend', createAttributeRow(attributeCount));
        attributeCount++;
    }

    function createAttributeRow(index) {
        return `
            <div class="attribute-row mb-3 border rounded p-3 position-relative">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">Attribute Name</label>
                            <input type="text" 
                                   class="form-control attribute-name" 
                                   name="attributes[${index}][name]" 
                                   required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">Description (Optional)</label>
                            <input type="text" 
                                   class="form-control attribute-description" 
                                   name="attributes[${index}][description]">
                        </div>
                    </div>
                </div>
            </div>
        `;
    }

    function reindexAttributes() {
        const rows = container.getElementsByClassName('attribute-row');
        Array.from(rows).forEach((row, index) => {
            const nameInput = row.querySelector('.attribute-name');
            const descInput = row.querySelector('.attribute-description');
            
            nameInput.name = `attributes[${index}][name]`;
            descInput.name = `attributes[${index}][description]`;
        });
    }

    // Form validation
    form.addEventListener('submit', async function(e) {
        e.preventDefault(); // Temporarily prevent form submission for validation

        // Validation checks
        if (container.children.length === 0) {
            Swal.fire({
                icon: 'error',
                title: 'Validation Error',
                text: 'At least one attribute is required'
            });
            return;
        }

        // Check if all required fields are filled
        const requiredFields = form.querySelectorAll('[required]');
        let isValid = true;

        requiredFields.forEach(field => {
            if (!field.value.trim()) {
                field.classList.add('is-invalid');
                isValid = false;
            } else {
                field.classList.remove('is-invalid');
            }
        });

        if (!isValid) {
            Swal.fire({
                icon: 'error',
                title: 'Validation Error',
                text: 'Please fill in all required fields'
            });
            return;
        }

        // If validation passes, submit the form
        try {
            const submitButton = form.querySelector('button[type="submit"]');
            submitButton.disabled = true; // Prevent double submission
            
            form.submit(); // Actually submit the form
        } catch (error) {
            console.error('Form submission error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Submission Error',
                text: 'There was an error submitting the form. Please try again.'
            });
        }
    });
});
</script>
@endpush
@push('styles')
<style>
.attribute-row {
    transition: all 0.3s ease;
}

.is-invalid {
    border-color: #dc3545;
}

.form-control:focus {
    box-shadow: none;
    border-color: #80bdff;
}

.btn:disabled {
    cursor: not-allowed;
    opacity: 0.65;
}
</style>

@endpush
@endsection