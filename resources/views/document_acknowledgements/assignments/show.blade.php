@extends('layouts.app')

@section('page-title', __('Review Document Acknowledgement'))
@section('page-heading', __('Review Document Acknowledgement'))

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('document_acknowledgements.assignments.index') }}">@lang('My Document Acknowledgements')</a></li>
    <li class="breadcrumb-item active">@lang('Review')</li>
@stop

@section('content')
    @include('partials.messages')

    <div class="card shadow mb-4">
        <div class="card-body">
            <h4>{{ $assignment->document->title }}</h4>
            <p>{{ $assignment->document->description }}</p>
            <div class="mb-3">
                <a href="{{ route('document_acknowledgements.assignments.download', $assignment) }}" class="btn btn-success">@lang('Download document')</a>
            </div>

            <div class="card mb-4">
                <div class="card-body">{!! $assignment->document->content !!}</div>
            </div>

            @if($assignment->status !== 'Signed')
                <div class="card">
                    <div class="card-header">@lang('Acknowledge and sign')</div>
                    <div class="card-body">
                        <form action="{{ route('document_acknowledgements.assignments.acknowledge', $assignment) }}" method="POST">
                            @csrf

                            <div class="form-group form-check">
                                <input type="checkbox" name="accepted" id="accepted" class="form-check-input" value="1" required>
                                <label class="form-check-label" for="accepted">@lang('I have read and accept this document')</label>
                            </div>

                            <input type="hidden" name="signature" value="1">

                            <button class="btn btn-primary" type="submit">@lang('Accept and sign')</button>
                        </form>
                    </div>
                </div>
            @else
                <div class="alert alert-success">
                    @lang('This document has already been signed.')
                </div>
            @endif
        </div>
    </div>
@stop