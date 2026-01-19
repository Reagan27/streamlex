@extends('layouts.app')

@section('page-title', __('View Appraisal'))
@section('page-heading', __('Appraisal for ' . $appraisal->user->first_name . ' ' . $appraisal->user->last_name))

@section('breadcrumbs')
    <li class="breadcrumb-item">
        <a href="{{ route('appraisals.index') }}">@lang('Appraisals')</a>
    </li>
    <li class="breadcrumb-item active">
        @lang('View')
    </li>
@stop

@section('content')

<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <h4>@lang('Appraisal Details')</h4>
                <p><strong>@lang('Appraisal Date'):</strong> 
                    @if($appraisal->appraisal_date instanceof \Carbon\Carbon)
                        {{ $appraisal->appraisal_date->format('Y-m-d') }}
                    @else
                        {{ $appraisal->appraisal_date }}
                    @endif
                </p>
                <p><strong>@lang('Week'):</strong> {{ $appraisal->period }}</p>
                <p><strong>@lang('Status'):</strong> {{ $appraisal->status ? 'Completed' : 'Pending' }}</p>
            </div>
            <div class="col-md-6">
                <h4>@lang('Ratings')</h4>
                <p><strong>@lang('Motivation '):</strong> {{ $appraisal->motivation_score }}</p>
                <p><strong>@lang('Resourcefulness '):</strong> {{ $appraisal->resourcefulness_score }}</p>
                <p><strong>@lang('Leadership '):</strong> {{ $appraisal->leadership_score }}</p>
                <p><strong>@lang('Discipline '):</strong> {{ $appraisal->discipline_score }}</p>
                <p><strong>@lang('Teamwork '):</strong> {{ $appraisal->teamwork_score }}</p>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-md-12">
                <h4>@lang('Comments')</h4>
                <p>{{ $appraisal->comments }}</p>
            </div>
        </div>
<!-- 
        <div class="row mt-4">
            <div class="col-md-12">
                <a href="{{ route('appraisals.edit', $appraisal) }}" class="btn btn-primary">@lang('Edit Appraisal')</a>
                @if(!$appraisal->status)
                    <form action="{{ route('appraisals.complete', $appraisal) }}" method="POST" style="display: inline-block;">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn btn-success">@lang('Mark as Completed')</button>
                    </form>
                @endif
            </div>
        </div> -->
    </div>
</div>

@stop