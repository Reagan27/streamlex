
<div class="col-6 mb-2">
    <div class="position-relative">
        <img src="{{ Storage::url($photo->photo_path) }}" 
             alt="{{ $photo->caption }}"
             class="img-thumbnail"
             style="height: 150px; width: 100%; object-fit: cover;">
        @if(isset($showDelete) && $showDelete)
            <button type="button"
                    class="btn btn-sm btn-danger position-absolute top-0 end-0 m-1"
                    onclick="deletePhoto('{{ $photo->id }}')">
                <i class="fas fa-times"></i>
            </button>
        @endif
        <div class="mt-1">
            <small class="text-muted">{{ $photo->caption }}</small>
        </div>
    </div>
</div>
