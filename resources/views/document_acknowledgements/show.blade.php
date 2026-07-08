@extends('layouts.app')

@section('page-title', __('Document Acknowledgement'))
@section('page-heading', __('Document Acknowledgement'))

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('compliance.document_acknowledgements.index') }}">@lang('Document Acknowledgements')</a></li>
    <li class="breadcrumb-item active">@lang('View')</li>
@stop

@section('content')
    @include('partials.messages')

    <div class="card shadow mb-4">
        <div class="card-body">
            <h4>{{ $document->title }}</h4>
            <p>{{ $document->description }}</p>

            <div class="mb-3">
                <a href="{{ route('compliance.document_acknowledgements.download', $document) }}" class="btn btn-success">@lang('Download PDF')</a>
            </div>

            <div class="mb-4">
                {!! $document->content !!}
            </div>

            <div class="card mb-4">
                <div class="card-header">@lang('Assigned users')</div>
                <div class="card-body">
                    <ul class="list-group">
                        @foreach($assignments as $assignment)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span>{{ $assignment->user->name }} ({{ $assignment->status }})</span>
                                <span>
                                    @if($assignment->signed_at)
                                        <small class="text-muted">@lang('Signed at'): {{ $assignment->signed_at->format('d M Y H:i') }}</small>
                                    @endif
                                </span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>

            <div class="card">
                <div class="card-header">@lang('Assign document')</div>
                <div class="card-body">
                    <form action="{{ route('compliance.document_acknowledgements.assign', $document) }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="user_ids">@lang('Users')</label>
                            <select name="user_ids[]" id="user_ids" class="form-control" multiple required>
                                @foreach(\Vanguard\User::orderBy('name')->get() as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }} - {{ $user->email }}</option>
                                @endforeach
                            </select>
                        </div>
                        <button class="btn btn-primary" type="submit">@lang('Assign')</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@stop
