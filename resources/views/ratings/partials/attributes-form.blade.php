<div class="row mt-4">
    <div class="col-12">
        <h5 class="mb-3">Rateable Attributes</h5>
        <div id="attributes-container">
            @if(isset($item) && $item->attributes->count() > 0)
                @foreach($item->attributes as $index => $attribute)
                    <div class="attribute-row mb-3 border rounded p-3 position-relative">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Attribute Name</label>
                                    <input type="text" 
                                           class="form-control @error('attributes.'.$index.'.name') is-invalid @enderror" 
                                           name="attributes[{{$index}}][name]" 
                                           value="{{ old('attributes.'.$index.'.name', $attribute->name) }}" 
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
                                           value="{{ old('attributes.'.$index.'.description', $attribute->description) }}">
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

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const container = document.getElementById('attributes-container');
    const addBtn = document.getElementById('add-attribute');
    let attributeCount = container.children.length;

    function createAttributeRow(index) {
        return `
            <div class="attribute-row mb-3 border rounded p-3 position-relative">
                <button type="button" class="btn btn-danger btn-sm position-absolute top-0 end-0 m-2 remove-attribute">
                    <i class="fas fa-times"></i>
                </button>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">Attribute Name</label>
                            <input type="text" 
                                   class="form-control" 
                                   name="attributes[${index}][name]" 
                                   required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">Description (Optional)</label>
                            <input type="text" 
                                   class="form-control" 
                                   name="attributes[${index}][description]">
                        </div>
                    </div>
                </div>
            </div>
        `;
    }

    addBtn.addEventListener('click', function() {
        container.insertAdjacentHTML('beforeend', createAttributeRow(attributeCount));
        attributeCount++;
    });

    container.addEventListener('click', function(e) {
        if (e.target.closest('.remove-attribute')) {
            e.target.closest('.attribute-row').remove();
        }
    });

    // Add initial attribute row if none exist
    if (attributeCount === 0) {
        container.insertAdjacentHTML('beforeend', createAttributeRow(0));
        attributeCount++;
    }
});
</script>
@endpush