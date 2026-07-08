@extends('layouts.app')

@section('page-title', __('Regions'))
@section('page-heading', __('Manage Regions'))

@section('content')
<div class="container-fluid">
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="card-title mb-0">Add New Region</h5>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('regions.store') }}">
                @csrf
                <div class="row g-2 align-items-end">
                    <div class="col-md-6">
                        <label class="form-label">Region Name</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Assign Counties</label>
                        <select name="counties[]" class="form-control counties-select" multiple="multiple" style="width: 100%">
                            <option></option>
                            @foreach($counties as $county)
                                <option value="{{ $county->id }}">{{ $county->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-success">Add Region</button>
                    </div>
                </div>
            </form>

            @push('scripts')
            <script>
                $(document).ready(function() {
                    $('.counties-select').select2({
                        placeholder: 'Select counties',
                        allowClear: true,
                        width: '100%'
                    });
                });
            </script>
            @endpush
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">Regions List</h5>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Counties</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($regions as $region)
                        <tr>
                            <td>{{ $region->name }}</td>
                            <td>
                                @foreach($region->counties as $county)
                                    <span class="badge bg-primary">{{ $county->name }}</span>
                                @endforeach
                            </td>
                            <td>
                                <form method="POST" action="{{ route('regions.destroy', $region->id) }}" style="display:inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Delete this region?')">Delete</button>
                                </form>
                                <a href="{{ route('regions.edit', $region->id) }}" class="btn btn-primary btn-sm">Edit</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@section('scripts-head')
    @stack('scripts')
@endsection
