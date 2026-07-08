@extends('layouts.app')

@section('page-title', __('Document Acknowledgements'))
@section('page-heading', __('Document Acknowledgements'))

@section('breadcrumbs')
    <li class="breadcrumb-item active">@lang('Document Acknowledgements')</li>
@stop

@section('content')
    @include('partials.messages')

    <div class="row mb-4">
        <div class="col-md-12 text-right">
            <a href="{{ route('compliance.document_acknowledgements.create') }}" class="btn btn-primary">@lang('Add acknowledgement')</a>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="form-inline mb-3">
                <form method="GET" action="{{ route('compliance.document_acknowledgements.index') }}" class="form-inline">
                    <input type="text" name="search" class="form-control mr-2" placeholder="@lang('Search documents')" value="{{ request('search') }}">
                    <button class="btn btn-secondary" type="submit">@lang('Filter')</button>
                </form>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>@lang('Title')</th>
                            <th>@lang('Created By')</th>
                            <th>@lang('Signed Page')</th>
                            <th>@lang('Assigned Users')</th>
                            <th>@lang('Actions')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($documents as $document)
                            <tr>
                                <td>{{ $document->title }}</td>
                                <td>{{ $document->createdBy?->name }}</td>
                                <td>{{ $document->signature_page }}</td>
                                <td>{{ $document->assignments()->count() }}</td>
                                <td>
                                    <a href="{{ route('compliance.document_acknowledgements.show', $document) }}" class="btn btn-sm btn-info">@lang('View')</a>
                                    <a href="{{ route('compliance.document_acknowledgements.edit', $document) }}" class="btn btn-sm btn-warning">@lang('Edit')</a>
                                    <a href="{{ route('compliance.document_acknowledgements.download', $document) }}" class="btn btn-sm btn-success">@lang('Download')</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">@lang('No document acknowledgements found.')</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{ $documents->links() }}
        </div>
    </div>
@stop