@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0 fw-bold">Active Data Collection Forms</h4>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            @if($dataCollections->isEmpty())
                <div class="alert alert-info m-3">No active data collection forms available.</div>
            @else
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">Title</th>
                            <th>Description</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($dataCollections as $entry)
                            <tr>
                                <td class="ps-4 fw-semibold">{{ $entry->title }}</td>
                                <td class="text-muted">{{ $entry->description }}</td>
                                <td>{{ $entry->start_date->format('Y-m-d') }}</td>
                                <td>{{ $entry->end_date->format('Y-m-d') }}</td>
                                <td>
                                    <a href="{{ route('data-collection.view', $entry->slug) }}"
                                        class="btn btn-sm btn-primary" title="View">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>

</div>
@endsection