@extends('layouts.app')

@section('page-title', __('Edit Region'))
@section('page-heading', __('Edit Region'))

@section('content')
<div class="container-fluid">
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="card-title mb-0">Edit Region</h5>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('regions.update', $region->id) }}">
                @csrf
                @method('PUT')
                <div class="row g-2 align-items-end">
                    <div class="col-md-6">
                        <label class="form-label">Region Name</label>
                        <input type="text" name="name" class="form-control" value="{{ $region->name }}" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Assign Counties</label>
                        <select name="counties[]" class="form-select counties-select" multiple>
                            @foreach($counties as $county)
                                <option value="{{ $county->id }}" {{ $region->counties->contains($county->id) ? 'selected' : '' }}>{{ $county->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-success">Update Region</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('.counties-select').select2({
            placeholder: 'Select counties',
            width: '100%'
        });
    });
</script>
@endpush

@section('scripts-head')
    @stack('scripts')
@endsection
