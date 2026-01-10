@extends('layouts.public')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-white py-3">
                    <h4 class="mb-0">{{ $item->title }}</h4>
                    @if($item->description)
                        <p class="text-muted mb-0 mt-2">{{ $item->description }}</p>
                    @endif
                </div>

                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form action="{{ route('ratings.submit', $item->slug) }}" method="POST" id="rating-form">
                        @csrf
                        
                        <div class="attributes-rating mb-4">
                            @foreach($item->attributes as $attribute)
                                <div class="attribute-rating mb-4">
                                    <label class="form-label d-block mb-3">
                                        <strong>{{ $attribute->name }}</strong>
                                        @if($attribute->description)
                                            <small class="d-block text-muted">{{ $attribute->description }}</small>
                                        @endif
                                    </label>
                                    
                                    <div class="star-rating-container">
                                        <div class="star-rating">
                                            @for($i = 1; $i <= 5; $i++)
                                                <div class="rating-item" data-rating="{{ $i }} star{{ $i > 1 ? 's' : '' }}">
                                                    <input type="radio"
                                                           id="attr_{{ $attribute->id }}_star{{ $i }}"
                                                           name="attributes[{{ $attribute->id }}]"
                                                           value="{{ $i }}"
                                                           required
                                                           {{ old("attributes.{$attribute->id}") == $i ? 'checked' : '' }}>
                                                    <label for="attr_{{ $attribute->id }}_star{{ $i }}">
                                                        <i class="far fa-star"></i>
                                                    </label>
                                                </div>
                                            @endfor
                                        </div>
                                    </div>
                                    @error("attributes.{$attribute->id}")
                                        <div class="text-danger mt-2 small">{{ $message }}</div>
                                    @enderror
                                </div>
                                @if(!$loop->last)<hr>@endif
                            @endforeach
                        </div>

                        <div class="mb-4">
                            <label for="comment" class="form-label">Additional Comments (Optional)</label>
                            <textarea class="form-control @error('comment') is-invalid @enderror"
                                      id="comment"
                                      name="comment"
                                      rows="4"
                                      placeholder="Share your thoughts...">{{ old('comment') }}</textarea>
                            @error('comment')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="rater_name" class="form-label">Your Name</label>
                                <input type="text"
                                       class="form-control @error('rater_name') is-invalid @enderror"
                                       id="rater_name"
                                       name="rater_name"
                                       value="{{ old('rater_name') }}"
                                       required>
                                @error('rater_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="rater_email" class="form-label">Your Email</label>
                                <input type="email"
                                       class="form-control @error('rater_email') is-invalid @enderror"
                                       id="rater_email"
                                       name="rater_email"
                                       value="{{ old('rater_email') }}"
                                       placeholder="email@example.com"
                                       required>
                                @error('rater_email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="location" class="form-label">Area/Location</label>
                            <input type="text"
                                   class="form-control @error('location') is-invalid @enderror"
                                   id="location"
                                   name="location"
                                   value="{{ old('location') }}"
                                   placeholder="Enter your area or location"
                                   required>
                            @error('location')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-star me-2"></i>Submit Rating
                            </button>
                            <p class="text-center text-muted small mt-2">
                                Your email will not be shared publicly
                            </p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<style>
.star-rating-container {
    padding: 20px 0;
}

.star-rating {
    display: flex;
    justify-content: flex-start;
    align-items: center;
    gap: 40px;
    padding-left: 20px;
}

.rating-item {
    position: relative;
}

.rating-item label {
    cursor: pointer;
}

.rating-item i {
    font-size: 2rem;
    color: #ddd;
    transition: color 0.2s ease;
}

/* Hide radio buttons */
.rating-item input {
    display: none;
}

/* Hover tooltip */
.rating-item::after {
    content: attr(data-rating);
    position: absolute;
    top: -55px;
    left: 50%;
    transform: translateX(-50%);
    opacity: 0;
    background: rgba(0, 0, 0, 0.7);
    color: white;
    padding: 4px 8px;
    border-radius: 4px;
    font-size: 0.875rem;
    pointer-events: none;
    transition: opacity 0.2s ease;
}

.rating-item:hover::after {
    opacity: 1;
}

/* Only fill the currently hovered star */
.star-rating .rating-item:hover i {
    color: #000;
}

/* Keep selected star filled */
.star-rating .rating-item input:checked + label i {
    color: #000;
}

@media (max-width: 768px) {
    .star-rating {
        gap: 25px;
    }
    
    .rating-item i {
        font-size: 1.75rem;
    }
}
</style>
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('rating-form');
    const attributes = document.querySelectorAll('.attribute-rating');
    
    // Form validation
    form.addEventListener('submit', function(e) {
        let isValid = true;
        attributes.forEach(attr => {
            const radios = attr.querySelectorAll('input[type="radio"]:checked');
            if (radios.length === 0) {
                isValid = false;
                const name = attr.querySelector('strong').textContent;
                Swal.fire({
                    icon: 'warning',
                    title: 'Rating Required',
                    text: `Please rate "${name}" before submitting`
                });
                e.preventDefault();
                return false;
            }
        });
    });

    // Auto-hide alerts after 5 seconds
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        }, 5000);
    });
});
</script>
@endpush
@endsection