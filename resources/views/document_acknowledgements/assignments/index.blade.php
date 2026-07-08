@extends('layouts.app')

@section('page-title', __('My Document Acknowledgements'))
@section('page-heading', __('My Document Acknowledgements'))

@section('breadcrumbs')
    <li class="breadcrumb-item active">@lang('My Document Acknowledgements')</li>
@stop

@section('content')
    @include('partials.messages')

    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>@lang('Document')</th>
                            <th>@lang('Status')</th>
                            <th>@lang('Viewed At')</th>
                            <th>@lang('Signed At')</th>
                            <th>@lang('Actions')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($assignments as $assignment)
                            <tr>
                                <td>{{ $assignment->document->title }}</td>
                                <td>{{ $assignment->status }}</td>
                                <td>{{ optional($assignment->viewed_at)->format('d M Y H:i') }}</td>
                                <td>{{ optional($assignment->signed_at)->format('d M Y H:i') }}</td>
                                <td>
                                    <a href="{{ route('document_acknowledgements.assignments.show', $assignment) }}" class="btn btn-sm btn-info">@lang('View')</a>
                                    @if($assignment->signed_file_path)
                                        <a href="{{ route('document_acknowledgements.assignments.download', $assignment) }}" class="btn btn-sm btn-success">@lang('Download signed copy')</a>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">@lang('No documents assigned.')</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{ $assignments->links() }}
        </div>
    </div>
@stop