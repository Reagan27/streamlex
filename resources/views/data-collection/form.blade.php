@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0 fw-bold">{{ isset($dataCollection) ? 'Edit' : 'New' }} Data Collection</h4>
        <a href="{{ route('admin.data-collection.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left me-1"></i> Back
        </a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-4">
            <form method="POST" action="{{ isset($dataCollection) ? route('admin.data-collection.update', $dataCollection->slug ?? $dataCollection->id) : route('admin.data-collection.store') }}">
                @csrf
                @if(isset($dataCollection))
                    @method('PUT')
                @endif

                {{-- Validation Errors --}}
                @if($errors->any())
                    <div class="alert alert-danger mb-3">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="form-group mb-3">
                    <label for="title" class="form-label fw-semibold">Title <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('title') is-invalid @enderror"
                           id="title" name="title"
                           value="{{ old('title', $dataCollection->title ?? '') }}" required>
                    @error('title')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group mb-3">
                    <label for="description" class="form-label fw-semibold">Description</label>
                    <textarea class="form-control @error('description') is-invalid @enderror"
                              id="description" name="description" rows="2">{{ old('description', $dataCollection->description ?? '') }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="start_date" class="form-label fw-semibold">Start Date <span class="text-danger">*</span></label>
                            <input type="date" class="form-control @error('start_date') is-invalid @enderror"
                                   id="start_date" name="start_date"
                                   value="{{ old('start_date', isset($dataCollection) ? $dataCollection->start_date->format('Y-m-d') : '') }}" required>
                            @error('start_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="end_date" class="form-label fw-semibold">End Date</label>
                            <input type="date" class="form-control @error('end_date') is-invalid @enderror"
                                   id="end_date" name="end_date"
                                   value="{{ old('end_date', isset($dataCollection) ? $dataCollection->end_date->format('Y-m-d') : '') }}">
                            @error('end_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="form-group mb-3">
                    <label for="project_id" class="form-label fw-semibold">Project <span class="text-danger">*</span></label>
                    <select class="form-select @error('project_id') is-invalid @enderror"
                            id="project_id" name="project_id" required>
                        <option value="">Select a Project</option>
                        @foreach(\Vanguard\Projects::orderBy('name')->get() as $project)
                            <option value="{{ $project->id }}"
                                {{ old('project_id', $dataCollection->project_id ?? '') == $project->id ? 'selected' : '' }}>
                                {{ $project->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('project_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Status Toggle --}}
                <div class="form-group mb-3">
                    <label class="form-label fw-semibold d-block">Status</label>
                    @php $currentStatus = old('status', $dataCollection->status ?? 1); @endphp
                    <input type="hidden" name="status" id="status-hidden" value="{{ $currentStatus ? '1' : '0' }}">
                    <div class="form-check form-switch" style="font-size: 1.1rem;">
                        <input class="form-check-input" type="checkbox" role="switch" id="status-toggle"
                               {{ $currentStatus ? 'checked' : '' }}
                               onchange="document.getElementById('status-hidden').value = this.checked ? '1' : '0';
                                         document.getElementById('status-label').textContent = this.checked ? 'Active' : 'Inactive';">
                        <label class="form-check-label fw-semibold" for="status-toggle" id="status-label">
                            {{ $currentStatus ? 'Active' : 'Inactive' }}
                        </label>
                    </div>
                </div>

                <div class="form-group mb-3">
                    <label for="iframe_code" class="form-label fw-semibold">Google Form iFrame Code <span class="text-danger">*</span></label>
                    <textarea class="form-control @error('iframe_code') is-invalid @enderror"
                              id="iframe_code" name="iframe_code" rows="3" required>{{ old('iframe_code', $dataCollection->iframe_code ?? '') }}</textarea>
                    @error('iframe_code')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group mb-4">
                    <label for="sheet_url" class="form-label fw-semibold">Google Sheet URL</label>
                    <input type="url" class="form-control @error('sheet_url') is-invalid @enderror"
                           id="sheet_url" name="sheet_url"
                           value="{{ old('sheet_url', $dataCollection->sheet_url ?? '') }}"
                           placeholder="https://docs.google.com/spreadsheets/d/YOUR_SHEET_ID/edit">
                    <small class="form-text text-muted">Paste the full Google Sheet URL linked to your Google Form responses.</small>
                    @error('sheet_url')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-check-lg me-1"></i>{{ isset($dataCollection) ? 'Update' : 'Create' }}
                    </button>
                    <a href="{{ route('admin.data-collection.index') }}" class="btn btn-secondary">Cancel</a>
                </div>

            </form>
        </div>
    </div>

</div>
@endsection