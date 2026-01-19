@extends('layouts.app')

@section('page-title', __('Email Details'))
@section('page-heading', __('Email Details'))

@section('breadcrumbs')
    <li class="breadcrumb-item">
        <a href="{{ route('emails.index') }}">@lang('Emails')</a>
    </li>
    <li class="breadcrumb-item active">
        @lang('Email Details')
    </li>
@stop

@section('content')
<div class="card">
    <div class="card-body">
        <h3>@lang('Email Details')</h3>

        <table class="table table-bordered">
            <tr>
                <th>@lang('Recipient')</th>
                <td>{{ $email->recipient }}</td>
            </tr>
            <tr>
                <th>@lang('Message')</th>
                <td>{{ $email->message }}</td>
            </tr>
            <tr>
                <th>@lang('Status')</th>
                <td>{{ $email->status_description }}</td>
            </tr>
            <tr>
                <th>@lang('Created At')</th>
                <td>{{ $email->formatted_created_at }}</td>
            </tr>
        </table>
    </div>
</div>
@endsection
